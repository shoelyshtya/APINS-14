<?php 
require_once '../../config/config.php';
require_once '../../config/db_connect.php';
$output = array('success' => false, 'messages' => array());
$idsw = $_POST['idsw'];
$siswa = $_POST['siswa'];
$tapel = $_POST['tapel'];
$smt = $_POST['smt'];
$kelas = $_POST['kelas'];
$sqlp = "SELECT * FROM siswa WHERE peserta_didik_id = '$siswa'";
$queryp = $connect->query($sqlp);
$rs = $queryp->fetch_assoc();
$nama=$rs['nama'];
$cek = $connect->query("select * from penempatan where peserta_didik_id='$siswa' and tapel='$tapel' and smt='$smt'")->num_rows;
if($cek>0){
    $sql = "UPDATE penempatan SET rombel='$kelas' WHERE id_rombel='$idsw'";
}else{
    $sql = "INSERT into penempatan(peserta_didik_id,nama,rombel,tapel,smt) values('$siswa','$nama','$kelas','$tapel','$smt')";
}
$query = $connect->query($sql);
//hahahaha
if($query === TRUE) {
	$output['success'] = true;
	$output['messages'] = $nama." Berhasil ditempatkan di kelas ".$kelas;
} else {
	$output['success'] = false;
	$output['messages'] = 'Error saat mencoba menempatkan siswa';
}
$connect->close();
echo json_encode($output);