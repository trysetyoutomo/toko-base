<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'users-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'nama Lengkap'); ?>
		<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'level'); ?>
		<?php
		$nilai = ConfigRole::model()->findAll();
		$data = CHtml::listData($nilai,'id','role_name');

		?>
		<?php echo $form->dropDownList($model, 'level', $data,array('prompt'=>'Pilih'));?>
                    
		<?php// echo $form->textField($model,'level'); ?>
		<?php// echo $form->textField($model,'level'); ?>
		
		<?php echo $form->error($model,'level'); ?>
	</div>
		<div class="row">
		<?php 
		$nilai = Branch::model()->findAllNew();
		$data = CHtml::listData($nilai,'id','branch_name');

		?>
		<?php echo $form->labelEx($model,'branch_id'); ?>
<?php echo $form->dropDownList($model,'branch_id', $data, array('empty' => 'Pilih ','separator'=>'|','class'=>'form-cont rol'))?>       
		
		<?php echo $form->error($model,'branch_id'); ?>
	</div>

	<div class="row" style="display:none">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status',array('value'=>1)); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Simpan' : 'Save',array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->