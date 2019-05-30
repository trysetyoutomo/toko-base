<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'letak-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'nama'); ?>
		<?php echo $form->textField($model,'nama',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'nama'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kode'); ?>
		<?php echo $form->textField($model,'kode',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'kode'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'keterangan'); ?>
		<?php echo $form->textField($model,'keterangan',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'keterangan'); ?>
	</div>
	<div class="row">
	<?php
$motif = Branch::model()->findAllnew();
$motif = CHtml::listData($motif,'id','branch_name');

	?>
		<?php echo $form->labelEx($model,'branch_id'); ?>
		<?php echo $form->dropDownList($model,'branch_id', $motif, array('empty' => 'Pilih ','separator'=>'|','class'=>'form-contro l'))?>
		<?php echo $form->error($model,'branch_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->