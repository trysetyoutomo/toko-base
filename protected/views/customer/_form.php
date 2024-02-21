<div class="form wide">
<div class="row">
	<div class="col-sm-12">
		<div class="container mt-20" >
			

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'customer-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>


	<?php 
	if ($model->isNewRecord){
		$model->kode = CustomerController::generateCustomerCode();
	}
	?>
	<div class="row" style="display: none;">
		<?php echo $form->labelEx($model,'kode',array('class'=>"col-sm-3")); ?> 	
		<?php echo $form->textField($model,'kode',array("class"=>"form-control")); ?>
		<?php echo $form->error($model,'kode'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'no_telepon',array('class'=>"col-sm-3")); ?> 	
		<?php echo $form->textField($model,'no_telepon',array("class"=>"form-control",'style'=>'max-width:150px')); ?>
		<?php echo $form->error($model,'no_telepon'); ?>
	</div>
	

	<div class="row">
		<?php echo $form->labelEx($model,'nama',array('class'=>"col-sm-3")); ?> 	
		<?php echo $form->textField($model,'nama',array("class"=>"form-control",'style'=>'max-width:150px')); ?>
		<?php echo $form->error($model,'nama'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'kode_agen',array('class'=>"col-sm-3")); ?> 	
		<?php echo $form->textField($model,'kode_agen',array("class"=>"form-control",'style'=>'max-width:150px')); ?>
		<?php echo $form->error($model,'kode_agen'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'alamat',array('class'=>"col-sm-3")); ?> 	
		<?php echo $form->textField($model,'alamat',array("class"=>"form-control",'style'=>'max-width:150px')); ?>
		<?php echo $form->error($model,'alamat'); ?>
	</div>


	<div class="row">
	<?php 
	$data = CustomerType::model()->findAll();
	$data = CHtml::listData($data,'id','customer_type');

	?>
		<?php echo $form->labelEx($model,'customer_type',array('class'=>"col-sm-3")); ?> 	
	<td ><?php echo $form->dropDownList($model,'customer_type', $data, array('empty' => 'Pilih ','separator'=>'|','class'=>'form-control', 'style'=>'max-width:150px'))?>
			<?php echo $form->error($model,'customer_type'); ?>
	</div>


	<div class="row" style="margin-top:2rem" >
		<label> </label>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Simpan' : 'Simpan ',array("class"=>"btn btn-primary","style"=>"min-width:auto")); ?>
	</div>

<?php $this->endWidget(); ?>

</div>
</div>
</div>
</div>
<!-- form -->