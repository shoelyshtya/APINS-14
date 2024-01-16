<?php
 include 'fpdf/fpdf.php';
 include 'exfpdf.php';
 include 'easyTable.php';
 include "../modul/qrcode/phpqrcode/qrlib.php";
 include '../config/db_connect.php';
 function TanggalIndo($tanggal)
{
	$bulan = array ('Januari',
				'Februari',
				'Maret',
				'April',
				'Mei',
				'Juni',
				'Juli',
				'Agustus',
				'September',
				'Oktober',
				'November',
				'Desember'
			);
	$split = explode('-', $tanggal);
	return $split[2] . ' ' . $bulan[ (int)$split[1]-1 ] . ' ' . $split[0];
};
$idp=$_GET['idp'];
$kelas=$_GET['kelas'];
$smt=$_GET['smt'];
$ab=substr($kelas, 0, 1);
$tapel=$_GET['tapel'];
$tahun1=substr($tapel,0,4);
$tahun2=substr($tapel,5,4);
if($smt==1){
	$smts="Ganjil";
}else{
	$smts="Genap";
};
$sqls = "select * from siswa where peserta_didik_id='$idp'";
$querys = $connect->query($sqls);
$siswa=$querys->fetch_assoc();
$rombs=$connect->query("select * from penempatan where peserta_didik_id='$idp' and tapel='$tapel' and smt='$smt'")->fetch_assoc();
$namafilenya="Raport ".$siswa['nama']." Semester ".$smt." Tahun ".$tahun1."-".$tahun2.".pdf";
//$namafilenya=$tahun1.$tahun2.$smt."-".$siswa['nama'].".pdf";
 
 $pdf=new exFPDF('P','mm',array(210,297));
 
 $pdf->AddPage(); 
 $pdf->SetFont('helvetica','',12);

 $table2=new easyTable($pdf, 1);
 $table2->rowStyle('font-size:15; font-style:B;');
 $table2->easyCell('KETERANGAN PINDAH SEKOLAH', 'align:C;');
 $table2->printRow();
 $table2->endTable(10);
 
  $table2=new easyTable($pdf, 1);
 $table2->rowStyle('font-size:12;');
 $table2->easyCell('Nama Peserta Didik : '.$siswa['nama'], 'align:L;');
 $table2->printRow();
 $table2->endTable(1);
 
 
//====================================================================
//Isi Mutasi
$pdf->SetFont('arial','',12);
$rapo=new easyTable($pdf, '{35, 40, 55, 100}', 'border:1');
$rapo->rowStyle('font-size:14; font-style:B; bgcolor:#BEBEBE;min-height:19');
$rapo->easyCell('Tanggal','align:C; valign:M');
$rapo->easyCell('Kelas yang Ditinggalkan','align:C; valign:M');
$rapo->easyCell('Alasan','align:C; valign:M');
$rapo->easyCell('Tanda Tangan Kepala Sekolah, Stempel Sekolah, dan Tanda Tangan Orang Tua/Wali','align:C; valign:M');
$rapo->printRow(true);

$rapo->rowStyle('font-size:12;min-height:30');
$rapo->easyCell('','align:C; valign:T');
$rapo->easyCell('','valign:T');
$rapo->easyCell('','align:C; valign:T');
$rapo->easyCell(".\n...................., ...................................\nKepala Sekolah\n\n\n\n\n\n\n ...................................................\nNIP.\n\n\nOrang Tua/Wali\n\n\n\n\n\n...................................................\n",'valign:T');
$rapo->printRow();
$rapo->rowStyle('font-size:12;min-height:30');
$rapo->easyCell('','align:C; valign:T');
$rapo->easyCell('','valign:T');
$rapo->easyCell('','align:C; valign:T');
$rapo->easyCell(".\n...................., ...................................\nKepala Sekolah\n\n\n\n\n\n\n ...................................................\nNIP.\n\n\nOrang Tua/Wali\n\n\n\n\n\n...................................................\n",'valign:T');
$rapo->printRow();

//akhir tabel rapor
$rapo->endTable(5);



 //$pdf->Output('D',$namafilenya);
 $pdf->Output();
 //$pdf->Output('F',$namafilenya);
