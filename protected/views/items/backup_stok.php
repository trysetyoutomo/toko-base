<applet name="jzebra" code="jzebra.PrintApplet.class" archive="jZebra/jzebra/jzebra.jar" width="0" height="0">
    <param name="printer" value="zebra">
</applet> 

<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-custom/jquery-ui.min.css">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-custom/jquery-ui.min.js"></script>

<div id="hasil"></div>
<style type="text/css">
	.dalem{
		width:100%;
	}
	thead tr td{
		font-weight: bolder;
	}
	table tr td{
		padding: 5px;
	}
</style>
<?php 
$this->renderPartial('application.views.site.main');
?>

<h1><i class="fa fa-book"></i>
Laporan Stok Items</h1>
<br>
<div class="col-sm-12">
	<form action="">
	<input type="hidden" name="r" value="items/laporanstok">
	<table>
	<tr>	
	<td>
	<label>
		Cari Nama 
	</label>
	</td>
	<td>
		
	<input type="text" style="width: 300px;display: inline;" class="form-control" name="cari" value="<?php echo $_REQUEST['cari'] ?>">

	</td>
	</tr>	
	<tr style="display: none;">
	<td>
		
	<label>
		Tempat 
	</label>
	</td>
	<td>
		
	<?php  $nilai = Branch::model()->findAllNew();?>
	<select id="cabang" name="cabang" class="form-control" style="width: 300px;display: inline;">
	<?php foreach($nilai as $k): ?>
		<option <?php if ($k->id==$_REQUEST['cabang']) echo "selected" ?> value="<?php echo $k->id ?>">
		<?php echo $k->branch_name ?>
			
		</option>
	<?php endforeach; ?>
		
	</select>
	</td>
	</tr>

	<tr>
	<td></td>
		<td >
			<button type="submit"  value="" class="btn btn-primary">
			<i class="fa fa-search"></i>
				Cari Item	
			</button>
		</td>
	</tr>
	
	</table>
	
</form>
<hr>

<!-- 
<select id="letak" style="width: 200px;display: none;" class="form-control">
<option value="">Semua Letak</option>
<?php 
 $branch = Yii::app()->user->branch();

$data = Letak::model()->findAll("branch_id = '$branch' ");
foreach ($data as $key => $value) { ?>
	<option 
	value="<?php echo $value->id ?>"
	>
	<?php echo $value->kode.' - '.$value->nama ?></option>
	<?php } ?>
</select> -->
	
	
</div>

<?php 
$filter = " where 1=1 and i.hapus = 0 ";
if (isset($_REQUEST['tgl'])){
	$tgl = $_REQUEST['tgl'];
	$tgl2 = $_REQUEST['tgl2'];
	$filter .= " and  date(tanggal) >= '$tgl' and date(tanggal)<='$tgl2' ";

}else{
	$tgl = date('Y-m-d');
	$tgl2 = date('Y-m-d');
	$filter .= "";
}

// $tempat = $_REQUEST['cabang'];
// if (isset($tempat) && $tempat!="" ){
	// var_dump($status_aktif);
	// $filter .= " and  barangmasuk.branch_id = '$tempat' ";
// }




?>
<?php
//  $form=$this->beginWidget('CActiveForm',array(
// 	'action'=>Yii::app()->createUrl('sales/laporanstok'),
// 	'method'=>'get',
// )); ?>

<!-- <div style="display: none;"> -->
	
<!-- <label>Tanggal 1</label>
<input name="Sales[date]" type="date" value="<?php echo $tgl; ?>" style="display:inline;padding:5px" name="tanggal" class="tanggal">
<label>Tanggal 2</label>
<input name="Sales[date2]" type="date" value="<?php echo $tgl2; ?>" style="display:inline;padding:5px" name="tanggal" class="tanggal">
<input type="text" name="key" placeholder="Kata Kunci" value="<?php echo $_REQUEST[key] ?>" >

 -->



<?php //echo CHtml::submitButton('Cari',array('class'=>'btn btn-primary')); ?>
<?php // $this->endWidget(); ?>

<!-- </div> -->
<?php

	
	// $username = Yii::app()->user->name;
	// $user = Users::model()->find('username=:un',array(':un'=>$username));
	// $idk = $user->level; 
	// $a = true;
	// if($idk < 5)
	// $a = true;
	// else
	// $a = false;
?>

<?php

if (isset($_REQUEST['key'])){
	$key = $_REQUEST['key'];
	$filter.= " and item_name like  '%$key%' ";
}

if (isset($_REQUEST['cari'])){
	$key = $_REQUEST['cari'];
	$filter.= " and item_name like  '%$key%' ";
}

// $sql  = "select * from items $filter order by tanggal desc";
$sql  = "select 
m.nama nama_subkategori,
concat(i.item_name,'  ','') as item_name,
c.category as nama_kategori,
i.hapus hapus, iss.id as satuan_id, i.id id, i.barcode barcode

 from items i inner join items_satuan iss
  on iss.item_id = i.id and i.is_stockable = 1
  left join categories as c on c.id = i.category_id
  left join motif m on m.category_id = c.id and m.id = i.motif

  $filter 
  and iss.is_default = 1
  group by iss.id
  order by c.category, m.id, i.item_name  asc

  ";
  // echo $filter;
  // echo $sql;
  // echo "<pre>";
  // print_r($_REQUEST);
  // echo "</pre>";
  // echo $sql;
$model = Yii::app()->db->createCommand($sql)->queryAll();
// echo "<pre>";
// print_r($model);
// echo "</pre>";

?>
<style type="text/css">
	.table tr td{
		border: 1px solid rgb(163, 0, 0,1);
	}
</style>
<br>
<?php
if (isset($_REQUEST['cabang'])){
?>
<table class="table items "  style="width: 80%" style="">
	<thead>
		
		<tr style="color:white;font-weight: bolder;background-color: rgba(42, 63, 84,1)" >
			<td>No</td>
			<td>Kategori</td>
			<td>Sub Kategori</td>
			<td>Nama Barang</td>
			<td>Stok Saat ini</td>
		</tr>

	</thead>
	<tbody>
		<?php 
		$no=1;
		foreach ($model as $m): ?>
		<tr>
			<td ><?php echo $no;?></td>
			<td ><?php echo $m['nama_kategori']; ?></td>
			<td ><?php echo $m['nama_subkategori']; ?></td>
			<td  >
				<a target="_blank" 
	href="<?php echo Yii::app()->createUrl("ItemsSatuan/kartu",array("id"=>$m['id'],'satuan_id'=>$m['satuan_id'])) ?>">
				<?php echo $m[item_name]; ?>
				</a>
			</td>
		

			<?php 
			$stok = ItemsController::getStok($m['id'],$m['satuan_id'],$_REQUEST['cabang']);
			$harga = round(ItemsController::getAverage($m['id'],$m['satuan_id'],$_REQUEST['cabang'])) ;
			// $stok = 125;
			// $harga = 10000;
			?>
			<td  class="set-stok-old" >
			<?php
			$satuanlist = ItemsController::getSatuanItems($m['id'],$stok);
			// $string = "Hello! 123 test this? 456. done? 100%";
			// $int = intval(preg_replace('/[^0-9]+/', '', $satuanlist), 10);
			// echo $int;
			echo $satuanlist;
			// $str = 'In My Cart : 11 12 items';
			// $matches = 	preg_match_all('!\d+!', $satuanlist, $matches);
				// print_r($matches)
				// echo $matches ;
			// $sqlx = " SELECT  * FROM items_satuan WHERE item_id = '$m[id]' order by urutan asc";
			//  // $datastauan = ItemsSatuan::model()->findAll(" item_id = '$m[id]' ");
			// $datastauan = Yii::app()->db->createCommand($sqlx)->queryAll();
			// $stok2 = $stok;
			// // echo $stok;
			// foreach ($datastauan as $key => $value) {
			
			// 	echo $value['nama_satuan']." :"." ";			
			// 	$satuan = $value['nama_satuan'];
			// 	$satuan_jml = $value['satuan'];
			// 	if ($stok2>=$stok){
			// 		$s = $stok % $satuan_jml;
			// 		if ($s==0){ // jika ga ada sisa maka
			// 			$total =  $stok/$satuan_jml;
			// 			$temp = $total*$satuan_jml;
			// 			$stok2 -= $temp;
			// 			echo $total."";
			// 		}else{ // jika ada sisa maka
			// 			// echo $s;
			// 			$total = $stok - $s;
			// 			$stok2-=$total;

			// 			$total =  $total/$satuan_jml;
			// 			echo $total;
						
			// 			// echo $total; 
			// 		}
			// 	}else{ // jika di bawah sotk yang ada maka
			// 		$s = $stok2%$satuan_jml;
			// 		// echo $s;
			// 		if ($s==0){
			// 			// echo $stok2*$satuan_jml;
			// 			$total =  $stok2/$satuan_jml;
			// 			$temp = $total*$satuan_jml;
			// 			$stok2 -=$temp ;
			// 			echo $total;
			// 			// $stok2 -= $temp;
			// 		}else{
			// 			$s = $stok2%$satuan_jml;
			// 			$sisa = $stok2 - $s;
			// 			$s = $sisa/$satuan_jml;
			// 			echo $s;
			// 			// echo $sisa;
			// 			$stok2-=$sisa;
			// 		}

			// 		//echo "-"."<br>";
			// 	}
			// 	echo "<br>";

			// 	// if ($stok2<=0){
			// 	// 	return;
			// 	// }

			// }
			 // echo $stok;
			 
			 ?></td>
			
			<td style="display: none;">
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
	<!-- <tr>
		<td colspan="8" style="text-align:right">
			<?php 
			echo number_format("$total");
			?>
		</td>
	</tr> -->
	</tbody>
</table>
<button id="cetak-all-stok" class="btn btn-primary" name="cetak-all-stok">
	<i class="fa fa-print"></i>
		Cetak
</button>

<?php 
}
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
// 		'name'=>'s',

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
	$('#cetak-all-stok').click(function(){
		var id = $("#cabang").val();
		
			$.ajax({
				url:'<?php echo Yii::app()->createUrl("sales/CetakStok") ?>',
				data:'id='+id,
				success: function(data){
					// alert(data);
					var json = jQuery.parseJSON(data);
					// $('#hasiljson').html(data);
					print_keluar(json);
					// console.log(data);
					
				},
				error: function(data){
					alert('error');
				}
			});
		
	});
	 $('.tanggal').datepicker(
        { 
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true

    });
	$('.set-stok').click(function(e){
		var i = $(".set-stok").index(this);
		// alert(i);
	   var before = parseInt($(this).attr("stok-before")) ;
	   var skrg =  parseInt($(".stok_real").eq(i).val() );
	   var harga =  parseInt($(this).attr("harga"));
	   var id = $(this).attr("item-id");
	   // var harga = $(this).attr("item-id");
	   // alert(before);
	   // alert(skrg);
	   // alert(id);
	   if (before!=skrg){
	   		// alert(skrg);
	   		// alert(before);
	   		if (skrg>before){	
			   var harga = parseInt(prompt("Masukan Harga Beli (Harga Beli Terakhir)",harga,harga) );
			   if (harga==0 || isNaN(harga) || harga==null){
				   	alert("Tidak Boleh Kosong");
				   	exit;
			   }
	   		}else{
	   			harga = 0;
	   		}
	   		// alet("auasdasdas");

	   		// alert(harga);
		   $.ajax({
	          url: "<?php echo Yii::app()->createUrl("items/setstok")?>",
	          // cache: false,
	          data : "before="+before+"&skrg="+skrg+"&id="+id+"&harga="+harga,
	          success: function(msg){
	            var data = JSON.parse(msg);
	            if (data.success==true){
	            	$(".set-stok-old").eq(i).html(skrg);
	            	window.location.reload();
	            	// alert("Berhasil Update Data");
	            }
	        	// alert(JSON.stringify(data));
	           },
	           error : function(d){
	           	alert(d);
	           	// var data = JSON.parse(d);
	           	// ale
	           	// alert(JSON.st)
	           }
	       });
	   }else{
	   	alert(" Tidak ada perubahan ");
	   }

	});
	$('.hapus').click(function(e){
		// e.preventDefault();
		if (!confirm("Yakin  ?")){
			return false
		}else{
			return true;
		}
	});

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


</fieldset>
