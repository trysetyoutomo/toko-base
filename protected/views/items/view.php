<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="<?php echo Yii::app()->createUrl('Items/admin'); ?>">Mengelola Items</a></li>
  <li class="breadcrumb-item active"> <?php echo $model->item_name ?></li>
  <li class="breadcrumb-item active">Lihat</li>
</ol>


<h1>Rincian Item #<?php echo $model->item_name; ?></h1>
<hr>
<div class="row">
    <div class="col-sm-12">
      <a href="<?php echo Yii::app()->controller->createUrl("create") ?>">
             <button class="btn btn-primary">
            <i class="fa fa-plus"></i> Tambah  
        </button>
        </a>
       <a href="<?php echo Yii::app()->controller->createUrl("admin") ?>">
             <button class="btn btn-primary">
            <i class="fa fa-list"></i> Kelola  Items
        </button>
        </a>
        <a href="<?php echo Yii::app()->controller->createUrl("update",array("id"=>$_REQUEST[id])) ?>">
        <button class="btn btn-primary">
            <i class="fa fa-pencil"></i> Ubah Item
        </button>
        </a>
      
         <a href="<?php echo Yii::app()->controller->createUrl("ItemsSatuan/admin",array("id"=>$_REQUEST[id])) ?>">
            <button class="btn btn-primary">
                <i class="fa fa-pencil"></i>  Kelola Detail Item , Satuan & Harga
            </button>
        </a>
        <?PHP 
        if ($model->has_bahan=="1"):
        ?>
         <a href="<?php echo Yii::app()->controller->createUrl("ItemsSource/create",array("id"=>$_REQUEST[id],"status"=>"ubah")) ?>">
            <button class="btn btn-primary">
                <i class="fa fa-pencil"></i> Kelola Bahan Baku
            </button>
        </a>
        <?php 
        endif;
        ?>

        
    </div>
</div>
<hr>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        // 'id',
        'item_name',
        // 'item_number',
        'description',
        array(
            "header"=>"Kategori",
            "label"=>"Kategori",
            "value"=>Categories::model()->findByPk($model[category_id])->category,
        ),
        'stok_minimum',
        'discount',
        // 'unit_price',
        // array(
        //     "header"=>"Modal",
        //     "label"=>"Modal",
        //     // "value"=>number_format(ItemsController::getAverage($model[id])),
        // ),
        
        // 'tax_percent',
        // 'total_cost',
        // array(
        //     "name"=>"total_cost",
        //     "label"=>"Harga Jual",
        //     "value"=>number_format($model[total_cost]),
        // ),
        //  array(
        //     "name"=>"persentasi_hasil",
        //     "label"=>"Persentasi",
        //     "value"=>number_format($model[persentasi]),
        // ),
        // 'discount',
        // 'image',
        // 'status',
        // 'ketebalan',
        // 'ukuran',
        // array(
        //     "name"=>"barcode",
        //     "label"=>"Barcode",
        //     // "value"=>number_format($model[persentasi]),
        // ),
        // '',
    ),
)); ?>


<?php 
// $this->renderPartial("kartupersediaan");
?>
