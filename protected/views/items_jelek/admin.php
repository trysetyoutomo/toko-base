<?php
/* @var $this ItemsController */
/* @var $model Items */

$this->breadcrumbs=array(
	'Items'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Items', 'url'=>array('index')),
	array('label'=>'Create Items', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#items-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Items</h1>



<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'items-grid',
	'dataProvider'=>$model,
	'filter'=>$filtersForm,
	'columns'=>array(
		'id',
		'item_name',
		'item_number',
		'description',
		'category_id',
		'unit_price',
		/*
		'tax_percent',
		'total_cost',
		'discount',
		'image',
		'status',
		'kode_outlet',
		'lokasi',
		'hapus',
		'modal',
		'stok',
		'barcode',
		'motif',
		'stok_minimum',
		'price_distributor',
		'price_reseller',
		'item_tax',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
