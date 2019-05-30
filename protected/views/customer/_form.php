
<div class="form wide ">

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
		<?php echo $form->labelEx($model,'kode'); ?>
		<?php echo $form->textField($model,'kode'); ?>
		<?php echo $form->error($model,'kode'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'nama'); ?>
		<?php echo $form->textField($model,'nama'); ?>
		<?php echo $form->error($model,'nama'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'alamat'); ?>
		<?php echo $form->textField($model,'alamat'); ?>
		<?php echo $form->error($model,'alamat'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'no_telepon'); ?>
		<?php echo $form->textField($model,'no_telepon'); ?>
		<?php echo $form->error($model,'no_telepon'); ?>
	</div>
	<div class="row">
	<?php 
	$data = CustomerType::model()->findAll();
	$data = CHtml::listData($data,'id','customer_type');

	?>
		<?php echo $form->labelEx($model,'customer_type'); ?>
	<td ><?php echo $form->dropDownList($model,'customer_type', $data, array('empty' => 'Pilih ','separator'=>'|','class'=>'fo rm-control'))?>
			<?php echo $form->error($model,'customer_type'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Simpan' : 'Save',array("class"=>"btn btn-primary")); ?>
		<button type="reset" class="btn btn-primary" id="reset-btn">reset</button>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->