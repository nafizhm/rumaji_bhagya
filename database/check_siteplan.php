<?php
// Read-only integration check: php database/check_siteplan.php <project ID>
if (PHP_SAPI!=='cli') { http_response_code(404);exit; }
mysqli_report(MYSQLI_REPORT_ERROR|MYSQLI_REPORT_STRICT);
define('BASEPATH',TRUE);define('ENVIRONMENT','development');
require __DIR__.'/../application/config/database.php';
$config=$db['default'];$conn=new mysqli($config['hostname'],$config['username'],$config['password'],$config['database']);$conn->set_charset('utf8');
class CI_Model { public $db; }
class SiteplanCheckDb
{
    private $conn;
    public function __construct($conn) { $this->conn=$conn; }
    public function query($sql,$params) {
        $statement=$this->conn->prepare($sql);$statement->bind_param(str_repeat('i',count($params)),...$params);$statement->execute();
        $rows=array();$result=$statement->get_result();while ($row=$result->fetch_object()) $rows[]=$row;
        return new class($rows) { private $rows;public function __construct($rows) { $this->rows=$rows; }public function result() { return $this->rows; } };
    }
}
require __DIR__.'/../application/models/Mod_siteplan.php';
$projid=(int)($argv[1]??10);$model=new Mod_siteplan();$model->db=new SiteplanCheckDb($conn);$units=$model->units($projid);
$statement=$conn->prepare('SELECT * FROM msiteplan WHERE projid=?');$statement->bind_param('i',$projid);$statement->execute();$master=$statement->get_result()->fetch_assoc();
if (!$master) throw new RuntimeException('No siteplan for this project.');
$statement=$conn->prepare('SELECT blok,no FROM mkav WHERE projid=?');$statement->bind_param('i',$projid);$statement->execute();$stock=$statement->get_result()->fetch_all(MYSQLI_ASSOC);
if (count($units)!==count($stock)) throw new RuntimeException('Stock and mapped unit counts differ.');
$svg=$master['header_svg'];$seen=array();$states=array();
foreach ($units as $unit) {
    $key=$unit->blok.'/'.$unit->no;if (isset($seen[$key])) throw new RuntimeException('Duplicate '.$key);$seen[$key]=TRUE;
    $svg.='<g data-unit="'.$unit->kode_unit.'">'.str_replace('[[warna]]','#ffffff',$unit->shape_svg).$unit->label_svg.'</g>';
    $states[$unit->state]=($states[$unit->state]??0)+1;
}
$svg.=$master['footer_svg'];$doc=new DOMDocument();if (!$doc->loadXML($svg,LIBXML_NONET)) throw new RuntimeException('Rendered SVG is invalid.');
$xp=new DOMXPath($doc);$xp->registerNamespace('s','http://www.w3.org/2000/svg');
if ($xp->query('//s:g[@data-unit]')->length!==count($stock)) throw new RuntimeException('Rendered geometry count mismatch.');
foreach ($xp->query('//s:g[@data-unit]') as $group) {
    if ($xp->query('./s:path|./s:polygon',$group)->length!==1) throw new RuntimeException('Invalid unit shape.');
    if (trim($xp->evaluate('string(.//s:text)',$group))!==$group->getAttribute('data-unit')) throw new RuntimeException('Label mapping mismatch.');
}
$source=__DIR__.'/../application/uploads/siteplan/'.$master['source_file'];
if (!is_file($source) || hash_file('sha256',$source)!==$master['source_hash']) throw new RuntimeException('Source archive hash mismatch.');
// Confirm status precedence without modifying application data.
$cases=array(
    array('BLA',NULL,1,NULL,'tersedia'),array('BLA',NULL,0,NULL,'belum_rilis'),
    array('BLA','Pending',0,NULL,'booking'),array('BLA','Disetujui',0,NULL,'booking'),
    array('BLA','Ditolak',0,NULL,'tidak_tersedia'),array('BLA',NULL,0,7,'spr'),
    array('SPR','Pending',0,7,'spr'),array('AKD','Disetujui',0,7,'akad'),array('AKS',NULL,0,7,'akad'),array('BTL',NULL,0,NULL,'batal')
);
foreach ($cases as $case) {
    $fake=(object)array('status'=>$case[0],'booking_status'=>$case[1],'ready_for_sale'=>$case[2],'sprno'=>$case[3],'booking_id'=>$case[1]!==NULL?1:NULL);
    $model->db=new class($fake) { private $row;public function __construct($row) { $this->row=$row; }public function query($sql,$params) { return new class($this->row) { private $row;public function __construct($row) { $this->row=$row; }public function result() { return array($this->row); } }; } };
    if ($model->units($projid)[0]->state!==$case[4]) throw new RuntimeException('Status precedence failed.');
}
echo json_encode(array('project'=>$projid,'stock'=>count($stock),'mapped'=>count($units),'valid_svg'=>TRUE,'source_hash'=>'matched','states'=>$states,'status_cases'=>count($cases)),JSON_PRETTY_PRINT).PHP_EOL;
