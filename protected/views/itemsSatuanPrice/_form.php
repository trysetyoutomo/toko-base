<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'items-satuan-price-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	<?php
		if ($model->isNewRecord){
			$model->item_satuan_id = $_REQUEST['id'];
		}
	?>
	<div class="row" style="display: none;">
		<?php echo $form->labelEx($model,'item_satuan_id'); ?>
		<?php echo $form->textField($model,'item_satuan_id',array('size'=>20,'maxlength'=>20,'class'=>'form -control')); ?>
		<?php echo $form->error($model,'item_satuan_id'); ?>
	</div>

	<?php 
	foreach (ItemsSatuanPriceMaster::model()->findAll() as $key => $value) {

		// echo $key;
		// echo "<br>";
		$price = ItemsSatuanPrice::model()->find("price_type = '$value->name' and item_satuan_id='$_REQUEST[id]' ")->price;


	?>

		<div class="row">
			<?php echo $form->labelEx($model,'price_type'); ?>
			<input  size="10" maxlength="255" name="ItemsSatuanPrice[price_type][]" id="ItemsSatuanPrice_price_type" type="text" value="<?php echo $value->name ?>">
			<?php echo $form->error($model,'price_type'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'price'); ?>
			<input size="20" maxlength="20" name="ItemsSatuanPrice[price][]" id="ItemsSatuanPrice_price" type="text" value="<?php echo $price; ?>">
			<?php echo $form->error($model,'price'); ?>
		</div>
	<br>
	<br>
	<?php  }?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Simpan' : 'Save',array("class"=>"btn btn-primary")); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->