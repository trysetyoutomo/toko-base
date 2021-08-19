<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'parameter-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php 
	// if (isset($form->errorSummary($model))){	
	// echo $form->errorSummary($model);
	// }

	 ?>

	<div class="row">
		<?php echo $form->labelEx($model,'pajak'); ?>
		<?php echo $form->textField($model,'pajak'); ?>
		<?php echo $form->error($model,'pajak'); ?>
	</div>

	<!-- <div class="row">
		<?php echo $form->labelEx($model,'meja'); ?>
		<?php echo $form->textField($model,'meja'); ?>
		<?php echo $form->error($model,'meja'); ?>
	</div> -->
	<div class="row">
		<?php echo $form->labelEx($model,'service'); ?>
		<?php echo $form->textField($model,'service'); ?>
		<?php echo $form->error($model,'service'); ?>
	</div>
	<?php
		// $model_diskon = 
		// print_r($diskon);
	 ?>
	 	<!-- <div class="row">
		<?php echo $form->labelEx($model,'service'); ?>
		<?php echo $form->textField($model,'service'); ?>
		<?php echo $form->error($model,'service'); ?>
	</div> -->
	<script type="text/javascript">
		$(document).ready(function(){
		
			$("#btn-hapus").click(function(e){
				let c = confirm("Yakin hapus ? ");
				if (!c) return false;
				let d_id = $("#Parameter_diskon").val();
				$.ajax({
					"data" : "id="+d_id,
					"url" : "index.php?r=diskon/hapus",
					success : function(data){
						let x = JSON.parse(data);
						if (x.success){
							location.reload();
						}
					}
				})

			});

			$("#tambah-diskon").click(function(e){
				var angka = 0;
				while (angka==0){
					 angka = prompt("masukan diskon","0");
				}
			});
		});	
	</script>
	<div class="row">
		<?php echo $form->labelEx($model,'diskon'); ?>
		<?php echo $form->dropdownlist($model,'diskon',$diskon); ?>
		<a href="<?php echo Yii::app()->createUrl('diskon/create') ?>">
			<input type="button" value="tambah diskon" id="" class="btn btn-primary">
		</a>
		<a class="btn btn-danger " id="btn-hapus">Hapus Diskon</a>
		<?php echo $form->error($model,'diskon'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Simpan',array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

