<div class="form wide">
<h1>Ubah Jumlah 
<?php  
$brg = Items::model()->findByPk($model->kode);
echo "'$brg->item_name'";

?>
</h1>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'outlet-form',
	'enableAjaxValidation'=>false,
)); ?>

	<!-- <p class="note">Fields with <span class="required">*</span> are required.</p> -->

	<?php echo $form->errorSummary($model); ?>


	<div class="row"  >
		<?php echo $form->labelEx($model,'jumlah'); ?>
		<?php echo $form->textField($model,'jumlah',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'jumlah'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'harga'); ?>
		<?php echo $form->textField($model,'harga',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'harga'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'supplier_id'); ?>
		<?php echo $form->dropdownlist($model,'supplier_id',CHtml::listdata(Supplier::model()->findAll(),"id","nama")); ?>
		<?php echo $form->error($model,'supplier_id'); ?>
	</div>




	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Simpan' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->