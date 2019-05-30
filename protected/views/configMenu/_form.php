<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'config-menu-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'controllerID'); ?>
		<?php echo $form->textField($model,'controllerID',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'controllerID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'actionID'); ?>
		<?php echo $form->textField($model,'actionID',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'actionID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'value'); ?>
		<?php echo $form->textField($model,'value',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'value'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'category_menu_id'); ?>
		<?php echo $form->textField($model,'category_menu_id'); ?>
		<?php echo $form->error($model,'category_menu_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hapus'); ?>
		<?php echo $form->textField($model,'hapus'); ?>
		<?php echo $form->error($model,'hapus'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->