<?php
$this->breadcrumbs=array(
	'Config Menus'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ConfigMenu', 'url'=>array('index')),
	array('label'=>'Create ConfigMenu', 'url'=>array('create')),
	array('label'=>'View ConfigMenu', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ConfigMenu', 'url'=>array('admin')),
);
?>

<h1>Update ConfigMenu <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>