<link href="<?php echo Yii::app()->request->baseUrl; ?>/select2/select2.css" rel="stylesheet"/>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/select2/select2.js"></script>
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
<script type="text/javascript">
	$(document).ready(function(){
		$('#nama').select2("open");
		$(document).on("keypress",".select2-input",function(e){
			if(e.which == 13) {
			    add_item();
	            // $("#nama").select2("open");

			}

		});
	});

</script>
	<br>
		<fieldset style="border:1px solid transparent;padding:20px;overflow:auto;">
				
			<h1> <i class="fa fa-pencil" aria-hidden="true"></i> Transaksi Biaya Pengeluaran </h1>
			<hr>
			 


			<div class="row">
				<label for="jumlah" >Tanggal</label>
				<input type="text" value="<?php echo date('Y-m-d'); ?>" class="form-control" style="max-width:150px;display:inline-block "  name="tanggal" id="tanggal">
			</div>

			<div class="row">
				<label for="jumlah" >Pembayaran</label>
				<select  style="width: 450px;display: inline"  id="pembayaran_via" class="form-control ">
                  <option value="CASH">CASH</option>
                  <?php 
					$criteria = new CDbCriteria;
					$criteria->select ='t.*';
					$criteria->condition =" store_id = '".Yii::app()->user->store_id()."' and group_id = 5 ";
					$criteria->join =" INNER JOIN akuntansi_subgroup as a on a.id = t.subgroup_id ";

                  $m = Bank::model()->findAll("aktif=1");
                  foreach ($m as $key => $value) {
                  echo "<option  value='$value->nama'>$value->nama</option>";
                  } ?>
                  </select>

			</div>


			<div class="row">
			<label for="jumlah" >Jenis Keluar</label>
			<select class="form-control" style="width: 450px;display: inline" name="jeniskeluar" id="jeniskeluar">
			<option>Pilih Jenis</option>
			<?php foreach (AkuntansiAkun::model()->findAll($criteria) as $jb) { ?>
				<option value="<?php echo $jb->id ?>"><?php echo $jb->nama_akun ?></option>
			<?php } ?>
			</select>

			<a style="text-decoration:none" href="<?php echo Yii::app()->createUrl('JenisBeban/create'); ?>">
			<button type="button"  class="btn btn-primary" >
			<i class="fa fa-plus"></i> Tambah
			</button>
			</a>
			</div>

			<div class="row">
				<label>Keterangan</label><textarea id="keterangan" class="form-control" style="max-width:450px;display:inline-block;min-height:100px" >Tidak Ada Keterangan </textarea>
			</div>
			
			<div class="row">
				<label>Total Pengeluaran</label>
				<input type="number" name="total" id="total" value="0" class="form-control" style="max-width:150px;display:inline-block">
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
			           changeMonth: true
					 });
		
					$("#nama").select2();
		            $("#nama").select2("open");

					$(document).on('click', '.img-hapus-menu', function(e) {
						// alert('masuk');
						var index = $('.img-hapus-menu').index(this);
						$('.baris').eq(index).remove();
					});
				});

				function add_item(){
					if ($('#nama').val()!=0 && $('#nama').val()!=null ){
					$.ajax({
						data : "id="+$('#nama').val(),
						url : "<?php echo Yii::app()->createAbsoluteUrl('items/getname') ?>",
						success : function(data){
							// alert(data);
							// alert(data);
						
					
							// alert($('#nama_barang').val());
							var nama = $('#nama');
							// alert(nama.val());
							var stok = $('#stok');
							var count = $('.pk[nilai="'+nama.val()+'"]').length;
							if (count==0){

								$('#users tbody').append(
									"<tr class='baris'>" +
									// "<td></td>";
									"<td style='display:none' class='pk' nilai="+nama.val()+"  >" + nama.val() + "</td>" +
									"<td>" + data + "</td>" +
									// "<td><input class='kode' style='width:100%;padding:4px;' maxlength='15' type='text' value='0'/></td>" +
									"<td><input class='jumlah' style='width:100%;padding:4px;' maxlength='15' type='text' value='"+stok.val()+"'/></td>" +
									"<td class='hapus'><i class=' img-hapus-menu'>	<img style='width:15px' src='img/delete.ico'></i></td>" +

									"</tr>"
								);
							}
							else{
								var now = $('.pk[nilai="'+nama.val()+'"]').closest('.baris').find('.jumlah').val();
								$('.pk[nilai="'+nama.val()+'"]').closest('.baris').find('.jumlah').val(parseInt(now)+parseInt(stok.val()));
							}
							// $("#select2-input").focus();
				            $("#nama").select2("open");
						
					}
					});
						}else{
							alert('tidak boleh kosong');
							$('#stok').val(1);
						}
				}
				function hasDuplicates(array) {
				    var valuesSoFar = Object.create(null);
				    for (var i = 0; i < array.length; ++i) {
				        var value = array[i];
				        if (value in valuesSoFar) {
				            return true;
				        }
				        valuesSoFar[value] = true;
				    }
				    return false;
				}
				function kirim(){
					if (confirm("Yakin ?")==false){return}

					var jml = $('.baris').length;
					var jsonObj = [];
					var inch = 0;
					var tanggal  = $('#tanggal').val();
					var user  = $('#user').val();
					var keterangan  = $('#keterangan').val();
					var jeniskeluar  = $('#jeniskeluar').val();
					var pembayaran_via  = $('#pembayaran_via').val();
					var total  = $('#total').val();
					// alert(keterangan);
					if (keterangan==""){
						alert("Keterangan wajib di isi");
						return ;
					}

					if (total=="" || total==0){
						alert("Total Pengeluaran wajib di isi");
						return ;
					}
					
					var head = {
						tanggal : tanggal,
						user : user,
						keterangan : keterangan,
						jeniskeluar : jeniskeluar,
						total : total,
						pembayaran_via :pembayaran_via
					}
						 
						 $.ajax({
							url: '<?php echo Yii::app()->createAbsoluteUrl('items/pengeluaranbaru'); ?>', 
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
				<div class=" widget-table">	
					<!-- <div class="widget-header">
						<span class="icon-list"></span>
						<h3 class="icon chart">Tabel Penjualan Barang</h3>		
					</div> -->
					<div class="widget-content" style="display:none">
						<table style="width:100%" id="users" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Nama Item</th>
									<!-- <th>Kode Barang </th> -->
									
									<!-- <th>Harga Barang</th> -->
									<th>Jumlah</th>
									<th>aksi</th>
								</tr>
							</thead>
							<tbody>
							<?php //foreach($model as $d):?>
								<!--<tr class="gradeA">
									<td><?php //echo $d->id; ?></td>
									<td><?php //echo $d->nama_barang; ?></td>
									<td><?php //echo $d->jumlah; ?></td>
									<td>
										<?php //echo CHtml::link(CHtml::image(Yii::app()->baseUrl.'/images/view.png'),array('view', 'id'=>$d->id));?>
										<?php //echo CHtml::link(CHtml::image(Yii::app()->baseUrl.'/images/update.png'),array('update', 'id'=>$d->id));?>
										<?php //echo CHtml::link(CHtml::image(Yii::app()->baseUrl.'/images/delete.png'),array('delete', 'id'=>$d->id),array('confirm'=>'Yakin hapus?'));?>
									</td>
								</tr>-->
							<?php //endforeach; ?>
							</tbody>
						</table>
					</div> <!-- .widget-content -->
				</div> <!-- .widget -->
				<button onclick="kirim()" class="btn btn-primary" > <i class="fa fa-save"></i> Simpan</button>
				</fieldset>
				</div>
			</div> <!-- .grid -->


	