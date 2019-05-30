<?php
/* @var $this ItemsController */
/* @var $model Items */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'item_name'); ?>
		<?php echo $form->textField($model,'item_name',array('size'=>30,'maxlength'=>30)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'item_number'); ?>
		<?php echo $form->textField($model,'item_number',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'description'); ?>
		<?php echo $form->textField($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'category_id'); ?>
		<?php echo $form->textField($model,'category_id',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'unit_price'); ?>
		<?php echo $form->textField($model,'unit_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tax_percent'); ?>
		<?php echo $form->textField($model,'tax_percent'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'total_cost'); ?>
		<?php echo $form->textField($model,'total_cost'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'discount'); ?>
		<?php echo $form->textField($model,'discount'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'image'); ?>
		<?php echo $form->textField($model,'image',array('size'=>60,'maxlength'=>80)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kode_outlet'); ?>
		<?php echo $form->textField($model,'kode_outlet',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'lokasi'); ?>
		<?php echo $form->textField($model,'lokasi'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'hapus'); ?>
		<?php echo $form->textField($model,'hapus'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'modal'); ?>
		<?php echo $form->textField($model,'modal'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stok'); ?>
		<?php echo $form->textField($model,'stok'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'barcode'); ?>
		<?php echo $form->textField($model,'barcode',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'motif'); ?>
		<?php echo $form->textField($model,'motif',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stok_minimum'); ?>
		<?php echo $form->textField($model,'stok_minimum'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'price_distributor'); ?>
		<?php echo $form->textField($model,'price_distributor'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'price_reseller'); ?>
		<?php echo $form->textField($model,'price_reseller'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'item_tax'); ?>
		<?php echo $form->textField($model,'item_tax'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->