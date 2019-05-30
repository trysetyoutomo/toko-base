<?php
$this->breadcrumbs=array(
	'Barangkeluars'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Barangkeluar', 'url'=>array('index')),
	array('label'=>'Create Barangkeluar', 'url'=>array('create')),
	array('label'=>'Update Barangkeluar', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Barangkeluar', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Barangkeluar', 'url'=>array('admin')),
);
?>

<h1>View Barangkeluar #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'tanggal',
		'sumber',
		'user',
		'keterangan',
		'jenis_keluar',
		'jenis',
	),
)); ?>
