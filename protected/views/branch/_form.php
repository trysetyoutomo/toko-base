<div class="form wide">

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'branch-form',
    'enableAjaxValidation' => false,
));
?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-2">
			<?php echo $form->labelEx($model, 'branch_name'); ?>
		</div>
		<div class="col-sm-10">
            <?php echo $form->textField($model, 'branch_name', array('size' => 60, 'maxlength' => 150, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'branch_name'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-2">
            <?php echo $form->labelEx($model, 'address'); ?>
		</div>
		<div class="col-sm-10">
            <?php echo $form->textField($model, 'address', array('size' => 60, 'maxlength' => 225, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'address'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-2">
            <?php echo $form->labelEx($model, 'telp'); ?>
		</div>
		<div class="col-sm-10">
            <?php echo $form->textField($model, 'telp', array('size' => 40, 'maxlength' => 40, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'telp'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-2">
            <?php echo $form->labelEx($model, 'slogan'); ?>
		</div>
			<div class="col-sm-10">
            <?php echo $form->textField($model, 'slogan', array('size' => 40, 'maxlength' => 40, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'slogan'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array("class" => "btn btn-primary")); ?>
        </div>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
