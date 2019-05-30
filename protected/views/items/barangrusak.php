<?php 
   $branch_id = Yii::app()->user->branch();
?>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/select2/select2.css" rel="stylesheet"/>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/select2/select2.js"></script>

<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-custom/jquery-ui.min.css">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-custom/jquery-ui.min.js"></script>


<style type="text/css">
	.stok-stokan tr td{
		border:1px solid transparent!important;
	}
	#trx-b-keluar tr td{
		padding: 10px;
	}
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

	  #full-screen{
      display: none;
      width: 100%;
      height: 100%;
      z-index: 999;
      background-color: rgba(0,0,0,0.4);
      position: fixed;
      top: 0px;
      bottom: 0px;
      left: 0px;
      right: 0px;
      margin: auto;
      }
     #wrapper-item-search{
      display: none;
      background-color: white;
      width: 350px;
      height: 140px;
      border: 2px solid black;
      position: fixed;
      top: 0px;
      bottom: 0px;
      left: 0px;
      right: 0px;
      margin: auto;
      z-index: 1000;
      padding: 10px;
      }
      #wrapper-item-search h1{
      font-size:20px;
      font-weight: bolder;
      }
      #wrapper-item-search .close{
      position: absolute;
      top: 10px;
      right: 10px;
      cursor: pointer;
      }
      #s2id_e1{
        width: 100%;
      }

</style>
<script type="text/javascript">
function list_action(act)
      {
      	 switch(act)
          {
          	   case 113 :

                  $("#full-screen").show();
                  $("#wrapper-item-search").show();
                  $("#e1").select2("open");

                  // alert('123');
                  break;
                case 27:

                  $("#full-screen").hide();
                  $("#wrapper-item-search").hide();
                break;
             }

      }


	$(document).ready(function(){
		// $("#nama > option").each(function() {
			// setTimeout(function(e){
				// add_item(this.value);
			// },500);
		    // alert(this.text + ' ' + this.value);
		// });

		$('body').keydown(function(event){
			var message = "";
	    // var message = '<BR>ada tombol yg di pencet gan!, keyCode = ' + event.keyCode + ' which = ' + event.which;
	    // alert(event.keyCode);
          if (event.keyCode>=0 || event.charCode>=0 || event.which>=0 ){
            // alert("123");
              message = message + '<BR>F1 - F12 / enter pressed';
              list_action(event.keyCode);
          }else{
            // alert("456");
              // list_action_other(event.which);
              // message = message + '<BR>key other than F1 - F12 pressed';
          }

          //print pesan
          $('#msg-keypress').html(message)

      });
		 $('#tanggal').datepicker({ dateFormat: 'yy-mm-dd' });
		// $('#nama').select2("open");
		$(document).on("change","#jeniskeluar",function(e){
			if ($(this).val()=="pengalihan"){
				$(".row-keluar-ke").show();
			}else{
				$(".row-keluar-ke").hide();
			}
		});
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

 <div id="full-screen"></div>
   <div id="wrapper-item-search">
      <p class="close">X</p>
      <h1 >Pencarian Item</h1>
     
      <?php echo CHtml::dropDownList('e1', '1', Items::model()->data_items("MENU"), array('prompt'=>'Silahkan pilih','style'=>'width:100%') ); ?>
      <input style="width: 100%;margin-top: 5px;" type="button" class="mybutton" name="tambah" value="Tambah" onclick="add_item($('#e1').val())">

   </div>
		<fieldset style="border:1px solid transparent;padding:20px;overflow:auto;">
				
			<h1> 
			<i class="fa fa-book"></i>
			Transaksi Barang Keluar
				</h1>
				<hr>
		<table  cellpadding="10" id="trx-b-keluar">
			

		<tr>
			<td>
				Tanggal Transaksi			
			</td>
			<td>
				<input type="text" value="<?php echo date('Y-m-d'); ?>" style="display:inline;padding:5px" name="tanggal" id="tanggal">
			</td>
			
		</tr>
		<tr>
				<td>
					Kode Transaksi			
				</td>
				<td>
					<input readonly="" type="text" value="<?php echo BarangkeluarController::generateKodeBKS(); ?>" style="display:inline;padding:5px"  id="kode_trx">
				</td>
				
			</tr>
			<tr>
				<td>
				Jenis Keluar	
				</td>
				<td>
							
			<select name="jeniskeluar" id="jeniskeluar">
			<?php 
			$data = JenisKeluar::model()->findAll();
			foreach ($data as $key => $value) {
			 ?>
				<option 


				value="<?php echo $value->nama ?>"><?php echo $value->nama ?></option>
			<?php } ?>
			</select>
				</td>
			</tr>

			<tr class="row-keluar-ke" style="display: none;">
			<td>
						
					Keluar ke 
					</td>
					<td>
						<select id="cabang">
						<option value="">Pilih</option>
							<?php 
// "id != '$branch_id' "
							$data = Branch::model()->findAll();
							foreach ($data as $key => $value) { ?>
								<option 
								value="<?php echo $value->id ?>"
								>
								<?php echo $value->branch_name ?></option>
						 	<?php } ?>
						</select>
						
					</td>
				</tr>


			<tr>
				<td>
					Keterangan
				</td>
				<td>
						<textarea id="keterangan" style=";width:403px;height:70px">-</textarea>
				</td>
			</tr>


		</table>
	
		

	
		<div class="">
			<div class="" >
				<input type="text" value="<?php echo Yii::app()->user->id ?>" style="display:none" name="user" id="user">
	
				
				<div class="data-table">
				<hr>
				<legend style="font-weight:bolder">Detail Items</legend>

			
				<?php 

				// $dataa = CHtml::listdata(Items::model()->findAll("hapus = 0 "),'id','item_name'); 
				// $array = array();
				// foreach (Items::model()->findAll("hapus = 0 ") as $x) {
				// 			$array[$x->id] = $x->barcode ." -  ".$x->item_name." - ".$x->ukuran." - ".$x->panjang." - ". $x->ketebalan ;
			
				// }
			$data = Items::model()->data_items("ALL");
					// echo "<pre>";
					// print_r($data);
					// echo "</pre>";

				 ?>



				<div class="row">
					<label for="nama" >Nama</label>
					
						<input type="text" name="nama" id="nama">

					
					<label for="add-all" style="width: 200px;">
						<input type="checkbox" id="add-all" name="add-all" > Tambah Semua						
					</label>
					

					<?php //echo CHtml::dropDownList('nama', '1', Items::model()->data_items("BAHAN"),array('class'=>'form -control','style'=>'width:450px;')  );
// 'empty'=>'silahkan pilih'
					?>						
				</div>
				

				<div class="row">
					<label for="jumlah" >Jumlah Keluar</label>
					<?php echo CHtml::textField('stok', '0',array('type'=>'number','id'=>'stok','class'=>'form-contro l','style'=>'width:50px;'));?>
				</div>

				
				<button class="btn btn-primary" onClick="add_item($('#nama').val())">Tambah</button>
				<script>

				$(document).ready(function(){
					$("#cabang").select2();
						$("#e1").select2({
		 escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
         minimumInputLength: 2,
	});

					// $("#nama").select2();
						// $("#nama").select2({
						// 	 escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
						// 	 placeholder: "Pilih Item",
					 //         minimumInputLength: 2,
						// });

					$("#jeniskeluar").select2();
		            // $("#nama").select2("open");

					$(document).on('click', '.hapus', function(e) {
						// alert('masuk');
						$(this).closest("tr").remove();
						// var index = $('.hapus').index(this);
						// $('.aris').eq(index).remove();
					});
				});
				var data_stok = "";

				function add_item(val){
					// alert(val);
					// if ($('#nama').val()!=0 && $('#nama').val()!=null ){
					if (val!=0 ){
					$.ajax({
						data : "id="+val,
						url : "<?php echo Yii::app()->createAbsoluteUrl('items/check') ?>",
						success : function(data){
							var d = JSON.parse(data);
							// var nama = val;
							// alert(val);
							var stok = $('#stok');
							var count = $('.pk[nilai="'+val+'"]').length;
							if (count==0){
								// data_stok = " ";
								appendToBaris(d,val,stok.val());
							}
							else{
								var now = $('.pk[nilai="'+val+'"]').closest('.baris').find('.jumlah').val();
								$('.pk[nilai="'+val+'"]').closest('.baris').find('.jumlah').val(parseInt(now)+parseInt(stok.val()));
							}
							// $("#select2-input").focus();
				            // $("#nama").select2("open");
						
					}
					});
						}else{
							alert('tidak boleh kosong');
							$('#stok').val(1);
						}
				}
				function appendToBaris(d,barcode,jumlah){

					$('#users tbody').append(
						"<tr class='baris'>" +
							"<td>"+d.nama_kategori+"</td>"+
							"<td>"+d.nama_sub_kategori+"</td>"+
							"<td style='display:none' class='pk' nilai="+barcode+"  >" + barcode + "</td>" +
							"<td>" + d.item_name +"</td>" +
							// "<td>"+

							"<td><input class='jumlah' style='width:100%;padding:4px;' maxlength='15' type='text' value='"+jumlah+"'/>"+
							"<br>"+data_stok+"</td>" +
							"<td  class='stok-awal'>"+d.stok+"</td>"+
							"<td  class='data-stok-akhir'><p class='stok-akhir'>0</p>"+
							"<button data-stok-now='"+d.stok+"' type='button' class='btn btn-danger btn-input-stok-now'><i class='fa fa-pencil'></i> Masukan Stok Saat Ini </button></td>"+

							"<td class='hapus'><i class='fa fa-times fa-2x'></i></td>" +

						"</tr>"
					);
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
					var cabang  = $('#cabang').val();
					var kode_trx  = $('#kode_trx').val();

					if (keterangan==""){
						alert("Keterangan wajib di isi");
						return ;
					}
					
					var head = {
						tanggal : tanggal,
						user : user,
						keterangan : keterangan,
						jeniskeluar : jeniskeluar,
						cabang : cabang,
						kode_trx : kode_trx
					}
					
					if ($(".baris").length==0){
						alert("tidak boleh kosong");
						return false;
					} 
					// var array_kode = [];
					// $('.kode').each(function (index, value){
					// 	array_kode.push($('.kode').eq(index).val());
					// });	
					// if (hasDuplicates(array_kode)){
					// 	alert('Terdapat kode yang sama');
					// 	return false;
					// }
					// alert(JSON.stringify(head));
					// alert(JSON.stringify(array_kode));
					// return false;

					$(".baris").each(function() {
						var idb = $(this).find('.pk').html();
						var jml = $(this).find('.jumlah').val();
						
						var kode = $(this).find('.kode').val();
						var status = $(this).find('.statusbarang').val();
						var harga = $(this).find('.harga').val();
						
						if (parseFloat(jml)!=0){	
							item = {};
							item["idb"] = idb;
							item["jml"] = jml;
							item["kode"] = kode;
							item["status"] = status;
							item["harga"] = harga;
							jsonObj.push(item);
						}
					});
					// alert(JSON.stringify(jsonObj));
					// exit;
					// alert(JSON.stringify(jsonObj));
						 
						 $.ajax({
							url: '<?php echo Yii::app()->createAbsoluteUrl('items/prosesrusakbarang'); ?>', 
							data: {
								jsonObj :jsonObj,
								head :head
							},
							type : "POST",
							success: function(result){
								var json = JSON.parse(result);
								if (json.success==true){
									alert('Data berhasil di simpan !  ');
									window.location.assign("index.php?r=items/laporanrusak");
									// window.location.reload();
								}else{
									alert(result);
								}
								// alert(result);

								// window.location.assign('index.php?r=items/laporanrusak');
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
					<div class="widget-content">
						<table style="width:100%" id="users" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Kategori</th>
									<th>Sub Kategori</th>
									<th>Nama Item</th>
								<!-- 	<th>Ukuran </th> 
									<th>Panjang </th> 
									<th>Ketebalan </th> --> 
									<!-- <th>Kode Barang </th> -->
									
									<!-- <th>Harga Barang</th> -->
									<th>Jumlah Keluar (awal-akhir)</th>
									<th>Jumlah Awal</th>
									<th>Jumlah Akhir</th>
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
				<button onclick="kirim()" class="btn btn-primary">Simpan</button>
				</fieldset>
				</div>
			</div> <!-- .grid -->


	<script type="text/javascript">
		$(document).ready(function(e){

// users tbody
		$(document).on("click",".btn-input-stok-now",function(e){
			// e.preventDefault();
			var stok_input = prompt("Silahkan masukan Stok Akhir ? ",0);
			if (isNaN(stok_input)){
				stok_input = 0;
			}else{
				stok_input = stok_input.replace(/,/g, '.');
				stok_input = parseFloat(stok_input);
				stok_input = Math.round(stok_input * 100) / 100;
			}
			
			$(this).closest("tr").find(".stok-akhir").html(stok_input);



			var stok_awal = parseFloat($(this).attr("data-stok-now")) ;
			var real_stok = stok_awal - stok_input;
			$(this).closest("tr").find(".jumlah").val(real_stok);
			
		});

	$(document).on('keydown', '#nama', function(e) {
		// e.preventDefault();
		if (e.keyCode==13 || e.which==13){
			 
			 add_item($('#nama').val());
				$(this).val("");
				$("#nama").focus(); 
		}

	});
	 $(document).on("change","#e1",function(e){
       var item_id = $("#e1").val();
       if (item_id!=''){
           add_item(item_id);
           $("#e1").select2("open");
           $('#e1').val(0);
       }else{
            $().toastmessage('showToast', {
               text : "Data tidak ditemukan",
               sticky : false,
               type     : 'warning'
           });
       }
   });

		$(document).on("click","#add-all",function(e){
			if ($(this).prop("checked")){
					$.ajax({
						// data : "id="+val,
						url : "<?php echo Yii::app()->createAbsoluteUrl('items/Data_all_items') ?>",
						success : function(data){
							var d = JSON.parse(data);
							$.each(d,function(i,v){
								var idd = v.kode+"-"+v.nama_satuan_id;
								appendToBaris(v,idd,0);
							});

						
						}
					});
			}else{
				$("#users tbody").html("");
			}
		});
	
		});
	</script>