<div class="form wide" style="margin-left:1rem" >
<div class="row">
	<div class="col-sm-6">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'config-menu-form',
			'enableAjaxValidation'=>false,
		)); ?>
	<div class="container">

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'controllerID',array('class'=>"col-sm-2")); ?>
		<?php echo $form->textField($model,'controllerID',array('class'=>'col-sm-10 form-control','size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'controllerID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'actionID',array('class'=>"col-sm-2")); ?>
		<?php echo $form->textField($model,'actionID',array('class'=>'col-sm-10 form-control','size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'actionID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'value',array('class'=>"col-sm-2")); ?>
		<?php echo $form->textField($model,'value',array('class'=>'col-sm-10 form-control','size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'value'); ?>
	</div>

	<div class="row">
		
		<?php 
		$dataCategoryMenu = ConfigMenuCategory::model()->findAll();
		$listDataCategoryMenu = CHtml::listData($dataCategoryMenu,'id','category_name');
		?>
		<?php echo $form->labelEx($model,'category_menu_id',array('class'=>"col-sm-2")); ?>
		<?php echo $form->dropDownList($model,'category_menu_id', $listDataCategoryMenu, array('empty' => 'Pilih ','separator'=>'|','class'=>'form-control col-lg-12')); ?>
		<?php echo $form->error($model,'category_menu_id'); ?>
	</div>

	<div class="row" style="display:none">
		<?php echo $form->labelEx($model,'hapus'); ?>
		<?php echo $form->textField($model,'hapus'); ?>
		<?php echo $form->error($model,'hapus'); ?>
	</div>

	<div class="row ">
	<label> </label>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array("class"=>"btn btn-primary","style"=>"min-width:auto")); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
</div>
</div>
</div>