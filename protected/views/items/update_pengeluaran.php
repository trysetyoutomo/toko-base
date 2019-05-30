<link href="<?php echo Yii::app()->request->baseUrl; ?>/select2/select2.css" rel="stylesheet"/>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/select2/select2.js"></script>


<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/datetimepicker/build/jquery.datetimepicker.full.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/datetimepicker/build/jquery.datetimepicker.min.css">


<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-custom/jquery-ui.min.css">
<!-- 
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-custom/jquery-ui.min.js"></script> -->
<script type="text/javascript">
		$(document).ready(function(){
		 $('#Pengeluaran_tanggal').datetimepicker();
		 // $('#Pengeluaran_tanggal').datepicker({ 
		 // 	dateFormat: 'yy-mm-dd' ,
   //         changeYear: true,
   //         changeMonth: true
		 // });
	 });
</script>
<h1>
	Ubah Pengeluaran
</h1>
<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'supplier-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'tanggal'); ?>
		<?php echo $form->textField($model,'tanggal',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'tanggal'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'jenis_pengeluaran'); ?>
			<select name="Pengeluaran[jenis_pengeluaran]" id="Pengeluaran_jenis_pengeluaran">
					<?php foreach (JenisBeban::model()->findAll() as $jb) { ?>
						<option value="<?php echo $jb->nama ?>"><?php echo $jb->nama ?></option>
					<?php } ?>
			</select>
		<?php echo $form->error($model,'jenis_pengeluaran'); ?>
	</div>

	<div class="row" style="display: none;">
		<?php echo $form->labelEx($model,'user'); ?>
		<?php echo $form->textField($model,'user',array('size'=>50,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'user'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'total'); ?>
		<?php echo $form->textField($model,'total',array('size'=>50,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'total'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'keterangan'); ?>
		<?php echo $form->textField($model,'keterangan',array('size'=>50,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'keterangan'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Simpan',array("class"=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->