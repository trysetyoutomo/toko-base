	<!-- 
-->
<applet name="jzebra" code="jzebra.PrintApplet.class" archive="jZebra/jzebra/jzebra.jar" width="0" height="0">
    <param name="printer" value="zebra">
</applet> 
<?php 
$this->renderPartial('application.views.site.main');
?>
<h1>
Laporan Kasir
</h1>
<hr>
<?php 
	if (isset($_REQUEST[tanggal])){
			$date = $_REQUEST[tanggal];
		}else{
			$date = date("Y-m-d");
		}
?>
<form method="post">
	<input type="hidden" name="r" value="sales/rekap"  />
			<label>
				Tanggal
			</label>
			<input type="text" value="<?php echo $date; ?>" style="display:inline;padding:5px" name="tanggal" class="tanggal" id="tanggal">
			<?php
			// 	$this->widget('zii.widgets.jui.CJuiDatePicker', array(
			// 		'name'=>'tanggal',
			// 		'attribute'=>'date',
			// //		'model'=>$model,
			// 		// additional javascript options for the date picker plugin
			// 		'options'=>array(
			// 			'dateFormat'=>'yy-mm-dd',
			// 			'showAnim'=>'fold', // 'show' (the default), 'slideDown', 'fadeIn', 'fold'
			// 			'showOn'=>'button', // 'focus', 'button', 'both'
			// 			'buttonText'=>Yii::t('ui','Select form calendar'),
			// 			'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar.png',
			// 			'buttonImageOnly'=>true,
			// 		),
			// 		'value'=>$date,
			// 		'htmlOptions'=>array(
			// 			'id'=>'tanggal',
			// 			'style'=>'height:20px;;width:80px;display:inline-block'
			// 		),
			// 	));
				
			// $this->renderPartial('summary',array('summary'=>$summary));

			?>
	<input type="submit" name="Cari" value="Cari" class="btn btn-primary" style="display:inline" />
	<!-- <input type="button" name="kirim" value="Kirim Email" class="btn btn-primary btn-kirim-email" style="display:inline" /> -->
</form>
<br>
<?php 
if (isset($_REQUEST['tanggal'])){
?>
<table class="items table">
	<thead>
		
		<tr style="color:white;font-weight: bolder;background-color: rgba(42, 63, 84,1)" >
			<td>No</td>
			<td>Petugas </td>
			<td>Saldo Cash Awal </td>
			<td>Total Cash </td>
			<td>Total Omset </td>
			<td>Total Biaya Bank </td>
			<td>Total Voucher </td>
			<td>Total Akhir </td>
			<td>Total Uang Fisik harus Ada </td>
			<td>Total Uang Fisik</td>
			<td>Selisih</td>
			<td>Cetak</td>
		</tr>
		

	</thead>
	<tbody>
	<?php 
		
$branch_id = Yii::app()->user->branch();
$sql  = "
select 


nama_user,
sum(sale_total_cost) total_omset,
sum(sale_total_cost) total_omset,
sum(adt_cost) as total_biaya,
sum(voucher) as voucher,
sum(cash) as cash,
total_fisik as total_fisik,
userid,
total_awal
		
from 
(
SELECT
	se.total_awal,
	sp.voucher voucher,
	sp.cash cash,
	adt_cost,
	u.username nama_user,
	s.inserter sia,
	se.id seid,
	u.id userid,
	se.total total_fisik,
	s.bayar,
	s.TABLE,
	inserter,
	s.id,
	sum(si.quantity_purchased) AS total_items,
	date as tanggal,
	sum(
		si.item_price * si.quantity_purchased
	) sale_sub_total,
	s.sale_tax,
	s.sale_service,
	s.sale_discount,
	sum(
		(
			(
				si.item_price * si.quantity_purchased
			) + (si.item_service) + (si.item_tax) - (
				si.item_discount * (
					si.item_price * si.quantity_purchased
				) / 100
			)
		)
	) sale_total_cost
FROM
	sales s
INNER JOIN sales_items si ON s.id = si.sale_id
INNER JOIN users u ON s.inserter = u.id
INNER JOIN items i ON i.id = si.item_id
INNER JOIN sales_payment sp ON sp.id = s.id
LEFT JOIN (
	SELECT DISTINCT
		tanggal,
		user_id,
		total,
		total_awal,
		id
	FROM
		setor
) AS se ON se.tanggal = date(s.date)
AND se.user_id = s.inserter
WHERE
	
 date(s.date) = '$date'
AND s. STATUS = 1
and branch = '$branch_id'
GROUP BY
	s.inserter,s.id
) AS A 

group by A.nama_user

	 ";
		$model = Yii::app()->db->createCommand($sql)->queryAll();



	?>
		<?php 
		
		if (isset($_REQUEST['id']) and isset($_REQUEST[tanggal]) ){
			$filter = "and si.item_id = $_REQUEST[id] and date(s.date)= '$_REQUEST[tanggal]' " ;
		}else{
			$filter = "and s.id = 999999999999999999999999999999999  " ;
		
		}
		// $date = date('Y-m-d');
			// $sql = "select *,s.id sid from sales_items si inner join sales s on s.id = si.sale_id 
			// inner join items i on i.id = si.item_id
			
			// where  s.status =  1   $filter  ";
			// $model = Yii::app()->db->createCommand($sql)->queryAll();
				$no = 1;
				$ttl = 0;
		foreach ($model  as $m ):
		?>
		<tr >
			<td><?php echo $no ?></td>
			<td><?php echo $m['nama_user']; ?></td>
			<td><?php echo number_format($m['total_awal']); ?></td>
			<td><?php echo number_format($m['cash']); ?></td>
			<?php 
			// $total_akhir = ($m['total_biaya']+$m['total_omset'])-$m['voucher'];
			$total_akhir = ($m['total_biaya']+$m['total_omset'])-$m['voucher'];
			?>
			<td style="text-align:left"><?php echo number_format($m['total_omset']); ?></td>
			<td style="text-align:left"><?php echo number_format($m['total_biaya']); ?></td>
			<td style="text-align:left"><?php echo number_format($m['voucher']); ?></td>
			<td style="text-align:left"><?php echo number_format($total_akhir); ?></td>

			<td style="text-align:left"><?php echo number_format($total_akhir+$m['total_awal']); ?></td>
			<td style="text-align:left"><?php echo number_format($m['total_fisik']); ?></td>
			<td style="text-align:left"><?php echo number_format($m['total_fisik']-($total_akhir+$m['total_awal'])); ?></td>

<!-- 				
 -->
			<td>
				<button class="cetak btn-primary btn" inserter="<?php echo $m['userid']; ?>" tanggal="<?php echo $date ?>" >
				<i class="fa fa-print" style="color:white!important"></i>
				Cetak Rekap</button>

				<a 
				href="<?php echo Yii::app()->createUrl("sales/cetakrekap&tanggal_rekap=$date&noprint=true&inserter=$m[userid]") ?>"
				class="btn-primary btn" inserter="<?php echo $m['userid']; ?>" tanggal="<?php echo $date ?>" >
				<i class="fa fa-print" style="color:white!important"></i>
				Preview </a>
				<?php 
				$filter = "user_id = '$m[userid]' and date(tanggal)='$date' ";
				$se = Setor::model()->find($filter);
				// echo $filter;

				if ($se){
					?>
					<a 
					href="<?php echo Yii::app()->createUrl("sales/hapusregister&tanggal_rekap=$date&noprint=true&inserter=$m[userid]") ?>"
					class="btn-primary btn btn-hapus-register" inserter="<?php echo $m['userid']; ?>" tanggal="<?php echo $date ?>" >
					<i class="fa fa-refresh" style="color:white!important"></i>
					Hapus Register </a>
				<?php 
				}
				?>
 
 
 
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

			
		$t_awal+=$m['total_awal'];
		$c+=$m['cash'];
		$tv+=$m['voucher'];
		$ttl+=$m['total_omset'];
		$tf+=$m['total_fisik'];
		$tf_hrs+=$total_akhir+$m['total_awal'];
		$ts+=$m['total_fisik']-($total_akhir+$m['total_awal']);
		$tb+=$m['total_biaya'];
		$ta+=$total_akhir;

		$no++;
		endforeach; ?>
		<tfoot>
			<tr>
				<td colspan="2">Total</td>
				<td><?php echo number_format($t_awal); ?></td>
				<td><?php echo number_format($c); ?></td>
				<td><?php echo number_format($ttl); ?></td>
				<td><?php echo number_format($tb); ?></td>
				<td><?php echo number_format($tv); ?></td>
				<td><?php echo number_format($ta); ?></td>
				<td><?php echo number_format($tf_hrs); ?></td>
				<td><?php echo number_format($tf); ?></td>
				<td><?php echo number_format($ts); ?></td>
				<td></td>
				
			</tr>
		</tfoot>
		
	</tbody>
</table>
<?php } ?>
<script>
$(document).ready(function(e){
	 $('.tanggal').datepicker({ dateFormat: 'yy-mm-dd',changeMonth:true,changeYear:true,});

	$('.btn-hapus-register').click(function(e){
		var c = confirm(" saldo awal dan saldo akhir transaksi kasir ini pada tanggal dipilih akan dihapus, Yakin ? ");
		if (!c) return false;
	});
	$('.btn-kirim-email').click(function(){
		$.ajax({
		url:'<?php echo Yii::app()->createUrl("site/KirimEMail") ?>',
		// data:'tanggal_rekap='+tanggal+"&inserter="+inserter,
		success: function(data){
			alert(data);
			

		},
		error: function(data){
		alert('error');
		}
		});

	});


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