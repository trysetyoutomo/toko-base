<?php
$this->breadcrumbs=array(
	'Items Sources',
);

$this->menu=array(
	array('label'=>'Create ItemsSource', 'url'=>array('create')),
	array('label'=>'Manage ItemsSource', 'url'=>array('admin')),
);
?>

<h1>Items Sources</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
