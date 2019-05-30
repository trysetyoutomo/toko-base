

<h1>Kelola kategori</h1>
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
		<input type="hidden" name="r" value="categories/admin">
		Cari Nama <input type="text" name="cari" value="<?php echo $_REQUEST['cari'] ?>">
		<input type="submit"  value="cari" class="btn btn-primary">
		</form>
	</div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'categories-grid',
	'dataProvider'=>$model,
	// 'dataProvider'=>$model->search(),
	// 'filter'=> 'filter'=>$filtersForm,,
	 // 'filter'=>$filtersForm,
	'columns'=>array(
		array(
			'header'=>'No.',
			'value'=>'($this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize) + array_search($data,$this->grid->dataProvider->getData())+1',
		),
		// 'id',
		array(
			'name'=>'category',
			'header'=>'Nama Kategori'
		),
		array(
			'class'=>'CButtonColumn',
			// 'visible'=>Yii::app()->user->getIdAdmin()==1,
			'template' => '{ubah}{rinci}{hapus}',
			'buttons' =>array(
			'view'=>array(
					'label'=> 'view',
					'url'=>'Yii::app()->createUrl("items/view", array("id"=>$data[id]))',      //A PHP expression for generating the URL of the button.

			),
			'ubah'=>array(

				'label'=> ' ',
				'options'=>array(
					'class'=>'fa fa-pencil',
				),
					// 'label'=> 'Update',
					'url'=>'Yii::app()->createUrl("categories/update", array("id"=>$data[id],"status"=>"ubah"))',      //A PHP expression for generating the URL of the button.

			),
			'rinci'=>array(

				'label'=> ' ',
				'options'=>array(
					'class'=>'fa fa-eye',
				),
					// 'label'=> 'Update',
					'url'=>'Yii::app()->createUrl("categories/view", array("id"=>$data[id],"status"=>"ubah"))',      //A PHP expression for generating the URL of the button.

			),
			'hapus'=>array(
				'label'=> '',
				'options'=>array(
					'class'=>'fa fa-times',
				),
				'class'=>'fa fa-times',
				'url'=>'Yii::app()->createUrl("categories/hapus", array("id"=>$data[id]))',      //A PHP expression for generating the URL of the button.

			),
			// 'view'=>array(
			// 	'label'=> '',
			// 	'url'=>'Yii::app()->createUrl("Items/view", array("id"=>$data[id]))',      //A PHP expression for generating the URL of the button.

			// ),

			

					
			
			),


		),
		// 'image',
		// 'status',
		// array(
		// 	'class'=>'CButtonColumn',
		// 	'template'=>"{update}{hap}",
		// 	'buttons'=>array(
		// 		'update'=>array(
		// 			'url'=>'Yii::app()->controller->createUrl("update", array("id"=>$data[id]))',      //A PHP expression for generating the URL of the button.
		// 		),
		// 		'delete'=>array(
		// 			'url'=>'Yii::app()->controller->createUrl("delete", array("id"=>$data[id]))',      //A PHP expression for generating the URL of the button.
		// 		),

		// 	)
		// ),
	),
)); ?>
