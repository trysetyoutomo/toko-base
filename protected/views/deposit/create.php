<?php
$this->breadcrumbs=array(
	'Deposits'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Deposit', 'url'=>array('index')),
	array('label'=>'Manage Deposit', 'url'=>array('admin')),
);
?>

<h1>Deposit Saldo Pulsa</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>