<?php
$this->breadcrumbs=array(
	'Items Satuan Prices'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List ItemsSatuanPrice', 'url'=>array('index')),
	array('label'=>'Create ItemsSatuanPrice', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('items-satuan-price-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Items Satuan Prices</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'items-satuan-price-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'item_satuan_id',
		'price_type',
		'price',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
