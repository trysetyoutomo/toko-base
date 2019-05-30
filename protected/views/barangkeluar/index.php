<?php
$this->breadcrumbs=array(
	'Barangkeluars',
);

$this->menu=array(
	array('label'=>'Create Barangkeluar', 'url'=>array('create')),
	array('label'=>'Manage Barangkeluar', 'url'=>array('admin')),
);
?>

<h1>Barangkeluars</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
