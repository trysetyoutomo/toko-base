<?php
$this->breadcrumbs=array(
	'Barangkeluar Details'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List BarangkeluarDetail', 'url'=>array('index')),
	array('label'=>'Create BarangkeluarDetail', 'url'=>array('create')),
	array('label'=>'View BarangkeluarDetail', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage BarangkeluarDetail', 'url'=>array('admin')),
);
?>

<h1>Update BarangkeluarDetail <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>