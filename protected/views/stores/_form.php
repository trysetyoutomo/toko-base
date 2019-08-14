<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'stores-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	
	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'logo'); ?>
		<?php echo $form->textField($model,'logo',array('size'=>40,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'logo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address1'); ?>
		<?php echo $form->textField($model,'address1',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'address1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address2'); ?>
		<?php echo $form->textField($model,'address2',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'address2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'city'); ?>
		<?php echo $form->textField($model,'city',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'city'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'state'); ?>
		<?php echo $form->textField($model,'state',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'state'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'postal_code'); ?>
		<?php echo $form->textField($model,'postal_code',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'postal_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'country'); ?>
		<?php echo $form->textField($model,'country',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'country'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'currency_code'); ?>
		<?php echo $form->textField($model,'currency_code',array('size'=>3,'maxlength'=>3)); ?>
		<?php echo $form->error($model,'currency_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'receipt_header'); ?>
		<?php echo $form->textArea($model,'receipt_header',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'receipt_header'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'receipt_footer'); ?>
		<?php echo $form->textArea($model,'receipt_footer',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'receipt_footer'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'TaxType'); ?>
		<?php echo $form->textField($model,'TaxType',array('size'=>5,'maxlength'=>5)); ?>
		<?php echo $form->error($model,'TaxType'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'nop'); ?>
		<?php echo $form->textField($model,'nop',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'nop'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->