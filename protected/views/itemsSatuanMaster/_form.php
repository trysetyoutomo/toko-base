<div class="form wide">
<div class="row">
	<div class="col-sm-6">
		<div class="container mt-20" >
<div class="form wide">
	<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'items-satuan-master-form',
	'enableAjaxValidation'=>false,
)); ?>


	<p class="note">Input dengan <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'nama_satuan',array('class'=>"col-sm-2")); ?> 
		<?php echo $form->textField($model,'nama_satuan',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'nama_satuan'); ?>
	</div>

	<div class="row">
		<label> </label>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Simpan' : 'Simpan ',array("class"=>"btn btn-primary","style"=>"min-width:auto")); ?>
	</div>

<?php $this->endWidget(); ?>


</div>
</div>
</div>
</div>
<!-- form -->