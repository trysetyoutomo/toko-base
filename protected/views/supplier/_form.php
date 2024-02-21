<div class="form wide">
<div class="row">
	<div class="col-sm-6">
		<div class="container mt-20" >

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'supplier-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'nama',array('class'=>"col-sm-2")); ?> 	
		<?php echo $form->textField($model,'nama',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'nama'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'telepon',array('class'=>"col-sm-2")); ?> 	
		<?php echo $form->textField($model,'telepon',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'telepon'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'alamat',array('class'=>"col-sm-2")); ?> 	
		<?php echo $form->textField($model,'alamat',array('size'=>50,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'alamat'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'keterangan',array('class'=>"col-sm-2")); ?> 	
		<?php echo $form->textField($model,'keterangan',array('size'=>50,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'keterangan'); ?>
	</div>

	<div class="row">
		<label> </label>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Simpan' : 'Simpan ',array("class"=>"btn btn-primary","style"=>"min-width:auto")); ?>
	</div>


	<!-- <div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Simpan' : 'Save',array("class"=>'btn btn-primary')); ?>
	<button type="reset" class="btn btn-primary" id="reset-btn">Reset</button>
	</div> -->

<?php $this->endWidget(); ?>

</div>
</div>
</div>
</div>
<!-- form -->