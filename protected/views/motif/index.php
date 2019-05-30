<?php
$this->breadcrumbs=array(
	'Motifs',
);

$this->menu=array(
	array('label'=>'Create Motif', 'url'=>array('create')),
	array('label'=>'Manage Motif', 'url'=>array('admin')),
);
?>

<h1>Motifs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
