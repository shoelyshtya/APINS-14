<?php $data="Nomor Whatsapp";?>
<?php include "layout/head.php"; ?>
</head>

<body class="preload-active aside-active aside-mobile-minimized aside-desktop-maximized">
	<!-- BEGIN Preload -->
	<?php include "layout/loader.php"; ?>
	<!-- END Preload -->
	<!-- BEGIN Page Holder -->
	<div class="holder">
		<!-- BEGIN Aside -->
		<?php include "layout/aside.php";?>
		<!-- END Aside -->
		<!-- BEGIN Page Wrapper -->
		<div class="wrapper ">
			<!-- BEGIN Header -->
			<?php include "layout/header.php";?>
			<!-- END Header -->
			<!-- BEGIN Page Content -->
			<div class="content">
				<div class="container-fluid g-5">
					<div class="row">
						<div class="col-12">
							<!-- BEGIN Portlet -->
							<div class="portlet">
								<div class="portlet-header portlet-header-bordered">
									<h3 class="portlet-title">Nomor Whatsapp</h3>
									<div class="portlet-addon">
										<select id="kelas" name="kelas" class="form-select">
                                            <?php if($level==11){ ?>
											<option value="0">All</option>
											<?php
											$sql3 = "select * from rombel where tapel='$tapel' and smt='$smt' order by nama_rombel asc";
											$query3 = $connect->query($sql3);
											while($nk=$query3->fetch_assoc()){
											?>
															
											<option value="<?=$nk['nama_rombel'];?>">Kelas <?=$nk['nama_rombel'];?></option>
											<?php 
											}
											?>
											<?php }else{ ?>
											<option value="<?=$kelas;?>">Kelas <?=$kelas;?></option>
											<?php } ?>
                                        </select>
									</div>
									<input type="hidden" name="tapel" id="tapel" class="form-control" value="<?=$tapel;?>" placeholder="Username">
									<input type="hidden" name="smt" id="smt" class="form-control" value="<?=$smt;?>" placeholder="Username">
								</div>
								<div class="portlet-body">
									
									<!-- BEGIN Datatable -->
									<table id="datatable-1" class="table table-bordered table-striped table-hover">
										<thead>
											<tr>
												<th>Nama</th>
												<th>Nomor Whatsapp</th>
											</tr>
										</thead>
									</table>
									<!-- END Datatable -->
								</div>
							</div>
							<!-- END Portlet -->
						</div>
					</div>
				</div>
			</div>
			<!-- END Page Content -->
			<!-- BEGIN Footer -->
			<?php include "layout/footer.php";?>
			<!-- END Footer -->
		</div>
		<!-- END Page Wrapper -->
	</div>
	<!-- END Page Holder -->
	<!-- BEGIN Modal -->
	<div class="modal fade" id="info">
		<div class="modal-dialog modal-fullscreen">
			<div class="modal-content">
				<div class="fetched-data"></div>
			</div>
		</div>
	</div>
	<!-- END Modal -->
	<?php include "layout/offcanvas-todo.php"; ?>
	<?php include "layout/script.php"; ?>
	<script>
	var TabelRombel;
	$(document).ready(function(){
		var kelas = $('#kelas').val();
		var tapel = $('#tapel').val();
		var smt = $('#smt').val();
		TabelRombel = $("#datatable-1").DataTable({ 
			"destroy":true,
			"searching": true,
			"paging":true,
			"responsive":true,
			"ajax": "modul/siswa/no-wa.php?kelas="+kelas+"&tapel="+tapel+"&smt="+smt,
		});
		$('#caridata').on( 'keyup', function () {
			TabelRombel.search( this.value ).draw();
		} );
		$('#kelas').change(function(){
				//Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
			var kelas = $('#kelas').val();
			var tapel = $('#tapel').val();
			var smt = $('#smt').val();
			TabelRombel = $("#datatable-1").DataTable({ 
				"destroy":true,
				"searching": true,
				"paging":true,
				"responsive":true,
				"ajax": "modul/siswa/no-wa.php?kelas="+kelas+"&tapel="+tapel+"&smt="+smt,
			});
		});
	});	
	function highlightEdit(editableObj) {
		$(editableObj).css("background","#FFF0000");
	} 
	function saveWA(editableObj,column,id) {
		// no change change made then return false
		if($(editableObj).attr('data-old_value') === editableObj.innerHTML)
		return false;
		// send ajax to update value
		$(editableObj).css("background","#FFF url(loader.gif) no-repeat right");
		$.ajax({
			url: "modul/siswa/simpanWA.php",
			cache: false,
			data:'column='+column+'&value='+editableObj.innerHTML+'&id='+id,
			success: function(response)  {
				console.log(response);
				// set updated value as old value
				$(editableObj).attr('data-old_value',editableObj.innerHTML);
				$(editableObj).css("background","#FFF url(checkup.png) no-repeat right");	
				
			}          
	   });
	}
	</script>
</body>
</html>
