<?php
$this->breadcrumbs=array(
	'Saldos'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Saldo', 'url'=>array('index')),
	array('label'=>'Create Saldo', 'url'=>array('create')),
	array('label'=>'Update Saldo', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Saldo', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Saldo', 'url'=>array('admin')),
);
?>

<h1>View Saldo #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'tanggal',
		'harga',
		'stok',
		'item_id',
	),
)); ?>
