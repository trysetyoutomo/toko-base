<link href="<?php echo Yii::app()->request->baseUrl; ?>/select2/select2.css" rel="stylesheet"/>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/select2/select2.js"></script>

<!-- untukd ate time -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/combo/combodate.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/combo/moment/moment.js"></script>


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

</style>
 <link rel="stylesheet" type="text/css" href="/toko/assets/4e98bbc2/jui/css/base/jquery-ui.css" />
  <script type="text/javascript" src="/toko/assets/4e98bbc2/jui/js/jquery-ui.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#nama').select2("open");

	    // $('#tanggal_pinjam').combodate({
		   //  minYear: 2016,
		   //  maxYear: 2017,
		   //  minuteStep: 10
	    // }); 
	    jQuery('#tanggal_pinjam').datepicker({
	    	'dateFormat':'yy-mm-dd',
	    	'showAnim':'fold',
	    	'showOn':'button',
	    	'buttonText':'Select form calendar',
	    	'buttonImage':'/toko/images/calendar.png',
	    	'buttonImageOnly':true}
    	);
    	 jQuery('#tanggal_kembali').datepicker({
	    	'dateFormat':'yy-mm-dd',
	    	'showAnim':'fold',
	    	'showOn':'button',
	    	'buttonText':'Select form calendar',
	    	'buttonImage':'/toko/images/calendar.png',
	    	'buttonImageOnly':true}
    	);
	    //  $('#tanggal_kembali').combodate({
		   //  minYear: 2016,
		   //  maxYear: 2017,
		   //  minuteStep: 10
	    // }); 
		$(document).on("keypress",".select2-input",function(e){
			if(e.which == 13) {
			    add_item();
	            // $("#nama").select2("open");

			}

		});
	});
	// $('.select2-input').keypress(function (e) {
	//  var key = e.which;
	//  if(key == 13)  // the enter key code
	//  	alert("123");
	//   // {
	//   //   $('input[name = butAssignProd]').click();
	//   //   return false;  
	//   // }
	// });
</script>
	<br>
		<fieldset style="border:1px solid transparent;padding:20px;overflow:auto;">
			<form name="form-peminjaman" id="form-peminjaman">
				
			<h1> Transaksi Peminjaman Barang 
					</h1>
				Tanggal pinjam
				<br>
				<input  data-format="YYYY-MM-DD" data-template="YYYY-MM-DD" type="date" value="<?php echo date('Y-m-d'); ?>" style="display:block;display:inline" name="tanggal_pinjam" id="tanggal_pinjam">
				<br>
				Tanggal Pengembalian
				<br>
				<input data-format="YYYY-MM-DD" data-template="YYYY-MM-DD" type="date" value="<?php echo date('Y-m-d'); ?>" style="display:block;display:inline" name="tanggal_kembali" id="tanggal_kembali">
				<br>
				
				Nama Peminjam
				<input type="text" value="" style="display:block;padding:5px" name="nama_peminjam" id="nama_peminjam">
				Deposit
				<input type="text" value="" style="display:block;padding:5px" name="deposit" id="deposit">
				
				Keterangan
				<textarea id="keterangan" name="keterangan" style="display:block;width:403px;height:70px">Tidak Ada Keterangan</textarea>

			</form>
		
	
		<div class="">
			<div class="" style="display:inline">
				<input type="text" value="<?php echo Yii::app()->user->id ?>" style="display:none" name="user" id="user">
	
				
				<div class="data-table">
				<legend style="font-weight:bolder">Data Barang</legend>

			

				<?php 

				$dataa = CHtml::listdata(Items::model()->findAll(),'id','item_name'); 
				$array = array();
				foreach (Items::model()->findAll("hapus = 0 ") as $x) {
					$array[$x->id] = $x->item_number ." -  ".$x->item_name;
				}
				 ?>
				<label for="nama" 
				style="
				position:fixed;
				top:100px;
				right:50px;

				" 
				>
						<br>
						<h1 class="label-gt">0</h1>
						<?php echo CHtml::dropDownList('nama', '1', $array,array('class'=>'form-control')  );?>
				</label>
				
				

				<label for="jumlah" style="display:inline">Jumlah
					<?php echo CHtml::textField('stok', '1',array('type'=>'number','id'=>'stok','class'=>'form-control'));?>
				</label>
				<button class="btn btn-primary" onClick="add_item()">Tambah</button>
				<script>
				
			
				$(document).ready(function(){
					$("#nama").select2();
		            $("#nama").select2("open");

					$(document).on('click', '.img-hapus-menu', function(e) {
						// alert('masuk');
						var index = $('.img-hapus-menu').index(this);
						$('.baris').eq(index).remove();
						estimasi();
					});

					$(document).on('change', '.jumlah', function(e) {
						var index = $('.jumlah').index(this);
						editable(index);
					});
					window.editable = function(index){
						// alert(index);
						var jumlah = parseInt( $('.jumlah').eq(index).val() );
						if (jumlah==null || jumlah=='0' || $('.jumlah').eq(index).val()=='') {
							alert('tidak boleh kosong');
							$('.jumlah').eq(index).val("1");
							jumlah = 1;
						}
						// var jumlah = parseInt($(this).val());
						var harga = parseInt( $('.harga').eq(index).html() );
						var total_baru = jumlah * harga;
						$('.grand_total').eq(index).html(total_baru);
						estimasi();

					}
					
				  var format = function(num){
			        var str = num.toString().replace("$", ""), parts = false, output = [], i = 1, formatted = null;
			        if(str.indexOf(".") > 0) {
			            parts = str.split(".");
			            str = parts[0];
			        }
			        str = str.split("").reverse();
			        for(var j = 0, len = str.length; j < len; j++) {
			            if(str[j] != ",") {
			                output.push(str[j]);
			                if(i%3 == 0 && j < (len - 1)) {
			                    output.push(",");
			                }
			                i++;
			            }
			        }
			        formatted = output.reverse().join("");
			        return("Rp. " + formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
			    };	


				window.add_item =   function (){
					// try{
							if ($('#nama').val()!=0 && $('#nama').val()!=null ){
								// alert('123');
							$.ajax({
								data : "id="+$('#nama').val(),
								url : "<?php echo Yii::app()->createAbsoluteUrl('items/check') ?>",
								success : function(data){
									// alert(data);
									// alert(data);
									var data = jQuery.parseJSON(data);
									// alert(JSON.stringify(data));
							
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
											"<td>" + data.item_name + "</td>" +
											// "<td><input class='kode' style='width:100%;padding:4px;' maxlength='15' type='text' value='0'/></td>" +
											"<td><input  class='jumlah' id='id_jumlah' style='width:100%;padding:4px;' maxlength='2' type='text' value='"+stok.val()+"'/></td>" +
											"<td class='harga' style='text-align:right'>"+data.total_cost+"</td>" +
											"<td class='grand_total' style='text-align:right'>"+data.total_cost* parseInt($('#stok').val())+"</td>" +
											"<td class='hapus'><i class='fa fa-times img-hapus-menu'>hapus</i></td>" +

											"</tr>"
										);
										
										// $('#users tbody').append(
											// editable();

											estimasi();
										
									}else{
										var now = $('.pk[nilai="'+nama.val()+'"]').closest('.baris').find('.jumlah').val();
									
										$('.pk[nilai="'+nama.val()+'"]').closest('.baris').find('.jumlah').val(parseInt(now)+parseInt(stok.val()));
										$('.jumlah').trigger('change');
										// alert('123');
									}
										// $("#select2-input").focus();
							            $("#nama").select2("open");
									}
											
								});
						}else{
							alert('tidak boleh kosong');
							$('#stok').val(1);
						}
					// }catch(err){
					// 	alert(err);
					// }
				}

			window.estimasi = function(){
				var grand_total = 0; 
				var subtotal = 0; 
				$(".baris").each(function(){
					subtotal = parseInt( $(this).find('.harga').html() ) * parseInt( $(this).find('.jumlah').val())    ;
					grand_total = grand_total + subtotal  ;
					
						// alert($(this).find('.grand_total').html());
					
				});
				$(".label-gt").html(format(grand_total));
			}


			window.kirim =  function (){
				try{
					if (confirm("Yakin ?")==false){return}

					var jml = $('.baris').length;
					var jsonObj = [];
					var inch = 0;
					var tanggal_pinjam  = $('#tanggal_pinjam').val();
					var tanggal_kembali  = $('#tanggal_kembali').val();
					var nama  = $('#nama_peminjam').val();
				 	var deposit  = $('#deposit').val();
					var keterangan  = $('#keterangan').val();
					if (keterangan=="" || tanggal_pinjam=="" || tanggal_kembali=="" || nama=="" || deposit==""){
						alert("Seluruh isian wajib di isi");
						return ;
					}
					// alert(deposit);
					
					var head = {
						tanggal_pinjam : tanggal_pinjam,
						tanggal_kembali : tanggal_kembali,
						nama : nama,
						deposit: deposit,
						keterangan : keterangan
					}
					
					if ($(".baris").length==0){
						alert("barang tidak boleh kosong");
						return false;
					} 
					
					$(".baris").each(function() {
						var idb = $(this).find('.pk').html();
						var jml = $(this).find('.jumlah').val();
						var kode = $(this).find('.kode').val();
						var status = $(this).find('.statusbarang').val();
						var harga = $(this).find('.harga').val();
						
						item = {};
						item["idb"] = idb;
						item["jml"] = jml;
						item["kode"] = kode;
						item["status"] = status;
						item["harga"] = harga;
						jsonObj.push(item);
					});


					// alert(JSON.stringify(jsonObj));
						 
						 $.ajax({
							url: '<?php echo Yii::app()->createAbsoluteUrl('items/pinjam'); ?>', 
							data: {
								jsonObj :jsonObj,
								head :head
							},
							success: function(result){
								// alert(result);
								if (result==1){
									alert('Peminjaman Barang  Berhasil!  ');
									window.location.assign('index.php?r=items/laporanpinjam');
								}else{
									alert(result);
								}
								// alert('haha');
							},
							error:function(result){
								// alert(result);
								alert(JSON.stringify(result));
								// alert('data tidak boleh kosong');
							}
						});
						
					}catch(err){
						alert(err);
					}
				}
				
				});
				</script>
				<div class=" widget-table">	
					<!-- <div class="widget-header">
						<span class="icon-list"></span>
						<h3 class="icon chart">Tabel Penjualan Barang</h3>		
					</div> -->
					<div class="widget-content">
						<table style="width:100%" id="users" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Nama Item</th>
									<!-- <th>Kode Barang </th> -->
									
									<!-- <th>Harga Barang</th> -->
									<th>Jumlah</th>
									<th>Harga</th>
									<th>Total</th>
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
				<button onclick="kirim()" class="btn">Simpan Data</button>
				</fieldset>
				</div>
			</div> <!-- .grid -->


	