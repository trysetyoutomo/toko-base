<?php
$this->breadcrumbs=array(
	'Motifs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Motif', 'url'=>array('index')),
	array('label'=>'Manage Motif', 'url'=>array('admin')),
);
?>

<h1>Sub Kategori Baru</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>