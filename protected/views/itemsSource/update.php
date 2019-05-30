<?php
$this->breadcrumbs=array(
	'Items Sources'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ItemsSource', 'url'=>array('index')),
	array('label'=>'Create ItemsSource', 'url'=>array('create')),
	array('label'=>'View ItemsSource', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ItemsSource', 'url'=>array('admin')),
);
?>

<h1>Update ItemsSource <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>