<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('controllerID')); ?>:</b>
	<?php echo CHtml::encode($data->controllerID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('actionID')); ?>:</b>
	<?php echo CHtml::encode($data->actionID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('value')); ?>:</b>
	<?php echo CHtml::encode($data->value); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('category_menu_id')); ?>:</b>
	<?php echo CHtml::encode($data->category_menu_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hapus')); ?>:</b>
	<?php echo CHtml::encode($data->hapus); ?>
	<br />


</div>