<link href="<?php  echo Yii::app()->request->baseUrl; ?>/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php  echo Yii::app()->request->baseUrl ?>/js/jQuery.print.min.js"></script>
<?php  
if (isset($_REQUEST['tgl1']) && isset($_REQUEST['tgl2']) ){
	$tgl1 = $_REQUEST['tgl1'];
	$tgl2 = $_REQUEST['tgl2'];

}else{

	$tgl1 = date("Y-m-d");
	$tgl2 = date("Y-m-d");
}
// echo $tgl1;
?>
<script type="text/javascript" src="<?php  echo Yii::app()->request->baseUrl; ?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php  echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="<?php  echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.css">
<style type="text/css">
#information-company{
    width: 100%;
    height: 150px;
    /*background-image:url('images/back-red.jpg')!important;*/
    margin-top: -20px;
}
#information-company img{

    float: left;
}
#information-company .company-name{
    float: left;
    margin-top:15px;
    margin-left:20px;
    color: white!important;
    color: black!important;
}
 #information-company .company-addres{
    float: left;
    top: 50px;
    left: 320px;
    /*margin-top:50px;*/
    /*margin-left:-330px;*/
    color: black!important;
    position: absolute;
}


</style>
<!-- <div id="information-company">
    <img src="<?php  echo Yii::app()->request->baseUrl; ?>/images/logo.png" style="width:300px;height:auto">      
    <p> <?php  $company = Branch::model()->findByPk(1);  ?>
        <h1  class="company-name"><?php  echo $company->branch_name ?></h1>
        <h4  class="company-addres"><?php  echo $company->address ?></h1>
    </p>
</div> -->
<hr>
	<a href="<?php  echo Yii::app()->request->baseUrl; ?>/index.php?r=sales"><img  class="no-print" style="height:50px;width:50px;float:right;" src="<?php  echo Yii::app()->request->baseUrl; ?>/images/home-logo.png"/></a>
		
	<title>
	Rekap Penjualan Barang		
	<?php  echo  "Periode : ".date("d M Y",strtotime($tgl1))." sampai ".date("d M Y",strtotime($tgl2)); ?>
	<?php  
	$branch_id = Yii::app()->user->branch();
	?>
 
	</title>
	<style>	
	/*.footer {
		color : red;
		text-width:bold;
		font-size:30px;
	}*/
	#header{
		text-align : left;
	}
	table{
		border-collapse: collapse;
	}
	table tr td{
		border: 1px solid black;
		padding: 2px;
	}
	</style>
	<link rel="stylesheet" type="text/css" href="<?php  echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />

	<?php  //Yii::app()->clientScript->registerCoreScript('jquery'); ?>
	<?php  //Yii::app()->getClientScript()->registerCoreScript('jquery.ui'); ?>
	<script type="text/javascript">
		$(document).ready(function(){
			// $("#tgl1").datetime
			$('#tgl1').datepicker({'dateFormat':'yy-mm-dd','showAnim':'fold','showOn':'button','buttonText':'Select form calendar','buttonImage':'images/calendar.png','buttonImageOnly':true});
			$('#tgl2').datepicker({'dateFormat':'yy-mm-dd','showAnim':'fold','showOn':'button','buttonText':'Select form calendar','buttonImage':'images/calendar.png','buttonImageOnly':true});

		});
	</script>


	<div class="data">
	
 	<h1 style="">Rekap Penjualan Item<br>
 	<!-- <i style="font-size:14px">Penjualan belum termasuk nilai voucher</i> -->
	<a style="color:red;text-decoration:none"></a>
	 </h1>
	 <?php  
		echo  "Periode : ".date("d M Y",strtotime($tgl1))." sampai ".date("d M Y",strtotime($tgl2)); 
	 ?>

	<div style="border-color:black;font-size:10px" >
	<form class="no-print">
		<input type="hidden" value="items/PrintDataStok" name="r">
	  Tanggal ke 1<input value="<?php  echo $tgl1 ?>" type="text" name="tgl1" id="tgl1">
	 Tanggal ke 2<input value="<?php  echo $tgl2 ?>" type="text" name="tgl2" id="tgl2">
			<input type="submit" value="cari" class="btn btn-primary">
				<input type="button" value="cetak" class="no-print btn btn-primary" onclick="print()" />

	</form>
	<table id="rekap" border="1" cellpadding="5"  style="border-collapse:collapse;width:100%;font-size:12px;border:1px solid black" colspan = "3" rowspan="3">
	<thead style="font-weight: bolder">
	<tr>
	<td>No</td>
	<td>Nama</td>
	<?php 
	$date1 = $tglheader;
	$date2 = $tgl2header;
	// $diff = (strto_di($date2) - strtotime($date1));
	$dif = Yii::app()->db->createCommand()
	->select("DATEDIFF('$tgl2','$tgl1') as selisih ")
	->from("sales")
	->limit("1")
	->queryRow();

	$days = $dif['selisih'];
	
	if ($tglheader=="" && $tglhader2==""){ 
		$hari = date('d'); 
		$bulan = date('m'); 
		$tahun = date('Y');
	}
	else{
		$hari =  date_format(date_create($tglheader), 'd');
		$bulan =  date_format(date_create($tglheader), 'm');
		$tahun =  date_format(date_create($tglheader), 'Y');	
	}

	$h = $hari;
	$d = $days;
	$k=1;
	$sql_tgl = "select * from time_dimension where date(db_date) >= '$tgl1' and date(db_date)<='$tgl2' ";
	$q_tgl = Yii::app()->db->createCommand($sql_tgl)->queryAll();
	$jmb_bris = count($q_tgl);

		foreach ($q_tgl as $qtgl) {
		?>
		<td><?php  echo date('d-m-Y',strtotime($qtgl['db_date'] )); ?>
		</td>
		<?php  } ?>
			<td>QTY</td>
			<td>Harga</td>
			<td>Total Penjualan</td>
			<td>Diskon</td>
			<td>Potongan</td>
			<td>Grand</td>
			<td>Harga Modal</td>
			<td>Total Modal</td>
			<!-- <td>Grand Total </td> -->
			<!-- <td>Keterangan</td> -->
		</tr>
		</thead>
		<?php 
		$sql2 = "
		SELECT
			si.sale_id slid,
			i.id AS iid,
			concat(i.item_name,' - ',iss.nama_satuan) AS item_name,
			si.item_price AS unit_price,
			si.item_modal AS item_modal
		FROM
			items i 
			inner join items_satuan iss on iss.item_id = i.id
			inner join sales_items  si  on  si.item_id = i.id and iss.id = si.item_satuan_id
			inner join sales  s on   s.id = si.sale_id
			#inner join si.satuan  = iss.id 
		WHERE
		 s.STATUS = 1
		AND date(s.date) >= '$tgl1'
		AND date(s.date) <= '$tgl2'
		and branch = '$branch_id'
		GROUP BY
			si.item_id,
			si.item_price,
			iss.id

		UNION ALL 

		SELECT
			si.sale_id slid,
			i.id_paket AS iid,
			i.nama_paket AS item_name,
			si.item_price AS unit_price,
			si.item_modal AS item_modal
		FROM
			paket i inner join sales_items_paket  si  on  si.item_id = i.id_paket
			inner join sales  s on   s.id = si.sale_id
		WHERE
		 s.STATUS = 1
		AND date(s.date) >= '$tgl1'
		AND date(s.date) <= '$tgl2'
		and branch = '$branch_id'
		
		GROUP BY
			si.item_id,
			si.item_price

		ORDER BY
			item_name ASC


			";
		$items = Yii::app()->db->createCommand($sql2)->queryAll();
		$no = 0;
		$km =1;
		// $sql_vouc = "select sum(voucher) as voucher from sales s inner join sales_payment sp
		// on sp.id = s.id 
		// where 
		// status = 1
		// and
		// date(s.date) >= '$tgl1' AND date(s.date) <= '$tgl2' ";
		// // echo $sql_vouc;
		// $query_voucher = Yii::app()->db->createCommand($sql_vouc)->queryRow();
		$query_voucher = 0;




		foreach($items as $values){
			$sqldis = "
			SELECT
				sale_id,
				item_id,
				si.item_modal item_modal,
				SUM(
					item_discount / 100 * si.quantity_purchased
				) * item_price diskon
			FROM
				sales_items si,
				sales s,
				items i
			WHERE
				si.sale_id = s.id
			AND si.quantity_purchased != 0
			AND s. STATUS = 1
			AND date(s.date) >= '$tgl1'
			AND date(s.date) <= '$tgl2'
			AND si.item_id = '$values[iid]'
			AND si.item_id = i.id
			AND si.item_price = $values[unit_price]
			and branch = '$branch_id'
		
			GROUP BY
				item_id


			UNION ALL 

			SELECT
				sale_id,
				item_id,
				si.item_modal item_modal,
				SUM(
					item_discount / 100 * si.quantity_purchased
				) * item_price diskon
			FROM
				sales_items_paket si,
				sales s,
				paket i
			WHERE
				si.sale_id = s.id
			AND si.quantity_purchased != 0
			AND s. STATUS = 1
			AND date(s.date) >= '$tgl1'
			AND date(s.date) <= '$tgl2'
			AND si.item_id = '$values[iid]'
			AND si.item_id = i.id_paket
			AND si.item_price = $values[unit_price]
			and branch = '$branch_id'
		
			GROUP BY
				item_id

			";
			$getdiscount = Yii::app()->db->createCommand($sqldis)->queryRow();
		$jml=0;
		$no++?>
		<tr style="width:100px;overflow:visible;" >
			<td><?php echo $no?></td>
			<td style="text-transform:uppercase"><?php echo $values["item_name"]?></td>
		
		<?php 
		$km = 1;
		// for($a=$hari;$a<=$hari+$days;$a++){
		foreach ($q_tgl as $qtgl) {
			// echo "123";
			
			$sql33 = "SELECT
				date(date) waktu,
				item_name,
				sum(quantity_purchased) qty,
				ifnull(item_discount,0) diskon,
				ifnull(si.item_modal,0) item_modal,
				ifnull(si.item_price,0) item_price,
				(
					si.item_price * sum(quantity_purchased)
				) AS 'total'
			FROM
				sales_items si,
				items i,
				sales s
			WHERE
				s.STATUS = 1
			AND date(date) = '".$qtgl[db_date]."' 
			AND si.item_id = i.id
			AND si.sale_id = s.id
			AND si.item_id = '".$values[iid]."'  
			and si.item_price = '".$values[unit_price]."' 

			UNION 

			SELECT
				date(date) waktu,
				nama_paket,
				sum(quantity_purchased) as qty,
				ifnull(item_discount,0) diskon,
				ifnull(si.item_modal,0) item_modal,
				ifnull(si.item_price,0) item_price,
				(si.item_price * sum(quantity_purchased) ) AS 'total'
			FROM
				sales_items_paket si,
				paket i,
				sales s
			WHERE
				s.STATUS = 1
			AND date(date) = '".$qtgl[db_date]."' 
			AND si.item_id = i.id_paket
			AND si.sale_id = s.id
			AND si.item_id = '".$values[iid]."'  
			and si.item_price = '".$values[unit_price]."' 

			order by waktu desc





			";

			$summary = Yii::app()->db->createCommand($sql33)->queryRow();
			?>
			<td  align="center"><?php 
			// echo "123";
			// var_dump($summary["waktu"]);

			if ($qtgl[db_date]==$summary["waktu"]){
				echo $summary['qty'];
				$jml += $summary['qty'];
			}else{
				echo "&nbsp; ";
				// .$summary["waktu"]." - ".$qtgl[db_date];
			}
			// echo $summary["waktu"];
			// echo $lengkap;
			?></td>
		<?php }?>
			<td align="right"><?php  echo $jml ?></td>
			<td align="right"><?php echo number_format(abs($values['unit_price']))?></td>
			<td align="right"><?php echo number_format(abs($values['unit_price'])*$jml )  ?></td>
			<td align="right"><?php  echo number_format($getdiscount['diskon']) ?></td>
			<td align="right"><?php  echo number_format(0) ?></td>
			<td align="right"><?php echo number_format(abs($values['unit_price'])*$jml - ($getdiscount['diskon']) )  ?></td>
			<td align="right"><?php echo number_format(abs($values['item_modal']))?></td>
			<td align="right"><?php echo number_format(abs($values['item_modal'])*$jml)?></td>
		</tr>
		<?php 
		$total_qty += $jml;
		$total_dsc += $dsc;
		$total_voucher = $query_voucher['voucher'];
	 	$total_diskon+=$getdiscount['diskon'];
		$total_price += ($values['unit_price']);
		$total_bruto += $values['unit_price']*$jml;
		$total_netto += $values['unit_price']*$jml - $getdiscount['diskon'] ;
		$total_modal += $values['item_modal']*$jml;
		// $total_tenant += (Outlet::model()->find("kode_outlet=$id")->persentase_hasil*(abs($values['unit_price'])*$jml-$dsc)/100);
		// $total_bumi += ((100-(Outlet::model()->find("kode_outlet=$id")->persentase_hasil))*(abs($values['unit_price'])*$jml-$dsc)/100);
	}


	?>
	<tr style="border:0px solid black">
	<?php 
	// for ($zzz=1;$zzz<=$jmb_bris;$zzz++) {?>
	
	<td colspan="<?php  echo $jmb_bris+2 ?>" style="border:1px solid black">&nbsp;</td>

	<?php  //}?>
	<td align="right" style="font-weight: bolder;"><?php echo number_format($total_qty)?></td>
	<td align="right" style="font-weight: bolder;">-</td>
	<td align="right" style="font-weight: bolder;"><?php echo number_format($total_bruto)?></td>
	<td align="right" style="font-weight: bolder;"><?php echo number_format($total_diskon); ?></td>
	<td align="right" style="font-weight: bolder;"><?php echo number_format($total_voucher); ?></td>
	<td align="right" style="font-weight: bolder;"><?php echo number_format($total_netto-$total_voucher); ?></td>
	
	
	<td align="right" style="font-weight: bolder;" class="footer"></td>
	<td align="right" style="font-weight: bolder;" class="footer"><?php echo number_format($total_modal)?></td>
	</tr>
	</table>
	</div>

	</div>
	<style>
	@media print
	{    
		.no-print, .no-print *
		{
			display: none !important;
		}
	}
	</style>
