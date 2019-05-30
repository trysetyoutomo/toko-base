<?php
$this->breadcrumbs=array(
	'Items Satuan Masters',
);

$this->menu=array(
	array('label'=>'Create ItemsSatuanMaster', 'url'=>array('create')),
	array('label'=>'Manage ItemsSatuanMaster', 'url'=>array('admin')),
);
?>

<h1>Items Satuan Masters</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
