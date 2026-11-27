<?php
// CLI only: clone the local database, retaining only Omah Nyaman project data.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$source = 'u1064161_rumajiapp';
$target = 'bhagya_v2';
$db = new mysqli('localhost', 'root', '');
$db->set_charset('utf8mb4');
if ($db->query("SELECT 1 FROM information_schema.SCHEMATA WHERE SCHEMA_NAME='$target'")->num_rows) {
    throw new RuntimeException('Target already exists; refusing to overwrite.');
}
$project = $db->query("SELECT projid,ptid FROM `$source`.mproj WHERE TRIM(namproj)='OMAH NYAMAN'")->fetch_all(MYSQLI_ASSOC);
if (count($project) !== 1) throw new RuntimeException('Expected exactly one Omah Nyaman project.');
$id = (int)$project[0]['projid'];
$pt = (int)$project[0]['ptid'];
$dir = dirname(__DIR__) . '/.tmp/bhagya_v2';
if (!is_dir($dir)) mkdir($dir, 0777, true);
$mysql = 'D:/1_laragon/bin/mysql/mysql-8.0.30-winx64/bin/';
function runProcess(array $args, ?string $input = null): void {
    $spec = [0 => $input ? ['file', $input, 'r'] : ['pipe', 'r'], 1 => ['pipe','w'], 2 => ['pipe','w']];
    $p = proc_open($args, $spec, $pipes);
    if (!is_resource($p)) throw new RuntimeException('Cannot start database tool.');
    if (!$input) fclose($pipes[0]);
    $out = stream_get_contents($pipes[1]); fclose($pipes[1]);
    $err = stream_get_contents($pipes[2]); fclose($pipes[2]);
    if (proc_close($p) !== 0) throw new RuntimeException($err . $out);
}
$backup = "$dir/source.sql";
runProcess([$mysql.'mysqldump.exe','-u','root','--single-transaction','--routines','--triggers','--hex-blob','--ignore-table='.$source.'.cetak_byr_v','--set-gtid-purged=OFF','--result-file='.$backup,$source]);
$sql = file_get_contents($backup);
$sourceView = $db->query("SHOW CREATE VIEW `$source`.cetak_byr_v")->fetch_row()[1];
$sql .= "\n" . $sourceView . ";\n";
$sql = str_replace('`'.$source.'`.', '`'.$target.'`.', $sql);
// Local copy must not depend on the old hosting-only definer account.
$sql = preg_replace('/DEFINER=`[^`]+`@`[^`]+`/', 'DEFINER=`root`@`localhost`', $sql);
file_put_contents("$dir/import.sql", $sql);
unset($sql);
$db->query("CREATE DATABASE `$target` CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci");
runProcess([$mysql.'mysql.exe','-u','root',$target], "$dir/import.sql");
$tables = $db->query("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA='$source' AND TABLE_TYPE='BASE TABLE' ORDER BY TABLE_NAME")->fetch_all(MYSQLI_NUM);
$scoped = array_column($db->query("SELECT TABLE_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='$source' AND COLUMN_NAME='projid'")->fetch_all(MYSQLI_NUM),0);
$children = [
    'tpencairan_retensi' => ['tpencairan', 'pencairan_id', 'id'],
    'trabd' => ['trabh', 'rabid', 'rabid'],
];
$delete = ["USE `$target`;", 'START TRANSACTION;'];
// Delete child tables first so existing foreign keys remain enabled.
foreach (['mlot_unit','tpengajuan_akad_peserta','tpengajuan_retensi_detail'] as $t) {
    $delete[] = "DELETE FROM `$t` WHERE projid IS NULL OR projid <> $id;";
}
foreach ($children as $t => [$parent,$fk,$pk]) {
    $delete[] = "DELETE c FROM `$t` c LEFT JOIN `$parent` p ON p.`$pk`=c.`$fk` WHERE p.`$pk` IS NULL OR p.projid IS NULL OR p.projid <> $id;";
}
foreach ($scoped as $t) $delete[] = "DELETE FROM `$t` WHERE projid IS NULL OR projid <> $id;";
$delete[] = "DELETE FROM mpt WHERE ptid <> $pt;";
$delete[] = 'COMMIT;';
file_put_contents(__DIR__.'/20261128_bhagya_v2_omah_nyaman.sql', implode("\n", $delete)."\n");
$db->select_db($target);
$db->begin_transaction();
foreach (array_slice($delete,2,-1) as $statement) $db->query($statement);
$report = [];
foreach ($tables as [$t]) {
    $where = in_array($t,$scoped,true) ? " WHERE projid=$id" : '';
    if ($t === 'mpt') $where = " WHERE ptid=$pt";
    if (isset($children[$t])) {
        [$p,$fk,$pk] = $children[$t];
        $where = " WHERE `$fk` IN (SELECT `$pk` FROM `$source`.`$p` WHERE projid=$id)";
    }
    $expected = (int)$db->query("SELECT COUNT(*) FROM `$source`.`$t`$where")->fetch_row()[0];
    $actual = (int)$db->query("SELECT COUNT(*) FROM `$target`.`$t`")->fetch_row()[0];
    if ($expected !== $actual) throw new RuntimeException("Count mismatch: $t ($expected / $actual)");
    $report[$t] = $actual;
}
foreach ($scoped as $t) {
    if ($db->query("SELECT 1 FROM `$t` WHERE projid IS NULL OR projid <> $id LIMIT 1")->num_rows) throw new RuntimeException("Unexpected project in $t");
}
$db->commit();
// Exercise the copied view and ensure it reads only the target database.
$db->query('SELECT * FROM cetak_byr_v LIMIT 1');
$view = $db->query("SHOW CREATE VIEW cetak_byr_v")->fetch_row()[1];
if (strpos($view, '`'.$source.'`.') !== false) throw new RuntimeException('View still references source.');
file_put_contents("$dir/verification.json", json_encode(['source'=>$source,'target'=>$target,'project_id'=>$id,'tables'=>$report], JSON_PRETTY_PRINT));
runProcess([$mysql.'mysqldump.exe','-u','root','--single-transaction','--routines','--triggers','--hex-blob','--set-gtid-purged=OFF','--result-file='.$dir.'/bhagya_v2.sql',$target]);
echo json_encode(['database'=>$target,'project_id'=>$id,'tables_verified'=>count($report),'counts'=>$report], JSON_PRETTY_PRINT), PHP_EOL;
