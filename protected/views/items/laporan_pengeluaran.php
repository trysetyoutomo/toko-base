
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/jQuery.print.min.js"></script>

<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-custom/jquery-ui.min.css">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-custom/jquery-ui.min.js"></script>

<div id="hasil"></div>
<style type="text/css">
	.dalem{
		width:100%;
	}
</style>
<!--
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/app/SalesItems/main.js"></script>
-->


<h1>
<i class="fa fa-book"></i>

Laporan Biaya Pengeluaran</h1>
<br>


<?php 
if (isset($_REQUEST['tanggal'])){
	$tgl = $_REQUEST['tanggal'];
	$tgl2 = $_REQUEST['tanggal2'];
	$filter = " and date(tanggal) >= '$tgl' and date(tanggal)<='$tgl2' ";

}else{
	$tgl = date('Y-m-d');
		$tgl2 = date('Y-m-d');
	$filter = "";

}
?>
<?php $form=$this->beginWidget('CActiveForm',array(
	'action'=>Yii::app()->createUrl('items/laporan_pengeluaran'),
	'method'=>'get',
)); ?>

<label>Tanggal 1</label>
<input type="text" value="<?php echo $tgl; ?>" style="display:inline;padding:5px" name="tanggal" class="tanggal">
<label>sampai</label>
<input  type="text" value="<?php echo $tgl2; ?>" style="display:inline;padding:5px" name="tanggal2" class="tanggal">





<?php echo CHtml::submitButton('Cari',array('class'=>'btn btn-primary','value'=>'Cari')); ?>
<input onclick="$('#data-cetak').print()" type="button" name="cetak" value="cetak" class="btn btn-primary">

<?php $this->endWidget(); ?>

<?php

	
	$username = Yii::app()->user->name;
	$user = Users::model()->find('username=:un',array(':un'=>$username));
	$idk = $user->level; 
	$a = true;
	if($idk < 5)
	$a = true;
	else
	$a = false;
?>

<?php

$branch = Yii::app()->user->branch();
$sql  = "select * from pengeluaran where 1=1   $filter and branch_id='$branch' order by tanggal desc";
// echo "$sql";
$model = Yii::app()->db->createCommand($sql)->queryAll();

?>
<style type="text/css">
	.table tr td{
		border: 1px solid rgb(163, 0, 0,1);
	}
</style>
<br>

<div  id="data-cetak">
<div class="print">
		<h1>Laporan Pengeluaran</h1>
		<h5>Periode <?php echo $tgl ?> sampai dengan <?php echo $tgl2 ?> </h5>
		<!-- <hr style="border:1px solid black"> -->
</div>
<table class="table items">


		
	<tr style="color:white;font-weight: bolder;background-color: rgba(42, 63, 84,1)" >
			<td>No</td>
			<!-- <td>ID Keluar</td> -->
			<td>Tanggal</td>
			<td>Petugas</td>
			<td>Jenis Pengeluaran </td>
			<td>Keterangan</td>
			<td>Total</td>
			<td class="no-print">Aksi</td>
			<!-- <td>Aksi</td> -->
		</tr>

	</thead>
	<tbody>
		<?php 
		$no=1;
		foreach ($model as $m): ?>
		<tr>
			<td style="width:5%"><?php echo $no;?></td>
			<!-- <td style="width:50%"><?php echo $m[id]; ?></td> -->
			<td style="width:20%"><?php echo date("d M Y H:i",strtotime($m[tanggal]) ); ?></td>
			<td style="width:20%"><?php echo $m[user]; ?></td>
			<td style="width:20%"><?php echo $m[jenis_pengeluaran]; ?></td>
			<td style="width:20%"><?php echo $m[keterangan]; ?></td>
			<td style="width:10%" align="right"><?php echo number_format($m[total]); ?></td>
			<td class="no-print">
			<a href="<?php echo Yii::app()->controller->createUrl('pengeluaranhapus',array("id"=>$m[id])) ?>" class="hapus">
				<i class="fa fa-times"></i>
			</a>
			<a href="<?php echo Yii::app()->controller->createUrl('items/ubah_pengeluaran',array("id"=>$m[id])) ?>" class="update">
				<i class="fa fa-pencil"></i>
			</a>
			</td>
			<!-- <tr>
				<td colspan="7">
					<table style="font-size:12px;width:500px;">

						<tr>
							<td>No</td>
							<td>Nama Item</td>
							<td>Qty</td>
							
						</tr>
						<?php 
						$sql = "SELECT 
						s.id, i.item_name, si.jumlah qty
						FROM
						barangkeluar s, barangkeluar_detail si, items i
						WHERE 
						si.head_id = s.id 
						AND
						i.id = si.kode

						AND s.id = '$m[id]'";
						$no2=1;
						foreach (Yii::app()->db->createCommand($sql)->queryAll() as $q ) {
						?>
						<tr>
							<td style="width:5%"><?php echo $no2; ?></td>
							<td style="width:20%"><?php echo $q[item_name]  ?></td>
							<td style="width:10%"><?php echo $q[qty]  ?></td>
							
						</tr>
						<?php 
						$no2++;
						} ?>
					</table>
				
				</td>
			</tr> -->
		</tr>
	<?php 
	$total = $total +$m[total];
	$no++;
	endforeach; ?>
	<tr>
		<td colspan="8" style="text-align:right">
			<?php 
			echo number_format("$total");
			?>
		</td>
	</tr>
	</tbody>
</table>
</div>
<?php 

//  $this->widget('zii.widgets.grid.CGridView', array(
// 	'id'=>'sales-grid',
// 	'dataProvider'=>$dataProvider,
// 	// 'filter'=>$model->search(),
// 	'columns'=>array(
// 		array(
// 			'header'=>'No.',
// 			'value'=>'($this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize) + array_search($data,$this->grid->dataProvider->getData())+1',
// 		),
// 		array(
// 		'name'=>'id',
// 		'header'=>'ID'
// 		),
// 		array(
// 		'name'=>'date',
// 		'header'=>'Tanggal'
// 		),
// 		// 'date',
// 		array(
// 		'name'=>'total_items',
// 		'header'=>'Total Menu',
// 			'class'=>'ext.gridcolumns.TotalColumn',
// //			'value'=>'$data->nilai',
// 			'type'=>'number',
// 			'footer'=>true,
// 		),	
// // 		array(
// // 			'name'=>'untung',
// // 			'header'=>'total Keuntungan',
// // 			'type'=>'number',
// // 			// 'htmlOptions'=>array('style'=>'text-align:right'),
// // 			'class'=>'ext.gridcolumns.TotalColumn',
// // //			'value'=>'$data->nilai',
// // 			'visible'=>$a,
// // 			'type'=>'number',
// // 			'footer'=>true,
// // 			'htmlOptions'=>array('style'=>'text-align:right'),

// // 		),	
// 		array(
// 			'name'=>'sale_sub_modal',
// 			'header'=>'total modal',
// 			'type'=>'number',
// 			'htmlOptions'=>array('style'=>'text-align:right'),
// 			'class'=>'ext.gridcolumns.TotalColumn',
// //			'value'=>'$data->nilai',
// 			'visible'=>$a,
// 			'type'=>'number',
// 			'footer'=>true,
// 			'htmlOptions'=>array('style'=>'text-align:right'),

// 		),	
// 		array(
// 			'name'=>'sale_sub_total',
// 			'header'=>'total bersih',
// 			'type'=>'number',
// 			'htmlOptions'=>array('style'=>'text-align:right'),
// 			'class'=>'ext.gridcolumns.TotalColumn',
// //			'value'=>'$data->nilai',
// 			'type'=>'number',
// 			'footer'=>true,
// 			'htmlOptions'=>array('style'=>'text-align:right'),

// 		),	
// 		array(
// 			'name'=>'sale_tax',
// 			'header'=>'total pajak',
// 			'type'=>'number',
// 			'htmlOptions'=>array('style'=>'text-align:right'),
// //			'value'=>'$data->nilai',
// 			'class'=>'ext.gridcolumns.TotalColumn',
// 			'type'=>'number',
// 			'footer'=>true,
// 			'htmlOptions'=>array('style'=>'text-align:right'),

// 		),	
// 		array(
// 			'name'=>'sale_service',
// 			'header'=>'total service',
// 			'type'=>'number',
// 			'htmlOptions'=>array('style'=>'text-align:right'),
// 			'class'=>'ext.gridcolumns.TotalColumn',
// //			'value'=>'$data->nilai',
// 			'type'=>'number',
// 			'footer'=>true,
// 			'htmlOptions'=>array('style'=>'text-align:right'),

// 		),	
// 		array(
// 			'name'=>'sale_discount',
// 			'header'=>'total diskon',
// 			'type'=>'number',
// 			'htmlOptions'=>array('style'=>'text-align:right'),
// 			'class'=>'ext.gridcolumns.TotalColumn',
// //			'value'=>'$data->nilai',
// 			'type'=>'number',
// 			'footer'=>true,
// 			'htmlOptions'=>array('style'=>'text-align:right'),

// 		),
		
// 		array(
// 			'name'=>'sale_total_cost',
// 			'header'=>'total kotor',
// 			'type'=>'number',
// 			'htmlOptions'=>array('style'=>'text-align:right'),
// 			'class'=>'ext.gridcolumns.TotalColumn',
// //			'value'=>'$data->nilai',
// 			'type'=>'number',
// 			'footer'=>true,
// 			'htmlOptions'=>array('style'=>'text-align:right'),

// 		),
// 		array(
// 			'name'=>'inserter',
// 			'header'=>'Kasir',
// 			'type'=>'number',
// 			'htmlOptions'=>array('style'=>'text-align:right'),
// 			'class'=>'ext.gridcolumns.TotalColumn',
// 			// 'value'=>'$data->user->username',
// 			'type'=>'text',
// 			'footer'=>true,
// 			'htmlOptions'=>array('style'=>'text-align:right'),

// 		),
// 		// array(
// 		// 	'name'=>'table',
// 		// 	'header'=>'Meja',
// 		// ),
// 		// array(
// 		// 	'name'=>'waiter',
// 		// 	'header'=>'waiter',
// 		// )
// 		// 'table'
// 		// ,
// 		array(
// 		'name'=>'bayar',

// 		'header'=>'bayar',
// 		'type'=>'number',		
// 		)
// 		,
// 		//'comment',
// 		// 'status',
// 		array(
// 		'type'=>'raw',
// 		'header'=>'Rincian Menu',
// 		'value'=>'CHtml::link("Detail",array("sales/detailitems","id"=>$data[id]),array("style"=>"text-decoration:none"))',
		
// 		),
// 		// array(
// 		// 	"name"=>"comment",
// 		// 	"header"=>"Description",
// 		// ),
// 		array
// 		(
// 			'name'=>'print',
// 			'header'=>'Cetak',
// 			'type'=>'raw',
// 			// 'value'=>'CHtml::ajaxButton("Cetak", array("sales/CetakReport","id"=>$data->id),array())',
// 			'value'=>'CHtml::ajaxButton("Cetak", array("sales/CetakReport"),array(
// 																					"data"=>array("id"=>$data[id]),
// 																					"success"=>"function(data){
// 																						// alert(\'cek\');
// 																						var sales = jQuery.parseJSON(data);
// 																						if (sales.sale_id!=\'\')
// 																						{
// 																							print_bayar(sales);
// 																						}
// 																					}",
// 																					"error"=>"function(data){alert(\'data\')}"
// 																				))',
// 		),	
// 		array(
// 		'class'=>'CButtonColumn',
// 		'template'=>'{delete}',
// 		'visible'=>$a,
// 		'buttons' => array(
			
// 				'delete' => array(
// 							'url'=>'Yii::app()->createUrl("Sales/delete", array("id"=>$data[id]))',      //A PHP expression for generating the URL of the button.
				
// 				),
// 			),
		
// 		), 		
// 	),
//)); ?>

<script>
$(document).ready(function(){
	 $('.tanggal').datepicker(
        { 
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true

    });
	// $('.hapus').click(function(e){
	// 	// e.preventDefault();
	// 	if (!confirm("Yakin  ?")){
	// 		return false
	// 	}else{
	// 		return true;
	// 	}
	// });

	$('.hapus-detail').click(function(e){
		// e.preventDefault();
		if (!confirm("Menghapus  , akan mempengaruhi stok, Yakin hapus  ?")){
		// if (!confirm("Yakin hapus ?")){
			return false
		}else{
			return true;
		}
	});
	


});
</script>
<div id="hasil">
</div>


