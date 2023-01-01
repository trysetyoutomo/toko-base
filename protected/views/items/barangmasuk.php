
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
  $model = Items::model()->data_items("TANPA_PULSA");
   $this->renderPartial('inc-pencarian-items',array('model'=>$model));
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
		<h1> <i class="fa fa-book"></i>&nbsp;Pembelian</h1>
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
            <td colspan="4">
             
            </td>
         </tr>
         <tr >
            <td>
               Kode PO
            </td>
            <td colspan="5">
               <select  id='po-data' style='width:100%;padding:4px;' maxlength='15'>
                  <option value="">Pilih dari Data PO</option>
                  <?php foreach (PurchaseOrder::model()->findAll(" status_aktif = 1 and branch_id = '".$branch_id."' ") as $s):  ?>
                  <option value='<?php echo $s->kode_trx ?>'><?php echo date("d M Y ",  strtotime($s->tanggal))  ."  - ". $s->kode_trx ?></option>
                  <?php endforeach; ?>	
               </select>
               <!-- <a href="" class="btn btn-danger btn-sm btn-hapus-po" style="width:10%">
                     <i class="fa fa-times"  style="color:white!important; " ></i>
               </a> -->
            </td>
         </tr>
         <tr>
            <td>
               Tanggal Transaksi <span class="text-danger">*</span>	
            </td>
            <td>
               <input class="form-control" type="text" value="<?php echo date('Y-m-d'); ?>" style="display:inline;padding:5px" name="tanggal" id="tanggal">
            </td>
            <td>
               ID Pembelian			
            </td>
            <td>
               <input  class="form-control" readonly="" type="text" value="<?php echo BarangMasukController::generateKodeBMS(); ?>" style="display:inline;padding:5px"  id="kode_trx">
            </td>
         </tr>
         <tr>
         </tr>
         <tr>
            <td>
               No Faktur
            </td>
            <td>
               <input  class="form-control"type="text" value="-"  style="display:inline;padding:5px" name="faktur" id="faktur">
            </td>
            <td>
               Supplier
            </td>
            <td>
             
               <select  id='supplier-data' style='width:100%;padding:4px;' maxlength='15'>
                  <?php foreach (Supplier::model()->findAll("store_id = '".$store_id."' ") as $s):  ?>
                  <option value='<?php echo $s->nama ?>'><?php echo $s->nama ?></option>
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
                     <?php echo $value->branch_name ?>
                  </option>
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
                     <?php echo $value->kode.' - '.$value->nama ?>
                  </option>
                  <?php } ?>
               </select>
            </td>
         </tr>
         <tr>
            <td>
               Keterangan
            </td>
            <td colspan="6">
               <textarea class="form-control" style="width: 100%" placeholder="barang masuk" id="keterangan" >Barang Masuk</textarea>
            </td>
         </tr>
      </table>
      
   </div>
   
   <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6" >
      <!-- <legend></legend> -->
      <table style="width:100%" border="0" cellpadding="0">  
         <tr>
            <td>
               <h5>Sub Total</h5>
            </td>
            <td >:</td>
            <td><label id="total-sub">0</label></td>
         </tr>
         <tr>
            <td>Diskon</td>
            <td>:</td>
            <td>
               <input class="form-control" type="number" id="total-diskon" value="0">
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
               <input class="form-control" type="number" id="total-bayar" value="0">
            </td>
         </tr>
         <tr>
            <td>Kembali</td>
            <td>:</td>
            <td>
               <input readonly class="form-control" type="text" id="total-kembali" value="0">
            </td>
         </tr>
         <tr>
            <td colspan="3">
               <button onclick="kirim()" class="btn btn-large btn-success" style="float:right;margin-top:1rem">Simpan Transaksi</button>
               <button style="float:right;margin-top:1rem" id="btn-tambah-item" class="btn btn-primary">Item Baru</button>
            </td>
         </tr>
      </table>
   </div>
</div>
<div class="" >
         <div class="" >
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
               <div class="row" >
                  <div class="col-sm-4 col-lg-4">
                     <!-- <label for="nama" style="margin-left: 5px;" >Barcode</label> -->
                        <?php //secho CHtml::dropDownList('nama2', '1', Items::model()->data_items("BAHAN"),array('class'=>'for m-control')  );?>
                        <input class="form-control"  type="text" name="nama" id="namabarcode" placeholder="Scan barcode disini">
                        <!-- <label for="add-all" style=""> -->
                        <!-- <input type="checkbox" name="add-all" id="add-all" > Tambah Semua Item						 -->
                     </label>
                  </div>
                  <div class="col-sm-4 col-lg-5">
                     <label for="jumlah" style="margin-left: 5px;">Jumlah</label>
                     <?php echo CHtml::textField('stok', '1',array('type'=>'number','id'=>'stok','class'=>'form-contro l','style'=>'width:50px;'));?>
                     <button class="btn btn-primary"  onClick="add_item($('#namabarcode').val())">Tambah ke keranjang</button>
                     
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
                     <th>Barcode</th>
                     <th>Kategori</th>
                     <th>Sub Kategori</th>
                     <th>Nama Item</th>
                     <th>Jumlah</th>
                     <th>Satuan</th>
                     <th>Harga Satuan </th>
                     <th>Total Harga Satuan </th>
                     <th>aksi</th>
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

   $(document).on("change","#po-data",function(e){
   	var value = $(this).val();
      if (value != ""){
         $.ajax({
            // data : "id="+val,
            url : "<?php echo Yii::app()->createAbsoluteUrl('items/GetdataPO') ?>",
            data : "poid="+value,
            success : function(data){
               table.clear().draw();
               var d = JSON.parse(data);
               $("#supplier-data").val(d[0].sumber).trigger("change");
               // alert(d[0].sumber);
               $.each(d,function(i,v){
                  var idd = v.kode+"##"+v.id;
                  appendToBaris(v,idd,v.jumlah_po,v.harga_beli);
               });
      
            
            }
         });
      }else{
         table.clear().draw();
      }
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
               alert(data);
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
   	 				$("#users tbody tr").each(function() {
   						var idb = $(this).find('.pk').html();
   
   
   						//  parseFloat($(this).find('.jumlah').val());
                     var jml = parseFloat($(this).find('.jumlah').val().replace(",","."));
   						var harga = parseFloat($(this).find('.harga').val());
   							// alert(jml);
   							// alert(harga);
   						
   						if (jml!=0 &&  harga!=0){
   							var total = jml*harga;
   							// alert('123');
   						}else{
   						
   							var total = 0;
   						}
                     $(this).find(".total-harga-satuan").html(total);
   
   
   						subtotal+=total;
   					});
   					// alert(subtotal);a
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
   
               var table = $("#users").DataTable();
               // table.column(1).visible(false) ;
   				function add_item(val){
   					if (val!=0 ){
   
   					// if ($('#namabarcode').val()!=0 && $('#namabarcode').val()!=null ){
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
   							var stok = $('#stok');
   							var count = $('.pk[nilai="'+val+'"]').length;
   
   							appendToBaris(d,val,stok.val(),d.harga_beli);
   							kalkulasiBeli();
                  
   						
   						}else{
   							alert("Barcode tidak ditemukan");
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
   				function appendToBaris(d,barcode,jumlah,harga=""){
                  // alert(barcode);
                  // let checkExist = table.column(1).search(String(barcode)).data().length;
                  // if (checkExist >0 ){
                  //    alert('123');
                  //    return false;
                  // }
   					var subkategori = "-";
   					if (d.nama_sub_kategori==null){
   						subkategori = "-";
   					}else{
   						subkategori = d.nama_sub_kategori;
   					}
            // 0++;

                  

            no = parseInt(table.rows().count()) + 1;
               table.row.add(
                  [
                     no,
                     barcode,
                     d.nama_kategori,
                     subkategori,
                     d.item_name, 
                     "<p style='display:none'  class='pk' nilai="+barcode+">"+barcode+"</p><input class='jumlah' style='width:100%;padding:4px;' maxlength='4' type='text' value='"+jumlah+"'/>",
                     "<p class='td-satuan' value='"+d.nama_satuan_id+"'>"+d.nama_satuan+"</p>",
                     "<input class='harga' value='"+harga+"' style='width:100%;padding:4px;' maxlength='15' value='0' type='text'/>",
                     "<p class='total-harga-satuan text-right'></p>",
                     "&nbsp;<i  class='hapus-baris fa fa-times'></i>" 
                  ]).draw(false);
 
               
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
   						poid : poid
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
