<?php
$this->breadcrumbs=array(
	'Items Satuan Prices'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ItemsSatuanPrice', 'url'=>array('index')),
	array('label'=>'Create ItemsSatuanPrice', 'url'=>array('create')),
	array('label'=>'Update ItemsSatuanPrice', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ItemsSatuanPrice', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ItemsSatuanPrice', 'url'=>array('admin')),
);
?>

<h1>View ItemsSatuanPrice #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'item_satuan_id',
		'price_type',
		'price',
	),
)); ?>
