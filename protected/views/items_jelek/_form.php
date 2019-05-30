<?php
/* @var $this ItemsController */
/* @var $model Items */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'items-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'item_name'); ?>
		<?php echo $form->textField($model,'item_name',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'item_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'item_number'); ?>
		<?php echo $form->textField($model,'item_number',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'item_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description'); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'category_id'); ?>
		<?php echo $form->textField($model,'category_id',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'category_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'unit_price'); ?>
		<?php echo $form->textField($model,'unit_price'); ?>
		<?php echo $form->error($model,'unit_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tax_percent'); ?>
		<?php echo $form->textField($model,'tax_percent'); ?>
		<?php echo $form->error($model,'tax_percent'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'total_cost'); ?>
		<?php echo $form->textField($model,'total_cost'); ?>
		<?php echo $form->error($model,'total_cost'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'discount'); ?>
		<?php echo $form->textField($model,'discount'); ?>
		<?php echo $form->error($model,'discount'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'image'); ?>
		<?php echo $form->textField($model,'image',array('size'=>60,'maxlength'=>80)); ?>
		<?php echo $form->error($model,'image'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kode_outlet'); ?>
		<?php echo $form->textField($model,'kode_outlet',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'kode_outlet'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lokasi'); ?>
		<?php echo $form->textField($model,'lokasi'); ?>
		<?php echo $form->error($model,'lokasi'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hapus'); ?>
		<?php echo $form->textField($model,'hapus'); ?>
		<?php echo $form->error($model,'hapus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'modal'); ?>
		<?php echo $form->textField($model,'modal'); ?>
		<?php echo $form->error($model,'modal'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'stok'); ?>
		<?php echo $form->textField($model,'stok'); ?>
		<?php echo $form->error($model,'stok'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'barcode'); ?>
		<?php echo $form->textField($model,'barcode',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'barcode'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'motif'); ?>
		<?php echo $form->textField($model,'motif',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'motif'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'stok_minimum'); ?>
		<?php echo $form->textField($model,'stok_minimum'); ?>
		<?php echo $form->error($model,'stok_minimum'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'price_distributor'); ?>
		<?php echo $form->textField($model,'price_distributor'); ?>
		<?php echo $form->error($model,'price_distributor'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'price_reseller'); ?>
		<?php echo $form->textField($model,'price_reseller'); ?>
		<?php echo $form->error($model,'price_reseller'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'item_tax'); ?>
		<?php echo $form->textField($model,'item_tax'); ?>
		<?php echo $form->error($model,'item_tax'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->