<div class="form wide">
	<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'items-satuan-master-form',
	'enableAjaxValidation'=>false,
)); ?>


	<p class="note">Input dengan <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'nama_satuan'); ?>
		<?php echo $form->textField($model,'nama_satuan',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'nama_satuan'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array("class"=>"btn btn-primary")); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->