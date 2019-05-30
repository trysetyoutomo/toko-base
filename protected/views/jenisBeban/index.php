<?php
$this->breadcrumbs=array(
	'Jenis Bebans',
);

$this->menu=array(
	array('label'=>'Create JenisBeban', 'url'=>array('create')),
	array('label'=>'Manage JenisBeban', 'url'=>array('admin')),
);
?>

<h1>Jenis Bebans</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
