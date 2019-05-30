<?php
$this->breadcrumbs=array(
	'Letaks',
);

$this->menu=array(
	array('label'=>'Create Letak', 'url'=>array('create')),
	array('label'=>'Manage Letak', 'url'=>array('admin')),
);
?>

<h1>Letaks</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
