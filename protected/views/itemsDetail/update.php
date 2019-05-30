<?php
$this->breadcrumbs=array(
	'Items Details'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ItemsDetail', 'url'=>array('index')),
	array('label'=>'Create ItemsDetail', 'url'=>array('create')),
	array('label'=>'View ItemsDetail', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ItemsDetail', 'url'=>array('admin')),
);
?>

<h1>Update ItemsDetail <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>