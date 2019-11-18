<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'deposit-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">untuk penyesuaian saldo, anda dapat menggunakan minus (-).</p>

	<?php echo $form->errorSummary($model); ?>
<div class="row">
		<?php echo $form->labelEx($model,'created_at'); ?>
		<?php echo $form->textField($model,'created_at',array("value"=>date("Y-m-d"),"readonly"=>true)); ?>
		<?php echo $form->error($model,'created_at'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'nominal'); ?>
		<?php echo $form->textField($model,'nominal'); ?>
		<?php echo $form->error($model,'nominal'); ?>
	</div>
	<?php  
	$xD = DepositJenisTransaksi::model()->findAll();
	$xD = CHtml::listData($xD,'id','jenis_transaksi');
	?>
	<div class="row">
		<?php echo $form->labelEx($model,'jenis_transaksi'); ?>
		<?php echo $form->dropDownList($model,'jenis_transaksi', $xD, array('empty' => 'Pilih ','separator'=>'|','class'=>'for m-control'))?>
		<?php echo $form->error($model,'jenis_transaksi'); ?>
	</div>
	
	<div style="display: none;">
		
	<div class="row">
		<?php echo $form->labelEx($model,'created_by'); ?>
		<?php echo $form->textField($model,'created_by'); ?>
		<?php echo $form->error($model,'created_by'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'updated_by'); ?>
		<?php echo $form->textField($model,'updated_by'); ?>
		<?php echo $form->error($model,'updated_by'); ?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model,'updated_at'); ?>
		<?php echo $form->textField($model,'updated_at'); ?>
		<?php echo $form->error($model,'updated_at'); ?>
	</div>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Tambah' : 'Tambah',array("class"=>"btn btn-primary")); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->