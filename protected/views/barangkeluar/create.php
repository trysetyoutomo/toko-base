<?php
$this->breadcrumbs=array(
	'Barangkeluars'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Barangkeluar', 'url'=>array('index')),
	array('label'=>'Manage Barangkeluar', 'url'=>array('admin')),
);
?>

<h1>Create Barangkeluar</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>