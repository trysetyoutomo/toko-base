<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('item_id')); ?>:</b>
	<?php echo CHtml::encode($data->item_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nama_satuan')); ?>:</b>
	<?php echo CHtml::encode($data->nama_satuan); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('satuan')); ?>:</b>
	<?php echo CHtml::encode($data->satuan); ?>
	<br />


</div>