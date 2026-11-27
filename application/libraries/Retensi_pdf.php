<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Draw the letter using the bundled PDF canvas, without the legacy HTML layout engine.
class Retensi_pdf
{
    private $pdf;
    private $y=50;
    private $left=45;
    private $right=550;
    private $pageHeight=841.89;

    private function font($style='')
    {
        $this->pdf->selectFont(APPPATH.'libraries/dompdf/lib/fonts/Helvetica'.$style.'.afm');
    }

    private function lines($text,$width,$size=11,$style='')
    {
        $this->font($style);$lines=array();
        foreach (preg_split('/\r\n|\r|\n/',(string)$text) as $paragraph) {
            $line='';
            foreach (preg_split('/\s+/u',trim($paragraph)) as $word) {
                $test=$line==='' ? $word : $line.' '.$word;
                if ($line!=='' && $this->pdf->getTextWidth($size,$test)>$width) { $lines[]=$line;$line=$word; }
                else $line=$test;
            }
            $lines[]=$line;
        }
        return $lines;
    }

    private function text($text,$x,$y,$size=11,$style='',$right=FALSE)
    {
        $this->font($style);
        if ($right) $x-=$this->pdf->getTextWidth($size,$text);
        $this->pdf->addText($x,$this->pageHeight-$y,$size,$text);
    }

    private function block($text,$style='',$indent=0,$size=11)
    {
        foreach ($this->lines($text,$this->right-$this->left-$indent,$size,$style) as $line) {
            if ($this->y>785) { $this->pdf->newPage();$this->y=50; }
            $this->text($line,$this->left+$indent,$this->y,$size,$style);$this->y+=17;
        }
    }

    private function money($value) { return 'Rp'.number_format($value,0,',','.').' ,-'; }

    public function generate($row,$project,$rows,$total,$owner,$bank,$words)
    {
        require_once APPPATH.'libraries/dompdf/autoload.inc.php';
        require_once APPPATH.'libraries/dompdf/lib/Cpdf.php';
        $this->pdf=new Cpdf(array(0,0,595.28,841.89),FALSE,APPPATH.'libraries/dompdf/lib/fonts');
        $this->pdf->addInfo('Title','Permohonan Pencairan Retensi - '.$row->no_surat);
        $this->pdf->addInfo('Author',$row->nama_perusahaan);
        $this->y=50;$this->pageHeight=841.89;$this->left=45;$this->right=550;
        $months=array(1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
        $date=new DateTimeImmutable($row->tanggal_surat);$dateLabel=$date->format('j').' '.$months[(int)$date->format('n')].' '.$date->format('Y');
        $annex=array();$page=array();$height=0;
        foreach ($rows as $item) {
            $rowHeight=max(count($this->lines($item->nama,270,10)),count($this->lines($item->blok.'/'.$item->no_unit,80,10)),count($this->lines(isset($item->kode_akad) ? $item->kode_akad : '',70,10)))*14+6;
            if ($page && $height+$rowHeight>380) { $annex[]=$page;$page=array();$height=0; }
            $page[]=array($item,$rowHeight);$height+=$rowHeight;
        }
        if ($page || !$annex) $annex[]=$page;
        $this->text(($row->kota_surat!=='' ? $row->kota_surat.', ' : '').$dateLabel,$this->right,50,11,'',TRUE);
        $this->y=87;
        foreach (array('No'=>$row->no_surat,'Hal'=>'Permohonan Pencairan Retensi','Lampiran'=>count($annex).' Lembar') as $label=>$value) {
            $this->text($label,$this->left,$this->y);$this->text(':',115,$this->y);
            $lines=$this->lines($value,420,11,$label==='Hal' ? '-Bold' : '');
            foreach ($lines as $line) { $this->text($line,128,$this->y,11,$label==='Hal' ? '-Bold' : '');$this->y+=17; }
        }
        $this->y+=28;$this->block('Kepada Yth:');$this->block('Kepala Cabang','-Bold');
        if ($row->bank_tujuan!=='') $this->block($row->bank_tujuan,'-Bold');
        $this->block($row->tujuan_surat,'-Bold');
        if ($row->alamat_tujuan!=='') $this->block($row->alamat_tujuan,'-Bold');
        $this->y+=24;$this->block('Dengan Hormat,');
        $this->block('Sehubungan dengan telah terselesaikannya '.$row->nama_retensi.' atas unit-unit rumah');
        $this->block('Perumahan '.($project ? $project->namproj : '').($row->alamat_proyek!=='' ? ' yang berlokasi di '.$row->alamat_proyek : ''));
        $this->block('maka bersama ini kami mengajukan permohonan pencairan dana Retensi sebagai berikut:');
        $this->y+=20;
        $summaryY=$this->y;
        foreach ($this->lines('1.   '.$row->nama_retensi.' sebanyak '.count($rows).' Unit',330) as $line) {
            $this->text($line,$this->left,$this->y);$this->y+=17;
        }
        $this->text('=   '.$this->money($total),$this->right,$summaryY,11,'',TRUE);
        $this->y+=27;$this->text('Jumlah',70,$this->y,11,'-Bold');$this->text($this->money($total),$this->right,$this->y,11,'-Bold',TRUE);
        $this->y+=31;$this->block('Terbilang:','-Bold',25);$this->block('('.$words.')','-BoldOblique',25);
        $this->y+=17;$this->block('(Rincian data unit terlampir)','-Oblique',25);
        $this->y+=17;$this->block('Dan mohon dana pencairan Retensi tersebut disetorkan ke rekening kami a.n.');
        $this->block($owner.', Nomor Rekening '.$row->no_rekening.($bank!=='' ? ' di '.$bank : '').'.','-BoldOblique');
        $this->block('Demikian kami sampaikan atas perhatian serta kerja sama yang baik kami ucapkan terima kasih.','-Oblique');
        $this->y+=20;$this->block('Hormat kami,');$this->block($row->nama_perusahaan);
        $number=0;
        foreach ($annex as $pageIndex=>$entries) {
            $this->pdf->newPage();
            $this->pdf->objects[$this->pdf->currentPage]['info']['mediaBox']=array(0,0,841.89,595.28);
            $this->pageHeight=595.28;$this->left=28;$this->right=813;$this->y=30;
            foreach (array('LAMPIRAN PENGAJUAN RETENSI '.mb_strtoupper($row->nama_retensi,'UTF-8'),$row->nama_perusahaan.' - '.($project ? $project->namproj : '')) as $title) {
                foreach ($this->lines($title,785,11,'-Bold') as $line) {
                    $this->font('-Bold');$width=$this->pdf->getTextWidth(11,$line);
                    $this->text($line,(841.89-$width)/2,$this->y,11,'-Bold');$this->y+=17;
                }
            }
            $this->y+=18;$columns=array(28,55,135,220,500,590,660,813);$headerY=$this->y;
            foreach (array('NO','NO AKAD','TGL AKAD','NAMA KONSUMEN','BLOK','TYPE',mb_strtoupper($row->nama_retensi,'UTF-8')) as $col=>$label) {
                $this->pdf->rectangle($columns[$col],$this->pageHeight-$headerY-30,$columns[$col+1]-$columns[$col],30);
                foreach ($this->lines($label,$columns[$col+1]-$columns[$col]-10,10,'-Bold') as $lineIndex=>$line) {
                    $this->font('-Bold');$width=$this->pdf->getTextWidth(10,$line);
                    $this->text($line,($columns[$col]+$columns[$col+1]-$width)/2,$headerY+18+$lineIndex*12,10,'-Bold');
                }
            }
            $this->y+=30;$subtotal=0;
            foreach ($entries as $entry) {
                list($item,$rowHeight)=$entry;$number++;$subtotal+=(int)$item->jumlah;
                $type=isset($item->tipe) ? (string)$item->tipe : '';
                if ($type!=='' && strpos($type,'/')===FALSE && !empty($item->ltstd)) $type.='/'.rtrim(rtrim(number_format($item->ltstd,2,'.',''),'0'),'.');
                $akadDate=!empty($item->tanggal_akad) ? (new DateTimeImmutable($item->tanggal_akad))->format('d/m/Y') : '';
                $values=array($number,isset($item->kode_akad) ? $item->kode_akad : '',$akadDate,$item->nama,$item->blok.'-'.$item->no_unit,$type,number_format($item->jumlah,0,',','.'));
                foreach ($values as $col=>$value) {
                    $this->pdf->rectangle($columns[$col],$this->pageHeight-$this->y-$rowHeight,$columns[$col+1]-$columns[$col],$rowHeight);
                    foreach ($this->lines($value,$columns[$col+1]-$columns[$col]-10,10) as $lineIndex=>$line) {
                        $x=$columns[$col]+5;$alignRight=$col===6;
                        if ($alignRight) $x=$columns[$col+1]-5;
                        elseif ($col!==3) { $this->font();$x=($columns[$col]+$columns[$col+1]-$this->pdf->getTextWidth(10,$line))/2; }
                        $this->text($line,$x,$this->y+14+$lineIndex*14,10,'',$alignRight);
                    }
                }
                $this->y+=$rowHeight;
            }
            $last=$pageIndex===count($annex)-1;
            $label=$last ? 'TOTAL' : 'SUBTOTAL';$amount=$last ? $total : $subtotal;
            $this->pdf->rectangle(28,$this->pageHeight-$this->y-24,632,24);
            $this->pdf->rectangle(660,$this->pageHeight-$this->y-24,153,24);
            $this->font('-Bold');$width=$this->pdf->getTextWidth(11,$label);
            $this->text($label,(28+660-$width)/2,$this->y+16,11,'-Bold');
            $this->text(number_format($amount,0,',','.'),808,$this->y+16,11,'-Bold',TRUE);
            $this->text('Lampiran '.($pageIndex+1).' dari '.count($annex),813,570,9,'',TRUE);
        }
        return $this->pdf->output();
    }
}
