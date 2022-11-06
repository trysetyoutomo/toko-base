
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

Laporan Buku Besar</h1>
<br>


<?php 
if (isset($_REQUEST['tanggal'])){
	$tgl = $_REQUEST['tanggal'];
	$tgl2 = $_REQUEST['tanggal2'];
	$filter = " and date(tanggal_posting) >= '$tgl' and date(tanggal_posting)<='$tgl2' ";

}else{
	$tgl = date('Y-m-d');
		$tgl2 = date('Y-m-d');
	$filter = "";

}
?>
<?php $form=$this->beginWidget('CActiveForm',array(
	'action'=>Yii::app()->createUrl('bukubesar/'),
	'method'=>'POST',
)); ?>

<label>Tanggal 1</label>
<input type="text" value="<?php echo $tgl; ?>" style="display:inline;padding:5px" name="tanggal" class="tanggal">
<label>sampai</label>
<input  type="text" value="<?php echo $tgl2; ?>" style="display:inline;padding:5px" name="tanggal2" class="tanggal">
<label for="jumlah" >Akun</label>
<select class="form-control" style="width: 250px;display: inline" name="akun_id" id="akun_id">
<option value="0">Pilih Jenis</option>
<?php foreach (AkuntansiAkun::model()->findAll($criteria) as $jb) { ?>
	<option <?php if ($_REQUEST['akun_id'] == $jb->id) echo "selected" ?> value="<?php echo $jb->id ?>"><?php echo $jb->nama_akun ?></option>
<?php } ?>
</select>




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

<?php 
if (isset($_REQUEST['akun_id'])):
$branch = Yii::app()->user->branch();
$sql  = "
SELECT
	aj.tanggal_posting as tanggal_posting,
	aj.id AS jurnal_id,
	akun_id,
	ak.kode_akun,
	ak.nama_akun,
	sum( debit ) AS debit,
	sum( kredit ) AS kredit, 
	debit - kredit AS saldo,
	u.username,
	keterangan
	
FROM
	akuntansi_jurnal aj
	INNER JOIN akuntansi_jurnal_detail ajd ON aj.id = ajd.jurnal_id
	INNER JOIN akuntansi_akun ak ON ak.id = ajd.akun_id 
	LEFT JOIN users u on u.id = aj.user_id
	where ak.id = {$akunID}
	GROUP BY
	ajd.id


  ";
// echo "$sql";
$model = Yii::app()->db->createCommand($sql)->queryAll();
?>

<table class="table items">
	
	<tr style="color:white;font-weight: bolder;background-color: rgba(42, 63, 84,1)" >
			<td>No</td>
			<!-- <td>ID Keluar</td> -->
			<td>Tanggal</td>
			<td>Keterangan</td>
			<td>Akun</td>
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
			
			
			// if ($tempJurnalID != $m['jurnal_id']){
			// 	$style="style='color:red'";
			// }else{
			// 	$style="style='color:green'";
			// }

			
			// if ($initDate != $m['tanggal_posting']){
			// 	$rowspan = 2;
			// }
		?>
		<tr <?=$style?>>
			<?php if ($tempJurnalID != $m['jurnal_id']){
					$no++;?>
					<td rowspan="<?=$jml_detail_jurnal?>" style="width:5%"><?php echo $no  ?></td>
					<td rowspan="<?=$jml_detail_jurnal?>" style="width:20%"><?php echo date("d M Y H:i",strtotime($m[tanggal_posting]) ) . " (".$m['username'].")"; ?></td>
					<td rowspan="<?=$jml_detail_jurnal?>" style="width:20%">
					<?php echo $m['keterangan']; ?>
				</td>
				<?php
					$tempJurnalID = $m['jurnal_id'];
			}
			?>
		

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
		<!-- <td colspan="9" style="text-align:right">
			<?php 
			echo number_format("$total");
			?>
		</td> -->
		<td colspan="4"></td>
		<td align="right"><?=number_format($totaldebit)?></td>
		<td align="right"><?=number_format($totalkredit)?></td>
		<!-- <td></td> -->
	</tr>
	</tbody>
</table>
<?php endif; ?>
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


