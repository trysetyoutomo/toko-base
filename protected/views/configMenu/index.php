<?php
$this->breadcrumbs=array(
	'Config Menus',
);

$this->menu=array(
	array('label'=>'Create ConfigMenu', 'url'=>array('create')),
	array('label'=>'Manage ConfigMenu', 'url'=>array('admin')),
);
?>

<h1>Config Menus</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
