<?php
$this->breadcrumbs=array(
	'Barangmasuks',
);

$this->menu=array(
	array('label'=>'Create Barangmasuk', 'url'=>array('create')),
	array('label'=>'Manage Barangmasuk', 'url'=>array('admin')),
);
?>

<h1>Barangmasuks</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
