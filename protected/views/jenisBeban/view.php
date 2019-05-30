<?php
$this->breadcrumbs=array(
	'Jenis Bebans'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List JenisBeban', 'url'=>array('index')),
	array('label'=>'Create JenisBeban', 'url'=>array('create')),
	array('label'=>'Update JenisBeban', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete JenisBeban', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage JenisBeban', 'url'=>array('admin')),
);
?>

<h1>View JenisBeban #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nama',
	),
)); ?>
