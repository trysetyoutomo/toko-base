<?PHP
$branch = Yii::app()->user->branch();
$branch = Branch::model()->findByPk($branch);
// $id = $_REQUEST['id'];
// $head = Barangmasuk::model()->findByPk($id);
// $detail = SalesController::

// $sql = "SELECT
// 	c.category as nama_kategori,
// 	supplier_id,
// 	s.id,
// 	i.item_name nama_item,
// 	si.jumlah qty,
// 	si.id sid,
// 	si.harga harga_item,
// 	i.id iid,
// 	iss.nama_satuan as nama_satuan  
// FROM
// 	barangmasuk s
// INNER JOIN barangmasuk_detail si ON si.head_id = s.id
// INNER JOIN items i ON i.id = si.kode
// INNER JOIN categories as c on c.id = i.category_id
// INNER JOIN items_satuan iss  on iss.id = si.satuan
// WHERE

//  s.id = '$id'
// group by si.id
// order by c.category, i.item_name asc
// ";
$tanggal = $_REQUEST['tanggal'];
$u = Users::model()->find("id = '$_REQUEST[user]' ");
$query = "SELECT
	categories.category nm,
	items.item_name item_name,
	item_price harga,
	sales_items.item_price * sum(quantity_purchased) price,
	sum(quantity_purchased
	) qp
FROM
	sales_items
INNER JOIN sales ON sales.id = sales_items.sale_id
INNER JOIN items ON items.id = sales_items.item_id
INNER JOIN categories ON categories.id = items.category_id
INNER JOIN sales_payment sp ON sp.id = sales.id
WHERE
	sales.STATUS = 1
	and inserter = '$u->id'
	and date(date)= '$tanggal'
GROUP BY
	item_id,
	item_price

ORDER BY categories.category asc
	"
	;
$query_gratis = "
SELECT
	categories.category nm,
	items.item_name item_name,
	bkd.harga harga,
	bkd.jumlah jumlah 
FROM
	barangkeluar_detail bkd
INNER JOIN barangkeluar bk ON bk.id = bkd.head_id
INNER JOIN items ON items.id = bkd.kode
INNER JOIN categories ON categories.id = items.category_id
WHERE bk.user = '$u->username'

	and date(tanggal)= '$tanggal'
GROUP BY
	bkd.kode,bkd.harga
ORDER BY categories.category asc
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
	<td align='center'>
	<!-- <h1>
	</h1> -->
	Rekap Harian
	<hr>
	
	</td></tr>
	<tr>
	<td align='left'>Tanggal : <?php echo date("d M Y ",strtotime($_REQUEST['tanggal']) ) ?></td>
	</tr>
	<tr>
		<td align='left'>Petugas : <?php 
		
		echo $u->username;
		 ?></td>
	</tr>
<!-- 	<tr>
		<td align='left'>Sumber : 
		<?php 
		if ($head->status_aktif=="0"){
			$br = Branch::model()->findByPk($head->sumber);
			echo $br->branch_name;
		}

		 ?></td>
	</tr>
 -->


	<tr>
	<tr>
		<td align="center">
		<h2>
			
Penjualan
		</h2>
		</td>
	</tr>
	<tr>
		<td>
		<br>

<?php 
// exit;
?>
		<table class="rincian" border="0" cellpadding="5" style="font-size:12px;width: 100%" >
			<tr style="font-weight: bolder">
				<td>No</td>
				<td>Kategori</td>
				<td>Nama Item</td>
				<td>Jumlah</td>
				<td>Harga</td>
				<td>Total</td>
			</tr>
			<?php 
		$no=1;
		foreach (Yii::app()->db->createCommand($query)->queryAll() as $q ) {
		?>
		<tr>
			<td><?php echo $no++ ?></td>
			<td><?php echo $q['nm'] ?></td>
			<td><?php echo $q['item_name'] ?></td>
			<td><?php echo intval($q['qp']) ?></td>
			<td><?php echo number_format($q['harga']) ?></td>
			<td><?php echo number_format($q['price']) ?></td>
		</tr>
		<?php 
		$total+=$q['price'];
		} ?>

		</table>

		<?php 
		$sql  = "select * from pengeluaran where 1=1 and date(tanggal)='$_REQUEST[tanggal]' and user='$u->username'

		 order by tanggal desc";

 		

		 




		?>
		<table width="100%">
			<tr>
				<td>
					Total Penjualan
				</td>
				<td align="right">
					<?php echo number_format($total) ?>
				</td>
			</tr>
			<tr>
				<td align="center" colspan="2">
				<h2>Pengeluaran</h2>
				</td>
			</tr>
			<tr>
			<td colspan="2">
			<table class="rincian" border="0" cellpadding="5" style="font-size:12px;width: 100%" >
			<tr style="font-weight: bolder">
				<td>No</td>
				<td>Nama Item </td>
				<td>Total</td>
			</tr>
			
				

			<?php
			$no=1;
			$total_pengeluaran = 0; 
			foreach (Yii::app()->db->createCommand($sql)->queryAll() as $q ) {
				?>
				<tr>
					<td><?php echo $no++ ?></td>
					<td><?php echo $q['jenis_pengeluaran'] ?></td>
					<td><?php echo number_format($q['total']) ?></td>
				</tr>


				<?php 
				$total_pengeluaran+=$q['total'];
			}
			?>
			</table>
			</td>
			</tr>
			<tr>
				<td>Total Pengeluaran</td>
				<td align="right"><?php echo number_format($total_pengeluaran)  ?></td>
			</tr>

			<tr>
				<td align="center" colspan="2">
				<h2>Compliment</h2>
				</td>
			</tr>
			<tr>
			<td colspan="2">
			<table class="rincian" border="0" cellpadding="5" style="font-size:12px;width: 100%" >
			<tr style="font-weight: bolder">
				<td>No</td>
				<td>Nama </td>
				<td>Jumlah</td>
				<td>Harga</td>
				<td>Total</td>
			</tr>
			
				

			<?php
			$no=1;
			$total_compliment = 0; 
			foreach (Yii::app()->db->createCommand($query_gratis)->queryAll() as $q ) {
				?>
				<tr>
					<td><?php echo $no++ ?></td>
					<td><?php echo $q['item_name'] ?></td>
					<td><?php echo number_format($q['jumlah']) ?></td>
					<td><?php echo number_format($q['harga']) ?></td>
					<td><?php echo number_format($q['harga']*$q['jumlah']) ?></td>
				</tr>


				<?php 
				$total_compliment+=$q['harga']*$q['jumlah'];
			}
			?>
			</table>
			<tr>
				<td align="center" colspan="2">
				<h2>Ringkasan</h2>
				</td>
			</tr>
			<tr>
				<td>Total Penjualan</td>
				<td align="right"><?php echo number_format($total)?></td>
			</tr>
			<tr>
				<td>Total Pengeluaran</td>
				<td align="right"><?php echo number_format($total_pengeluaran)?></td>
			</tr>
			<tr>
				<td>Total Komplimen</td>
				<td align="right"><?php echo number_format($total_compliment)?></td>
			</tr>

			<?php 
			$omset = $total+$total_pengeluaran+$total_compliment;
			?>
			<tr>
				<td>Total Omset </td>
				<td align="right" style="font-weight: bolder"><?php echo number_format($omset)?></td>
			</tr>
			<tr><td colspan="2"></td></tr>
			<tr><td colspan="2"></td></tr>
			<tr><td colspan="2"></td></tr>
			<tr>
				<td align="center">
					<br>
					<br>
					<br>
					<br>
					_________________<br>
					    Petugas
				</td>
				<td align="center">
					<br>
					<br>
					<br>
					<br>
					_________________<br>
					    Pemeriksa
				</td>
			</tr>

		</table>


		
			


		</td>
	</tr>
		
	</tr>


</table>