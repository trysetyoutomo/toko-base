<title>
	<?php 
	echo "Faktur ID #$_REQUEST[id]";
	?>
</title>
<body onload=" $('#faktur').print();">
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/jQuery.print.min.js"></script>
<style type="text/css">
	@media print{

		html{
			padding: 0px!important;
			margin:0px!important;
			
		}
		.nav_menu,button{

			display: none!important;
		}
	}	

	@font-face {
	    font-family: myFirstFont;
	    src: url("font/Courier Prime.ttf");
	}

	table *{
		padding: 0px!important;
		margin:0px!important;
	/*	font-family: myFirstFont;*/
	}
	thead tr td,.bodiku tr td{
		padding: 0px;
		border:1px solid black;
	}
	tfoot tr td{
		border:0px solid black;
		/*padding*/: 1px!important;
	}
	#faktur .x{
		float: left;
	}
</style>
<?php 
$bid = Yii::app()->user->branch();
$branch = Branch::model()->findByPk($bid);
$parameter = Parameter::model()->findByPk(1);
$sql = "

select 
s.id id,
nama,
customer_id,
s.bayar,s.table,inserter, s.comment comment,
date,
tanggal_jt,
s.waiter waiter,

s.sale_voucher voucher,
sisa,
bayar_real

from sales s, users u , sales_payment sp
where
 s.id = $id
and inserter = u.id
group by s.id 



";
// union  all 

// SELECT
// 	*
// FROM
// 	(
// 		SELECT
// 			nama,
// 			customer_id,

// 		IF (
// 			bayar < sum(
// 				si.item_price * si.quantity_purchased
// 			)
// 			OR bayar = 0,
// 			'Kredit',
// 			'Lunas'
// 		) AS sb,
// 		s.bayar,
// 		s.TABLE,
// 		inserter,
// 		s. COMMENT COMMENT,
// 		s.id,
// 		sum(si.quantity_purchased) AS total_items,
// 		date,
// 		tanggal_jt,
// 		s.waiter waiter,
// 		sum(
// 			(
// 				si.item_price * si.quantity_purchased
// 			) - (
// 				item_modal * si.quantity_purchased
// 			)
// 		) untung,
// 		sum(
// 			si.item_price * si.quantity_purchased
// 		) sale_sub_total,
// 		sum(
// 			item_modal * si.quantity_purchased
// 		) sale_sub_modal,
// 		sum(si.item_service) sale_service,
// 		sum(si.item_tax) sale_tax,
// 		s.sale_voucher voucher,
// 		sum(
// 			si.item_discount / 100 * (
// 				si.item_price * si.quantity_purchased
// 			)
// 		) sale_discount,
// 		sum(
// 			(
// 				(
// 					si.item_price * si.quantity_purchased
// 				) + si.item_service + (si.item_tax) - (
// 					si.item_discount / 100 * (
// 						si.item_price * si.quantity_purchased
// 					)
// 				)
// 			)
// 		) - (sp.voucher) sale_total_cost
// 	FROM
// 		sales s,
// 		sales_items_paket si,
// 		users u,
// 		paket i,
// 		sales_payment sp
// 	WHERE
// 		sp.id = s.id
// 	AND i.id_paket = si.item_idp
// 	AND s.id = si.sale_id
// 	AND s.id = $id
// 	AND inserter = u.id
// 	GROUP BY
// 		s.id
// 	) data2


$model = Yii::app()->db->createCommand($sql)->queryRow();
?>
<style type="text/css">
	.page{
		border-collapse: collapse;
		border: 0px solid black;
		margin-left: 15px;
		/*letter-spacing: 10px;*/
	}
	.space{
		width: 10px;
	}
	table{
		border: 1px solid white;
	}

</style>
	<?php 
			$sql_d = SalesController::sqlSalesDetail($id);
			// var_dump()
			// echo $sql_d;
			$no = 1;
			$model2 = Yii::app()->db->createCommand($sql_d)->queryAll();
			$has_header = true;
			$total = count($model2);
			if ($total>=14){		
				// $has_header = true;
			$halaman = 14; // per halaman
			}else if ($total>8 && $total<=10){		
				// $has_header = false;
				$halaman = 8; // per halaman

			}else {
				// $has_header = true;
				$halaman = 9;
			 // per halaman
			}
			// $page= 1;
			// $mulai = ($page>1) ? ($page * $halaman) - $halaman : 0;
			
			$pages = ceil($total/$halaman); 
			// if ($jm>10){	
				// $pg +
			// 	$page = floor($jm/$perpage);
			// 	// $page = $jm - 
			// }else{
			// 	$page = 1;
			// }
			// echo $pages;
			?>
<div id="faktur" >
<?php
$ukuranKertas = SiteController::getConfig("ukuran_kertas"); 
if ($ukuranKertas == "24cmx14cm"){
	$this->renderPartial("28x14",
		array(
			"pages"=>$pages,
			"branch"=>$branch,
			"model2"=>$model2,
			"model"=>$model,
			"no"=>$no,
			"halaman"=>$halaman,
			"mulai"=>$halaman,
			"sql_d"=>$sql_d
		)
	);
}else if  ($ukuranKertas == "12cmx14cm"){
	$this->renderPartial("12x14",
		array(
			"has_header"=>$has_header,
			"pages"=>$pages,
			"branch"=>$branch,
			"model2"=>$model2,
			"model"=>$model,
			"no"=>$no,
			"halaman"=>$halaman,
			"mulai"=>$halaman,
			"sql_d"=>$sql_d
		)
	);
}
?>
</div>
<div style="clear: both"></div>

<br>
<!-- <a href="<?php echo Yii::app()->createUrl("sales/") ?>"> -->
<!-- <button style="float: left;" class="btn btn-primary" > <i class="fa fa-chevron-left"></i> Kembali </button> -->
<!-- </a> -->

<button style="float: left;" class="btn btn-primary" onclick="$('#faktur').print();"> <i class="fa fa-print"></i> Cetak </button>

<?php 

function Terbilang($x)
{
  $abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
  if ($x < 12)
    return " " . $abil[$x];
  elseif ($x < 20)
    return Terbilang($x - 10) . "belas";
  elseif ($x < 100)
    return Terbilang($x / 10) . " puluh" . Terbilang($x % 10);
  elseif ($x < 200)
    return " seratus" . Terbilang($x - 100);
  elseif ($x < 1000)
    return Terbilang($x / 100) . " ratus" . Terbilang($x % 100);
  elseif ($x < 2000)
    return " seribu" . Terbilang($x - 1000);
  elseif ($x < 1000000)
    return Terbilang($x / 1000) . " ribu" . Terbilang($x % 1000);
  elseif ($x < 1000000000)
    return Terbilang($x / 1000000) . " juta" . Terbilang($x % 1000000);
}
// echo "<pre>";
// print_r($model2);
// echo "</pre>";
?>
</body>