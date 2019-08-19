<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
<style>
	p,h1,h2,h3,h4,h5,a,td,th,label,body{
		font-family: 'Open Sans', sans-serif;
	}
</style>
<div class="container " >
	<div class="row">
		<div class="col-sm-2"></div>
		<div class="col-sm-8">
		<h1 class="mt-70">Daftar Toko</h1>

		<div class="form wide mt-20" >

		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'stores-form',
			'enableAjaxValidation'=>false,
			'htmlOptions'=>array(
				'enctype'=>"multipart/form-data"
			)
		)); ?>

		<p class="note mt-10 mb-10">Isian dengan <span class="required">*</span> wajib disi.</p>

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
		<?php echo $form->labelEx($model,'postal_code'); ?>
		<?php echo $form->textField($model,'postal_code',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'postal_code'); ?>
		</div>





		<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Daftar' : 'Save',array("class"=>"btn btn-primary")); ?>
		</div>

		<?php $this->endWidget(); ?>

		</div>
		<!-- form -->
		<div>
	</div>
</div>