<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'controllerID'); ?>
		<?php echo $form->textField($model,'controllerID',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'actionID'); ?>
		<?php echo $form->textField($model,'actionID',array('size'=>30,'maxlength'=>30)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'value'); ?>
		<?php echo $form->textField($model,'value',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'category_menu_id'); ?>
		<?php echo $form->textField($model,'category_menu_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'hapus'); ?>
		<?php echo $form->textField($model,'hapus'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->