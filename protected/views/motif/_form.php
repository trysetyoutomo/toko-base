<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'motif-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	<?php 
	$nilai = Categories::model()->findAll("store_id = '".Yii::app()->user->store_id()."' ");
	$data = CHtml::listData($nilai,'id','category');

	?>
	<div class="row">
		<?php echo $form->labelEx($model,'category_id'); ?>
		<?php echo $form->dropDownList($model,'category_id', $data, array('empty' => 'Pilih Kategori','separator'=>'|'))?>
		<?php echo $form->error($model,'category_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'nama'); ?>
		<?php echo $form->textField($model,'nama',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'nama'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Simpan' : 'Simpan',array("class"=>"btn btn-primary")); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->