
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


