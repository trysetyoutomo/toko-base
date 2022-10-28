<body onload="onload()">
<script>
	function onload(){
		$('#faktur').print();
		setTimeout(() => {
			window.close();
		}, 1000); 
		// window.onfocus = function () { setTimeout(function () { window.close(); }, 500); }
	}
</script>
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/jQuery.print.min.js"></script>
<style type="text/css">
	*{
		font-weight:bold;
	}
	#faktur{
		width:100%;
	}
	#faktur .x{
		float: left;
	}
	tfoot{
		font-style: initial;
	}
	@media print
	{
		button{
			display:none;
		}
		@page
		{
			size: auto;   /* auto is the initial value */
			margin: 0mm;  /* this affects the margin in the printer settings */
		}

		body
		{
			font-size:15px!important
		}
	}
</style>
<?php 
$bid = Yii::app()->user->branch();
$branch = Branch::model()->findByPk($bid);
$parameter = Parameter::model()->findByPk(1);
$sql = "

select 
pembayaran_via,
sp.cash,
s.id id,
faktur_id,
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
<div id="faktur" >
<?php 
// echo "123";
// var_dump($pages);
// for ($i=1; $i<=$pages ; $i++){ 
// $mulai = ($i>1) ? ($i * $halaman) - $halaman : 0;
// $model2 = Yii::app()->db->createCommand($sql_d." limit $halaman offset $mulai ")->queryAll();
?>
<table class="x" style="width:100%;font-family:courier!important"  border="0" cellpadding="20" 
	>
	<tr>
		<td valign="top" align="center" style="text-align: center;padding:0px 15px 0px 15px" >
			<!-- <img src="<?php echo Yii::app()->request->baseUrl ?>/logo/<?php echo $parameter['gambar'] ?>" width="100" > -->
			<h3 style="display: inline;"><?php echo $branch->branch_name ?></h3>
			<h4 style="margin:0px;">
				<?php echo $branch->address." ".$branch->telp ?>
			</h4>
		</td>
	</tr>
	<?php 
		$u = Users::model()->findByPk($model['inserter']);
				//  echo "Petugas :  ".$u->username;
	?>
	<tr>
		<td colspan="2" align="left" style="padding:0px 15px 0px 15px">
			<table border="0" style="width: 100%;text-align: center" >
			<tr><td colspan="3" style="text-align: left;font-size:14px">
				<?php 
					echo date("d.m.Y.H:i",strtotime($model['date']))."/".$model['faktur_id']."/".$u->username
				?>
			</td></tr>

			</table>
			<table border="0" style="width: 100%;text-align: center;display:none" >
				<tr><td colspan="3" style="text-align: left;"><?php echo "ID &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:   ".$model['faktur_id'] ?></td></tr>
				<tr><td colspan="3" style="text-align: left;"><?php
			

				  ?></td></tr>
				<tr><td colspan="3" style="text-align: left;"><?php echo "Tgl &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: "  ?></td></tr>
				<tr><td colspan="3" style="text-align: left;"><?php echo "Customer &nbsp;: ".$model['nama'] ?></td></tr>
				<?php 
					if (isset($model['tanggal_jt'])){
						?>
							<tr><td colspan="3" style="text-align: left;"><?php echo "Tgl Jth Tempo : ".date("d M Y H:i",strtotime($model['dattanggal_jte']))  ?></td></tr>
						<?php 
					}
				?>
			</table>
		</td>
	</tr>
	<tr>
		<td style="padding:0px 15px 0px 15px">
		<table border="0" class="dd" width="100%" cellpadding="0">
			<thead style="font-weight: bolder">
			<tbody class="bodiku">
			<tr><td colspan="2" style="border:1px dashed white;padding:0"><div style="width:100%;border-bottom: 1px dashed black;"></div></td></tr>
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
				<tr>
		<td colspan="2" style="border:1px dashed white;padding:0">
			<div style="width:100%;border-bottom: 1px dashed black;"></div>
		</td>
	</tr>
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
		<tr>
		<td colspan="2" style="border:1px dashed white;padding:0">
			<div style="width:100%;border-bottom: 1px dashed black;"></div>
		</td>
	</tr>


				<tr style="border-top:1px solid white">
					
				<td >Grand Total</td>
					<td align="right" ><?php echo number_format($gt2) ?></td>

				</tr>
					<tr><td >Bayar</td><td align="right" ><?php echo number_format($model['bayar']) ?></td></tr>
					
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
					<td style="border-right:1px solid white;" align="right"><?php echo number_format( abs($model['bayar']-$gt2)) ?></td>
					</tr>
				<?php if ($model['cash'] > 0){ ?>
				
				<?php  }else{ ?>
					<tr><td >Non Tunai</td><td align="right" ><?php echo ($model['pembayaran_via']) ?></td></tr>
				<?php  } ?>
				<tr><td colspan="2" align="center">Terimakasih atas kunjunganya</td></tr>
			</tfoot>
		</table>

		</td>
	</tr>
	</table>

<?php // } ?>
</div>
<div style="clear: both"></div>

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