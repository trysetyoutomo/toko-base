<style type="text/css">
	td {
		border-width: 0px!important;
	}
	#faktur{
		width: 400px;
		letter-spacing: 0px;
		font-size:10px;	
	}

</style>
<?php 
	$no=1;
// $halaman = 100;
for ($i=1; $i<=$pages ; $i++){ 
$mulai = ($i>1) ? ($i * $halaman) - $halaman : 0;
// $model2 = Yii::app()->db->createCommand($sql_d);
$model2 = Yii::app()->db->createCommand($sql_d." limit $halaman offset $mulai ")->queryAll();
?>
<!-- <br> -->
<table class="x"  border="1" style="width: 500px!important" >
	<tr >
		
		<td colspan="2">
		<?php

		// if (count($model2)>=9 && count($model2)<=10 ){
		if($i==1){
		?>
			<table border="0" style="font-size: 10px;width: 100%" >
				<tr>
					<td  colspan="10">
						<h4 style="font-weight: bolder"><?php echo $branch->branch_name ?>
						</h4>
						<?php echo $branch->address." ".$branch->telp ?>
						<div style="float: right;text-align: right;" >
							<?php 
							if ($pages>1){

							?>
							<!-- Hal <?php echo $i; ?> dari <?php echo $pages ?> -->
							<?php } ?>
							
						</div>
					</td>
					</tr>
					<tr>
						<td colspan="10">
							<hr>
						</td>
					</tr>
				<tr>
				<td >Nomor</td><td class="space"> : </td>

				<td  ><?php echo $model['id'] ?></td>

				<td>Waktu</td>
				<td class="space">: </td>
				<td><?php echo date("d/m/Y",strtotime($model['date']))  ?></td>
				<!-- <tr><td>Kasir</td><td class="space">: </td><td><?php echo $model['inserter'] ?></td></tr> -->
				<!-- <tr><td>Sales</td><td class="space">: </td><td>Asep</td></tr> -->
				</tr>
				<tr>

				<td style="width: 50px;">Customer</td><td class="space">: </td><td ><?php echo $model['nama'] ?></td>
				<td>Jatuh Tempo</td>
				<td class="space">: </td><td>
					<?php 
					if ($model['tanggal_jt']=="0000-00-00"){
						echo "-";
					}else{
						echo  date("d/m/Y",strtotime($model['tanggal_jt'])) ;
					}
					?>
				</td>
				</tr>
				<tr>
				<?php 
				$payment = SalesPayment::model()->findByPk($id);
				$label = "";
				$paket_kartu = false;
				if ($payment->cash!="0"){
					$label = "Tunai";
					$paket_kartu = false;
				}else if ($payment->edc_bca!="0"){
					$label = "KARTU DEBIT";
					$paket_kartu = true;
				}else if ($payment->edc_niaga!="0"){
					$label = "KARTU KREDIT";
					$paket_kartu = true;
				}else if ($payment->credit_bca!="0"){
					$label = "Transfer Mandiri";
					$paket_kartu = true;
				}
				// echo $payment->cash;
				
				?>
				<td>Pembayaran</td><td class="space">: </td><td><?php echo $label ?></td><!--
				-->	
				
					<td>

					
					
					</td>
					<td></td>
					<td>
						<?php 

						
						?>
					</td>
				</tr>

			</table>
			<?php } ?>
		</td>
	</tr>
	<?php if ($i==1): ?>
	<tr>
		<td colspan="9" style="border:1px solid white">
			<div style="border:1px dashed black;"></div>
		</td>
	</tr>
<?php endif; ?>
	</table>
	<table class="x"  border="1" style="width: 500px!important" >
	<tr>
		<td  style="border:0px solid black">

		<table style="width: 100%" border="0"  >
			<?php 
			//if ($i==1//){
			?>
			<thead  >
				<tr>
 				<td align="left" >No</td>
 				<td align="left" >Nama</td>
 				<td style='width:35px;'>Qty</td>
 				<td align="center" >Unit</td>
				<td align="right" >Hrg</td>
				<td  align="right">Total</td>
			</tr>

			<tr>
				<td colspan="6" style="border:1px solid white">
			<div style="border:1px dashed black;"></div>
				</td>
			</tr>
			
			</thead>
			<?php //} ?>

			<tbody class="bodiku" style="letter-spacing: 1px">
			<?php 
		
			foreach ($model2 as $key => $value) { ?>
				<tr >
									<td><?php echo $no ?></td>

 					<td><?php 
 					// if )
 					$nama = $value['name'];
 					$lengt = strlen($nama);

 					$nama = substr($nama,0,19);
					if ($lengt>=19)
						echo $nama."..";
					else
						echo $nama;

 					 ?></td>
					<td><?php echo intval($value['qty']) ?></td>
				 <td align="center"><?php echo $value['nama_satuan'] ?></td>
					<td align="right"><?php echo number_format($value['price']) ?></td>
					<td style="display:none" align="right"><?php echo number_format($value['idc'])." " ?></td>
					<td align="right"><?php echo number_format($value['total']) ?></td>
				
				</tr>
			<?php 
			$gt1+=$value['total']; 
			$no++;
			}

			 ?>
			<tr>
			</tr>	
			</tbody>
			</table>
			<table>
				<?php 
				$hutang = $model['sisa'];
				$gt2 = intval($gt1) + intval($hutang) -  intval($model['voucher']);

if ($i==$pages){
				?>
	</table>
	<?php if ($i==1) { ?>
	<hr style="border: 1px dashed black">
	<?php } ?>
	<table>

	<tfoot >
				<tr  style='border-top:1px dashed black'>
					<td   valign="top" colspan="10" rowspan="5" style="text-transform: capitalize;border-left:1px solid white;border-bottom: 1px solid white;width: 500px;">
					

<table border="0">
	<tr>
		<td colspan="2" >
		
			<table  border="0">
				<tr >
					
					<td align="center" style="padding-left: 20px;border-right:1px solid white">
					Hormat Kami <br><br><br>
					_________________ &nbsp;&nbsp;
						
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
				</td>	

				<td style="border: 0px solid black;">
				<table style="width: 200px;float: right;margin-top: 0px;">
				<tr>
					<td>Total</td>
					<td style="border-right:1px solid white" align="right"><?php echo number_format($gt1) ?></td>
				</tr>
				<tr><?php 
				
				?>
				<td style="border:1px solid white"> Sisa Sebelumnya</td>
					<td align="right" style="border-right:1px solid white"><?php echo str_replace(',', '.', number_format($hutang)); ?></td>

				</tr>
				<tr>
					<td>Potongan</td>
					<td align="right" style="border-right:1px solid white"><?php echo number_format($model['voucher']) ?></td>
				</tr>
				<tr>
				<td style="border:1px solid white"> Grand</td>
					<td align="right" style="border-right:1px solid white"><?php echo number_format($gt2) ?></td>

				</tr>
				
				<?php 
				$total_akhir = $gt2 +$hutang;

				?>
				<tr style="display:none;">
				<td style="border:1px solid white"> Total Akhir</td>
					<td align="right" style="border-right:1px solid white"><?php echo str_replace(',', '.', number_format($total_akhir)); ?></td>

				</tr>
				<tr>
				<td style="border-right:1px solid white"> Tunai</td>
					<td align="right" style="border-right:1px solid white"><?php echo number_format($model['bayar_real']) ?></td>

				</tr>
				<tr>
				<td style="border-right:1px solid white;border-bottom:1px solid white">
					<?php 
					if ($model['bayar_real']>=$gt2){
						echo "Kembali ";
					}else{
						echo "Sisa  ";
					}
					?>
				</td>
				<td style="border-right:1px solid white;border-bottom:1px solid white" align="right">
				<?php 
				if ($paket_kartu==false)
					echo number_format( abs($model['bayar_real']-$gt2) );
				else
					echo number_format(0);

				 ?>
				 	

				 </td>
				</tr>
				
				<?php 

				?>
					
				</td>
				</tr>
				</table>	
			</tfoot>
<?php 
}else{
?>
<tfoot>
	
</tfoot>
<?php } ?>

		</table>

		</td>
	</tr>
	</table>

<?php } ?>
