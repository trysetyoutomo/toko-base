
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
<i class="fa fa-balance-scale "></i>

Neraca</h1>
<br>


<?php
if (isset($_REQUEST['tanggal2'])){
	// $tgl = $_REQUEST['tanggal'];
	$tgl2 = $_REQUEST['tanggal2'];
	$filter = "  and date(tanggal_posting)<='$tgl2' ";

}else{
	// $tgl = date('Y-m-d');
		$tgl2 = date('Y-m-d');
	$filter = "";

}
?>
<?php $form=$this->beginWidget('CActiveForm',array(
	'action'=>Yii::app()->createUrl('neraca/'),
	'method'=>'POST',
)); ?>

<label>Periode sampai</label>
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

  $sql = NeracaController::queryNeraca($branch,$filter);
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
		<h1>Laporan Jurnal</h1>
		<h5>Periode <?php echo $tgl ?> sampai dengan <?php echo $tgl2 ?> </h5>
		<!-- <hr style="border:1px solid black"> -->
</div>
<table class="table items">



	<tr style="color:white;font-weight: bolder;background-color: rgba(42, 63, 84,1)" >
			<td>No</td>
			<td>Nama Akun</td>
			<!-- <td>ID Keluar</td> -->
			<!-- <td>Tanggal</td>
			<td>Keterangan</td>
			<td>Akun</td> -->
			<td>Debit </td>
			<td>Kredit</td>
			<!-- <td>Saldo</td> -->
			<!-- <td class="no-print">Aksi</td> -->
			<!-- <td>Aksi</td> -->
		</tr>

	</thead>
	<tbody>
		<?php
		$totalRow = 1;
		$no=0;
		$jurnal_id = "";
		foreach ($model as $m):
			$countRow ++;
			$jml_detail_jurnal = $m['jml_detail_jurnal'];


		?>
		<tr>

            <td style="width:5%"><?=$countRow?></td>
			<td align="left" style="width:20%"><?php echo "(".$m['kode_akun'].") - ".$m['nama_akun']; ?></td>
			<td  jurnal_id=<?=$m['jurnal_id']?>  align="right" style="width:20%"><?php echo number_format($m[debit]); ?></td>
			<td  align="right" style="width:20%"><?php echo number_format($m[kredit]); ?></td>
			<!-- <td  style="width:10%" align="right"><?php echo number_format($m[total]); ?></td> -->
			<!-- <td  class="no-print"> -->
			<!-- <a href="<?php echo Yii::app()->controller->createUrl('pengeluaranhapus',array("id"=>$m[id])) ?>" class="hapus">
				<i class="fa fa-times"></i>
			</a>
			<a href="<?php echo Yii::app()->controller->createUrl('items/ubah_pengeluaran',array("id"=>$m[id])) ?>" class="update">
				<i class="fa fa-pencil"></i>
			</a> -->
			<!-- </td> -->

		</tr>
	<?php

	$total = $total +$m[total];
	$totaldebit+=$m[debit];
	$totalkredit+=$m[kredit];

	endforeach; ?>
	<tr>
        <td></td>
        <td></td>
		<td align="right"><?=number_format($totaldebit)?></td>
		<td align="right"><?=number_format($totalkredit)?></td>
		<!-- <td></td> -->
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


