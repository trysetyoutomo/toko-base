<link href="<?php echo Yii::app()->request->baseUrl; ?>/select2/select2.css" rel="stylesheet"/>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/select2/select2.js"></script>



<style type="text/css">
	#trx-b-masuk tr td{
		padding: 5px;
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

</style>

<style type="text/css">
    #wrap-selisih{
        position: fixed;
        top: 20px;
        right: 20px;
        width: 300px;
        height: 350px;
        background-color: white;
        padding: 10px;

        border: 3px solid black;

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
  <?php 
     $model = Items::model()->data_items("TANPA_PULSA");
    $this->renderPartial("application.views.items.inc-pencarian-items",
      array("model"=>$model)
    );
   ?>

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
		$("#e1").select2({
		 escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
         minimumInputLength: 2,
		});



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

		$( "#tambah-supplier-form" ).dialog({
			minWidth : 650,
			modal : true,
			open : false
		});
		 $("#tambah-supplier-form").dialog("close");
		 $('#tanggal').datepicker({ dateFormat: 'yy-mm-dd',changeMonth:true,changeYear:true,});
		 $('#tanggal-jt').datepicker({ dateFormat: 'yy-mm-dd',changeMonth:true,changeYear:true,});


		// $('#nama').select2("open");

		$(document).on("change","#isbayar",function(e){
			if ($(this).val()=="0")
				$("#row-jatuh-tempo").show();
			else
				$("#row-jatuh-tempo").hide();
		});
		$(document).on("keypress",".select2-input",function(e){
			if(e.which == 13) {
			    add_item($('#nama').val());
	            // $("#nama").select2("open");row-jatuh-tempo

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
				
			<h1> 
			<i class="fa fa-book"></i>
			Purchase Order (PO) </h1>
			<hr>
			<div class="row">
			<div class="col-sm-12 alert alert-info " role="alert">
				<div id="shorcut"  >
					<ul class="" style="list-style:none;" >
					<li class="d-inline">Esc = Batal </li>
					<li class="d-inline"> F2 = Pilih Item </li>
					</ul>
				</div>
			</div>
			</div>

			<div class="row">
				<div class="col-sm-4">
			<table cellpadding="0" id="trx-b-masuk" class="w-100" style="width:100%">
			<tr>
				<td colspan="2">
						<legend style="font-weight:bolder">Data Transaksi</legend>

				</td>
			</tr>
				<tr>
					<td>
						Tanggal PO			
					</td>
					<td>
						<input type="text" value="<?php echo date('Y-m-d'); ?>" style="display:inline;padding:5px" class="form-control" name="tanggal" id="tanggal">
					</td>
					
				</tr>
				<tr>
					<td>
						Kode PO			
					</td>
					<td>
						<input class="form-control" readonly="" type="text" value="<?php echo BarangMasukController::generateKodePO(); ?>" style="display:inline;padding:5px"  id="kode_trx">
					</td>
					
				</tr>
				<tr style="display: none;">
					<td>
						
			No Faktur
					</td>
					<td>
			<input type="text" value="-"  style="display:inline;padding:5px" name="faktur" id="faktur">
						
					</td>
				</tr>
				<tr style="display: none;">
					<td>
						
			Status Pembayaran
					</td>
					<td>
						<select id="isbayar">
							<option value="1">Sudah Bayar</option>
							<option value="0">Belum Bayar</option>
						</select>
						
					</td>
				</tr>
					<tr>
					<td>
						
			Supplier
					</td>
					<td>
					<select id='supplier-data' style='padding:4px;' maxlength='15'>					
					<?php
					$store_id = Yii::app()->user->store_id();
					 foreach (Supplier::model()->findAll(" store_id = '$store_id' ") as $s):  ?>
					<option value='<?php echo $s->nama ?>'><?php echo $s->nama ?></option>
					 <?php endforeach; ?>	
					 </select>
					</td>
				</tr>
				<tr id="row-jatuh-tempo" style="display: none;">
					<td>
						
			Jatuh tempo
					</td>
					<td>
							<input type="text" value="<?php echo date('Y-m-d'); ?>" style="display:inline;padding:5px" name="tanggal" id="tanggal-jt">
						
					</td>
				</tr>

				<tr style="display: none;">
					<td>
						
					Masuk ke 
					</td>
					<td>
						<select id="cabang">
							<?php 
							 $branch = Yii::app()->user->branch();

							$data = Branch::model()->findAll("id = '$branch' ");
							foreach ($data as $key => $value) { ?>
								<option 
								value="<?php echo $value->id ?>"
								>
								<?php echo $value->branch_name ?></option>
						 	<?php } ?>
						</select>
						
					</td>
				</tr>
					<tr style="display: none;">
					<td>
						
					Etalase 
					</td>
					<td>
						<select id="letak">
							<?php 
							 $branch = Yii::app()->user->branch();

							$data = Letak::model()->findAll("branch_id = '$branch' ");
							foreach ($data as $key => $value) { ?>
								<option 
								value="<?php echo $value->id ?>"
								>
								<?php echo $value->kode.' - '.$value->nama ?></option>
						 	<?php } ?>
						</select>
						
					</td>
				</tr>
				<tr>
					<td>
						Keterangan
					</td>
					<td>
						<textarea class="form-control" placeholder="barang masuk" id="keterangan" style="display:inline;height:70px">-</textarea>
					</td>
				</tr>
				<tr>
					<td colspan="2">
							<button onclick="kirim()" class="btn btn-primary">Simpan</button>
					</td>
				</tr>
			</table>
		</div>
	
			<div class="col-sm-7" >
				<input type="text" value="<?php echo Yii::app()->user->id ?>" style="display:none" name="user" id="user">
	
				
				<div class="data-table">
				<legend style="font-weight:bolder">Keranjang</legend>

			

				<?php 

				$dataa = CHtml::listdata(Items::model()->findAll("hapus = 0 "),'id','item_name'); 
				$array = array();
				foreach (Items::model()->findAll("hapus = 0 ") as $x) {
					// $array[$x->id] = $x->barcode ." -  ".$x->item_name." - ".$x->ukuran." - ".$x->panjang." - ". $x->ketebalan ;
					$array[$x->id] = $x->barcode ." -  ".$x->item_name;
				}
				 ?>

				<div class="row">
					<label for="nama" >Barcode</label>
					<?php // echo CHtml::dropDownList('nama', '1', Items::model()->data_items("BAHAN"),array('class'=>'for m-control')  );?>
					<input type="text" name="nama" id="nama" class="form-control " style='max-width:100px;display:inline'>

					

					<label for="add-all" style="width: 200px;display: none;">
						<input type="checkbox" name="add-all" id="add-all" > Tambah Semua						
					</label>
				</div>
				

				<div class="row">
					<label for="jumlah" >Jumlah</label>
					<?php echo CHtml::textField('stok', '1',array('type'=>'number','id'=>'stok','class'=>'form-control','style'=>'max-width:50px;display:inline'));?>
					<button style="margin-top: 1rem;" class="btn btn-primary"  onClick="add_item($('#nama').val())">Tambah ke Keranjang</button>
			
				</div>
				<hr>
				<script>

$(document).ready(function(){
	$("#isbayar").select2();
	$("#cabang").select2();
	$("#letak").select2();
	$("#letak").select2();
	$("#supplier-data").select2();
    // $("#nama").select2("open");


    <?php 
    if (isset($_REQUEST['id']) && !empty($_REQUEST['id']) ) {
    	$id = $_REQUEST['id'];
    	?>
    	$("#nama").val("<?php echo $id ?>").trigger("change");
    	add_item();
    	<?php 
    }
    ?>
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

	
	$(document).on('change', '.satuan', function(e) {
		var e = $(this);
		// var harga_beli = $this().attr("value-harga-beli");
		// alert(harga_beli);
		var harga_beli = $(this).find("option:selected").attr("value-harga-beli");
		$(this).closest(".baris").find(".harga").val(harga_beli);
		
		
		//   $.ajax({
  //           // type: 'POST',
  //           url: '<?php echo Yii::app()->createAbsoluteUrl("Items/GetHargaBeliBySatuanID"); ?>',
  //          	data : "id="+satuan_id,
  //           success:function(data){
  //           	var json = JSON.parse(data);
  //           	e.find("tr").find(".harga").val(json.harga_beli);
  //           },
  //           error:function(data){
  //               // alert(data);
  //               // alert(JSON.stringify(data));
  //           },
  
  //           dataType:'html'
  //       });


	});
	$(document).on('click', '.hapus', function(e) {
		// alert('masuk');
		var index = $('.hapus').index(this);
		$('.baris').eq(index).remove();
	});

	function getSupplier(i){
        $.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createAbsoluteUrl("Supplier/GetSupplier"); ?>',
           
            success:function(data){
            	// alert(data);
               $(".supplier").eq(i).html(data);
               // $("#namapel").val("umum").trigger("change");
            },
            error:function(data){
                // alert(data);
                alert(JSON.stringify(data));
            },
  
            dataType:'html'
        });
     }
     var i = 0;
	 $(document).on("click",".tambah-supplier",function(e){
	 	 i = $(".tambah-supplier").index(this);
	 	 $("#tambah-supplier-form").dialog("open");

	 });
	 $(document).on("submit","#supplier-form",function(e){
        e.preventDefault();
         var data = $(this).serializeArray();
         // alert(data);
         data.push({ name: "isajax", value: "true" });
         // alert(data);

        $.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createAbsoluteUrl("Supplier/create"); ?>',
            data:data,
            success:function(data){
                // alert(data);
                if (data=="sukses"){
                    // getCustomer(); 
                    getSupplier(i);
                     $("#tambah-supplier-form").dialog("close");
                     $("#reset-btn").trigger("click");
                }
                else
                    alert(data);
            },
            error:function(data){
                // alert(data);
                alert(JSON.stringify(data));
            },
  
            dataType:'html'
        });
     });

function add_item(val){
					if (val!=0 ){

					// if ($('#nama').val()!=0 && $('#nama').val()!=null ){
					$.ajax({
						data : "id="+val,
						url : "<?php echo Yii::app()->createAbsoluteUrl('items/check') ?>",
						success : function(data){
							var d = JSON.parse(data);
							if (d.item_name!=null){
							// if ()

							// var string = "<option value='0'>pcs</option>";
							var string = " ";

							$.each(d.satuan,function(i,v){
								if (v.isdefault==1){	
									string+= "<option value-harga-beli="+v.harga_beli+"  value-id="+v.id+" selected value='"+v.satuan+"'>"+v.nama_satuan+"</option>";
								}else{
									string+= "<option value-harga-beli="+v.harga_beli+" value-id="+v.id+" value='"+v.satuan+"'>"+v.nama_satuan+"</option>";
								}
							});
							
							var select = "<select class='satuan'>"+string+"</select>";
							// alert(select);

							// alert($('#nama_barang').val());
							// var nama = $('#nama');
							// alert(nama.val());
							var stok = $('#stok');
							// alert(stok.val());
							var count = $('.pk[nilai="'+val+'"]').length;
							// if (count==0){

							appendToBaris(d,val,stok.val(),d.harga_beli);
							kalkulasiBeli();

						// alert("123");

								
							// getSupplier();
							// }
							// else{
							// 	var now = $('.pk[nilai="'+nama.val()+'"]').closest('.baris').find('.jumlah').val();
							// 	$('.pk[nilai="'+nama.val()+'"]').closest('.baris').find('.jumlah').val(parseInt(now)+parseInt(stok.val()));
							// }
							// $("#select2-input").focus();
				            // $("#nama").select2("open");
						
						}else{
							alert("Barcode tidak ditemukan");
							$("#nama").focus();
						}
					}
					});
						}else{
							$("#nama").focus();
							alert('tidak boleh kosong');
							$('#stok').val(1);
						}
				}
				function appendToBaris(d,barcode,jumlah){
					var nama_sub_kategori ;
					if (d.nama_sub_kategori==null){
						nama_sub_kategori = "-";
					}else{
						nama_sub_kategori = d.nama_sub_kategori;
					}


					$('#users tbody').append(
					"<tr class='baris'>" +
					// "<td></td>";
					"<td>"+d.nama_kategori+"</td>"+
					"<td>"+nama_sub_kategori+"</td>"+
					// "<td>1</td>"+
					"<td style='display:none'  class='pk' nilai="+barcode+"  >" + barcode + "</td>" +
					"<td>" + d.item_name + "</td>" +

					// "<td><input class='kode' style='width:100%;padding:4px;' maxlength='15' type='text' value='0'/></td>" +
					"<td><input class='jumlah' style='width:100%;padding:4px;' maxlength='15' type='text' value='"+jumlah+"'/></td>" +

					// "<td>"+select+"</td>" +
					"<td class='td-satuan' value='"+d.nama_satuan_id+"'>"+d.nama_satuan+"</td>" +


					"<td><input class='harga' value='"+d.harga_beli+"' style='width:100%;padding:4px;' maxlength='15' value='0' type='text'/></td>" +
					// "<td>"+

					
					

					// "<select class='supplier' style='width:70%;padding:4px;' maxlength='15' >"+
					// <?php foreach (Supplier::model()->findAll() as $s):  ?>
					// "<option value='<?php echo $s->id ?>'><?php echo $s->nama ?></option>"+
					// <?php endforeach; ?>
					// "</select>"+
					// "<button  class='btn btn-primary tambah-supplier' >Tambah Supplier</button>"+
					// "&nbsp;&nbsp;"+
					// "</td>" +
					"<td >&nbsp;<i  class='hapus fa fa-times'></i > "+

					"</td> " +

					"</tr>"
				);
				}
					// });

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
					var tanggal_jt  = $('#tanggal-jt').val();
					var faktur  = $('#faktur').val();
					var user  = $('#user').val();
					var keterangan  = $('#keterangan').val();
					var isbayar  = $('#isbayar').val();
					var kode_trx  = $('#kode_trx').val();
					var cabang  = $('#cabang').val();
					var letak  = $('#letak').val();
					var sumber  = $('#supplier-data').val();
					// alert(cabang);

					if (isbayar=="1"){
						if (faktur==""){
							alert("Faktur Tidak boleh kosong");
							return ;
						}
					}
					// alert(keterangan);
					// if (keterangan==""){
					// 	alert("Keterangan wajib di isi");
					// 	return ;
					// }
					
					var head = {
						tanggal : tanggal,
						user : user,
						faktur : faktur,
						keterangan : keterangan,
						cabang : cabang,
						letak : letak,
						kode_trx : kode_trx,
						tanggal_jt : tanggal_jt,
						isbayar : isbayar,
						sumber : sumber
					}
					
					if ($(".baris").length==0){
						alert("tidak boleh kosong");
						return false;
					} 
					var array_kode = [];
					$('.kode').each(function (index, value){
						array_kode.push($('.kode').eq(index).val());
					});	
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
						jml = jml.replace(/,/g, '.');
						jml = parseFloat(jml);
						jml = Math.round(jml * 100) / 100;

						// alert(jml);
						var kode = $(this).find('.kode').val();

						var status = $(this).find('.statusbarang').val();
						var harga = $(this).find('.harga').val();
						// var supplier = $(this).find('.supplier').val();
						// var satuan = $(this).find('.satuan').val();
						// var satuan = $(this).find('.satuan').find(" option:selected").attr("value-id");
						var satuan = $(this).find('.td-satuan').attr("value");
						// alert(satuan);
					


						// if (jml<1){
						// 	alert('jumlah tidak boleh kosong');
						// 	exit;
						// }
						// if (jml==""){
						// 	alert('jumlah tidak boleh kosong');
						// 	exit;
						// }

						if (harga==""){
							alert('Harga tidak boleh kosong');
							exit;
						}


						if (parseFloat(jml)>0){	
							item = {};
							item["idb"] = idb;
							item["jml"] = jml;
							item["kode"] = kode;
							// alert(kode);
							item["status"] = status;
							item["harga"] = harga;
							// item["supplier"] = supplier;
							item["satuan"] = satuan;
							jsonObj.push(item);
						}
					});

					// alert(JSON.stringify(jsonObj));
					// alert(JSON.stringify(head));
						 
						 $.ajax({
							url: '<?php echo Yii::app()->createAbsoluteUrl('items/prosesmasukbarangPO'); ?>', 
							data: {
								jsonObj :jsonObj,
								head :head
							},
							type : "POST",
							success: function(result){
								// alert(result);
								// alert(re.status);
								var re = JSON.parse(result);
								if (re.status == "1"){
									alert('Berhasil! mencatan Data PO  ');
									
									window.location.reload();
									// window.location.assign('index.php?r=items/laporanmasuk');
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
									<th>Jumlah</th>
									<th>Satuan</th>
									<th>Harga Satuan </th> 
									<!-- <th>Ukuran </th> 
									<th>Panjang </th> 
									<th>Ketebalan </th>  -->
									<!-- <th>Supplier / Pemasok </th>  -->
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
			
				</div>
			</div> <!-- .grid -->


	
<div id="tambah-supplier-form" title="Tambah Supplier" >
<?php
$model = new Supplier;
$this->renderPartial('application.views.supplier._form',array("model"=>$model));
 ?>
</div>

	<script type="text/javascript">
		$(document).ready(function(e){


		$(document).on("click","#add-all",function(e){
			if ($(this).prop("checked")){
					$.ajax({
						// data : "id="+val,
						url : "<?php echo Yii::app()->createAbsoluteUrl('items/Data_all_items') ?>",
						success : function(data){
							var d = JSON.parse(data);
							$.each(d,function(i,v){
								var idd = v.kode+"-"+v.id;
								appendToBaris(v,idd,0);
							});

						
						}
					});
			}else{
				$("#users tbody").html("");
			}
		});
// users tbody
		// $(document).on("click","#add-all",function(e){
		// 	if ($(this).prop("checked")){
		// 		$("#stok").val(0);
		// 		$("#nama > option").each(function(i,v) {
		// 			// setTimeout(function(e){
		// 				// alert(this.value);
		// 				// alert(this.value);
		// 			var val = this.value;
		// 		    setTimeout(function(){
		// 		       nad(val);
		// 		    },500 + ( i * 500 ));
		// 		  //   setTimeout(function(){
		// 				// add_item(this.value);
				     
		// 		  //      }, 450);
		// 		  //   },500 + ( i * 500 ));
		// 			// },1000);
		// 		    // alert(this.text + ' ' + this.value);
		// 		});
		// 		// $("#stok").val(1);
		// 	}else{
		// 		$("#users tbody").html("");
		// 	}

		// });
	
		});
	</script>