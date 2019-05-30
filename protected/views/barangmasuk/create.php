<?php
$this->breadcrumbs=array(
	'Barangmasuks'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Barangmasuk', 'url'=>array('index')),
	array('label'=>'Manage Barangmasuk', 'url'=>array('admin')),
);
?>

<h1>Create Barangmasuk</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>