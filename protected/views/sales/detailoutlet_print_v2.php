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
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/xlsx/xlsx.full.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.css">
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
    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/logo.png" style="width:300px;height:auto">      
    <p> <?php $company = Branch::model()->findByPk(1);  ?>
        <h1  class="company-name"><?php echo $company->branch_name ?></h1>
        <h4  class="company-addres"><?php echo $company->address ?></h1>
    </p>
</div> -->
<hr>
	<a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=sales"><img  class="no-print" style="height:50px;width:50px;float:right;" src="<?php echo Yii::app()->request->baseUrl; ?>/images/home-logo.png"/></a>
		
	<title>
	Rekap Penjualan Barang		
	<?php echo  "Periode : ".date("d M Y",strtotime($tgl1))." sampai ".date("d M Y",strtotime($tgl2)); ?>
 
	</title>
	<style>	
	.footer {
		color : red;
		text-width:bold;
		font-size:30px;
	}
	#header{
		text-align : left;
	}
	</style>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<script type="text/javascript">
		$(document).ready(function(){
			// $("#tgl1").datetime
			$('#tgl1').datepicker({
				showOn: "button",
				buttonImage: "https://jqueryui.com/resources/demos/datepicker/images/calendar.gif",
				buttonImageOnly: true,
				buttonText: "Select date",
				dateFormat: "yy-mm-dd"
			});
			$('#tgl2').datepicker({showOn: "button",
          buttonImage: "https://jqueryui.com/resources/demos/datepicker/images/calendar.gif",
          buttonImageOnly: true,
          buttonText: "Select date",
		  dateFormat: "yy-mm-dd"

		});

		});


		function htmlTableToExcel(type){
			var data = document.getElementById('rekapmenu_table');
			var excelFile = XLSX.utils.table_to_book(data, {sheet: "sheet1"});
			XLSX.write(excelFile, { bookType: type, bookSST: true, type: 'base64' });
			const urlParams = new URLSearchParams(window.location.search);
			const tgl1 = urlParams.get('tgl1')
			const tgl2 = urlParams.get('tgl2')
			XLSX.writeFile(excelFile, 'Rekap_penjualan_barang_'+$("#tgl1").val()+"_"+$("#tgl2").val() + "."+type);
		}
	</script>


<div class="data">

<h1 style="">Rekap Penjualan Item<br>
<!-- <i style="font-size:14px">Penjualan belum termasuk nilai voucher</i> -->
<a style="color:red;text-decoration:none"></a>
<?php echo  "Periode : ".date("d M Y",strtotime($tgl1))." sampai ".date("d M Y",strtotime($tgl2));  ?>

<div style="border-color:black;font-size:10px" >
<form>
<input type="hidden" value="sales/rekapmenu" name="r">
	Tanggal ke 1<input value="<?php echo $tgl1; ?>" type="text" name="tgl1" id="tgl1">
	Tanggal ke 2<input value="<?php echo $tgl2; ?>" type="text" name="tgl2" id="tgl2">
	Customer 
	<?php  $nilai = Customer::model()->findAll(" store_id = ".Yii::app()->user->store_id()." ");?>
	<select id="customer" name="customer" class="tobe-select2 " style="display: inline;">
		<option value="">Semua Member</option>
		<?php foreach($nilai as $k): ?>
		<option <?php if ($k->nama==$_REQUEST['customer']) echo "selected" ?> value="<?php echo $k->nama ?>">
		<?php echo $k->nama ?>
			
		</option>
		<?php endforeach; ?>
	</select>
	Kategori Items 
	<?php  $nilai = Categories::model()->findAll(" store_id = ".Yii::app()->user->store_id()." ");?>
	<select id="customer" name="category" class="tobe-select2 " style="display: inline;">
		<option value="">Semua Kategori</option>
		<?php foreach($nilai as $k): ?>
		<option <?php if ($k->id==$_REQUEST['category']) echo "selected" ?> value="<?php echo $k->id ?>">
		<?php echo $k->category ?>
			
		</option>
		<?php endforeach; ?>
	</select>

	<input type="submit" value="cari">
</form>

<table id="rekapmenu_table" border="1" cellpadding="5"  style="border-collapse:collapse;width:100%;font-size:12px;border:1px solid black" colspan = "3" rowspan="3">
	<tr>
		<td>No</td>
		<td>Nama</td>
		<td>Kategori</td>
		<td>Sub Kategori</td>
		<?php
		$date1 = $tglheader;
		$date2 = $tgl2header;
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
		// echo $sql_tgl;
		$q_tgl = Yii::app()->db->createCommand($sql_tgl)->queryAll();
		$jmb_bris = count($q_tgl);
		$branch_id = Yii::app()->user->branch();
		$whereCustomer = isset($_REQUEST['customer']) && !empty($_REQUEST['customer']) ? " and s.nama = '".$_REQUEST['customer']."'" : ""; 
		$whereCategory = isset($_REQUEST['category']) && !empty($_REQUEST['category']) ? " and i.category_id = '".$_REQUEST['category']."'" : ""; 
		foreach ($q_tgl as $qtgl) { ?>
		<td><?php echo date('d-m-Y',strtotime($qtgl[db_date] )); ?></td>
		<?php } ?>

		
			<td>Total QTY</td>
			<td>Harga Jual</td>
			<td>Total Harga Penjualan</td>
			<td>Diskon</td>
			<td>Voucher/Potongan</td>
			<td>Omzet</td>
			<td>Harga Modal</td>
			<td>Total Harga Modal</td>
			<!-- <td>Grand Total </td> -->
			<!-- <td>Keterangan</td> -->
		</tr>
		<?php
			// $connection = Yii::app()->db;
			// ` = $connection->createCommand("		
			// select  from
			// 
			// where
			// 
			// group by 
			// order by i.item_name asc
			// ");
		// $items = Items::model()->findAll("kode_outlet=$id",array('order' => 'id')); 
		// echo $tgl1;
		// echo $tgl2;
		
		$items = Yii::app()->db->createCommand()
			->select('si.sale_id slid,i.id iid,
				i.item_name item_name, 
				c.category category_name, 
				sc.nama subcategory_name, 
				si.item_price unit_price, 
				si.item_modal item_modal, 
				si.item_modal modal
				')
			->from(' items i, sales s, sales_items si, categories c, motif sc ')
			->where("s.id = si.sale_id
				and
				si.item_id = i.id 
				
				and
				s.status=1
				and
				date(s.date) >= '$tgl1' AND date(s.date) <= '$tgl2' 
				and s.branch = '$branch_id'
				and is_sales_item_bahan is null
				{$whereCustomer}
				{$whereCategory}
				")
			->group("si.item_id, si.item_price, si.item_modal ")
			->order("i.item_name asc")
			->queryAll();
				// and
				// date(s.date) > '$tgl1' and date(s.date) < '$tgl2'  				
		// print_r($items);
		$no = 0;
		$km =1;
		$sql_vouc = "select sum(voucher) as voucher from sales s inner join sales_payment sp
		on sp.id = s.id 
		where 
		status = 1
		and s.branch = '$branch_id'
		{$whereCustomer}
		and
		date(s.date) >= '$tgl1' AND date(s.date) <= '$tgl2' ";
		// echo $sql_vouc;
		$query_voucher = Yii::app()->db->createCommand($sql_vouc)->queryRow();
		foreach($items as $values){
			$sqldis = "
				SELECT sale_id,item_id,
				SUM(item_discount/100*si.quantity_purchased)*item_price diskon
				FROM 
				sales_items si,sales s,items i
				WHERE 
				si.sale_id = s.id
				AND
				si.quantity_purchased !=0
				AND
				s.status = 1
				and 
				date(s.date) >= '$tgl1' AND date(s.date) <= '$tgl2' 
				
				{$whereCustomer}
				{$whereCategory}
				AND si.item_id = $values[iid]  AND si.`item_id` = i.`id`
					and si.item_price =  $values[unit_price]   and si.item_modal = $values[item_modal]
				GROUP BY item_id
			";
			$getdiscount = Yii::app()->db->createCommand($sqldis)->queryRow();
		$jml=0;$no++?>
		<tr style="width:100px;overflow:visible;" >
			<td><?php echo $no?></td>
			<td style="text-transform:uppercase"><?php echo $values["item_name"]?></td>
			<td style="text-transform:uppercase"><?php echo $values["category_name"]?></td>
			<td style="text-transform:uppercase"><?php echo $values["subcategory_name"]?></td>
		
		<?php
		$km = 1;
		foreach ($q_tgl as $qtgl) {
			$summary = Yii::app()->db->createCommand()
			->select("date(date) waktu,item_name,		sum(if(si.item_price<0,-quantity_purchased,quantity_purchased)) qty,item_discount diskon,si.item_price,(si.item_price*sum(quantity_purchased)) as 'total'")
			->from('sales_items si,items i,sales s')
			->where("
				s.status = 1 
				{$whereCustomer}
				{$whereCategory}
				and date(date) = '".$qtgl[db_date]."' 
				and si.item_id = i.id 
				and si.sale_id = s.id  
				and si.item_id = '".$values[iid]."' 
				and si.item_price = '".$values[unit_price]."' 
				and si.item_modal = '".$values[item_modal]."' 
				
			")
			->group("i.id ,date(s.date)")
			->queryRow();
		?>
		<td  align="center">
		<?php
			if ($qtgl[db_date]==$summary["waktu"]){
				echo floatval($summary['qty']);
				$jml += floatval($summary['qty']);
			}else{
				echo " &nbsp; ";
			}
		 ?>
		</td>
		<?php } ?>
			<td><?php echo $jml?></td>
			<td><?php echo number_format(abs($values['unit_price']))?></td>
			<td><?php echo number_format(abs($values['unit_price'])*$jml )  ?></td>
			<td><?php echo number_format($getdiscount['diskon']) ?></td>
			<td><?php echo number_format(0) ?></td>
			<td><?php echo number_format(abs($values['unit_price'])*$jml - ($getdiscount['diskon']) )  ?></td>
			<td><?php echo number_format(($values['modal']))?></td>
			<td><?php echo number_format(($values['modal'])*$jml)?></td>
		</tr>
		<?php 
		$total_qty += $jml;
		$total_dsc += $dsc;
		$total_voucher = $query_voucher['voucher'];
	 	$total_diskon+=$getdiscount['diskon'];
		$total_price += ($values['unit_price']);
		$total_bruto += $values['unit_price']*$jml;
		$total_netto += $values['unit_price']*$jml - $getdiscount['diskon'] ;
		$total_modal += $values['modal']*$jml;
		 ?>
	<?php } ?> 

	
	<tr style="border:0px solid black">
	<td></td>
	<td></td>	
	<td></td>	
	<td></td>	
	<?php
	 for ($zzz=1;$zzz<=$jmb_bris;$zzz++) {?>
	
	<td style="border:1px solid black"></td>
	<?php } ?>
	<td><?php echo number_format($total_qty)?></td>
	<td>-</td>
	<td><?php echo number_format($total_bruto)?></td>
	<td><?php echo number_format($total_diskon); ?></td>
	<!-- <td><?php echo number_format($total_voucher); ?></td> -->
	<td><?php echo 0 ?></td>
	<td><?php echo number_format($total_netto-$total_voucher); ?></td>
	
	
	<td class="footer"></td>
	<td class="footer"><?php echo number_format($total_modal)?></td>
	</tr>


	</table>
	</div>

	</div>

	<input type="button" value="cetak" class="no-print" onclick="print()" />
	<input type="button" value="cetak Excel" class="no-print" onclick="htmlTableToExcel('xlsx')" />
	<style>
	@media print
	{    
		input{
			display:none!important;
		}
		.no-print, .no-print *
		{
			display: none !important;
		}
	}
	</style>

