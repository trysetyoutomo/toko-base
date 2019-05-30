<?php
$this->breadcrumbs=array(
	'Items Satuan Prices'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ItemsSatuanPrice', 'url'=>array('index')),
	array('label'=>'Create ItemsSatuanPrice', 'url'=>array('create')),
	array('label'=>'View ItemsSatuanPrice', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ItemsSatuanPrice', 'url'=>array('admin')),
);
?>

<h1>Update ItemsSatuanPrice <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>