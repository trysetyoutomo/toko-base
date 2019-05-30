<?php
$this->breadcrumbs=array(
	'Motifs'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Motif', 'url'=>array('index')),
	array('label'=>'Create Motif', 'url'=>array('create')),
	array('label'=>'Update Motif', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Motif', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Motif', 'url'=>array('admin')),
);
?>

<h1>View Sub Kategori #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'category_id',
		'nama',
	),
)); ?>
