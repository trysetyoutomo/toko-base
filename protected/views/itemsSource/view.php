<?php
$this->breadcrumbs=array(
	'Items Sources'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ItemsSource', 'url'=>array('index')),
	array('label'=>'Create ItemsSource', 'url'=>array('create')),
	array('label'=>'Update ItemsSource', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ItemsSource', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ItemsSource', 'url'=>array('admin')),
);
?>

<h1>View ItemsSource #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'item_id',
		'jumlah',
	),
)); ?>
