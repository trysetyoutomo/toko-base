
<div class="form wide">

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'branch-form',
    'enableAjaxValidation' => false,
));
?>
    <?php 
        $store_id = Yii::app()->user->store_id();
        $nilai = Categories::model()->findAll("store_id = '$store_id' ",array('order'=>'category'));
        $data = CHtml::listData($nilai,'id','category');
        // $data = array('all' => 'Select All') +  $data;
        
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
        <div class="col-sm-12 col-md-12 col-lg-2">
            <?php echo $form->labelEx($model, 'categories'); ?>
		</div>
			<div class="col-sm-10">
            <?php echo $form->dropDownList($model, 'categories', $data,array('class'=>'','style'=>'','multiple'=>true));?>
            <?php echo $form->error($model, 'categories'); ?>
              <!-- Add subtitle below -->
            <div class="subtitle "><i>Kategori items yang diizinkan pada halaman POS (Point of Sales) </i></div>
            
        </div>
    </div>




    <div class="row">
        <div class="col-sm-6">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array("class" => "btn btn-primary")); ?>
        </div>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<script>
$(document).ready(function(){
    $("#Branch_categories").select2();
    <?php if ($model->isNewRecord) : ?>
        setTimeout(() => { 
            $(".select-all").trigger("click");
        }, 1000);
    <?php endif; ?>

    // Flag to prevent recursion
    var preventRecursion = false;

    $('#Branch_categories').siblings('.select2-container').append('<span class="select-all" ><i class="fa-regular fa-square-check fa-2x" style="color:gray"></i></span>');
    
    $(document).on('click', '.select-all', function (e) {
      selectAllSelect2($(this).siblings('.selection').find('.select2-search__field'));
    });
    
    $(document).on("keyup", ".select2-search__field", function (e) {
      var eventObj = window.event ? event : e;
      if (eventObj.keyCode === 65 && eventObj.ctrlKey)
        selectAllSelect2($(this));
    });
    
    function selectAllSelect2(that) {
    
      var selectAll = true;
      var existUnselected = false;
      var item = $(that.parents("span[class*='select2-container']").siblings('select[multiple]'));
    
      item.find("option").each(function (k, v) {
        if (!$(v).prop('selected')) {
          existUnselected = true;
          return false;
        }
      });
    
      selectAll = existUnselected ? selectAll : !selectAll;
    
      item.find("option").prop('selected', selectAll);
      item.trigger('change');
    }
});
</script>
