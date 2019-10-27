	<!-- 
-->
<applet name="jzebra" code="jzebra.PrintApplet.class" archive="jZebra/jzebra/jzebra.jar" width="0" height="0">
    <param name="printer" value="zebra">
</applet> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/jQuery.print.min.js"></script>


<?php 
$this->renderPartial('application.views.site.main');
?>
<h1>
<i class="fa fa-book"></i>
Laporan Pulsa
</h1>
<hr>
<?php 
	if (isset($_REQUEST[tanggal])){
			$date = $_REQUEST[tanggal];
		}else{
			$date = date("Y-m-d");
		}
?>
<form>
	<input type="hidden" name="r" value="sales/laporanpulsa"  />
			<label>
				Tanggal
			</label>
			<input type="text" value="<?php echo $date; ?>" style="display:inline;padding:5px" name="tanggal" class="tanggal" id="tanggal">
		

	<input type="submit" name="Cari" value="Cari" class="btn btn-primary"  />
	<input type="button" name="Cetak" value="Cetak" class="btn btn-primary"  onclick="$('#data-cetak').print()" />
	<a href="<?php echo Yii::app()->createUrl("Deposit/create") ?>" class="btn btn-primary" ><i class="fa fa-plus"></i> Deposit</a>
</form>
<br>
<?php 
if (isset($_REQUEST['tanggal'])){
?>

<?php 
$sisa =  DepositController::getSaldoAkhir($_REQUEST['tanggal']);
// echo $sisa;
?>
<div id="data-cetak">
<div class="alert alert-success">
	Sisa Deposit Sebelum <?php echo date("d M Y",strtotime($_REQUEST['tanggal'])) ?>
	<br>
	Rp. <?php echo number_format($sisa); ?>
</div>
<table class="items table">
	<thead>
		
		<tr style="color:white;font-weight: bolder;background-color: rgba(42, 63, 84,1)" >
			<td>No</td>
			<td>Tanggal Transaksi</td>
			<td>Nama Item</td>
			<td>QTY</td>
			<td>Total Keluar</td>
			<td>Total Masuk</td>
			<td>Provider</td>
			<td>Nomor</td>
			<td>Saldo Akhir</td>
		</tr>
		

	</thead>
	<tbody>
	<?php 
		
$branch_id = Yii::app()->user->branch();
$sql  = "

select * from (

SELECT
	i.id item_id,
	si.permintaan as nomor,
	i.item_name,
	pr.nama_provider,
	si.item_modal * si.quantity_purchased as item_total_cost,
	u.username nama_user,
	sum( si.quantity_purchased ) AS total_items,

	date AS tanggal
	
FROM
	sales s
	INNER JOIN sales_items si ON s.id = si.sale_id
	INNER JOIN users u ON s.inserter = u.id
	INNER JOIN items i ON i.id = si.item_id
	INNER JOIN provider pr on pr.id = i.provider_id
	INNER JOIN sales_payment sp ON sp.id = s.id
WHERE
	
 date(s.date) = '$date'
AND s.STATUS = 1
and branch = '$branch_id'
and i.is_pulsa = 1
GROUP BY
si.id

union all 

select 
'1' as item_id,
'0' as nomor,
'DEPOSIT' as item_name,
'DEPOSIT' as nama_provider,
nominal,
'admin',
'1',
created_at as tanggal
from deposit 
where 
date( created_at) = '$date' 

) as tbl

order by tbl.tanggal asc




	 ";
	 // echo $sql;
		$model = Yii::app()->db->createCommand($sql)->queryAll();



	?>
		<?php 
		
		if (isset($_REQUEST['id']) and isset($_REQUEST[tanggal]) ){
			$filter = "and si.item_id = $_REQUEST[id] and date(s.date)= '$_REQUEST[tanggal]' " ;
		}else{
			$filter = "and s.id = 999999999999999999999999999999999  " ;
		}
		$no = 1;
		$ttl = 0;
		$nilai_sisa = $sisa;
		foreach ($model  as $m ):
		?>
		<tr
		<?php 
		if ($m['item_name']=="DEPOSIT")
			echo "style='background-color:#78ff78'";
		else
			echo "style='background-color:#f7b7b7'";
		?>

		>
			<td><?php echo $no ?></td>
			<td><?php echo $m['tanggal']; ?></td>
			<?php 
			// $total_akhir = ($m['total_biaya']+$m['total_omset'])-$m['voucher'];
			?>
			<td><?php echo $m['item_name'] ?></td>
			<td><?php echo $m['total_items'] ?></td>
			<td style="text-align:right;">
				<?php 
				if ($m['item_name']!="DEPOSIT")
					echo number_format($m['item_total_cost']); 
				else
					echo "0";
				
				?></td>
			
			<td style="text-align:right;">
				<?php 
				if ($m['item_name']=="DEPOSIT")
					echo number_format($m['item_total_cost']);
				else
					echo "0";
				?>
			</td>
			
			<td style="text-align:left"><?php echo $m['nama_provider']; ?></td>
			<td style="text-align:left"><?php echo $m['nomor']; ?></td>
			<td align="right" >
				<?php 
			if ($m['item_name']=="DEPOSIT"){	
				$nilai_sisa = $nilai_sisa+$m['item_total_cost'];
			}else{
				$nilai_sisa = $nilai_sisa-$m['item_total_cost'];
			}

			echo  number_format($nilai_sisa);

			 ?></td>


			<td style="display:none;">
				<button class="cetak btn-primary btn" inserter="<?php echo $m['userid']; ?>" tanggal="<?php echo $date ?>" >
				<i class="fa fa-print" style="color:white!important"></i>
				Cetak Rekap</button>

				<button class="btn-primary btn" >
				<a 
				href="<?php echo Yii::app()->createUrl("sales/cetakrekap&tanggal_rekap=$date&noprint=true") ?>"
				 inserter="<?php echo $m['userid']; ?>" tanggal="<?php echo $date ?>" >
				<i class="fa fa-print" style="color:white!important"></i>
				Preview </a>
				</button>
 
 
				<!-- <a href="<?php 
				echo Yii::app()->createUrl("sales/rekapdetail",array("user"=>$m['userid'],"tanggal"=>$_REQUEST[tanggal])) ?>" class="btn-primary btn" inserter="<?php echo $m['userid']; ?>" tanggal="<?php echo $date ?>" >
				<i class="fa fa-print" style="color:white!important"></i>
				Cetak Rekap</a>
				<button 
				user-id='<?php echo $m['userid'] ?>'
				class="hapus btn-primary btn" seid="<?php echo $m['userid']; ?>" >Hapus</button>
 -->
			</td>
			
		</tr>
		<?php 

			
		$c+=$m['cash'];
		$tv+=$m['voucher'];
		$ttl+=$m['total_omset'];
		$tf+=$m['total_fisik'];
		$ts+=$m['total_fisik']-$total_akhir;
		$tb+=$m['total_biaya'];
		$ta+=$total_akhir;

		$no++;
		endforeach; ?>
		<tfoot >
			<tr>
				<td colspan="8"><h2>Sisa Saldo</h2> </td>
				<td align="right" style="color:green" ><h2><b><?php echo number_format($nilai_sisa)   ?></b></h2></td>
				
				
			</tr>
		</tfoot>
		
	</tbody>
</table>
</div>
<?php } ?>
<script>
$(document).ready(function(e){
	 $('.tanggal').datepicker({ dateFormat: 'yy-mm-dd',changeMonth:true,changeYear:true,});

	$('.hapus').click(function(){
		var c = confirm("Yakin ?");

		var id = $(this).attr("user-id");
		var tanggal = $("#tanggal").val();

	

		if (!c){return false};

		var href = "index.php?r=setor/HapusByTanggal&id="+id+"&tanggal="+tanggal;
		window.location.assign(href);
		

		});
	$('.cetak').click(function(){
		var tanggal = $(this).attr("tanggal");
		var inserter = $(this).attr("inserter");
		
		if(tanggal==''){
			alert('Pilih tanggal terlebih dahulu');
			return false;
		}else{
			$.ajax({
				url:'<?php echo Yii::app()->createUrl("sales/cetakrekap") ?>',
				data:'tanggal_rekap='+tanggal+"&inserter="+inserter,
				success: function(data){
					// alert(data);
					var json = jQuery.parseJSON(data);
					// $('#hasiljson').html(data);
					print_rekap(json,false);
					// console.log(data);
					
				},
				error: function(data){
					alert('error');
				}
			});
		}
	});
});
</script>