<div class="form wide">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'items-satuan-price-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>
    <?php
    if ($model->isNewRecord) {
        $model->item_satuan_id = $_REQUEST['id'];
    }
    ?>

    <div class="row" style="display: none;">
        <?php echo $form->labelEx($model, 'item_satuan_id', array('class' => 'col-sm-1')); ?>
        <div class="col-sm-8">
            <?php echo $form->textField($model, 'item_satuan_id', array('size' => 20, 'maxlength' => 20, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'item_satuan_id'); ?>
        </div>
    </div>

    <?php
    foreach (ItemsSatuanPriceMaster::model()->findAll() as $key => $value) {
        $price = ItemsSatuanPrice::model()->find("price_type = '$value->name' and item_satuan_id='$_REQUEST[id]' ");
        $item_satuan_id = $price->item_satuan_id;
        $price = $price->price;
    ?>

        <div class="row" style="display:none">
            <?php echo $form->labelEx($model, 'price_type', array('class' => 'col-sm-1')); ?>
            <div class="col-sm-8">
                <input size="10" maxlength="255" name="ItemsSatuanPrice[price_type][]" id="ItemsSatuanPrice_price_type" type="text" value="<?php echo $value->name ?>" class="form-control">
                <?php echo $form->error($model, 'price_type'); ?>
            </div>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, $value->label_name, array('class' => 'col-sm-1')); ?>
            <div class="col-sm-8">
                <input size="20" maxlength="20" name="ItemsSatuanPrice[price][]" id="ItemsSatuanPrice_price" type="text" value="<?php echo $price; ?>" class="form-control">
                <?php echo $form->error($model, 'price'); ?>
            </div>
        </div>
        <br>
        <br>
    <?php } ?>

    <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-8">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Simpan' : 'Save', array("class" => "btn btn-primary")); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->
