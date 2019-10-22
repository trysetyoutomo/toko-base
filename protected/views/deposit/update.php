<?php
$this->breadcrumbs=array(
	'Deposits'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Deposit', 'url'=>array('index')),
	array('label'=>'Create Deposit', 'url'=>array('create')),
	array('label'=>'View Deposit', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Deposit', 'url'=>array('admin')),
);
?>

<h1>Update Deposit <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>