

<style type="text/css">
	#trx-b-masuk tr td{
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
  </style>
 <?php 
 $this->renderPartial('inc-pencarian-items');
 ?>
<!-- 

<div id="wrap-selisih" style="display: none;">
	<legend style="font-weight:bolder">Masukan Menu</legend>
    <hr>
    <table>
    	<tr>
    		<td><h5>Sub Total</h5></td>
    		<td style="width: 30px;">:</td>
    		<td><label id="total-sub">0</label></td>
    	</tr>
    	<tr>
    		<td>Diskon</td>
    		<td>:</td>
    		<td>
    			<input type="number" id="total-diskon" value="0">
    		</td>
    	</tr>
    	<tr>
    		<td>Grand Total</td>
    		<td>:</td>
    		<td>
    			<label id="total-grand">0</label>
    		</td>
    	</tr>
    	<tr>
    		<td>Bayar</td>
    		<td>:</td>
    		<td>
    			<input type="number" id="total-bayar" value="0">
    		</td>
    	</tr>
    	<tr>
    		<td>Kembali</td>
    		<td>:</td>
    		<td>
    			<input type="text" id="total-kembali" value="0">
    		</td>
    	</tr>
    	<tr>
    		<td colspan="2">
					<button onclick="kirim()" class="btn btn-primary">Simpan</button>

    		</td>
    	</tr>




    </table>
</div> -->
<script type="text/javascript">
	
	$(document).ready(function(){


		
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
	<div class="row">
		<div class="col-sm-6">
			<fieldset style="border:1px solid transparent;padding:20px;overflow:auto;">
				
			<h1> 
			<i class="fa fa-book"></i>
			Pembelian Barang </h1>

			<div id="accordion">
			 <!-- <h3>Transaksi Pembelian</h3> -->
			<div>
			<table border="0" cellpadding="10" id="trx-b-masuk" style="width: 300px;">
			<tr>
				<td colspan="4">
						<legend style="font-weight:bolder">Data Transaksi</legend>

				</td>
			</tr>
				<tr>
					<td>
						Tanggal Transaksi			
					</td>
					<td>
						<input type="text" value="<?php echo date('Y-m-d'); ?>" style="display:inline;padding:5px" name="tanggal" id="tanggal">
					</td>
					<td>
						Kode Transaksi			
					</td>
					<td>
						<input readonly="" type="text" value="<?php echo BarangmasukController::generateKodeBMS(); ?>" style="display:inline;padding:5px"  id="kode_trx">
					</td>
				</tr>
				<tr>
					
					
					
				</tr>
				<tr>
					<td>
						
			No Faktur
					</td>
					<td>
			<input type="text" value="-"  style="display:inline;padding:5px" name="faktur" id="faktur">
						
					</td>
					<td>
						
			Supplier
					</td>
					<td>
					<select id='supplier-data' style='width:70%;padding:4px;' maxlength='15'>					
					<?php foreach (Supplier::model()->findAll() as $s):  ?>
					<option value='<?php echo $s->nama ?>'><?php echo $s->nama ?></option>
					 <?php endforeach; ?>	
					 </select>
					</td>
				</tr>
				<tr >
					<td>
						
			Kode PO
					</td>
					<td colspan="5">
						<select id='po-data' style='width:70%;padding:4px;' maxlength='15'>		
						<option value="">Pilih</option>			
						<?php foreach (PurchaseOrder::model()->findAll(" status_aktif = 1 ") as $s):  ?>
						<option value='<?php echo $s->kode_trx ?>'><?php echo $s->kode_trx ?></option>
						 <?php endforeach; ?>	
					 </select>
			

						
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
						
					Letak 
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
					<td colspan="6">
						<textarea style="width: 100%" placeholder="barang masuk" id="keterangan" >Barang Masuk</textarea>
					</td>
				</tr>
			</table>

	<td colspan="4">
	<legend style="font-weight:bolder">Ringkasan</legend>
	</td>

    <table>
    	<tr>
    		<td><h5>Sub Total</h5></td>
    		<td style="width: 30px;">:</td>
    		<td><label id="total-sub">0</label></td>
    	</tr>
    	<tr>
    		<td>Diskon</td>
    		<td>:</td>
    		<td>
    			<input type="number" id="total-diskon" value="0">
    		</td>
    	</tr>
    	<tr>
    		<td>Grand Total</td>
    		<td>:</td>
    		<td>
    			<label id="total-grand">0</label>
    		</td>
    	</tr>
    	<tr>
    		<td>Bayar</td>
    		<td>:</td>
    		<td>
    			<input type="number" id="total-bayar" value="0">
    		</td>
    	</tr>
    	<tr>
    		<td>Kembali</td>
    		<td>:</td>
    		<td>
    			<input type="text" id="total-kembali" value="0">
    		</td>
    	</tr>
    	<tr>
    		<td colspan="2">
					<button onclick="kirim()" class="btn btn-primary">Simpan</button>

    		</td>
    	</tr>




    </table>
			</div>
			<div>


				
				
				</fieldset>
		</div>
		<div class="col-sm-6" style="margin-top:90px;"> <!-- kolom kedua -->
			<div class="">
			<div class="" >
				<input type="text" value="<?php echo Yii::app()->user->id ?>" style="display:none" name="user" id="user">
	
				
				<div class="data-table">
				<legend style="font-weight:bolder">Masukan Menu</legend>

			

				<?php 

				$dataa = CHtml::listdata(Items::model()->findAll("hapus = 0 "),'id','item_name'); 
				$array = array();
				foreach (Items::model()->findAll("hapus = 0 ") as $x) {
					// $array[$x->id] = $x->barcode ." -  ".$x->item_name." - ".$x->ukuran." - ".$x->panjang." - ". $x->ketebalan ;
					$array[$x->id] = $x->barcode ." -  ".$x->item_name;
				}
				 ?>

				<div class="row">
					<label for="nama" >Nama</label>
					<?php //secho CHtml::dropDownList('nama2', '1', Items::model()->data_items("BAHAN"),array('class'=>'for m-control')  );?>
					<input type="text" name="nama" id="nama">

					<label for="add-all" style="width: 200px;">
						<input type="checkbox" name="add-all" id="add-all" > Tambah Semua Item						
					</label>
				</div>
				

				<div class="row">
					<label for="jumlah" >Jumlah</label>
					<?php echo CHtml::textField('stok', '1',array('type'=>'number','id'=>'stok','class'=>'form-contro l','style'=>'width:50px;'));?>
					
					<button class="btn btn-primary"  onClick="add_item($('#nama').val())">Tambah ke Table</button>
			
				</div>
				<hr>
			</div>
		</div>
		</div>

			
			<div class=" widget-table">	
	
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
									<th>aksi</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div> <!-- .widget-content -->
				</div> <!-- .widget -->

		</div>
	</div>
		
			

	
<div id="tambah-supplier-form" title="Tambah Supplier" >
<?php
$model = new Supplier;
$this->renderPartial('application.views.supplier._form',array("model"=>$model));
 ?>
</div>	

	<script type="text/javascript">
		$(document).ready(function(e){
		$("#nama").focus();


		$(document).on("change","#po-data",function(e){
			var value = $(this).val();
			$.ajax({
				// data : "id="+val,
				url : "<?php echo Yii::app()->createAbsoluteUrl('items/GetdataPO') ?>",
				data : "poid="+value,
				success : function(data){
					$("#users tbody").html(" ");
					var d = JSON.parse(data);
					$("#supplier-data").val(d[0].sumber).trigger("change");
					// alert(d[0].sumber);
					$.each(d,function(i,v){
						var idd = v.kode+"-"+v.id;
						appendToBaris(v,idd,v.jumlah_po,v.harga_beli);
					});

				
				}
			});
		})
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
							kalkulasiBeli();

						
						}
					});
			}else{
				$("#users tbody").html("");
				kalkulasiBeli();
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
		// 		       add_item(val);
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
	</div>
	</div>


	<script>

$(document).ready(function(){
	$(document).on('keyup', '#total-diskon,#total-bayar', function(e) {
		kalkulasiBeli();
	});

	$(document).on('keyup', '.harga,.jumlah', function(e) {
		kalkulasiBeli();
	});
	$(document).on('click', '.harga,.jumlah', function(e) {
		$(this).select();
	});
	$(document).on("click",".close,#full-screen",function(e){
        $("#wrapper-item-search").hide();
        $("#full-screen").hide();
   });
	 // $( "#accordion" ).accordion();
	$("#nama2").select2({
		 escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
         minimumInputLength: 2,
	});
	$("#e1").select2({
		 escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
         minimumInputLength: 2,
	});

	$("#isbayar").select2();
	$("#cabang").select2();
	$("#letak").select2();
	$("#letak").select2();
	$("#po-data").select2();
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
		kalkulasiBeli();
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

	 			function kalkulasiBeli(){
	 				// alert()
	 				// console.log("sopan");
	 				var subtotal = 0;
	 				$(".baris").each(function() {
						var idb = $(this).find('.pk').html();


						var jml = Math.round($(this).find('.jumlah').val());
						var harga = Math.round($(this).find('.harga').val());
							// alert(jml);
							// alert(harga);
						
						if (jml!=0 &&  harga!=0){
							var total = jml*harga;
							// alert('123');
						}else{
						
							var total = 0;
						}


						subtotal+=total;
					});
					// alert(subtotal);
					var diskon = Math.round($("#total-diskon").val());
					var bayar = Math.round($("#total-bayar").val());
					var grand = subtotal-diskon;
					var kembali = bayar-grand;
					$("#total-sub").attr("asli",subtotal);
					$("#total-sub").html(numberWithCommas(subtotal) );

					$("#total-grand").attr("asli",grand);
					$("#total-grand").html(numberWithCommas(grand) );

					// alert(kembali);
					$("#total-kembali").attr("asli",kembali);
					$("#total-kembali").val(numberWithCommas(kembali) );







	 			}
				function numberWithCommas(x) {
					var string = x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
					return string;
				}

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
				function appendToBaris(d,barcode,jumlah,harga=""){
					var subkategori = "-";
					if (d.nama_sub_kategori==null){
						subkategori = "-";
					}else{
						subkategori = d.nama_sub_kategori;
					}
					$('#users tbody').append(
					"<tr class='baris'>" +
					// "<td></td>";
					"<td>"+d.nama_kategori+"</td>"+
					"<td>"+subkategori+"</td>"+
					// "<td>1</td>"+
					"<td style='display:none'  class='pk' nilai="+barcode+"  >" + barcode + "</td>" +
					"<td>" + d.item_name + "</td>" +

					// "<td><input class='kode' style='width:100%;padding:4px;' maxlength='15' type='text' value='0'/></td>" +
					"<td><input class='jumlah' style='width:100%;padding:4px;' maxlength='15' type='text' value='"+jumlah+"'/></td>" +

					// "<td>"+select+"</td>" +
					"<td class='td-satuan' value='"+d.nama_satuan_id+"'>"+d.nama_satuan+"</td>" +


					"<td><input class='harga' value='"+harga+"' style='width:100%;padding:4px;' maxlength='15' value='0' type='text'/></td>" +
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
					kalkulasiBeli();
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
					var poid  = $('#po-data').val();
					// alert(cabang);


					// baru pembelian
					var subtotal = $("#total-sub").attr("asli");
					var diskon = $("#total-diskon").val();
					var grand = $("#total-grand").attr("asli");
					var bayar = $("#total-bayar").val();
					var kembali = $("#total-kembali").attr("asli");

					// al
					

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
					// ert(subtotal);
					// alert(diskon);
					// alert(grand);
					// alert(bayar);
					// alert(kembali);
					var head = {
						subtotal : subtotal,
						diskon : diskon,
						grand : grand,
						bayar : bayar,
						kembali : kembali,
						tanggal : tanggal,
						user : user,
						faktur : faktur,
						keterangan : keterangan,
						cabang : cabang,
						letak : letak,
						kode_trx : kode_trx,
						tanggal_jt : tanggal_jt,
						isbayar : isbayar,
						sumber : sumber,
						poid : poid
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
						// alert(idb);
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
							item["status"] = status;
							item["harga"] = harga;
							// item["supplier"] = supplier;
							item["satuan"] = satuan;
							jsonObj.push(item);
						}
					});

					// alert(JSON.stringify(jsonObj));
					// alert(JSON.stringify(head));
						 
					var kembali = $("#total-kembali").attr("asli");
					if (kembali<0){
						alert(" Total bayar dibawah total semua barang, sisa dari pembelian akan disimpan ke data piutang")
					}
						 $.ajax({
							url: '<?php echo Yii::app()->createAbsoluteUrl('items/prosesmasukbarang'); ?>', 
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
									alert('Berhasil! mencatat barang masuk  ');
									
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