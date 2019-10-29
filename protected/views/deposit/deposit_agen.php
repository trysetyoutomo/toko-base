<?php
$this->breadcrumbs=array(
	'Deposits'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Deposit', 'url'=>array('index')),
	array('label'=>'Manage Deposit', 'url'=>array('admin')),
);
?>

<h1>Deposit Agen</h1>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'deposit-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">untuk penyesuaian saldo, anda dapat menggunakan minus (-).</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'nominal'); ?>
		<?php echo $form->textField($model,'nominal'); ?>
		<?php echo $form->error($model,'nominal'); ?>
	</div>
	<?php 
	$nilai = Customer::model()->findAll("store_id = '".Yii::app()->user->store_id()."' and customer_type = '4' ");
	$data = CHtml::listData($nilai,'id','nama');

	?>
	<div class="row">
		<?php echo $form->labelEx($model,'customer_id'); ?>
		<?php echo $form->dropDownList($model,'customer_id', $data, array('empty' => 'Pilih Agen','separator'=>'|',"class"=>"form-control","style"=>"width:200px"))?>
		
		<?php echo $form->error($model,'customer_id'); ?>
	</div>
	<div class="row">
		<LABEL></LABEL>
		<a  href="<?php echo Yii::app()->createUrl("customer/create") ?>" class=""><i class="fa fa-plus"></i> Tambah Agen</a>
		
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
		<?php echo $form->labelEx($model,'created_at'); ?>
		<?php echo $form->textField($model,'created_at'); ?>
		<?php echo $form->error($model,'created_at'); ?>
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