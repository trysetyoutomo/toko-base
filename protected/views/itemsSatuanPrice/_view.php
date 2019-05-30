<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('item_satuan_id')); ?>:</b>
	<?php echo CHtml::encode($data->item_satuan_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('price_type')); ?>:</b>
	<?php echo CHtml::encode($data->price_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('price')); ?>:</b>
	<?php echo CHtml::encode($data->price); ?>
	<br />


</div>