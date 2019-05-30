<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'barangkeluar-detail-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row" style="display:none">
		<?php echo $form->labelEx($model,'kode'); ?>
		<?php echo $form->textField($model,'kode'); ?>
		<?php echo $form->error($model,'kode'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'jumlah'); ?>
		<?php echo $form->textField($model,'jumlah'); ?>
		<?php echo $form->error($model,'jumlah'); ?>
	</div>

	<div class="row" style="display:none">
		<?php echo $form->labelEx($model,'head_id'); ?>
		<?php echo $form->textField($model,'head_id'); ?>
		<?php echo $form->error($model,'head_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'harga'); ?>
		<?php echo $form->textField($model,'harga',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'harga'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Simpan',array("class"=>"btn btn-primary")); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->