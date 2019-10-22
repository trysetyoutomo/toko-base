<?php
$this->breadcrumbs=array(
	'Deposits',
);

$this->menu=array(
	array('label'=>'Create Deposit', 'url'=>array('create')),
	array('label'=>'Manage Deposit', 'url'=>array('admin')),
);
?>

<h1>Deposits</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
