<?php
$this->breadcrumbs=array(
	'Items Details'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ItemsDetail', 'url'=>array('index')),
	array('label'=>'Create ItemsDetail', 'url'=>array('create')),
	array('label'=>'Update ItemsDetail', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ItemsDetail', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ItemsDetail', 'url'=>array('admin')),
);
?>

<h1>View ItemsDetail #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'kode',
		'jumlah',
		'supplier_id',
		'harga',
		'tanggal',
	),
)); ?>
