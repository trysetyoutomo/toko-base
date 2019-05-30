<?php
$this->breadcrumbs=array(
	'Config Menus'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ConfigMenu', 'url'=>array('index')),
	array('label'=>'Create ConfigMenu', 'url'=>array('create')),
	array('label'=>'Update ConfigMenu', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ConfigMenu', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ConfigMenu', 'url'=>array('admin')),
);
?>

<h1>View ConfigMenu #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'controllerID',
		'actionID',
		'value',
		'category_menu_id',
		'hapus',
	),
)); ?>
