
<!-- Datatables -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>


<!-- Modal -->
<div class="modal fade" id="tambah-item-baru" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Item Baru</h4>
      </div>
      <div class="modal-body">
        <?php 
            $model = new Items;
            $datasatuan= new ItemsSatuan;
            $this->renderPartial('application.views.items._form',array(
               "model"=>$model,
               "datasatuan"=>$datasatuan
            ));
            $this->renderPartial('application.views.items._form_js');
        ?>
      </div>

    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="tambah-kategori-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Kategori Baru</h4>
      </div>
      <div class="modal-body">
       <?php 
         $model = new Categories;
        $this->renderPartial('application.views.categories._form',array("model"=>$model));
       ?>
      </div>

    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="tambah-kategori-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Kategori Baru</h4>
      </div>
      <div class="modal-body">
       <?php 
         $model = new Categories;
        $this->renderPartial('application.views.categories._form',array("model"=>$model));
       ?>
      </div>

    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="tambah-satuan-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Satuan Baru</h4>
      </div>
      <div class="modal-body">
       <?php
        $model = new ItemsSatuanMaster;
        $this->renderPartial('application.views.itemsSatuanMaster._form',array("model"=>$model));
         ?>
      </div>

    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="tambah-subkategori-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Subkategori Baru</h4>
      </div>
      <div class="modal-body">
       <?php
          $model = new Motif;
        $this->renderPartial('application.views.motif._form',array("model"=>$model));
         ?>
      </div>

    </div>
  </div>
</div>

<style type="text/css">
   #users_wrapper{
      overflow:auto;
   }
   tr td{
   padding: 2px;
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
   /* width: 100px; */
   }
   .row{
   /*margin-left: 0px;*/
   /*margin-bottom: 5px;*/
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
//   $model = Items::model()->data_items("TANPA_PULSA");
//    $this->renderPartial('inc-pencarian-items',array('model'=>$model));
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
   
   
   	// $('#namabarcode').select2("open");
   
   	$(document).on("change","#isbayar",function(e){
   		if ($(this).val()=="0")
   			$("#row-jatuh-tempo").show();
   		else
   			$("#row-jatuh-tempo").hide();
   	});
   	$(document).on("keypress",".select2-input",function(e){
   		if(e.which == 13) {
   		    add_item($('#namabarcode').val());
               // $("#nama").select2("open");row-jatuh-tempo
   
   		}
   
   	});
   
    
   });
</script>
  <?php 
  $branch_id = Yii::app()->user->branch();
  $store_id = Yii::app()->user->store_id();
  ?>
<div class="row">
	<div class="col-12">
		<h1> <i class="fa fa-book"></i>&nbsp;Kelola Saldo Awal</h1>
	</div>
</div>
<div class="row">
  <div class="col-sm-12 alert alert-info col-xs-12 " role="alert">
    <div id="shorcut"  >
        <ul class="" style="list-style:none;" >
          <li class="d-inline">Esc = Batal </li>
          <li class="d-inline">F2 = Pilih Item </li>
        </ul>
    </div>
  </div>
</div>

<div class="row">
     <legend style="font-weight:bolder">Data Transaksi</legend>
   <div class="col-sm-6">
      <table border="0" cellpadding="0" id="trx-b-masuk"  >
   
         <tr>
            <td>
               Tanggal Transaksi <span class="text-danger">*</span>	
            </td>
            <td>
               <input class="form-control" type="text" value="<?php echo date('Y-m-d'); ?>" style="display:inline;padding:5px" name="tanggal" id="tanggal">
            </td>
         </tr>
        
      </table>
      
   </div>
   
   <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6" >
      <table style="width:100%" border="0" cellpadding="0">  
         <tr>
            <td colspan="3">
               <button disabled id="btn-simpan-saldo-awal" onclick="kirim()" class="btn btn-large btn-success" style="float:right;margin-top:1rem">Simpan Saldo Awal</button>
            </td>
         </tr>
      </table>
   </div>
</div>
<div class="" >
         <div class="" >
            <input type="text" value="<?php echo Yii::app()->user->id ?>" style="display:none" name="user" id="user">
            <div class="data-table">
               <?php 
                  $dataa = CHtml::listdata(Items::model()->findAll("hapus = 0 "),'id','item_name'); 
                  $array = array();
                  foreach (Items::model()->findAll("hapus = 0 ") as $x) {
                  	$array[$x->id] = $x->barcode ." -  ".$x->item_name;
                  }
                   ?>
               <div class="row" >
                  <div class="col-sm-4 col-lg-4">
                     <!-- <label for="nama" style="margin-left: 5px;" >Barcode</label> -->
                        <?php //secho CHtml::dropDownList('nama2', '1', Items::model()->data_items("BAHAN"),array('class'=>'for m-control')  );?>
                        <input list="browsers" class="form-control"  type="text" name="nama" id="namabarcode" placeholder="Cari nama akun">

                        <datalist id="browsers">
                           <?php 
                            foreach ($listAkun as $key => $value) {
                            echo "<option  value='$value->kode_akun'>{$value->nama_akun}</option>";
                            }
                           ?>
                        </datalist>
                        <!-- <label for="add-all" style=""> -->
                        <!-- <input type="checkbox" name="add-all" id="add-all" > Tambah Semua Item						 -->
                     </label>
                  </div>
                  <div class="col-sm-4 col-lg-5">
                     <?php echo CHtml::textField('stok', '1',array('type'=>'number','id'=>'stok','class'=>'form-contro l','style'=>'width:50px;'));?>
                     <button class="btn btn-primary"  onClick="add_item($('#namabarcode').val())">Tambahkan</button>
                     
                  </div>

               </div>
               <div class="row">
                 
               </div>
               <hr>
            </div>
         </div>
      </div>
    <!-- kolom kedua -->
      <div class=" widget-table">
         <div class="widget-content">
            <table style="width:100%" id="users" class="table table-te table-striped">
               <thead>
                  <tr>
                     <th>No</th>
                     <th>Kode Akun</th>
                     <th>Nama Akun</th>
                     <th>Debit</th>
                     <th>Kredit</th>
                     <th>Aksi</th>
                  </tr>
               </thead>
               <tbody>
               </tbody>
            </table>
         </div>
         <!-- .widget-content -->
      </div>
      <!-- .widget -->
      <div id="tambah-supplier-form" title="Tambah Supplier" >
   <?php
      $model = new Supplier;
      $this->renderPartial('application.views.supplier._form',array("model"=>$model));
       ?>
</div>
<script type="text/javascript">
   $(document).ready(function(e){
   $("#namabarcode").focus();
   
   $(document).on("click","#btn-tambah-item",function(e){
      $("#tambah-item-baru").modal("show");
   });
   
   });
</script>
</div>
</div>
<script>
   $(document).ready(function(){

   	$(document).on('keyup', '#total-diskon,#total-bayar', function(e) {
   		kalkulasiSaldo();
   	});
   
   	$(document).on('keyup', '.harga,.jumlah,.debit,.kredit', function(e) {
   		kalkulasiSaldo();
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
       	$("#namabarcode").val("<?php echo $id ?>").trigger("change");
       	add_item();
       	<?php 
      }
      ?>
   });
   
    $(document).on('keydown', '#namabarcode', function(e) {
      // e.preventDefault();
      if (e.keyCode==13 || e.which==13){
         
         add_item($('#namabarcode').val());
          $(this).val("");
          $("#nama").focus(); 
      }
   
    });
    //class="jumlah"
    $(document).on('keydown', '.jumlah,.harga', function(e) {
        if (e.keyCode==13 || e.which==13){
            $("#total-bayar").focus();
            $("#total-bayar").selectAll();
        }      
    });
    $(document).on('keydown', '#total-bayar', function(e) {
         if (e.keyCode==13 || e.which==13){
            kirim();
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
   		
   
   	});
   	$(document).on('click', '.btn-hapus-po', function(e) {
         e.preventDefault();
         var po_id = $("#po-data").val();
         if (po_id == ""){
            alert("Pilih terlebih dahulu PO mana yang akan dihapus")
            return;
         } 

         $.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createAbsoluteUrl("Items/deletePO"); ?>',
            data : "id="+po_id,
               success:function(data){
            //    alert(data);
               // $(".supplier").eq(i).html(data);
               // $("#namapel").val("umum").trigger("change");
            },
            error:function(data){
                  // alert(data);
                  alert(JSON.stringify(data));
            },
   
            dataType:'html'
         });
      });

   	$(document).on('click', '.hapus-baris', function(e) {
          table.row( $(this).parents('tr') ).remove().draw();
   	// 	// alert('masuk');
   	// 	var index = $('.hapus-baris').index(this);
		// //    alert(index);
		// var th = this;
		// $(th).closest("tr").remove()
   	// 	$('.baris-baris').eq(index).remove();
   		kalkulasiSaldo();
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
   
   	 			function kalkulasiSaldo(){
   	 				var subtotal = 0;
                    var totalDebit = 0;
                    var totalKredit = 0;
   	 				$("#users tbody tr").each(function() {
                        totalDebit += parseFloat($(this).find('.debit').val());
                        totalKredit += parseFloat($(this).find('.kredit').val());
   					});
                    if (totalDebit > 0 && totalKredit > 0){
                        if (totalDebit != totalKredit){
                            $("#btn-simpan-saldo-awal").attr("disabled",true);
                        }else{
                            $("#btn-simpan-saldo-awal").removeAttr("disabled");
                        }
                    }

                    // alert(totalDebit);
                    // alert(totalKredit);

                    
   					// alert(subtotal);a
   					// var diskon = Math.round($("#total-diskon").val());
   					// var bayar = Math.round($("#total-bayar").val());
   					// var grand = subtotal-diskon;
   					// var kembali = bayar-grand;
   					
   					// $("#total-sub").attr("asli",subtotal);
   					// $("#total-sub").html(numberWithCommas(subtotal) );
   

   					// $("#total-grand").attr("asli",grand);
   					// $("#total-grand").html(numberWithCommas(grand) );


   
   					// // alert(kembali);
   					// $("#total-kembali").attr("asli",kembali);
   					// $("#total-kembali").val(numberWithCommas(kembali) );
   
   	 			}
   				function numberWithCommas(x) {
   					var string = x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
   					return string;
   				}
   
               var table = $("#users").DataTable();
               // table.column(1).visible(false) ;
   				function add_item(val){
   					if (val!=0 ){
   
   					// if ($('#namabarcode').val()!=0 && $('#namabarcode').val()!=null ){
   					$.ajax({
   						data : "kode="+val,
   						url : "<?php echo Yii::app()->createAbsoluteUrl('akun/check') ?>",
   						success : function(data){
                        var d = JSON.parse(data);
                        if (d.nama_akun!=null){   				
   							appendToBaris(d);
   							kalkulasiSaldo();   						
   						}else{
   							alert("Kode akun tidak ditemukan");
   							$("#nama").focus();
   						}
   					}
   					});
   						}else{
   							$("#nama").focus();
   							alert('item tidak boleh kosong');
   							$('#stok').val(1);
   						}
   				}
          var no = 0;
        function appendToBaris(json){                  
            no = parseInt(table.rows().count()) + 1;
               table.row.add(
                  [
                     no,
                     json.kode_akun,
                     json.nama_akun,
                     "<input class='form-control debit' value='0'  >",
                     "<input class='form-control kredit' value='0' >",
                     "&nbsp;<i  class='hapus-baris fa fa-times'></i>" 
                  ]).draw(false);              
   					kalkulasiSaldo();
                    $("#namabarcode").val(" ");
                  
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
   					var metode_pembayaran = $("#metode-pembayaran").val();
   
   
   					if (isbayar=="1"){
   						if (faktur==""){
   							alert("Faktur Tidak boleh kosong");
   							return ;
   						}
   					}
   	
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
   						poid : poid,
   						metode_pembayaran : metode_pembayaran
   					}
   					
   					if ($("#users tbody tr").length==0){
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
   
   					$("#users tbody tr").each(function() {
   						var idb = $(this).find('.pk').html();
   						// alert(idb);
   						var jml = $(this).find('.jumlah').val().replace(",",".");

                     
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
   							alert(" Pembayaran dibawah harga total barang, sisa dari pembelian akan disimpan ke data piutang");
   						}
   						if (confirm("Yakin ?")==false){return}
   
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

<script type="text/javascript">
   $(document).ready(function(e){
     $("#nama").focus();
  });
</script>
