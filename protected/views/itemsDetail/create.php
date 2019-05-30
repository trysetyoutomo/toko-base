<?php
$this->breadcrumbs=array(
	'Items Details'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ItemsDetail', 'url'=>array('index')),
	array('label'=>'Manage ItemsDetail', 'url'=>array('admin')),
);
?>

<h1>Create ItemsDetail</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>