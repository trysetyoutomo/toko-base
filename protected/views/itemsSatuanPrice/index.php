<?php
$this->breadcrumbs=array(
	'Items Satuan Prices',
);

$this->menu=array(
	array('label'=>'Create ItemsSatuanPrice', 'url'=>array('create')),
	array('label'=>'Manage ItemsSatuanPrice', 'url'=>array('admin')),
);
?>

<h1>Items Satuan Prices</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
