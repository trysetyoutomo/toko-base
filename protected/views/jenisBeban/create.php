<?php
$this->breadcrumbs=array(
	'Jenis Bebans'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List JenisBeban', 'url'=>array('index')),
	array('label'=>'Manage JenisBeban', 'url'=>array('admin')),
);
?>

<h1>Jenis Pengeluaran Baru</h1>
<hr>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>