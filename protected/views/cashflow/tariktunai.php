<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-custom/jquery-ui.min.css">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-custom/jquery-ui.min.js"></script>

<style type="text/css">
	#footer{
		display: none;
	}
	#users tr td{
		border: 1px solid #c3d9ff;
	}
	.dalem{
		width:100%;
	}
	#page{
	}
	label{
		width: 100px;
	}
	.row{
		margin-left: 0px;
		margin-bottom: 5px;
	}

</style>

	<br>
		<fieldset style="border:1px solid transparent;padding:20px;overflow:auto;">
				
			<h1> <i class="fa fa-sign-out" aria-hidden="true"></i> Tarik Tunai </h1>
			<hr>
			
			<div class="row">
				<label for="jumlah" >Tanggal Tarik Tunai</label>
				<input type="text" value="<?php echo date('Y-m-d'); ?>" class="form-control" style="max-width:150px;display:inline-block "  name="tanggal" id="tanggal">
			</div>

			<div class="row">
				<label for="jumlah" >Akun Bank</label>
				<select  style="width: 450px;display: inline"  id="pembayaran_via" class="form-control ">
                  <?php 
					$criteria = new CDbCriteria;
					$criteria->select ='t.*';
					$criteria->condition =" store_id = '".Yii::app()->user->store_id()."' and group_id = 5 ";
					$criteria->join =" INNER JOIN akuntansi_subgroup as a on a.id = t.subgroup_id ";

                  $m = Bank::model()->findAll("aktif=1 and nama != 'CASH'");
                  foreach ($m as $key => $value) {
                  	echo "<option  value='$value->nama'>$value->nama</option>";
                  } ?>
                  </select>

			</div>


			<div class="row">
				<label>Nominal Tarik Tunai</label>
				<input type="text" name="total" id="total" value="0" class="form-control" style="max-width:450px;display:inline-block;text-align:right" />
			</div>

            <div class="row">
				<label>Biaya </label>
				<input type="text" name="biaya" id="biaya" value="0" class="form-control" style="max-width:450px;display:inline-block;text-align:right">
			</div>
			
			<div class="row">
				<label>Catatan</label>
				<textarea id="keterangan" class="form-control"  style="max-width:450px;display:inline-block;min-height:100px" >-</textarea>
			</div>
			
		

	
		<div class="">
			<div class="" style="display:inline">
				<input type="text" value="<?php echo Yii::app()->user->id ?>" style="display:none" name="user" id="user">
	
				
				<div class="data-table">
				<!-- <legend style="font-weight:bolder">Data Barang</legend> -->

			

				<?php 

				$dataa = CHtml::listdata(Items::model()->findAll(),'id','item_name'); 
				$array = array();
				foreach (Items::model()->findAll() as $x) {
					$array[$x->id] = $x->item_number ." -  ".$x->item_name;
				}
				 ?>
	
				<script>

				$(document).ready(function(){
					 $('#tanggal').datepicker({ 
					 	dateFormat: 'yy-mm-dd' ,
			           changeYear: true,
			           changeMonth: true,
			           maxDate: "<?=date("Y-m-d")?>"
					 });
		
					$("#jeniskeluar").select2();
		            // $("#jeniskeluar").select2("open");

					$(document).on('click', '.img-hapus-menu', function(e) {
						// alert('masuk');
						var index = $('.img-hapus-menu').index(this);
						$('.baris').eq(index).remove();
					});
				});

		
				function kirim(){
					if (confirm("Data akan disimpan, lanjutkan ?")==false){return}

					var jml = $('.baris').length;
					var jsonObj = [];
					var inch = 0;
					var tanggal  = $('#tanggal').val();
					var user  = $('#user').val();
					var keterangan  = $('#keterangan').val();
					var jeniskeluar  = $('#jeniskeluar').val();
					var pembayaran_via  = $('#pembayaran_via').val();
					var total  = $('#total').val();
					var biaya  = $('#biaya').val();
					// alert(keterangan);
					if (keterangan==""){
						alert("Keterangan wajib di isi");
						return ;
					}

					if (total=="" || total==0){
						alert("Total Tarik wajib di isi");
						return ;
					}
					
					var head = {
						tanggal : tanggal,
						user : user,
						keterangan : keterangan,
						total : total,
						biaya : biaya,
						pembayaran_via :pembayaran_via
					}
						 
						 $.ajax({
							url: '<?php echo Yii::app()->createAbsoluteUrl('cashflow/tariktunai'); ?>', 
							data: {
								// jsonObj :jsonObj,
								head :head
							},
							success: function(result){
								// alert(result);
								alert('Data berhasil di simpan !  ');
								window.location.reload();
								// window.location.assign('index.php?r=items/laporan_pengeluaran');
								// alert('haha');
							},
							error:function(result){
								// alert(result);
								alert(JSON.stringify(result));
								// alert('data tidak boleh kosong');
							}
						});
						
					}
				</script>
				<button onclick="kirim()" class="btn btn-primary" > <i class="fa fa-save"></i> Simpan</button>
				</fieldset>
				</div>
			</div> <!-- .grid -->


	