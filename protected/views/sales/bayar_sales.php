<table style="width: 100%" cellpadding="10">
	<tr>
		<td>
			Nomor Tagihan
					</td>
		<td>
			<input type="text" readonly="" name="no-tagihan" id="no-tagihan" >
		</td>
	</tr>
	<tr>
		<td>
			Tanggal Bayar
		</td>
		<td>
			<input type="date" value="<?php echo date("Y-m-d")?>" name="tanggal-bayar" id="tanggal-bayar" class="tanggal">
		</td>
	</tr>
	<tr>
		<td>
			Total Tagihan
		</td>
		<td>
			<input readonly="" type="text" name="total-tagihan" id="total-tagihan">
		</td>
	</tr>
	<tr>
		<td>
			Total Bayar
		</td>
		<td>
			<input  type="number" name="total-bayar" id="total-bayar">
			<a style="display: inline" class="fa fa-check btn-lunasikan"></a>
		</td>
	</tr>
	<tr>
		<td>
			Sisa Tagihan
		</td>
		<td>
			<input  type="number" name="sisa-bayar" id="sisa-bayar">
		</td>
	</tr><tr>
		<td colspan="2">
			<input  type="button" id="simpan-bayar" value="Simpan" class="btn btn-primary">
		</td>
	</tr>

</table>