<?php
$this->breadcrumbs=array(
	'Items Satuans',
);

$this->menu=array(
	array('label'=>'Create ItemsSatuan', 'url'=>array('create')),
	array('label'=>'Manage ItemsSatuan', 'url'=>array('admin')),
);
?>

<h1>Items Satuans</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
