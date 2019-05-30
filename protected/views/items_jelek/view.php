<?php
/* @var $this ItemsController */
/* @var $model Items */

$this->breadcrumbs=array(
	'Items'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Items', 'url'=>array('index')),
	array('label'=>'Create Items', 'url'=>array('create')),
	array('label'=>'Update Items', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Items', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Items', 'url'=>array('admin')),
);
?>

<h1>View Items #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'item_name',
		'item_number',
		'description',
		'category_id',
		'unit_price',
		'tax_percent',
		'total_cost',
		'discount',
		'image',
		'status',
		'kode_outlet',
		'lokasi',
		'hapus',
		'modal',
		'stok',
		'barcode',
		'motif',
		'stok_minimum',
		'price_distributor',
		'price_reseller',
		'item_tax',
	),
)); ?>
