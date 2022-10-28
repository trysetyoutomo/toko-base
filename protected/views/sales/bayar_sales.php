<style>
	#form-bayar-piutang tr td{
		padding:3px;
	}
</style>

<table id="form-bayar-piutang" style="width: 100%" cellpadding="20">
	<tr>
		<td>
			Nomor Tagihan
					</td>
		<td>
			<input class="form-control" type="text" readonly="" name="no-tagihan" id="no-tagihan" >
		</td>
	</tr>
	<tr>
		<td>
			Tanggal Bayar
		</td>
		<td>
			<input class="form-control" type="date" value="<?php echo date("Y-m-d")?>" name="tanggal-bayar" id="tanggal-bayar" class="tanggal">
		</td>
	</tr>
	<tr>
		<td>
			Total Tagihan
		</td>
		<td>
			<input class="form-control" readonly="" type="text" name="total-tagihan" id="total-tagihan">
		</td>
	</tr>
	<tr>
		<td>
			Total Bayar
		</td>
		<td style="position:relative">
			<input class="form-control" type="number" name="total-bayar" id="total-bayar">
			<a style="    display: inline;position: absolute;right: 10px;top: 10px;" class="fa fa-check btn-lunasikan"></a>
		</td>
	</tr>
	<tr>
		<td>
			Pembayaran via
		</td>
		<td style="position:relative">
			<select id="pembayaran_via"  style="" class="form-control">
    			<option value="CASH">CASH</option>
    			<?php 
    			$m = Bank::model()->findAll("aktif=1");
    			foreach ($m as $key => $value) {
    				echo "<option  value='$value->nama'>$value->nama</option>";
    			} ?>
    		</select>
		</td>
	</tr>
	<tr>
		<td>
			Sisa Tagihan
		</td>
		<td>
			<input readonly class="form-control" type="number" name="sisa-bayar" id="sisa-bayar">
		</td>
	</tr><tr style="margin-top:1rem">
		<td colspan="2">
			<input  type="button" id="simpan-bayar" value="Simpan" class="btn btn-primary">
		</td>
	</tr>

</table>