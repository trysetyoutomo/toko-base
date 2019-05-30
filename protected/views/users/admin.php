

<h1>Mengelola Pengguna</h1>
<hr>
<div class="row">
	<div class="col-sm-8">
		<a href="<?php echo Yii::app()->controller->createUrl("create") ?>">
		<button class="btn btn-primary">
			<i class="fa fa-plus"></i> Tambah
		</button>
		</a>
	</div>
	<div class="col-sm-4">
		<form action="">
		<input type="hidden" name="r" value="Users/admin">
		Cari Nama <input type="text" name="cari" value="<?php echo $_REQUEST['cari'] ?>">
		<input type="submit"  value="cari" class="btn btn-primary">
		</form>
	</div>
</div>

<?php //echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'users-grid',
	'dataProvider'=>$model,
	'filter'=>$filtersForm,
	'columns'=>array(
		array(
			'header'=>'No.',
			'value'=>'($this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize) + array_search($data,$this->grid->dataProvider->getData())+1',
		),
		'username',
		'name',
		array(
			'name'=>'password',
			'value'=>'md5($data->password)',
			'type'=>'raw'
		),
		array(
			'name'=>'level',
			'value'=>ConfigRole::model()->findByPk($data->level)->role_name,
			'type'=>'raw',	
		),
		// array(
		// 	'name'=>'branch_id',
		// 	// 'value'=>'Akses::model()->findByPk($data->level)->akses',
		// 	'type'=>'raw',	
		// ),
		// 'status',
		/*
		'branch_id',
		*/
		array(
			'class'=>'CButtonColumn',
			// 'visible'=>Yii::app()->user->getIdAdmin()==1,
			'template' => '{update}',
			'buttons' =>array(
			// 'view'=>array(
			// 		'label'=> 'view',
			// 		'url'=>'Yii::app()->createUrl("Users/view", array("id"=>$data[id]))',      //A PHP expression for generating the URL of the button.

			// ),
			'update'=>array(
					'label'=> 'Update',
					'url'=>'Yii::app()->createUrl("Users/update", array("id"=>$data[id],"status"=>"ubah"))',      //A PHP expression for generating the URL of the button.

			),
			// 'delete'=>array(
			// 		'label'=> 'hapus',
			// 		'url'=>'Yii::app()->createUrl("Users/delete", array("id"=>$data[id]))',      //A PHP expression for generating the URL of the button.

			// ),
			

					
			
			),


		),
	),
)); ?>
