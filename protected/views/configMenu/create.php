<?php
$this->breadcrumbs=array(
	'Config Menus'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ConfigMenu', 'url'=>array('index')),
	array('label'=>'Manage ConfigMenu', 'url'=>array('admin')),
);
?>

<h1>Create ConfigMenu</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>