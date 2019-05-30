<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-custom/jquery-ui.min.css">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-custom/jquery-ui.min.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
	 $('#Barangkeluar_tanggal').datepicker(
        { 
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true

    	});
    });
</script>
<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'barangkeluar-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'tanggal'); ?>
		<?php echo $form->textField($model,'tanggal'); ?>
		<?php echo $form->error($model,'tanggal'); ?>
	</div>

	<div class="row" style="display:none">
		<?php echo $form->labelEx($model,'sumber'); ?>
		<?php echo $form->textArea($model,'sumber',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'sumber'); ?>
	</div>

	<div class="row" style="display:none">
		<?php echo $form->labelEx($model,'user'); ?>
		<?php echo $form->textField($model,'user',array('size'=>60,'maxlength'=>60)); ?>
		<?php echo $form->error($model,'user'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'keterangan'); ?>
		<?php echo $form->textField($model,'keterangan',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'keterangan'); ?>
	</div>
	<?php 
	$nilai = JenisKeluar::model()->findAll();
	$data = CHtml::listData($nilai,'nama','nama');

	?>
	<div class="row">
		<?php echo $form->labelEx($model,'jenis_keluar'); ?>
		<?php echo $form->dropdownlist($model,'jenis_keluar',$data); ?>
		<?php echo $form->error($model,'jenis_keluar'); ?>
	</div>

	<div class="row" style="display:none">
		<?php echo $form->labelEx($model,'jenis'); ?>
		<?php echo $form->textField($model,'jenis',
		
		array('size'=>20,'maxlength'=>20)

		); ?>
		<?php echo $form->error($model,'jenis'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Simpan',array("class"=>"btn btn-primary")); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->