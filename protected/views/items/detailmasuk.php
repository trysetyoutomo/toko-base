<?PHP
$branch = Yii::app()->user->branch();
$branch = Branch::model()->findByPk($branch);
$id = $_REQUEST['id'];
$head = Barangmasuk::model()->findByPk($id);


$sql = "SELECT
	c.category as nama_kategori,
	supplier_id,
	s.id,
	i.item_name nama_item,
	si.jumlah qty,
	si.id sid,
	si.harga harga_item,
	i.id iid,
	iss.nama_satuan as nama_satuan  
FROM
	barangmasuk s
INNER JOIN barangmasuk_detail si ON si.head_id = s.id
INNER JOIN items i ON i.id = si.kode
INNER JOIN categories as c on c.id = i.category_id
INNER JOIN items_satuan iss  on iss.id = si.satuan
WHERE

 s.id = '$id'
group by si.id
order by c.category, i.item_name asc
";

?>
<table width="400px" border="0" style="border: 1px solid black;margin:5px;padding:5px">
	<tr>
		<TD align='center' style="font-size:20px;">
			<?php  echo $branch->branch_name;?>	
		</TD>
	</tr>
	<tr><td style="font-size:15px;"  align='center'><?php  echo $branch->address." ".$branch->telp ;?>	</td></tr>
	<tr>
	<td align='center'>Daftar Rincian</td></tr>
	<tr>
	<td align='left'>Tanggal : <?php echo date("d M Y H:i ",strtotime($head->tanggal) ) ?></td>
	</tr>
	<tr>
		<td align='left'>Kode TRX : <?php echo $head->kode_trx ?></td>
	</tr>
	<tr>
		<td align='left'>Petugas : <?php echo $head->user ?></td>
	</tr>
	<tr>
		<td align='left'>Sumber : 
		<?php 
		if ($head->status_aktif=="0"){
			$br = Branch::model()->findByPk($head->sumber);
			echo $br->branch_name;
		}

		 ?></td>
	</tr>



	<tr>
	<tr>
		<td>
		<br>

		<table class="rincian" border="1" cellpadding="5" style="font-size:12px;width: 100%" >
			<tr style="font-weight: bolder">
				<td>No</td>
				<td>Kategori</td>
				<td>Nama Item</td>
				<td>Jumlah</td>
				<td>Satuan</td>
			</tr>
			<?php 
		$no=1;
		foreach (Yii::app()->db->createCommand($sql)->queryAll() as $q ) {
		?>
		<tr>
			<td><?php echo $no++ ?></td>
			<td><?php echo $q['nama_kategori'] ?></td>
			<td><?php echo $q['nama_item'] ?></td>
			<td><?php echo intval($q['qty']) ?></td>
			<td><?php echo $q['nama_satuan'] ?></td>
		</tr>
		<?php } ?>

			</table>


		
			


		</td>
	</tr>
		
	</tr>


</table>