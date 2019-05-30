<?php 
for ($i=1; $i<=$pages ; $i++){ 
$mulai = ($i>1) ? ($i * $halaman) - $halaman : 0;
$model2 = Yii::app()->db->createCommand($sql_d." limit $halaman offset $mulai ")->queryAll();
?>
<table class="x" width="100%" 
	>
	<tr>
		<td>
			<table border="0" width="100%"  >
				<tr>
					<td style="width: 100px;" colspan="9">
						<h3><?php echo $branch->branch_name ?>
						</h3>
						<?php echo $branch->address." ".$branch->telp ?>
						<div style="float: right;text-align: right;" >
							Hal <?php echo $i; ?> dari <?php echo $pages ?>
							
						</div>
					</td>
					</tr>
				<tr>
				<td >Nomor</td><td class="space"> : </td>

				<td style="width: 100px;"   ><?php echo $model['id'] ?></td>

				<td>Waktu</td><td class="space">: </td><td><?php echo date("Y-m-d H:i",strtotime($model['date']))  ?></td>
				<!-- <tr><td>Kasir</td><td class="space">: </td><td><?php echo $model['inserter'] ?></td></tr> -->
				<!-- <tr><td>Sales</td><td class="space">: </td><td>Asep</td></tr> -->
				<td>Customer</td><td class="space">: </td><td style="width: 100px;"><?php echo $model['nama'] ?></td></tr>
				<tr>
				<td>Jatuh Tempo</td>
				<td class="space">: </td><td>
					<?php 
					if ($model['tanggal_jt']=="0000-00-00"){
						echo "-";
					}else{
						echo  date("d M Y h:n",strtotime($model['tanggal_jt'])) ;
					}
					?>
				</td>
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

					Sisa Hutang
					
					</td>
					<td>:</td>
					<td>
						<?php 

						$hutang = SalesController::getHutangByCustomer($model['nama'],$model['id']);
						
						echo str_replace(',', '.', number_format($hutang));
						?>
					</td>
				</tr>
				<!-- <tr><td colspan="3" align="center"><br><b>FAKTUR PENJUALAN</b></td></tr> -->
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="border:1px solid white">
			<div style="width:100%;border:1px dashed black;"></div>
		</td>
	</tr>
	<!-- <tr>
		<td width="50%" valign="top">
			Kepada Yth :
			<div style="border:1px solid black;height: 55px;width:90%;padding:5px;">
				<?php echo $model['nama'] ?>
			</div>
			<br>
		</td>
		<td width="50%">
			


		</td>
	</tr> -->
	<tr>
		<td colspan="2" style="border:0px solid white">
		<table border="1" class="dd" width="100%" cellpadding="0">
			<thead style="font-weight: bolder">
				<tr>
					<td>No</td>
					<td>Kode Barang</td>
					<td>Nama Barang</td>
					<td>Satuan</td>
					<td>Jumlah</td>
					<td>Harga</td>
					<td>Diskon</td>
					<td align="right">Subtotal</td>
				</tr>
			</thead>

			<tbody class="bodiku">
			<?php 
			foreach ($model2 as $key => $value) { ?>
				<tr style="border-bottom: 0px solid black">
					<td><?php echo $no++; ?></td>
					<td><?php echo $value['barcode'] ?></td>
					<td><?php echo $value['name'] ?></td>
					<td><?php echo $value['nama_satuan'] ?></td>
					<td><?php echo intval($value['qty']) ?></td>
					<td align="right"><?php echo number_format($value['price']) ?></td>
					<td align="right"><?php echo number_format($value['idc'])." ($value[item_discount]%)" ?></td>
					<td align="right"><?php echo number_format($value['total']) ?></td>
				
				</tr>
			<?php 
			$gt1+=$value['total']; 
			}

			 ?>
			</tbody>
				<?php 
				$gt2 = intval($gt1) -  intval($model['voucher']);

if ($i==$pages){
				?>
			
<tr>
		<td colspan="8" style="border:1px solid white">
			<div style="width:100%;border:1px dashed black;"></div>
		</td>
	</tr>
	<tfoot >
				<tr >
					<td   valign="top" colspan="7" rowspan="5" style="text-transform: capitalize;font-weight:bolder;border-left:1px solid white;border-bottom: 1px solid white">
						Terbilang : <?php echo Terbilang($gt2); ?>

<table style="margin-left: 0px;" border="0">
	<tr>
		<td>
			Note : <?php echo $model['keterangan'] ?>
		</td>
	</tr>
	<tr>
		<td colspan="2" >
		
			<table  border="0">
				<tr>
					<td align="center"  style="border-right: 1px solid white">
					Yang Menerima <br><br><br>
					_________________________
					</td>
					<td>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</td>
					<td align="center" style="padding-left: 20px;border-right:1px solid white">
					Hormat Kami <br><br><br>
					_________________________
						
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
				</td>	

				<td style="border: 0px solid black;">
				<table style="width: 150px;float: right;margin-top: 0px;">
				<tr>
					<td>Total</td>
					<td style="border-right:1px solid white" align="right"><?php echo number_format($gt1) ?></td>
				</tr>
				<tr>
					<td>Potongan</td>
					<td align="right" style="border-right:1px solid white"><?php echo number_format($model['voucher']) ?></td>
				</tr>
				<tr>
					
				<td style="border:1px solid white"> Grand</td>
					<td align="right" style="border-right:1px solid white"><?php echo number_format($gt2) ?></td>

				</tr>
				<tr>
				<td style="border-right:1px solid white"> Tunai</td>
					<td align="right" style="border-right:1px solid white"><?php echo number_format($model['bayar']) ?></td>

				</tr>
				<tr>
				<td style="border-right:1px solid white;border-bottom:1px solid white">
					<?php 
					if ($model['bayar']>=$gt2){
						echo "Kembali ";
					}else{
						echo "Sisa  ";
					}
					?>
				</td>
				<td style="border-right:1px solid white;border-bottom:1px solid white" align="right">
				<?php 
				if ($paket_kartu==false)
					echo number_format($model['bayar']-$gt2);
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