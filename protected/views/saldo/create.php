<?php
$this->breadcrumbs=array(
	'Saldos'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Saldo', 'url'=>array('index')),
	array('label'=>'Manage Saldo', 'url'=>array('admin')),
);
?>

<h1>Create Saldo</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>