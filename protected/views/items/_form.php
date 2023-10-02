
<link href="<?php echo Yii::app()->request->baseUrl; ?>/select2/select2.css" rel="stylesheet"/>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/select2/select2.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/selectric/public/jquery.selectric.min.js"></script>

<style type="text/css">
	/*table{
		border: 1px solid black;
	}*/
	#table-items-form tr td{
		padding: 4px;
	}
	label{
		text-transform: capitalize;
	}
</style>
<script type="text/javascript">
	

	$(document).on("change","#Items_is_pulsa",function(e){
		var val = $(this).val();
		// alert(val);
		if (val=="0")
			$("#row-provider").hide();
		else	
			$("#row-provider").show();
	});
	$(document).on("change","#Items_is_bahan",function(e){
		var val = $(this).val();
		if (val=="0"){
			$("#row_has_bahan").show();
			$("#Items_is_bahan").val(0);
		}
		else{
			$("#row_has_bahan").hide();
			$("#Items_has_bahan").val(0);
		}
	});
	function generateBarcodeAction(){
		$.ajax({
			// data : "id="+$('#nama').val(),
			url : "<?php echo Yii::app()->createAbsoluteUrl('items/GenerateBarcodeAction') ?>",
			success : function(data){
				// alert(data);
				$("#hidden-barcode").val(data);
				generateBarcode();
			}
		});

	}
	// 	if ($('#nama').val()!=0 && $('#nama').val()!=null ){
	// 	$.ajax({
	// 		data : "id="+$('#nama').val(),
	// 		url : "<?php echo Yii::app()->createAbsoluteUrl('items/getname') ?>",
	// 		success : function(data){

	// 			var nama = $('#nama');
	// 			// alert(nama.val());
	// 			var stok = $('#stok');
	// 			// alert(stok.val());
	// 			var count = $('.pk[nilai="'+nama.val()+'"]').length;
	// 			// if (count==0){

	// 				$('#users tbody').append(
	// 					"<tr class='baris'>" +
	// 					// "<td></td>";
	// 					"<td style='display:none' class='pk' nilai="+nama.val()+"  >" + nama.val() + "</td>" +
	// 					"<td>" + data + "</td>" +
	// 					// "<td><input class='kode' style='width:100%;padding:4px;' maxlength='15' type='text' value='0'/></td>" +
	// 					"<td><input class='harga' style='width:100%;padding:4px;' maxlength='15' value='1' type='text'/></td>" +
	// 					"<td class='hapus'>&nbsp;<i class='fa fa-times'></i > "+

	// 					"</td> " +

	// 					"</tr>"
	// 				);
	// 		    $("#nama").select2("open");

	// 		}
	// 	});
	// 	}else{
	// 		alert('tidak boleh kosong');
	// 		$('#stok').val(1);
	// 	}
	// }

</script>
<?php 
// var_dump($_REQUEST['is_generate']);
?>
<script type="text/javascript">
	function generateBarcode(){
		var hidden_barcode = $("#hidden-barcode").val();
		<?php 
		if ($model->isNewRecord){
			
		?>
			$("#Items_barcode").val(hidden_barcode);
			$("#Items_barcode").attr("readonly",true);
		<?php 
		}
		?>

	}
	$(document).ready(function(){
		// $("#Items_barcode").focus();
		$("#nama").select2();
		$("#Items_is_bahan").trigger("change");
		//auto generate
		setTimeout(function(){
			$("#is_generate").trigger("click");
		},100);
		// $("#is_generate").trigger("click");

		<?php if ($_REQUEST['is_generate']=="on"){ ?>
			generateBarcodeAction();
		<?php }else{ ?>
			// $("#Items_barcode").focus();
		<?php } ?>

		$(document).on('keypress', '#Items_barcode,#Items_item_name', function(e) {
			  if (e.keyCode=="13"){
				return false;
			  }
		});
		$(document).on('click', '#is_generate', function(e) {
			// alert("123");
			if ($(this).prop("checked")){
					var hidden_barcode = $("#hidden-barcode").val();
					$("#Items_barcode").val(hidden_barcode);
					$("#Items_barcode").attr("readonly",true);
				
			}else{
				$("#Items_barcode").removeAttr("readonly");
				$("#Items_barcode").val("");
				// $("#Items_barcode").focus();
			}
		});

		$(document).on('click', '.hapus', function(e) {
			// alert('masuk');
			var index = $('.hapus').index(this);
			$('.baris').eq(index).remove();
		});

	
		$('#Items_description').html(' - ');
		<?php if (!$model->isNewRecord): ?>
			// $("#Items_persentasi").on("keyup",function(e){
			// 	$.ajax({
			// 	url : '<?php echo $this->createUrl('items/average')?>',
			// 	data : 'id='+<?php echo $_REQUEST["id"] ?>+"&val="+$(this).val(),
			// 		success : function(data)
			// 		{
			// 			$("#Items_total_cost").val(data);
			// 			$("#Items_price_reseller").val(data);
			// 			$("#Items_price_distributor").val(data);
			// 		},
			// 	});
			// 	// alert("123");
			// });
		<?php endif;?>

		// Items_total_cost
	})
	function itemnumber(){
		// id_category = $('#Items_category_id').val();
		// id_outlet = $('#Items_kode_outlet').val();
		// $.ajax({
		// 	url : '<?php //echo $this->createUrl('items/itemnumber')?>',
		// 	data : 'id='+id_category+'&id2='+id_outlet,
		// 		success : function(data)
		// 		{
		// 			$("#Items_item_number").val(data);
		// 		},
		// });<input id="Items_total_cost" name="Items[total_cost]" value="38936" type="text">
	}
	function getMotif(category){
		$.ajax({
		url : '<?php echo $this->createUrl('items/getMotif')?>',
		data : 'id='+category,
			success : function(data)
			{
				$("#Items_motif").html(data);
				setTimeout(function(e){
					$("#Items_motif").val($("#Items_motif option:last").val());
				},100)
			},
		});
	}
	
	function unitprice(){
		// id = $('#itemnumber').val();
		// $.ajax({
		// 	url : '<?php //echo $this->createUrl('site/unitprice')?>',
		// 	data : 'id='+id,
		// 		success : function(data)
		// 		{
		// 			var total = (parseInt(data)+data/10);
		// 			$('#Items_unit_price').val(data);
		// 			$('#Items_tax_percent').val(data/10);
		// 			$('#Items_total_cost').val(total);
		// 		},
		// });
	}
	
	$(document).ready(function(){
		 $("#Items_category_id").change(function(){
		 	var nilai = $(this).val();
		 	getMotif(nilai);
		 });	

		//  $("#Items_total_cost").keyup(function(){
		//  	nilai = $('#Items_total_cost').val();
		// 	total = (parseInt(nilai)+nilai/10);
		//  	//$('#Items_tax_percent').val(nilai/10);
		//  	$('#Items_unit_price').val(total);
		//  });

		 <?PHP 
		 if ($model->isNewRecord){
		 ?>
	 	 	$("#Items_category_id").trigger("change");
	 	 <?php
	 	 }else{
	 	 ?>
	 	 $("#row-provider").show();
	 	<?php } ?>
	});

	
</script>
<?php
/* @var $this ItemsController */
/* @var $model Items */
/* @var $form CActiveForm */
?>

<div class="row">
<div class="col-sm-10">


    <div class="mws-panel-body no-padding">
        <?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'items-form',
	'enableAjaxValidation'=>false,
        'htmlOptions' => array(
                'class' => 'mws-form',
            )
)); ?>
<?php
$store_id = Yii::app()->user->store_id();

$nilai = Categories::model()->findAll("status=0 and store_id = '$store_id' ",array('order'=>'category'));
$data = CHtml::listData($nilai,'id','category');

$motif = Motif::model()->findAll();
$motif = CHtml::listData($motif,'id','nama');


$nilai2 = ItemsSatuanMaster::model()->findAll(" store_id = '$store_id' ");
$satuan = CHtml::listData($nilai2,'id','nama_satuan');
$branch_id = Yii::app()->user->branch();

$nilai2 = Letak::model()->findAll("branch_id = '$branch_id' ");
$letak = CHtml::listData($nilai2,'id','nama');



$nilai2 = Outlet::model()->findAll();
$data2 = CHtml::listData($nilai2,'kode_outlet','nama_outlet');

// print_r($model);
?>

        <div class="mws-form-inline">
                 <?php echo $form->errorSummary($model); ?>
                 <?php 
                 // if ($model->isNewRecord)
	                 echo $form->errorSummary($datasatuan); 
                 ?>

<!--
            <p class="note">Fields with <span class="required">*</span> are required.</p>
-->
           
				<table style="border:1px;width:100%" border="0" cellpadding="2" id="table-items-form">
				  <?PHP if ($model->isNewRecord){ ?>
               <tr>
               <?php 
               	// <!-- // $model->barcode = ItemsController::generateBarcode(); -->
               // }
               ?>
					<td>
               <input value="<?php echo ItemsController::generateBarcode(); ?>" type="hidden" name="hidden-barcode" id="hidden-barcode">
					<?php echo $form->labelEx($model,'barcode'); ?>
						
					</td>
	                <td><?php 


	                echo $form->textField($model,'barcode',array('class'=>'form-control','style'=>'text-transform:uppercase')); 
	                ?>

	                </td>
    				<TD>
    				<?php if ($model->isNewRecord){ ?>
    					<label for="is_generate">
	                		<input type="checkbox" name="is_generate" id="is_generate"> Buat Barcode Otomatis ?
    					</label>
    					<?php }else{
    						?>
	    					<!-- <label for="is_generate">
			            		<input disabled="" type="checkbox" name="is_generate" id="is_generate"> Generate ?
	    					</label> -->

    						<?php 

    					}

    					 ?>
				</TD>

	                <td><?php echo $form->error($model,'barcode'); ?></td>
                </tr>
                <?PHP } ?>


                <tr>
				<td><?php echo $form->labelEx($model,'item_name'); ?></td>
                <td><?php echo $form->textField($model,'item_name',array('size'=>20,'maxlength'=>30,'class'=>'form-control','style'=>'text-transform:uppercase')); ?></td>
                <td><?php echo $form->error($model,'item_name'); ?></td>
                </tr> 

				<tr>
				<td style="width: 100px"><label>kategori </label></td>
				<td style="width: 700px"><?php echo $form->dropDownList($model,'category_id', $data, array('empty' => 'Pilih ','separator'=>'|','class'=>'form-control'))?>
				<td style="width: 200px;">
					<button  type="button"  class='btn btn-primary tambah-kategori' >
						<i class="fa fa-plus" style="color:white!important"></i>
					</button>
				</td>
			
				</td>
				</tr>
				<tr > 
				<td><label>Sub Kategori</label></td>
				<td><?php echo $form->dropDownList($model,'motif', $motif, array('empty' => 'Pilih ','separator'=>'|','class'=>'form-control'))?></td>
				<td>
					<button  type="button"  class='btn btn-primary tambah-subkategori' >
						<i class="fa fa-plus" style="color:white!important"></i>
					</button>
	
				</td>
				</tr>
				<?PHP 
				if ($model->isNewRecord){
				?>
				<tr>
				<td style="width: 200px"><label>Satuan </label></td>
				<td style="width: 500px"><?php echo $form->dropDownList($model,'satuan_id', $satuan, array('empty' => 'Pilih ','separator'=>'|','class'=>'form-control'))?></td>
				<TD>
					<button  type="button"  class='btn btn-primary tambah-satuan' >
						<i class="fa fa-plus" style="color:white!important"></i>
					</button>

				</TD>
				</tr>
				<?php 
				}
				?>
				<?php 
				// if ($model->isNewRecord){
				?>
					<tr style="display: none;" > 
					<td><label>Letak</label></td>
					<td><?php echo $form->dropDownList($model,'letak_id', $letak, array('empty' => 'Pilih ','separator'=>'|','class'=>'form-control'))?></td>
					<TD>
						<button style="display: none;" type="button"  class='btn btn-primary tambah-letak' >
							<i class="fa fa-plus" style="color:white!important"></i>
						</button>

					</TD>
					</tr>
				<?php 
				// }
				?>
				
				
				<tr style="display:none">
				<td ><?php echo $form->labelEx($model,'item_number'); ?></td>
				<td><?php echo $form->textField($model,'item_number',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?></td>
				<td><?php echo $form->error($model,'item_number'); ?></td>
				</tr>
				
				
                    <tr style="display: none;">
				<td><?php echo $form->labelEx($model,'ukuran'); ?></td>
                <td><?php echo $form->textField($model,'ukuran',array('class'=>'form-control')); ?></td>
                <td><?php echo $form->error($model,'ukuran'); ?></td>
                </tr>
                 <tr style="display: none;">
				<td><?php echo $form->labelEx($model,'ketebalan'); ?></td>
                <td><?php echo $form->textField($model,'ketebalan',array('class'=>'form-control')); ?></td>
                <td><?php echo $form->error($model,'ketebalan'); ?></td>
                </tr>
                     <tr style="display: none;">
				<td><?php echo $form->labelEx($model,'panjang'); ?></td>
                <td><?php echo $form->textField($model,'panjang',array('class'=>'form-control')); ?></td>
                <td><?php echo $form->error($model,'panjang'); ?></td>
                </tr>

				<tr>
				<td><?php echo $form->labelEx($model,'description'); ?></td>
                <td><?php echo $form->textarea($model,'description',array('value'=>' - ','class'=>'form-control')); ?></td>
                <td><?php echo $form->error($model,'description'); ?></td>
                </tr>
                <tr style="display:none" >
				<td><?php //echo $form->labelEx($model,'modal'); ?></td>
                <td><?php //echo $form->textField($model,'modal',array('class'=>'form-control')); ?></td>
                <td><?php //echo $form->error($model,'modal'); ?></td>
                </tr>
<!-- 				// only new record since total cost later will handled by satuan
 -->
				<?php 
				if ($model->isNewRecord){

							
                if ($model->unit_price==""){
                	$model->unit_price = "0";
                }
				?> 
				<tr >
				<td><?php echo $form->labelEx($model,'unit_price'); ?></td>
                <td><?php echo $form->textField($model,'unit_price',array('class'=>'form-control')); ?></td>
                <td><?php echo $form->error($model,'unit_price'); ?></td>
                </tr>
				<?php } ?>

				<?php if (!$model->isNewRecord): ?>
                <tr style="display: none;">
				<td><label>Rata - Rata Modal</label> </td>
                <td><input readonly="" value="<?php //echo ItemsController::getAverage($model->id); ?>" />
<a href="#data-log">
<button type="button"  class="btn btn-danger"><i class="fa fa-chevron-down"></i> Rincian </button>
</a>
                </td>
                <td></td>
                </tr> 
            	<?php else :?>
            	   <tr style="display:none"> 
				<td><?php echo $form->labelEx($model,'modal'); ?></td>
                <td><?php echo $form->textField($model,'modal',array('class'=>'form-control')); ?></td>
     
                <td><?php echo $form->error($model,'modal'); ?></td>
             </tr> 
            	<?php endif?>


                <tr style=""> 
                <?php 
				// only new record since total cost later will handled by satuan
                if ($model->isNewRecord){
				
                // if ($model->total_cost==""){
                // 	$model->total_cost = "0";
                // }
                ?>
				<td><?php echo $form->labelEx($model,'total_cost'); ?></td>
                <td><?php echo $form->textField($model,'total_cost',array('class'=>'form-control')); ?></td>
                <td><?php echo $form->error($model,'total_cost'); ?></td>
                </tr>
                  <?php 
				  }
                if ($model->price_reseller==""){
                	$model->price_reseller = "0";
                }

            	// }


                ?>

			 <?php  if ($model->isNewRecord){ ?>
              <tr > 
				<td><?php echo $form->labelEx($model,'stok'); ?></td>
                <td><?php echo $form->textField($model,'stok',
                array('class'=>'form-control','style'=>'display:inline','maxlength'=>3)); ?></td>
     
                <td><?php echo $form->error($model,'stok'); ?></td>
             </tr> 
			 <?php } ?>

             
                 <tr style="display: none;"> 
				<td><?php echo $form->labelEx($model,'price_reseller'); ?></td>
                <td><?php echo $form->textField($model,'price_reseller',array('class'=>'form-control')); ?></td>
                <td><?php echo $form->error($model,'price_reseller'); ?></td>
                </tr>
                   <?php 
                if ($model->price_distributor==""){
                	$model->price_distributor = "0";
                }
                ?>
                 <tr style="display: none;"> 
				<td><?php echo $form->labelEx($model,'price_distributor'); ?></td>
                <td><?php echo $form->textField($model,'price_distributor',array('class'=>'form-control')); ?></td>
                <td><?php echo $form->error($model,'price_distributor'); ?></td>
                </tr>
              <tr style="display: none;"> 
			
						<td colspan="3" ><label style="color:red" >

<i>Harga Jual  akan otomatis terisi, setelah melakukan transaksi barang masuk</label></i></td>
           

				
                   </tr>

            
             

              <?php 
                // if ($model->stok_minimum==""){
                // 	$model->stok_minimum = "0";
                // }
                // var_dump($model->stok_minimum);
                ?>
                <tr>
					<td><?php echo $form->labelEx($model,'stok_minimum'); ?></td>
	                <td><?php echo $form->textField($model,'stok_minimum',array('class'=>'form-control')); ?></td>
	                <td><?php echo $form->error($model,'stok_minimum'); ?></td>
                </tr>
                  <?php 
                if ($model->discount==""){
                	$model->discount = "0";
                }
                ?>
                <tr>
					<td><?php echo $form->labelEx($model,'discount'); ?></td>
	                <td><?php echo $form->textField($model,'discount',array('class'=>'form-control')); ?></td>
	                <td><?php echo $form->error($model,'discount'); ?></td>
                </tr>
				 <!-- <tr >
					<td colspan="2">
						<br>
						Ubah Harga pada Satuan Utama ?  
						<a href="" class="link"> Klik dsini</a>
						</br>
						</br>
					</td>
                </tr> -->

		
                <tr style="display:none">
					<td><?php echo $form->labelEx($model,'lokasi'); ?></td>
	                <td><?php echo $form->dropDownList($model,'lokasi',array('1'=>'Bar','2'=>'Dapur')); ?></td>
	                <td><?php echo $form->error($model,'lokasi'); ?></td>
                </tr>
                 <tr style="display:none">
					<td><?php echo $form->labelEx($model,'gambar'); ?></td>
	                <td><?php echo $form->filefield($model,'gambar',array('1'=>'Bar','2'=>'Dapur')); ?></td>
	                <td><?php echo $form->error($model,'gambar'); ?></td>
                </tr>
             
                 <tr style="display: none;">
					<td><?php echo $form->labelEx($model,'ispaket'); ?></td>
	                <td><?php echo $form->textField($model,'ispaket',array('class'=>'form-control')); ?></td>
	                <td><?php echo $form->error($model,'ispaket'); ?></td>
                </tr>
                <?php 
                if ($model->isNewRecord)
                	$style = 'style="display: none;" ';
                else
                	$style = 'style="display: block;" ';
                ?>

                	<?php 
				$usaha = SiteController::getConfig("jenis_usaha");
				// if ($usaha=="Restauran"){
				?>
		                   <tr id="row_is_bahan" >
							<td><?php echo $form->labelEx($model,'is_bahan'); ?></td>
			              <td><?php echo $form->dropDownList($model,'is_bahan',
			              array(
			              	'0'=>'Tidak',
			              	'1'=>'Ya')
		              	  ); ?></td>
			                  <td><?php echo $form->error($model,'is_bahan'); ?></td>
		                </tr>
		                 <tr id="row_has_bahan">
							<td><?php echo $form->labelEx($model,'has_bahan'); ?></td>
			              <td><?php echo $form->dropDownList($model,'has_bahan',
			              array(
			              	'0'=>'Tidak',
			              	'1'=>'Ya')
		              	  ); ?></td>
			                  <td><?php echo $form->error($model,'has_bahan'); ?></td>
		                </tr>
		                 <tr style="display: none;" >
							<td><?php echo $form->labelEx($model,'is_stockable'); ?></td>
			              <td><?php echo $form->dropDownList($model,'is_stockable',
			              array(
			              	'1'=>'Ya',
			              	'0'=>'Tidak'
			              	)
		              	  ); ?></td>
			                  <td><?php echo $form->error($model,'is_stockable'); ?></td>
		                </tr>
            	<?php// } ?>

            	<?php  if ($usaha=="Konter"){ ?>
            		 <tr id="row_is_pulsa" >
							<td><?php echo $form->labelEx($model,'is_pulsa'); ?></td>
			              <td><?php echo $form->dropDownList($model,'is_pulsa',
			              array(
			              	'0'=>'Tidak',
			              	'1'=>'Ya'
			              ),array("class"=>"form-control")

		              	  ); ?></td>
			                  <td><?php echo $form->error($model,'is_pulsa'); ?></td>
		                </tr>

		                <?php 
							$data_provider = Provider::model()->findAll();
							$data_provider = CHtml::listData($data_provider,'id','nama_provider');

		                ?>
		                 <tr id="row-provider"  style="display: none;">
							<td><?php echo $form->labelEx($model,'provider_id'); ?></td>
			              <td><?php echo $form->dropDownList($model,'provider_id',$data_provider,array("class"=>"form-control")
		              	  ); ?></td>
			                  <td><?php echo $form->error($model,'provider_id'); ?></td>
		                </tr>

            	<?php  } ?>

				





                <!-- <tr> -->
                  <?php 

					// $dataa = CHtml::listdata(Items::model()->findAll("hapus = 0 "),'id','item_name'); 
					// $array = array();
					// foreach (Items::model()->findAll("hapus = 0 ") as $x) {
					// 	$array[$x->id] = $x->barcode ." -  ".$x->item_name;
					// }
				 ?>
                	<!-- <td colspan="3"> -->
						<!-- <label for="nama" >Nama</label> -->
						<?php 
						// echo CHtml::dropDownList('nama', '1', $array,
						// 	array(
						// 		'class'=>'form-control',
						// 		'style'=>'width:60%;float:left',
						// 		'empty'=>'silahkan pilih'
						// 	) );?>		
						<!-- <button style="display: inline" type="button" onclick="add_item()" class="btn btn-primary"><i class="fa fa-plus"></i></button> -->
                					
                	<!-- </td> -->
                <!-- </tr> -->
                <!-- <tr> -->
                <!-- <td colspan="3"> -->
                	
              
                	<!--
            		<tab le style="width:100%" id="users" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Nama Item</th>
								<th>Jumlah</th>
								<th>aksi</th>
							</tr>
						</thead>
						<tbody></tbody>
						</table>
                	</td>
                </tr>
				 -->
                </table>
              
				
           

              
                  
				<div class="mws-form-row" style="display:none">
                    <?php echo $form->labelEx($model,'image'); ?>
                    <div class="mws-form-item">
                        <?php echo $form->textField($model,'image',array('size'=>60,'maxlength'=>80)); ?>
                    </div>
                    <?php echo $form->error($model,'image'); ?>

                </div>
                <div class="mws-form-row">
                    <div class="mws-form-item">
                        <?php echo $form->hiddenField($model,'status',array('value'=>1)); ?>
                    </div>
                    <?php echo $form->error($model,'status'); ?>

                </div>

                


            <!-- form -->
        </div>
        <div class="mws-button-row">
            
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
         </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
</div>

