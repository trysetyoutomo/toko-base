<?php
/* @var $this ItemsController */
/* @var $data Items */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('item_name')); ?>:</b>
	<?php echo CHtml::encode($data->item_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('item_number')); ?>:</b>
	<?php echo CHtml::encode($data->item_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('category_id')); ?>:</b>
	<?php echo CHtml::encode($data->category_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('unit_price')); ?>:</b>
	<?php echo CHtml::encode($data->unit_price); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tax_percent')); ?>:</b>
	<?php echo CHtml::encode($data->tax_percent); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('total_cost')); ?>:</b>
	<?php echo CHtml::encode($data->total_cost); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discount')); ?>:</b>
	<?php echo CHtml::encode($data->discount); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('image')); ?>:</b>
	<?php echo CHtml::encode($data->image); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kode_outlet')); ?>:</b>
	<?php echo CHtml::encode($data->kode_outlet); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lokasi')); ?>:</b>
	<?php echo CHtml::encode($data->lokasi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hapus')); ?>:</b>
	<?php echo CHtml::encode($data->hapus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modal')); ?>:</b>
	<?php echo CHtml::encode($data->modal); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stok')); ?>:</b>
	<?php echo CHtml::encode($data->stok); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('barcode')); ?>:</b>
	<?php echo CHtml::encode($data->barcode); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('motif')); ?>:</b>
	<?php echo CHtml::encode($data->motif); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stok_minimum')); ?>:</b>
	<?php echo CHtml::encode($data->stok_minimum); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('price_distributor')); ?>:</b>
	<?php echo CHtml::encode($data->price_distributor); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('price_reseller')); ?>:</b>
	<?php echo CHtml::encode($data->price_reseller); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('item_tax')); ?>:</b>
	<?php echo CHtml::encode($data->item_tax); ?>
	<br />

	*/ ?>

</div>