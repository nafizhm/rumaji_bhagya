<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akad_biaya_pdf
{
    private $pdf;
    private $height=841.89;
    private $y;
    private $page=0;
    private $widths;
    private $labels;
    private $heading;
    private $totalColumn;

    private function font($bold=FALSE)
    {
        $this->pdf->selectFont(APPPATH.'libraries/dompdf/lib/fonts/Helvetica'.($bold ? '-Bold' : '').'.afm');
    }

    private function lines($value,$width,$size)
    {
        $result=array();$line='';
        // Break even long unspaced names so text cannot overlap adjacent columns.
        foreach (preg_split('//u',(string)$value,-1,PREG_SPLIT_NO_EMPTY) as $char) {
            if ($char==="\r") continue;
            if ($char==="\n" || ($line!=='' && $this->pdf->getTextWidth($size,$line.$char)>$width)) {
                $result[]=trim($line);$line='';
            }
            if ($char!=="\n") $line.=$char;
        }
        $result[]=trim($line);return $result;
    }

    private function text($value,$x,$y,$size)
    {
        $this->pdf->addText($x,$this->height-$y,$size,$value);
    }

    private function cells($values,$bold=FALSE,$header=FALSE)
    {
        $this->font($bold);$size=$header ? 7 : 6.5;$lines=array();$height=22;
        foreach ($values as $i=>$value) {
            $lines[$i]=$this->lines($value,$this->widths[$i]-8,$size);
            $height=max($height,count($lines[$i])*10+10);
        }
        if (!$header && $this->y+$height>805) { $this->newPage();$this->font($bold); }
        $x=28;
        foreach ($lines as $i=>$parts) {
            if ($i===$this->totalColumn || ($bold && !$header)) {
                $this->pdf->setColor($i===$this->totalColumn ? array(1,0.88,0.55) : array(0.90,0.94,0.98));
                $this->pdf->filledRectangle($x,$this->height-$this->y-$height,$this->widths[$i],$height);
                $this->pdf->setColor(array(0,0,0));
            }
            $this->pdf->rectangle($x,$this->height-$this->y-$height,$this->widths[$i],$height);
            foreach ($parts as $j=>$part) {
                $tx=$x+4;
                if (!$header && $i>=3 && $i<15) $tx=$x+$this->widths[$i]-4-$this->pdf->getTextWidth($size,$part);
                $this->text($part,$tx,$this->y+12+$j*10,$size);
            }
            $x+=$this->widths[$i];
        }
        $this->y+=$height;
    }

    private function newPage()
    {
        if ($this->page) $this->pdf->newPage();
        $this->page++;$this->font(TRUE);$this->y=30;
        $this->text('RINCIAN PENGAJUAN BIAYA AKAD',28,$this->y,13);$this->y+=19;
        $this->font();
        foreach ($this->heading as $label=>$value) {
            $this->text($label,28,$this->y,9);
            $this->text(':',116,$this->y,9);
            foreach ($this->lines($value,1038,9) as $part) {
                $this->text($part,124,$this->y,9);$this->y+=13;
            }
        }
        $this->font();$this->text('Halaman '.$this->page.' | Nominal dalam Rupiah',28,824,8);
        $this->y+=8;$this->cells($this->labels,TRUE,TRUE);
    }

    private function cents($value)
    {
        $parts=explode('.',(string)($value ?? '0'));
        return (int)$parts[0]*100+(int)str_pad(isset($parts[1]) ? $parts[1] : '',2,'0');
    }

    private function money($cents)
    {
        return number_format(intdiv($cents,100),0,',','.').',' .str_pad((string)($cents%100),2,'0',STR_PAD_LEFT);
    }

    public function generate($row,$project,$rows,$fields)
    {
        require_once APPPATH.'libraries/dompdf/autoload.inc.php';
        require_once APPPATH.'libraries/dompdf/lib/Cpdf.php';
        // A3 landscape accommodates every cost column on the same sheet.
        $this->pdf=new Cpdf(array(0,0,1190.55,841.89),FALSE,APPPATH.'libraries/dompdf/lib/fonts');
        $this->pdf->addInfo('Title','Biaya Akad - '.$row->kode_akad);
        $this->widths=array_merge(array(22,115,45),array_fill(0,count($fields),64),array(90,94));
        $this->labels=array_merge(array('No','Nama Konsumen / SPR / Jenis','Unit'),array_values($fields),array('Bank Cabang','Nama Notaris'));
        $totalIndex=array_search('biaya_akad',array_keys($fields),TRUE);
        $this->totalColumn=$totalIndex===FALSE ? -1 : 3+$totalIndex;
        $this->heading=array('Proyek'=>($project ? $project->namproj : '-'),'Kode Akad'=>$row->kode_akad,
            'Tanggal Akad'=>date('d-m-Y',strtotime($row->tanggal_akad)),'Status'=>'Closed',
            'Closed pada'=>date('d-m-Y H:i',strtotime($row->biaya_closed_at)),
            'Jumlah peserta'=>(string)count($rows));
        if (!empty($row->keterangan)) $this->heading['Keterangan']=$row->keterangan;
        $this->page=0;$this->newPage();$totals=array_fill_keys(array_keys($fields),0);
        foreach ($rows as $i=>$item) {
            $values=array($i+1,($item->nama ?: '-')."\nSPR ".$item->sprno."\n".$item->jenis,($item->blok ?: '-').'/'.($item->no ?: '-'));
            foreach ($fields as $field=>$label) {
                $cents=$this->cents($item->$field);$totals[$field]+=$cents;
                $values[]=$item->$field===NULL ? '-' : $this->money($cents);
            }
            $values[]=$item->bank_cabang;$values[]=$item->nama_notaris;$this->cells($values);
        }
        $values=array('','TOTAL','');
        foreach ($totals as $total) $values[]=$this->money($total);
        $values[]='';$values[]='';$this->cells($values,TRUE);
        return $this->pdf->output();
    }
}
