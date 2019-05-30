<?php
$this->breadcrumbs=array(
	'Items Details',
);

$this->menu=array(
	array('label'=>'Create ItemsDetail', 'url'=>array('create')),
	array('label'=>'Manage ItemsDetail', 'url'=>array('admin')),
);
?>

<h1>Items Details</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
