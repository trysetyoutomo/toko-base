<body onload="$('#faktur').print();">
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/jQuery.print.min.js"></script>
<style type="text/css">
/*tr td{
	border: 1px solid white;
}*/
/*	thead tr td,.bodiku{
		padding: 5px;
		border:2px solid black;
	}*/
/*	tfoot tr td{
		border:0px solid black;
		padding: 1px!important;
	}*/
	#faktur .x{
		float: left;
	}
	tfoot{
		font-style: initial;
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
sum(si.item_tax) tax,
sum(si.item_service) service, 
s.sale_voucher voucher

from sales s, users u , sales_payment sp, sales_items  si
where
 s.id = $id
and inserter = u.id
and sp.id = s.id 
and si.sale_id = s.id 
group by s.id 



";

$model = Yii::app()->db->createCommand($sql)->queryRow();
?>
<style type="text/css">
	$page{
		border-collapse: collapse;
		border: 0px solid black;
		margin-left: 15px;
		letter-spacing: 2px;
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

			$total = count($model2);
			$halaman = 6;
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
<div id="faktur">
<?php 
// echo "123";
// var_dump($pages);
// for ($i=1; $i<=$pages ; $i++){ 
// $mulai = ($i>1) ? ($i * $halaman) - $halaman : 0;
// $model2 = Yii::app()->db->createCommand($sql_d." limit $halaman offset $mulai ")->queryAll();
?>
<table class="x" style="width: 270px;"  border="0" cellpadding="20" 
	>
	<tr>
		<td valign="top" align="center" style="text-align: center;" >
			<!-- <img src="<?php echo Yii::app()->request->baseUrl ?>/logo/<?php echo $parameter['gambar'] ?>" width="100" > -->
			<h3 style="display: inline;"><?php echo $branch->branch_name ?></h3>
		
			<br>
			<?php echo $branch->address." ".$branch->telp ?>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<table border="0" style="width: 100%;text-align: center" >
				<tr><td colspan="3" style="text-align: center;"><?php echo $model['id'] ?></td></tr>
				<tr><td colspan="3" style="text-align: center;"><?php
				$u = Users::model()->findByPk($model['inserter']);
				 echo $u->username;

				  ?></td></tr>
				<tr><td colspan="3" style="text-align: center;"><?php echo date("d M Y H:i",strtotime($model['date']))  ?></td></tr>
				<!-- <tr><td>Kasir</td><td class="space">: </td><td><?php echo $model['inserter'] ?></td></tr> -->
				<!-- <tr><td>Sales</td><td class="space">: </td><td>Asep</td></tr> -->
				<tr><td colspan="3" style="text-align: center;"><?php echo $model['nama'] ?></td></tr>
				<!-- <tr><td>Jatuh Tempo</td><td class="space">: </td><td>
					<?php 
					if ($model['tanggal_jt']=="0000-00-00"){
						echo "-";
					}else{
						echo  date("d M Y h:n",strtotime($model['tanggal_jt'])) ;
					}
					?>
				</td></tr> -->
				<?php 
				// $payment = SalesPayment::model()->findByPk($id);
				// $label = "";
				// if ($payment->cash!="0"){
				// 	$label = "Cash";
				// }else if ($payment->edc_bca!="0"){
				// 	$label = "GIRO";
				// }else if ($payment->edc_niaga!="0"){
				// 	$label = "EDC MANDIRI";
				// }else if ($payment->credit_bca!="0"){
				// 	$label = "Transfer Mandiri";
				// }
				// echo $payment->cash;
				
				?>
				<!-- <tr><td>Cara Pembayaran</td><td class="space">: </td><td><?php echo $label ?></td></tr> -->
				<!-- <tr><td colspan="3" align="center"><br><b>FAKTUR PENJUALAN</b></td></tr> -->
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="border:1px solid white">
			<div style="width:100%;height: 10px;border-bottom: 2px solid black;"></div><br>
		</td>
	</tr>
	<!-- <tr>
		<td width="50%" valign="top">
			Kepada Yth :
			<div style="border:1px solid white;height: 55px;width:90%;padding:5px;">
				<?php echo $model['nama'] ?>
			</div>
			<br>
		</td>
		<td width="50%">
			


		</td>
	</tr> -->
	<tr>
		<td >
		<table border="0" class="dd" width="100%" cellpadding="0">
			<thead style="font-weight: bolder">
					<!-- 
				<tr>
					<td></td>
				</tr>
			</thead>
					 -->

			<tbody class="bodiku">
			<?php 
			foreach ($model2 as $key => $value) { ?>
				<tr>
					<!-- <td><?php echo $no++; ?></td> -->
					<td colspan="2"><?php echo $value['name'] ?></td>
				
				</tr>
				<tr>
					<td >
						<?php
						 echo intval($value['qty'])." x ".number_format($value['price'])." -  diskon (".$value['item_discount']." %)";
						 $sbt = intval($value['qty'])*intval($value['price'])-$value['idc'];
						 // echo intval($value['qty'])." x ".number_format($value['price'])." - ".$value['idc']." %";

						  ?>
					</td>
					<td align="right"><?php echo number_format($sbt) ?></td>
						

				</tr>
			<?php 
			$gt1+=$sbt; 
			}

			 ?>
				
			</tbody>
				<?php 
				$gt2 = ( intval($gt1) + intval($model['tax']) + intval($model['service']) ) -  intval($model['voucher']);
				?>
			<tfoot>
				<tr style=""><td colspan="2"></td></tr>
				<tr><td colspan="2"><br></td></tr>
				<tr >	
					<td>Subtotal</td>
					<td  align="right"><?php echo number_format($gt1) ?></td>
				</tr>
				<tr>
					<td>Potongan</td>
					<td align="right" ><?php echo number_format($model['voucher']) ?></td>
				</tr>
				<tr>
					<td>Pajak</td>
					<td align="right" ><?php echo number_format($model['tax']) ?></td>
				</tr>
				<tr>
					<td>Service</td>
					<td align="right" ><?php echo number_format($model['service']) ?></td>
				</tr>
				<tr style="border-top:1px solid white">
					
				<td >Grand Total</td>
					<td align="right" ><?php echo number_format($gt2) ?></td>

				</tr>
				<tr>
				<td >Bayar</td>
					<td align="right" ><?php echo number_format($model['bayar']) ?></td>

				</tr>
				<tr>
				<td style="border-right:1px solid white;">
					<?php 
					if ($model['bayar']>=$gt2){
						echo "Kembali ";
					}else{
						echo "Sisa ";
					}
					?>
				</td>
				<td style="border-right:1px solid white;" align="right"><?php echo number_format($model['bayar']-$gt2) ?></td>

				</tr>
					<tr><td colspan="2"><br></td></tr>
						<tr><td colspan="2"><br></td></tr>
				<tr><td colspan="2" align="center">Terimakasih atas kunjunganya</td></tr>

			</tfoot>
			


		</table>

		</td>
	</tr>
	</table>

<?php // } ?>
</div>
<div style="clear: both"></div>

<br>
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