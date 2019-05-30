<?php
$this->breadcrumbs=array(
	'Items Satuan Masters'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ItemsSatuanMaster', 'url'=>array('index')),
	array('label'=>'Create ItemsSatuanMaster', 'url'=>array('create')),
	array('label'=>'Update ItemsSatuanMaster', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ItemsSatuanMaster', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ItemsSatuanMaster', 'url'=>array('admin')),
);
?>

<h1>View ItemsSatuanMaster #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nama_satuan',
	),
)); ?>
