<?php
session_start();
$level = $_SESSION['level'];
require_once '../../config/db_connect.php';
$kelas=$_GET['kelas'];
$tapel=$_GET['tapel'];
$smt=$_GET['smt'];
$proyek=$_GET['proyek'];
$ab=substr($kelas,0,1);
if($proyek==0){
?>
	<select class="form-select" id="siswa" name="siswa">
		<option value="0">Pilih Siswa</option>
	</select>
<?php 
}else{
	$nkelas=$connect->query("select * from data_proyek where id_proyek='$proyek'")->fetch_assoc();
	$rmb=$nkelas['kelas'];
	$sql4 = "select penempatan.peserta_didik_id,siswa.nama from penempatan left join siswa on penempatan.peserta_didik_id=siswa.peserta_didik_id where penempatan.rombel='$rmb' and penempatan.tapel='$tapel' and penempatan.smt='$smt' order by siswa.nama asc";;
	$query4 = $connect->query($sql4);
?>
	<select class="form-select" id="siswa" name="siswa">
		<option value="0">Pilih Siswa</option>
		<?php 
		while($nk=$query4->fetch_assoc()){
		?>
		<option value="<?=$nk['peserta_didik_id'];?>"><?=$nk['nama'];?></option>
		<?php } ?>
	</select>
<?php } ?>
