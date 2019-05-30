
<script type="text/javascript">
    
</script>

<fieldset>
	<legend>
		
<h1>Laporan Penjualan Periode</h1>
	</legend>

<b>Penjualan Tanggal <?php echo $tanggal_awal;?> s/d <?php echo $tanggal_akhir;?></b>
<?php //echo $tal; ?>

<table width="100%" align="center" cellpadding="3" border="2">
	<div class="layer"> 
	<thead style="background:skyblue">
		<tr style="background-color:#eaeaea;">
			<!-- <th style="width: 80%"><b>Menu</th> -->
			<th><b>Head Informasi</th>
			<!-- <th><b>Id</th>
			<th><b>Tanggal</th>
			<th><b>Sub Total</th>
			<th><b>Discount</th>
			<th><b>Service</th>
			<th><b>Pajak</th>
			<th><b>Grand Total</th> -->
			<th><b>Cara Bayar</th>
			<!-- <th><b>Meja</th>
			<th><b>waiter</th> -->
			<th><b>Deatil Pesanan</th>
		</tr>	
	</thead>
		</div>
	<tbody>
	<?php foreach ($sqlval as $tg):  ?>
					<tr>
						<td width="30%">
							<table align="center" cellpadding="3" border="1">
								<tbody>
									<tr>
										<td>Id</td>
										<td><center><?php echo $tg['id']; ?></center></td>
									</tr>
									<tr>
										<td>Meja</td>
										<td><center><?php echo $tg['table']; ?></center></td>
									</tr>
									<tr>
										<td>Tanggal</td>
										<td><center><?php echo $tg['date']; ?></center></td>
									</tr>
									<tr>
										<td>Waiter</td>
										<td><center><?php echo $tg['waiter']; ?></center></td>
									</tr>
									<tr>
									<?php
									$kasir = users::model()->find(" id = '$tg[inserter]' ")->name;
									?>
										<td>Kasir</td>
										<td><?php echo $kasir; ?></td>
										<!-- <td><?php //echo $tg['inserter']; ?></td> -->
									</tr>
									<tr>
										<td>Sub Total</td>
										<td><?php echo $tg['ssubtotal']; ?></td>
									</tr>
									<tr>
										<td>Discount</td>
										<td><?php echo $tg['sale_discount']; ?></td>
									</tr>
									<tr>
										<td>Service</td>
										<td><?php echo $tg['sservice']; ?></td>
									</tr>
									<tr>
										<td>Tax</td>
										<td><?php echo $tg['stax']; ?></td>
									</tr>
									<tr>
										<td>Total</td>
										<td><?php echo $tg['stotal']; ?></td>
									</tr>
								</tbody>
							</table>
						</td>
						<td width="20%">
							<center>
								<?php 
									$sql = "
										SELECT p.cash, p.voucher, p.compliment, p.edc_bca, p.edc_niaga, p.credit_bca, p.credit_mandiri, p.dll
										FROM sales_payment p
										INNER JOIN sales s 
										ON p.id = s.id
										WHERE s.id = '$tg[id]' 
										";
									$detailpp = Yii::app()->db->createCommand($sql)->queryAll();
								?>
								<table cellpadding="3" border="1">
									<!-- <div> 
										<thead>
											<tr>
												<th colspan="2"><b>Cara Bayar</th>
											</tr>	
										</thead>
									</div> -->
									<tbody>
										<?php foreach ($detailpp as $testpp){ ?>
											<tr>
												<td>Cash</td>
												<td><?php echo $testpp['cash']; ?></td>
											</tr>
											<tr>
												<td>Voucher</td>
												<td><?php echo $testpp['voucher']; ?></td>
											</tr>
											<tr>
												<td>Compliment</td>
												<td><?php echo $testpp['compliment']; ?></td>
											</tr>
											<tr>
												<td>Edc BCA</td>
												<td><?php echo $testpp['edc_bca']; ?></td>
											</tr>
											<tr>
												<td>Edc Mandiri</td>
												<td><?php echo $testpp['edc_niaga']; ?></td>
											</tr>
											<tr>
												<td>Credit BCA</td>
												<td><?php echo $testpp['credit_bca']; ?></td>
											</tr>
											<tr>
												<td>Credit Mandiri</td>
												<td><?php echo $testpp['credit_mandiri']; ?></td>
											</tr>
											<tr>
												<td>Pending</td>
												<td><?php echo $testpp['dll']; ?></td>
											</tr>
										<?php } ?>
										<?php //endforeach; ?>
										<?php //endforeach; ?>
									</tbody>
								</table>
							</center>
						</td>
						<!-- <td><?php //echo $tgtg; ?></td> -->
						<td width="50%">
							<center>
								<?php 
									$sql = "
										SELECT i.item_id, i.quantity_purchased, i.item_price, i.item_total_cost
										FROM sales_items i
										INNER JOIN sales s ON i.sale_id = s.id
										WHERE s.id = '$tg[id]' 
										";
									$detail = Yii::app()->db->createCommand($sql)->queryAll();
								?>
								<table cellpadding="3" border="1">
									<div> 
										<thead>
											<tr>
												<th><b>Menu Pesanan</th>
												<th><b>Jumlah</th>
												<th><b>Harga</th>
												<th><b>Sub Total</th>
											</tr>	
										</thead>
									</div>
									<tbody>
										<?php foreach ($detail as $test){ ?>
											<tr>
											<?php 
											$sqld = "select item_name from items where id = '$test[item_id]'";
											$namamenu = Yii::app()->db->createCommand($sqld)->queryRow();
											?>
												<td><?php echo $namamenu['item_name']; ?></td>
												<td><center><?php echo $test['quantity_purchased']; ?></center></td>
												<td><?php echo $test['item_price']; ?></td>
												<?php
												$asd = $test['quantity_purchased'] * $test['item_price'];
												?>
												<td><?php echo $asd; ?></td>
												<!-- <td><?php //echo $test['item_total_cost']; ?></td> -->
											</tr>
										<?php } ?>
										<?php //endforeach; ?>
										<?php //endforeach; ?>
									</tbody>
								</table>
							</center>
						</td>
						<!-- <td><center><?php //echo $nilaipd['totaljumlah']; ?></center></td> -->
						<!-- <td><center><?php //echo $nilailm['tanggal']; ?></center></td>
						<td><center><?php //echo $nilailm['pelayan']; ?></center></td>
						<td><center>Rp.<?php //echo $nilailm['total']; ?></center></td>
						<td><center>Rp.<?php //echo $nilailm['pajak']; ?></center></td>
						<td><center>Rp.<?php //echo $nilailm['service']; ?></center></td>
						<td><center>Rp.<?php //echo $nilailm['grand_total']; ?></center></td> -->
					</tr>
	<?php endforeach; ?>
	</tbody>
	<tbody>
					<tr>
						<td colspan="2"><?php //echo $tg['id']; ?></td>
						<td>
							<center>
								<?php 
									$sql = "
									select sum(si.item_price * si.quantity_purchased) ssubtotal, s.sale_discount, sum(si.item_service) sservice, sum(si.item_tax) stax, sum(((si.item_price * si.quantity_purchased) +  (si.item_service) + (si.item_tax) - (si.item_discount*(si.item_price * si.quantity_purchased)/100))) stotal, sum(si.quantity_purchased) siitems 
									from sales s, sales_items si
									where s.id = si.sale_id
									and date between '$tal' and '$tak'
									and status = '1'
									";
									$detailsales = Yii::app()->db->createCommand($sql)->queryAll();
								?>
								<table cellpadding="3" border="1">
									<div> 
										<thead>
											<tr>
												<th colspan="4"><center>Penjualan</center></th>
												<!-- <th></th> -->
											</tr>	
										</thead>
									</div>
									<tbody>
										<?php foreach ($detailsales as $testsales){ ?>
											<tr>
												<td colspan="2"><b>Net Sales</td>
												<td colspan="2"><?php echo $testsales['stotal']; ?></td>
											</tr>
											<tr>
												<td colspan="2"><b>Gross Sales</td>
												<td colspan="2"><?php echo $testsales['ssubtotal']; ?></td>
											</tr>
											<tr>
												<td colspan="2"><b>Discount</td>
												<td colspan="2"><?php echo $testsales['sale_discount']; ?></td>
											</tr>
											<tr>
												<td colspan="2"><b>Service</td>
												<td colspan="2"><?php echo $testsales['sservice']; ?></td>
											</tr>
											<tr>
												<td colspan="2"><b>Tax</td>
												<td colspan="2"><?php echo $testsales['stax']; ?></td>
											</tr>
											<tr>
												<td colspan="2"><b>Total Items</td>
												<td colspan="2"><?php echo $testsales['siitems']; ?></td>
											</tr>
										<?php } ?>
										<?php //endforeach; ?>
										<?php //endforeach; ?>
									</tbody>
								</table>
							</center>
						</td>
					</tr>
	</tbody>
	<tbody>
					<tr>
						<td colspan="2"><?php //echo $tg['id']; ?></td>
						<td>
							<center>
								<?php 
									$sql = "
										select
										sum(p.cash) as cash, sum(p.voucher) as voucher, sum(p.compliment) as compliment, sum(p.edc_bca) as edc_bca, sum(p.edc_niaga) as edc_niaga, sum(p.credit_bca) as credit_bca, sum(p.credit_mandiri) as credit_mandiri, sum(p.dll) as dll
										from sales_payment p
										inner join sales s
										on p.id = s.id
										where date
										between '$tal' and '$tak'
									";
									$detailpayment = Yii::app()->db->createCommand($sql)->queryAll();
								?>
								<table cellpadding="3" border="1">
									<div> 
										<thead>
											<tr>
												<th colspan="4"><center>Cara Pembayaran</center></th>
												<!-- <th></th> -->
											</tr>	
										</thead>
									</div>
									<tbody>
										<?php foreach ($detailpayment as $testpayment){ ?>
											<tr>
												<td colspan="2"><b>Total Payment</td>
												<td colspan="2"><?php echo $testpayment['cash']+$testpayment['voucher']+$testpayment['compliment']+$testpayment['edc_bca']+$testpayment['edc_niaga']+$testpayment['credit_bca']+$testpayment['credit_mandiri']+$testsales['dll']; ?></td>
											</tr>
											<tr>
												<td colspan="2"><b>Net Cash</td>
												<td colspan="2"><?php echo $testpayment['cash']; ?></td>
											</tr>
											<tr>
												<td colspan="2"><b>Voucher</td>
												<td colspan="2"><?php echo $testpayment['voucher']; ?></td>
											</tr>
											<tr>
												<td colspan="2"><b>Compliment</td>
												<td colspan="2"><?php echo $testpayment['compliment']; ?></td>
											</tr>
											<tr>
												<td colspan="2"><b>Edc BCA</td>
												<td colspan="2"><?php echo $testpayment['edc_bca']; ?></td>
											</tr>
											<tr>
												<td colspan="2"><b>Edc Mandiri</td>
												<td colspan="2"><?php echo $testpayment['edc_niaga']; ?></td>
											</tr>
											<tr>
												<td colspan="2"><b>Credit BCA</td>
												<td colspan="2"><?php echo $testpayment['credit_bca']; ?></td>
											</tr>
											<tr>
												<td colspan="2"><b>Credit Mandiri</td>
												<td colspan="2"><?php echo $testpayment['credit_mandiri']; ?></td>
											</tr>
											<tr>
												<td colspan="2"><b>Pending</td>
												<td colspan="2"><?php echo $testsales['dll']; ?></td>
											</tr>
										<?php } ?>
										<?php //endforeach; ?>
										<?php //endforeach; ?>
									</tbody>
								</table>
							</center>
						</td>
					</tr>
	</tbody>
</table>
	<!-- <div class="row buttons">
		<?php //echo CHtml::Button('Show', array('submit'=>array('sales/periodereportexport'))); ?>
	</div> -->
