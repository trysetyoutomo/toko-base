<div class="form wide">
<div class="row">
	<div class="col-sm-12">
		<div class="container mt-20" >
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'letak-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'nama',array('class'=>"col-sm-2")); ?> 	
		<?php echo $form->textField($model,'nama',array('class'=>"col-sm-10 form-control",'size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'nama'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kode',array('class'=>"col-sm-2")); ?> 	
		<?php echo $form->textField($model,'kode',array('class'=>"col-sm-10 form-control",'size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'kode'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'keterangan',array('class'=>"col-sm-2")); ?> 	
		<?php echo $form->textField($model,'keterangan',array('class'=>"col-sm-10 form-control",'size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'keterangan'); ?>
	</div>
	<div class="row">
	<?php
$store_id = Yii::app()->user->store_id();
$motif = Branch::model()->findAll("store_id = '$store_id' ");
$motif = CHtml::listData($motif,'id','branch_name');

	?>
		<?php echo $form->labelEx($model,'branch_id',array('class'=>"col-sm-2")); ?> 	
		<?php echo $form->dropDownList($model,'branch_id', $motif, array('empty' => 'Pilih ','separator'=>'|','class'=>'form-control col-lg-10'))?>
		<?php echo $form->error($model,'branch_id'); ?>
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