	
<h3 class="well"><a href="#" onclick="window.history.back()" style="">
<i class="fa fa-arrow-left"></i>
</a> 
 Detail penjualan #<?php echo ' Faktur '.$_GET['id']; ?></h3>
</legend>	
<div class="well">
<div class="row"><div class="col-lg-2">Faktur ID</div><div class="col-lg-6"><?php echo $sales->faktur_id ?></div></div>
<div class="row"><div class="col-lg-2">Tanggal Transaksi</div><div class="col-lg-6"><?php echo $sales->date ?></div></div>
<div class="row"><div class="col-lg-2">Kasir</div><div class="col-lg-6"><?php echo $sales->nama_kasir ?></div></div>
<div class="row"><div class="col-lg-2">Customer</div><div class="col-lg-6"><?php echo $sales->nama ?></div></div>
<div class="row"><div class="col-lg-2">Total</div><div class="col-lg-6"><?php echo number_format($sales->sale_total_cost) ?></div></div>
<div class="row"><div class="col-lg-2">Uang Bayar</div><div class="col-lg-6"><?php echo number_format($sales->bayar) ?></div></div>
<!-- <div class="row"><div class="col-lg-2">Kembali</div><div class="col-lg-6"><?php echo number_format($sales->kembali) ?></div></div> -->
<div class="row"><div class="col-lg-2">Pembayaran Via</div><div class="col-lg-6"><?php echo $sales->pembayaran_via == '0' ? "CASH" : $sales->pembayaran_via ?></div></div>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	//'id'=>'outlet-grid',
	'dataProvider'=>$detailtransaksi,
	'filter'=>$model,
	'columns'=>array(
		// array(
		// 'name'=>'id',
		// 'header'=>'ID',
		
		// ),
		
		array(
			'name'=>'id',
			'header'=>	'id',
			'visible'=>true,
		)
		,
		// array(
		// 'name'=>'name',
		// 'header'=>'nama menu',
		// 'value'=>'<a href="asd.com" >cinta</a>',
		// 'type'=>'html'
		// //'footer'=>true,
		// ),
		array(
		'name'=>'name',
		'type'=>'raw',
		'header'=>'Nama Item',
		'value'=>'CHtml::link($data[name],array("SalesItems/update","id"=>$data[id]),array("style"=>"text-decoration:none","target"=>"_blank"))',
		
		),
		array(
		'name'=>'price_modal',
		'header'=>'Harga Modal ',
		'visible'=>Yii::app()->user->getLevel()==2,

		'footer'=>true,
		'class'=>'ext.gridcolumns.TotalColumn',
		'type'=>'number',
		'htmlOptions'=>array('style'=>'text-align:right'),
		//'footerHtmloptions'=>array('style'=>'text-align:right'),
		'footerHtmlOptions'=>array('style'=>'text-align:right;font-weight:bold;'),
		),
		array(
		'name'=>'price',
		'header'=>'harga ',
		'footer'=>true,
		'class'=>'ext.gridcolumns.TotalColumn',
		'type'=>'number',
		'htmlOptions'=>array('style'=>'text-align:right'),
		//'footerHtmloptions'=>array('style'=>'text-align:right'),
		'footerHtmlOptions'=>array('style'=>'text-align:right;font-weight:bold;'),
		),
		array(
		'name'=>'qty',
		'header'=>'jumlah',
		'footer'=>true,
		'class'=>'ext.gridcolumns.TotalColumn',
		// 'type'=>'decimal',
		'htmlOptions'=>array('style'=>'text-align:right'),
		'footerHtmlOptions'=>array('style'=>'text-align:right;font-weight:bold;'),


		),
		array(
		'name'=>'subtotal',
		'header'=>'Sub total ',
		'footer'=>true,
		'class'=>'ext.gridcolumns.TotalColumn',
		'type'=>'number',
		'htmlOptions'=>array('style'=>'text-align:right'),
		//'footerHtmloptions'=>array('style'=>'text-align:right'),
		'footerHtmlOptions'=>array('style'=>'text-align:right;font-weight:bold;'),
		),
		array(
		'name'=>'submodal',
		'header'=>'Total Modal ',
					'visible'=>Yii::app()->user->getLevel()==2,

		'footer'=>true,
		'class'=>'ext.gridcolumns.TotalColumn',
		'type'=>'number',
		'htmlOptions'=>array('style'=>'text-align:right'),
		//'footerHtmloptions'=>array('style'=>'text-align:right'),
		'footerHtmlOptions'=>array('style'=>'text-align:right;font-weight:bold;'),
		),
		array(
		'name'=>'tax',
		'header'=>'pajak',
		'visible'=>Yii::app()->user->getLevel()==2,

		'footer'=>true,
		'class'=>'ext.gridcolumns.TotalColumn',
		'type'=>'number',
		'htmlOptions'=>array('style'=>'text-align:right'),
		'footerHtmlOptions'=>array('style'=>'text-align:right;font-weight:bold;'),

		),
		array(
		'name'=>'service',
		'visible'=>Yii::app()->user->getLevel()==2,

		'header'=>'service',
		'footer'=>true,
		'class'=>'ext.gridcolumns.TotalColumn',
		'type'=>'number',
		'htmlOptions'=>array('style'=>'text-align:right'),
		'footerHtmlOptions'=>array('style'=>'text-align:right;font-weight:bold;'),

		),
		array(
		'name'=>'idc',
		'header'=>'discount',
		'footer'=>true,
		'class'=>'ext.gridcolumns.TotalColumn',
		'type'=>'number',
		'htmlOptions'=>array('style'=>'text-align:right'),
		'footerHtmlOptions'=>array('style'=>'text-align:right;font-weight:bold;'),
		
		),
		
		array(
		'name'=>'total',
		'header'=>'Total',
		'footer'=>true,
		'type'=>'number',
		'class'=>'ext.gridcolumns.TotalColumn',
		'htmlOptions'=>array('style'=>'text-align:right'),
		'footerHtmlOptions'=>array('style'=>'text-align:right;font-weight:bold;'),

		),
		array(
		'name'=>'permintaan',
		'header'=>'Keterangan',
		// 'footer'=>true,
		// 'type'=>'number',
		// 'class'=>'ext.gridcolumns.TotalColumn',
		'htmlOptions'=>array('style'=>'text-align:right'),
		// 'footerHtmlOptions'=>array('style'=>'text-align:right;font-weight:bold;'),

		),
		array( 
		'class'=>'CButtonColumn',
		'template'=>'{ganti}',
		// 'template'=>'{update}{hapus}',
		// 'visible'=>$a,
		
			'buttons' => array(
			
				'hapus' => array(
						// 'label'=> 'Bayar',
						// 'hint'=>'Bayar',
						'url'=>'Yii::app()->createUrl("Salesitems/hapus", array("id"=>$data[id]))',      //A PHP expression for generating the URL of the button.
						'imageUrl'=>Yii::app()->request->baseUrl.'/assets/9d933b95/gridview/delete.png',
						// /voila/
						 // 'visible'=>$a,
						'options'=>array(
							'class'=>'delete'
						),
			
				),
				
				'ganti' => array(
					// 'label'=> 'Bayar',
					// 'hint'=>'Bayar',
					'url'=>'Yii::app()->createUrl("Salesitems/tukarbarang", array("id"=>$data[id]))',      //A PHP expression for generating the URL of the button.
					'imageUrl'=>Yii::app()->request->baseUrl.'/img/ganti.png',
					 // 'visible'=>$a,
					// 'options'=>array(
						// 'class'=>'btn btn-small update'
					// ),
				
				),
				
				
			),
		), 	
		
				

	),
)); ?>

</div>

<script type="text/javascript">
	$(document).ready(function(){
	// 	$(document).on("click",".delete",function(e){
	// 		e.preventDefault();
	// 		var c = confirm("yakin ? ");
	// 		if(!c){exit;}

	// 		$.ajax({
	// 			url : "<?php echo Yii::app()->createUrl("Salesitems/hapus") ?>",
	// 			data 
	// 		});

	// 	});
		jQuery('#yw0 a.delete').live('click',function() {
			if(!confirm('Are you sure you want to delete this item?')) return false;
			var th=this;
			var afterDelete=function(){};
			$.fn.yiiGridView.update('yw0', {
				type:'POST',
				url:$(this).attr('href'),
				success:function(data) {
					// alert(data);
					if (!data=="sukses"){
						alert(data);
					}
					$.fn.yiiGridView.update('yw0');
					afterDelete(th,true,data);
				},
				error:function(XHR) {
					return afterDelete(th,false,XHR);
				}
			});
			return false;
		});
	});

</script>
