
<h1>Mengelola Sub Kategori</h1>
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
		<input type="hidden" name="r" value="motif/admin">
		Cari Nama <input type="text" name="cari" value="<?php echo $_REQUEST['cari'] ?>">
		<input type="submit"  value="cari" class="btn btn-primary">
		</form>
	</div>
</div>

<!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'motif-grid',
	'dataProvider'=>$model,
	'filter'=>$filtersForm,
	'columns'=>array(
		// 'id',
		// array(
		// 	'name'=>'category_id',
		// 	'header'=>'Kategori',
		// 	'type'=>'raw',
		// 	'value'=>'Categories::model()->findByPk($data->category_id)->category',
		// ),
		'category',
		'nama',
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
					'url'=>'Yii::app()->controller->createUrl("update", array("id"=>$data[id],"status"=>"ubah"))',      //A PHP expression for generating the URL of the button.

			),
			'rinci'=>array(

				'label'=> ' ',
				'options'=>array(
					'class'=>'fa fa-eye',
				),
					// 'label'=> 'Update',
					'url'=>'Yii::app()->controller->createUrl("view", array("id"=>$data[id],"status"=>"ubah"))',      //A PHP expression for generating the URL of the button.

			),
			'hapus'=>array(
				'label'=> '',
				'options'=>array(
					'class'=>'fa fa-times',
				),
				'class'=>'fa fa-times',
				'url'=>'Yii::app()->controller->createUrl("hapus", array("id"=>$data[id]))',      //A PHP expression for generating the URL of the button.

			),
			// 'view'=>array(
			// 	'label'=> '',
			// 	'url'=>'Yii::app()->createUrl("Items/view", array("id"=>$data[id]))',      //A PHP expression for generating the URL of the button.

			// ),

			

					
			
			),


		),
	),
)); ?>
