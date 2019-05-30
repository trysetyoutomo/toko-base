<?php
$this->breadcrumbs=array(
	'Barangmasuks'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Barangmasuk', 'url'=>array('index')),
	array('label'=>'Create Barangmasuk', 'url'=>array('create')),
	array('label'=>'View Barangmasuk', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Barangmasuk', 'url'=>array('admin')),
);
?>

<h1>Update Barang masuk #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>