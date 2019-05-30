<?php
$this->breadcrumbs=array(
	'Barangmasuks'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Barangmasuk', 'url'=>array('index')),
	array('label'=>'Create Barangmasuk', 'url'=>array('create')),
	array('label'=>'Update Barangmasuk', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Barangmasuk', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Barangmasuk', 'url'=>array('admin')),
);
?>

<h1>View Barangmasuk #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'tanggal',
		'sumber',
		'user',
		'keterangan',
		'jenis',
	),
)); ?>
