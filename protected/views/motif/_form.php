<div class="form wide">
	<div class="row">
		<div class="col-sm-12">
			<div class="container mt-20">

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
					<?php echo $form->labelEx($model,'category_id',array('class'=>"col-sm-2")); ?>
					<?php echo $form->dropDownList($model,'category_id', $data, array('empty' => 'Pilih Kategori','separator'=>'|',"class"=>"form-control col-sm-10"))?>
					<?php echo $form->error($model,'category_id'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model,'nama',array('class'=>"col-sm-2")); ?>
					<?php echo $form->textField($model,'nama',array('size'=>50,'maxlength'=>50, "class"=>"form-control")); ?>
					<?php echo $form->error($model,'nama'); ?>
				</div>

				<div class="row" style="margin-top:2rem">
					<label> </label>
					<?php echo CHtml::submitButton($model->isNewRecord ? 'Simpan' : 'Daftar ',array("class"=>"btn btn-primary","style"=>"min-width:auto")); ?>
				</div>

				<?php $this->endWidget(); ?>

			</div>
		</div>
	</div>
</div>
<!-- form -->