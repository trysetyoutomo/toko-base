<?php
$this->breadcrumbs=array(
	'Items Satuans'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ItemsSatuan', 'url'=>array('index')),
	array('label'=>'Create ItemsSatuan', 'url'=>array('create')),
	array('label'=>'Update ItemsSatuan', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ItemsSatuan', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ItemsSatuan', 'url'=>array('admin')),
);
?>

<h1>View ItemsSatuan #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'item_id',
		'nama_satuan',
		'satuan',
	),
)); ?>
