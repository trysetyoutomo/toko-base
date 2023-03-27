<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'akun-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'nama_akun'); ?>
		<?php echo $form->textField($model,'nama_akun',array('size'=>50,'maxlength'=>50,'class'=>'form-control','style'=>'max-width:250px;','class'=>'form-control','style'=>'max-width:250px;')); ?>
		<?php echo $form->error($model,'nama_akun'); ?>
	</div>

    <div class="row">
        <?php 
		$nilai = AkuntansiAkunSubGroup::model()->findAll();
		$data = CHtml::listData($nilai,'id','nama_subgroup');
		$array = [];
		foreach ($nilai as $key => $value) {
			$GroupName = AkuntansiGroup::model()->findByPk($value->group_id)->nama_subgroup;
			$array[$value->id] = $GroupName." - ".$value->nama_subgroup;
		}
        ?>
		<?php echo $form->labelEx($model,'subgroup_id'); ?>
		<?php echo $form->dropDownList($model, 'subgroup_id', $array,array('prompt'=>'Pilih','class'=>'form-control','style'=>'max-width:250px;'));?>
		<?php echo $form->error($model,'subgroup_id'); ?>
	</div>

	<div class="row">
        <?php 
		$nilai = AkuntansiAkunSubGroupDetail::model()->findAll();
		$array = CHtml::listData($nilai,'id','nama_subgroup_detail');
		// $array = [];
		// foreach ($nilai as $key => $value) {
		// 	$GroupName = AkuntansiGroup::model()->findByPk($value->group_id)->nama_subgroup;
		// 	$array[$value->id] = $GroupName." - ".$value->nama_subgroup;
		// }
        ?>
		
	</div>

	

    <div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Simpan' : 'Save',array('class'=>'btn btn-primary')); ?>
	</div>


<?php $this->endWidget(); ?>