<?php
/* @var $this CategoriesController */
/* @var $model Categories */
/* @var $form CActiveForm */
?>

<div class="form wide">
<div class="row">
	<div class="col-sm-12">
		<div class="container mt-20" >

    
    <div class="mws-panel-body no-padding">
        <?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'categories-form',
	'enableAjaxValidation'=>false,
        'htmlOptions' => array(
                'class' => 'mws-form',
            )
)); ?>
        <div class="mws-form-inline">

            <!-- <p class="note">Fields with <span class="required">*</span> are required.</p> -->

            <?php echo $form->errorSummary($model); ?>
<!--
                            <div class="row">
                    <?php echo $form->labelEx($model,'id'); ?>
                    <div class="mws-form-item">
                        <?php echo $form->textField($model,'id'); ?>
                    </div>
                    <?php echo $form->error($model,'id'); ?>

                </div>
-->    
                    <div class="row">
                    <?php echo $form->labelEx($model,'category',array('class'=>"col-sm-2")); ?> 	
                    <?php echo $form->textField($model,'category',array('size'=>50,'maxlength'=>50, "class"=>"form-control col-sm-10")); ?>
                    <?php echo $form->error($model,'category'); ?>
                    </div>
				<!--
                                <div class="row">
                    <?php echo $form->labelEx($model,'image'); ?>
                    <div class="mws-form-item">
                        <?php echo $form->textField($model,'image',array('size'=>60,'maxlength'=>200)); ?>
                    </div>
                    <?php echo $form->error($model,'image'); ?>

                </div>
                                <div class="row">
                    <?php echo $form->labelEx($model,'status'); ?>
                    <div class="mws-form-item">
                        <?php echo $form->textField($model,'status'); ?>
                    </div>
                    <?php echo $form->error($model,'status'); ?>

                </div>
                -->


            <!-- form -->
        </div>
        <br>
        <div class="mws-button-row">
            <input type="submit" value="Simpan" class="btn btn-primary">
            <!-- <input type="reset" value="Reset" class="btn "> -->
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
</div>
</div>
</div>