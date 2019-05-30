
<h1>Kelola Satuan</h1>
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
		<input type="hidden" name="r" value="itemsSatuanMaster/admin">
		Cari Nama <input type="text" name="cari" value="<?php echo $_REQUEST['cari'] ?>">
		<input type="submit"  value="cari" class="btn btn-primary">
		</form>
	</div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'customer-grid',
	'dataProvider'=>$model,
	// 'filter'=>$filtersForm,
	'columns'=>array(
		'id',
		array(
		'name'=>'nama_satuan',
		'header'=>'Nama Satuan',
		),
		// 'alamat',
		// 'no_telepon',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}{delete}{update}',
			'buttons'=>array
			   (
			   	'view' => array
			        (
			            // 'label'=>'',
			            // 'class'=>'btn-tolak',
			                 //  'options'=>array( 
			                	// 'class'=>'aksi fa fa-send	',
			                	// 'title'=>'Kirim Berkas' 
			              		// ),

			                  // 'visible'=>'$data[dokumen]==0 && $data[acc_1]==2 && $data[acc_2]==2 &&'."$level=='admin'",
			            // 'imageUrl'=>Yii::app()->request->baseUrl.'/images/email.png',
			            'url'=>'Yii::app()->controller->createUrl("view", array("id"=>$data[id]))',
			        ),
		        	'delete' => array
			        (
			            // 'label'=>'',
			            'class'=>'hapus',
			                 //  'options'=>array( 
			                	// 'class'=>'aksi fa fa-send	',
			                	// 'title'=>'Kirim Berkas' 
			              		// ),

			                  // 'visible'=>'$data[dokumen]==0 && $data[acc_1]==2 && $data[acc_2]==2 &&'."$level=='admin'",
			            // 'imageUrl'=>Yii::app()->request->baseUrl.'/images/email.png',
			            'url'=>'Yii::app()->controller->createUrl("delete", array("id"=>$data[id]))',
			        ),
			        'update' => array
			        (
			            // 'label'=>'',
			            'class'=>'update',
			                 //  'options'=>array( 
			                	// 'class'=>'aksi fa fa-send	',
			                	// 'title'=>'Kirim Berkas' 
			              		// ),

			                  // 'visible'=>'$data[dokumen]==0 && $data[acc_1]==2 && $data[acc_2]==2 &&'."$level=='admin'",
			            // 'imageUrl'=>Yii::app()->request->baseUrl.'/images/email.png',
			            'url'=>'Yii::app()->controller->createUrl("update", array("id"=>$data[id]))',
			        )
			  	)


		),
	),
)); ?>
