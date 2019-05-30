<?php
$this->breadcrumbs=array(
	'Barangkeluar Details'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List BarangkeluarDetail', 'url'=>array('index')),
	array('label'=>'Manage BarangkeluarDetail', 'url'=>array('admin')),
);
?>

<h1>Create BarangkeluarDetail</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>