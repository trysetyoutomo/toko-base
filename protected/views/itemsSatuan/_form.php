<!-- <script src="http://code.jquery.com/jquery-2.1.0.min.js"></script> -->
<link rel="stylesheet" href="<?php echo Yii::app()->baseUrl; ?>/js/typehead/dist/jquery.typeahead.min.css">
<script src="<?php echo Yii::app()->baseUrl; ?>/js/typehead/dist/jquery.typeahead.min.js"></script>


<style type="text/css">
	.typeahead__list{
		width: 200px;
		margin-left: 110px;
	}
	.js-typeahead{
		position : relative!important;
	}
</style>
<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'items-satuan-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row" style="display: none;"  >

		<?php echo $form->labelEx($model,'item_id'); ?>
	<?php 
	if ($model->isNewRecord){
	?>
		<?php echo $form->textField($model,'item_id',array("value"=>$_REQUEST['id'])); ?>
	<?php }else{  ?>
		<?php echo $form->textField($model,'item_id',array("value"=>$model->item_id)); ?>
	<?php }  ?>
		<?php echo $form->error($model,'item_id'); ?>
	</div>
	<?php 
	// $nilai2 = ItemsSat::model()->findAll();
	// $letak = CHtml::listData($nilai2,'id','nama');

	?>
	<div class="row">
		<?php //echo $form->labelEx($model,'nama_satuan'); ?>
		<?php //echo $form->dropDownList($model,'nama_satuan', $letak, array('empty' => 'Pilih ','separator'=>'|','class'=>'for m-control'))?>
		<div class="row">
		<label for="ItemsSatuan_harga" class="required">Nama Satuan 
		<span class="required">*</span></label>		
	  <!-- <div class="js-result-container"></div> -->

		        <div class="typeahead__container">
		            <div class="typeahead__field" style="width: 150px;">
		            <span class="typeahead__query">
		                <input  value="<?php echo $model->nama_satuan; ?>" list="datasatuan" class="js-typeahead form-control" name="ItemsSatuan[nama_satuan]" type="search" autofocus autocomplete="off">
		            </span>
		            </div>
		        <!-- </div> -->

		</div>
	
		
		<?php // echo $form->textField($model,'nama_satuan',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'nama_satuan'); ?>
	</div>
	<?php 

	// if ()

	// source code dibawah ini untuk mendapatkan satuan utama
	// var_dump($model->satuan);
	// exit;
	$item_id = $_REQUEST['id'];
	$nama_satuan =ItemsSatuan::model()->find(" item_id ='$item_id' and is_default = 1 ")->nama_satuan; 

	if ($model->is_default=="1"){
		$array = array("readonly"=>true);
		$nama_satuan = $nama_satuan." (Satuan Utama)";
		// $nama_satuan = "";
	}else{
		$array = array();
		$nama_satuan = $nama_satuan." (Satuan Utama)";
	}
	// var_dump($array);
	?>
	<div class="row">
		<?php echo $form->labelEx($model,'satuan'); ?>
		<?php echo $form->textField($model,'satuan',array("class"=>'form-control','style'=>'max-width:150px','readonly'=>$model->is_default=="1")); ?>
		<?php  echo $nama_satuan; ?>
	<?php echo $form->error($model,'satuan'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'harga'); ?>
		<?php echo $form->textField($model,'harga',array("class"=>'form-control','style'=>'max-width:150px')); ?>
	<?php echo $form->error($model,'harga'); ?>
	</div>
	<div class="row">

		<?php echo $form->labelEx($model,'harga_beli'); ?>
		<?php echo $form->textField($model,'harga_beli',array("class"=>'form-control','style'=>'max-width:150px')); ?>

	<?php echo $form->error($model,'harga_beli'); ?>
	</div>
	<div class="row">
	<?php 
	if ($model->isNewRecord){
		$model->barcode = ItemsController::generateBarcode();
		// echo "c123";
	}
	?>

		<?php echo $form->labelEx($model,'barcode'); ?>
		<?php echo $form->textField($model,'barcode',array("class"=>'form-control','style'=>'max-width:150px','readonly'=>false)); ?>
		<?php echo $form->error($model,'barcode'); ?>	
	</div>
	<div class="row">

	<?php 
	$branch_id = Yii::app()->user->branch();
	$nilai2 = Letak::model()->findAll("branch_id = '$branch_id' ");
$letak = CHtml::listData($nilai2,'id','nama');

	?>
		<?php echo $form->labelEx($model,'letak_id'); ?>
		<?php echo $form->dropDownList($model,'letak_id', $letak, array('empty' => 'Pilih ','separator'=>'|','class'=>'form-control','style'=>'max-width:150px'))?>
	<?php echo $form->error($model,'letak_id'); ?>
	</div>
	<div class="row" >
		<?php echo $form->labelEx($model,'Satuan Utama'); ?>
		<?php echo $form->dropDownList($model,'is_default', array("0"=>"Tidak","1"=>"Ya"), array('class'=>'form-control','style'=>'max-width:150px'))?>
		<?php echo $form->error($model,'is_default'); ?>
	</div>
	<div class="row" style="display: none;" >
		<?php echo $form->labelEx($model,'stok_minimum'); ?>
	<?php echo $form->textField($model,'stok_minimum'); ?>
		<?php echo $form->error($model,'stok_minimum'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Simpan' : 'Save',array("class"=>"btn btn-primary")); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->


    <script>

    	$(document).ready(function(e){
    		$("#ItemsSatuan_barcode").keydown(function(e){
    			if (e.which==13 || e.keyCode==13){
    				e.preventDefault();
    			}
    		});
    	});
    	<?php 
    	$sql = "select * from items_satuan_master";
    	$data = Yii::app()->db->createCommand($sql)->queryAll();
    	$array = array();
    	

    	?>
    	var src = <?php echo json_encode($array) ?>;

        // var data = {
        //     countries:src
            
        // };

        // typeof $.typeahead === 'function' && $.typeahead({
        //     input: ".js-typeahead",
        //     minLength: 1,
        //     order: "asc",
        //     group: true,
        //     maxItemPerGroup: 3,
        //     groupOrder: function (node, query, result, resultCount, resultCountPerGroup) {

        //         var scope = this,
        //             sortGroup = [];

        //         for (var i in result) {
        //             sortGroup.push({
        //                 group: i,
        //                 length: result[i].length
        //             });
        //         }

        //         sortGroup.sort(
        //             scope.helper.sort(
        //                 ["length"],
        //                 false, // false = desc, the most results on top
        //                 function (a) {
        //                     return a.toString().toUpperCase()
        //                 }
        //             )
        //         );

        //         return $.map(sortGroup, function (val, i) {
        //             return val.group
        //         });
        //     },
        //     hint: true,
        //     // dropdownFilter: "All",
        //     href: "#",
        //     template: "{{display}}, <small><em>{{group}}</em></small>",
        //     emptyTemplate: "no result for {{query}}",
        //     source: {
        //         satuan: {
        //             data: data.countries
        //         }
        //         // capital: {
        //         //     data: data.capitals
        //         // }
        //     },
        //     // callback: {
        //     //     onClickAfter: function (node, a, item, event) {
        //     //         event.preventDefault();

        //     //         var r = confirm("You will be redirected to:\n" + item.href + "\n\nContinue?");
        //     //         if (r == true) {
        //     //             window.open(item.href);
        //     //         }

        //     //         $('.js-result-container').text('');

        //     //     },
        //     //     onResult: function (node, query, obj, objCount) {

        //     //         console.log(objCount)

        //     //         var text = "";
        //     //         if (query !== "") {
        //     //             text = objCount + ' elements matching "' + query + '"';
        //     //         }
        //     //         $('.js-result-container').text(text);

        //     //     }
        //     // },
        //     debug: false
        // });

    </script>

<datalist id="datasatuan">
	<?php 
	foreach ($data as $key => $value) {
		echo "<option value='$value[nama_satuan]'>";
	}
	?>  
</datalist>