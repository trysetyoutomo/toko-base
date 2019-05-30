<?php
$this->breadcrumbs=array(
	'Saldos'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Saldo', 'url'=>array('index')),
	array('label'=>'Create Saldo', 'url'=>array('create')),
	array('label'=>'View Saldo', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Saldo', 'url'=>array('admin')),
);
?>

<h1>Update Saldo <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>