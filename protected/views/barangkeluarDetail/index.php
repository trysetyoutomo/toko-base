<?php
$this->breadcrumbs=array(
	'Barangkeluar Details',
);

$this->menu=array(
	array('label'=>'Create BarangkeluarDetail', 'url'=>array('create')),
	array('label'=>'Manage BarangkeluarDetail', 'url'=>array('admin')),
);
?>

<h1>Barangkeluar Details</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
