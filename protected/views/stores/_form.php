<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  
<style>
	p,h1,h2,h3,h4,h5,a,td,th,label,body{
		font-family: 'Open Sans', sans-serif;
	}
	.container{
		background-color: #ffff;
		position: relative;
		top:4rem;
		padding-bottom: 5rem;
	}

	.container input, .container select{
		border-radius: 5px;
		border:1px gray solid;
		padding:5px;
	}
	.container .row{
		margin-top:10px;
	}
</style>
<script>
    function togglePasswordVisibility() {
      var passwordInput = document.getElementById("Users_password");
      var icon = document.getElementById("togglePasswordIcon");

      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
      } else {
        passwordInput.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
      }
    }
  </script>
<div class="container well " >
	<div class="row">
		<div class="col-sm-2"></div>
		<div class="col-sm-8">
		<h1 class="mt-70 font-weight-bold">Toko Baru</h1>
		<h5>Silahkan lengkapi informasi berikut ini untuk menyelesaikan pendaftaran toko</h5>

		<div class="form wide mt-20" >

		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'stores-form',
			'enableAjaxValidation'=>false,
			'htmlOptions'=>array(
				'enctype'=>"multipart/form-data"
			)
		)); ?>
		<?php if (!empty($message)): ?>
		<div class="alert alert-danger" role="alert">
			<?=$message?>
		</div>
		<?php endif; ?>

		<p class="note mt-10 mb-10">Isian dengan <span class="required">*</span> wajib disi.</p>

		<!-- <?php echo $form->errorSummary($model); ?> -->
		<div class="row-group d-block">
		<div class="row">
		<?php echo $form->labelEx($model,'name'); ?> 	
		<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'name'); ?>
		</div>

		<div class="row">
		<?php echo $form->labelEx($model,'store_type'); ?>
		<?php echo $form->textField($model,'store_type',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'store_type'); ?>
		</div>

	

		<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'email'); ?>
		</div>

		<div class="row">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'phone'); ?>
		</div>

		<div class="row">
		<?php echo $form->labelEx($model,'address1'); ?>
		<?php echo $form->textField($model,'address1',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'address1'); ?>
		</div>


		<div class="row">
		<?php echo $form->labelEx($model,'postal_code'); ?>
		<?php echo $form->textField($model,'postal_code',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'postal_code'); ?>
		</div>
		</div>

		<div class="row position-relative" >
			<?php echo $form->labelEx($u,'password'); ?>
			<?php echo $form->passwordField($u,'password',array('size'=>50,'maxlength'=>50)); ?>
			<i style="position:absolute;right:10.5rem;top:13px;" id="togglePasswordIcon" class="fas fa-eye icon" onclick="togglePasswordVisibility()"></i> 
			<?php echo $form->error($u,'password'); ?>
			<!-- <input type="checkbox" onclick="togglePasswordVisibility()">  -->
		</div>



		<div class="row buttons mt-3" style="justify-content: left;">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Daftar' : 'Daftar ',array("class"=>"btn btn-primary","style"=>
	"min-width:150px")); ?>
		</div>

		<?php $this->endWidget(); ?>

		</div>
		<!-- form -->
		<div>
	</div>
</div>