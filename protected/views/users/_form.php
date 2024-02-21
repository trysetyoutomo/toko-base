<div class="form wide">
<div class="row">
	<div class="col-sm-12">
		<div class="container mt-20" >

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'users-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'username',array('class'=>"col-sm-2")); ?> 	
		<?php echo $form->textField($model,'username',array('size'=>50,'maxlength'=>50,'class'=>'col-sm-10 form-control','style'=>'max-width:250px;','class'=>'col-sm-10 form-control','style'=>'max-width:250px;')); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>
	<div class="row">
		<label ></label>
		<label style="min-width:350px;text-align:left" class="text-info">username digunakan untuk masuk ke dalam sistem</label>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model,'email',array('class'=>"col-sm-2")); ?> 	
		<?php echo $form->textField($model,'email',array('size'=>50,'maxlength'=>50,'class'=>'col-sm-10 form-control','style'=>'max-width:250px;')); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>
	<div class="row">
		<label ></label>
		<label style="min-width:350px;text-align:left" class="text-info">email digunakan untuk kebutuhan verifikasi dan notifkasi</label>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'nama Lengkap',array('class'=>"col-sm-2")); ?> 	
		<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50,'class'=>'col-sm-10 form-control','style'=>'max-width:250px;')); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password',array('class'=>"col-sm-2")); ?> 	
		<?php echo $form->passwordField($model,'password',array('size'=>50,'maxlength'=>50,'class'=>'col-sm-10 form-control','style'=>'max-width:250px;')); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>
	<div class="row">
		<label>
	</div>
	<div class="row" style="margin-bottom:1rem">
		<input id="showpwd" type="checkbox" onclick="myFunction()"> Tampilkan Password
		<label for="showpwd"></label>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'level',array('class'=>"col-sm-2")); ?> 	
		<?php
		$nilai = ConfigRole::model()->findAll("role_name!='superadmin' && role_name != 'gudang'");
		$data = CHtml::listData($nilai,'id','role_name');
		?>
		<?php echo $form->dropDownList($model, 'level', $data,array('prompt'=>'Pilih','class'=>'col-sm-10 form-control','style'=>'max-width:250px;'));?>
                    		
		<?php echo $form->error($model,'level'); ?>
	</div>
		<div class="row">
		<?php 
		$nilai = Branch::model()->findAll(" store_id = '".Yii::app()->user->store_id()."' ");
		$data = CHtml::listData($nilai,'id','branch_name');

		?>
		<?php echo $form->labelEx($model,'branch_id',array('class'=>"col-sm-2")); ?> 	
			<?php echo $form->dropDownList($model,'branch_id', $data, array('empty' => 'Pilih ','separator'=>'|','class'=>'col-sm-10 form-control','style'=>'max-width:250px;'))?>       
		
		<?php echo $form->error($model,'branch_id'); ?>
			<div class="row">
		<label ></label>
		<label style="min-width:350px;text-align:left" class="text-info">Cabang dimana petugas ditugaskan</label>
	</div>
	</div>

	<div class="row" style="display:none">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status',array('value'=>1)); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<label> </label>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Simpan' : 'Simpan ',array("class"=>"btn btn-primary","style"=>"min-width:auto")); ?>
	</div>

<?php $this->endWidget(); ?>

</div>
</div>
</div>
</div>
<!-- form -->
<script>
function myFunction() {
  var x = document.getElementById("Users_password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
</script>
