<?php
$this->breadcrumbs=array(
	'Saldos',
);

$this->menu=array(
	array('label'=>'Create Saldo', 'url'=>array('create')),
	array('label'=>'Manage Saldo', 'url'=>array('admin')),
);
?>

<h1>Saldos</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
