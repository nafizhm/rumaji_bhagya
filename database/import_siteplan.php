<?php
// CLI only: php database/import_siteplan.php <SVG path> <project ID> [--apply]
if (PHP_SAPI!=='cli') { http_response_code(404);exit; }
mysqli_report(MYSQLI_REPORT_ERROR|MYSQLI_REPORT_STRICT);
try {
    $source=$argv[1]??'';$projid=(int)($argv[2]??0);$apply=in_array('--apply',$argv,TRUE);
    if (!$source || !$projid || !is_file($source)) throw new RuntimeException('Provide an SVG file and project ID.');
    define('BASEPATH',TRUE);define('ENVIRONMENT','development');
    require __DIR__.'/../application/config/database.php';
    $config=$db['default'];$conn=new mysqli($config['hostname'],$config['username'],$config['password'],$config['database']);$conn->set_charset('utf8');
    $query=$conn->prepare('SELECT blok,no FROM mkav WHERE projid=?');$query->bind_param('i',$projid);$query->execute();$stock=$query->get_result()->fetch_all(MYSQLI_ASSOC);
    if (!$stock) throw new RuntimeException('Project has no stock units.');
    $normalize=function($code) { return strtoupper(preg_replace('/\s+/','',$code)); };
    $stockByCode=array();foreach ($stock as $unit) $stockByCode[$normalize($unit['blok'].$unit['no'])]=$unit;
    $doc=new DOMDocument();$doc->preserveWhiteSpace=TRUE;
    if (!$doc->load($source,LIBXML_NONET)) throw new RuntimeException('Invalid SVG.');
    if ($doc->documentElement->localName!=='svg') throw new RuntimeException('Expected SVG document.');
    // Keep only passive SVG content in the imported document.
    foreach (iterator_to_array($doc->getElementsByTagName('*')) as $element) {
        if (in_array(strtolower($element->localName),array('script','foreignobject','animate','animatetransform','set'),TRUE)) throw new RuntimeException('Active SVG content is not allowed.');
        foreach ($element->attributes as $attribute) {
            if (stripos($attribute->localName,'on')===0) throw new RuntimeException('SVG event attributes are not allowed.');
            if ($attribute->localName==='href' && $attribute->value!=='' && $attribute->value[0]!=='#'
                && !preg_match('~^data:image/(png|jpeg|webp);base64,~i',$attribute->value)) throw new RuntimeException('External SVG references are not allowed.');
        }
    }
    $xp=new DOMXPath($doc);$xp->registerNamespace('s','http://www.w3.org/2000/svg');
    $units=array();$groups=array();$shapes=array();
    foreach ($xp->query('//s:text') as $text) {
        $code=$normalize($text->textContent);
        if (!preg_match('/^[A-Z]+[0-9]+[A-Z]?$/',$code)) continue;
        if (!isset($stockByCode[$code])) throw new RuntimeException('SVG unit not found in stock: '.$code);
        if (isset($units[$code])) throw new RuntimeException('Duplicate SVG unit: '.$code);
        $group=$text->parentNode;
        while ($group instanceof DOMElement && !$xp->query('./s:polygon|./s:path',$group)->length) $group=$group->parentNode;
        if (!$group instanceof DOMElement) throw new RuntimeException('No geometry for '.$code);
        $geometry=$xp->query('./s:polygon|./s:path',$group);
        if ($geometry->length!==1) throw new RuntimeException('Expected one shape for '.$code);
        for ($ancestor=$group;$ancestor instanceof DOMElement;$ancestor=$ancestor->parentNode) {
            if ($ancestor->hasAttribute('transform')) throw new RuntimeException('Unsupported ancestor transform for '.$code);
        }
        $shape=$geometry->item(0);$shapeCopy=$shape->cloneNode(TRUE);
        $style=rtrim($shapeCopy->getAttribute('style'),';');
        $shapeCopy->setAttribute('style',$style.';fill:[[warna]] !important;');
        $labelGroup=$text->parentNode;
        if ($labelGroup===$group) throw new RuntimeException('Expected separate label group for '.$code);
        $units[$code]=array_merge($stockByCode[$code],array('code'=>$code,'kind'=>$shape->localName,
            'map'=>$shape->getAttribute($shape->localName==='polygon'?'points':'d'),
            'matrix'=>$labelGroup->getAttribute('transform'),'shape'=>$doc->saveXML($shapeCopy),'label'=>$doc->saveXML($labelGroup)));
        $groups[]=$group;$shapes[$shape->localName]=($shapes[$shape->localName]??0)+1;
    }
    $missing=array_diff(array_keys($stockByCode),array_keys($units));
    if ($missing || count($stock)!==count($units)) throw new RuntimeException('SVG/stock mismatch. Missing: '.implode(', ',$missing));
    foreach ($groups as $group) $group->parentNode->removeChild($group);
    $root=$doc->documentElement;$viewBox=$root->getAttribute('viewBox');
    $root->setAttribute('width','100%');$root->setAttribute('height','100%');$root->setAttribute('id','siteplan-svg');$root->setAttribute('preserveAspectRatio','xMidYMid meet');
    $svg=$doc->saveXML($root);$closing=strrpos($svg,'</svg>');
    if ($closing===FALSE || !$viewBox) throw new RuntimeException('SVG must have a viewBox and closing tag.');
    $header=substr($svg,0,$closing);$footer='</svg>';$hash=hash_file('sha256',$source);
    echo json_encode(array('project'=>$projid,'stock_count'=>count($stock),'svg_count'=>count($units),'geometry'=>$shapes,'mode'=>$apply?'apply':'dry-run'),JSON_PRETTY_PRINT).PHP_EOL;
    if (!$apply) exit;
    $schema=file_get_contents(__DIR__.'/20261126_siteplan.sql');
    $conn->multi_query($schema);do { if ($result=$conn->store_result()) $result->free(); } while ($conn->more_results() && $conn->next_result());
    $existing=$conn->prepare('SELECT COUNT(*) n FROM msiteplan_unit WHERE projid=?');$existing->bind_param('i',$projid);$existing->execute();
    if ((int)$existing->get_result()->fetch_assoc()['n']>0) throw new RuntimeException('Siteplan already imported. No existing geometry was changed.');
    $folder=__DIR__.'/../application/uploads/siteplan';if (!is_dir($folder) && !mkdir($folder,0750,TRUE)) throw new RuntimeException('Cannot create source archive.');
    $sourceFile=$hash.'.svg';if (!is_file($folder.'/'.$sourceFile) && !copy($source,$folder.'/'.$sourceFile)) throw new RuntimeException('Cannot archive source SVG.');
    $conn->begin_transaction();
    $insert=$conn->prepare('INSERT INTO msiteplan (projid,header_svg,footer_svg,view_box,source_hash,source_file,updated_at) VALUES (?,?,?,?,?,?,NOW())');
    $insert->bind_param('isssss',$projid,$header,$footer,$viewBox,$hash,$sourceFile);$insert->execute();
    $insert=$conn->prepare('INSERT INTO msiteplan_unit (projid,blok,no,kode_unit,jenis_map,map,matrik,shape_svg,label_svg) VALUES (?,?,?,?,?,?,?,?,?)');
    foreach ($units as $unit) {
        $insert->bind_param('issssssss',$projid,$unit['blok'],$unit['no'],$unit['code'],$unit['kind'],$unit['map'],$unit['matrix'],$unit['shape'],$unit['label']);$insert->execute();
    }
    $conn->commit();echo 'Imported '.count($units).' units. Stock data unchanged.'.PHP_EOL;
} catch (Throwable $error) {
    if (isset($conn)) { try { $conn->rollback(); } catch (Throwable $ignored) {} }
    fwrite(STDERR,$error->getMessage().PHP_EOL);exit(1);
}
