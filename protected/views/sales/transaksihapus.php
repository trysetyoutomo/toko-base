<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/jQuery.print.min.js"></script>

<h1><i class="fa fa-book"></i> Transaksi Cancel </h1><hr>
<?php 
if (isset($_REQUEST[tanggal])){
			$date = $_REQUEST[tanggal];
		}else{
			$date = date("Y-m-d");
		}
?>
<form>
	<input type="hidden" name="r" value="sales/transaksihapus"  />
			Tanggal Transaksi 
			<input type="date" value="<?php echo date('Y-m-d'); ?>" style="display:inline;padding:5px" name="tanggal" id="tanggal">
			
		
	<input type="submit" class="btn btn-primary" name="Cari" value="Cari" />
	<input type="button" name="Cetak" value="Cetak" class="btn btn-primary"  onclick="$('#data-cetak').print()" />

</form>
<br>
<table class="table" id="data-cetak">
	<thead>
		
		<tr style="background:rgba(42, 63, 84,1) ;color:white">
			<td>No</td>
			<td>ID</td>
			<td>Tanggal Transaksi</td>
			<td>Cancel oleh</td>
			<td>Cancel pada</td>
		</tr>
		

	</thead>
	<tbody>
		<?php 
		
		if (isset($_REQUEST[tanggal]) ){
			$filter = "and si.item_id = $_REQUEST[id] and date(s.date)= '$_REQUEST[tanggal]' " ;
			$date = $_REQUEST[tanggal];
		}else{
			$filter = "and s.id = 999999999999999999999999999999999  " ;
		
			$date = date('Y-m-d');
		}
		$branch_id = Yii::app()->user->branch();
		$sql  = "select  
		s.*,u.username from 
		sales s 
		left join users u on u.id = s.deleted_by
		where 
		s.branch = '".$branch_id."'
		and
		s.status = 2  
		and
		date(s.date)='$date' ";
		$model = Yii::app()->db->createCommand($sql)->queryAll();

			// $sql = "select *,s.id sid from sales_items si inner join sales s on s.id = si.sale_id 
			// inner join items i on i.id = si.item_id
			
			// where  s.status =  1   $filter  ";
			// $model = Yii::app()->db->createCommand($sql)->queryAll();
				$no = 1;
				$ttl = 0;
		foreach ($model  as $m ):
		?>
		<tr >
			<td><?php echo $no ?></td>
			<td><?php echo $m['faktur_id']; ?></td>
			<td><?php echo $m['date']; ?></td>
			<td><?php echo $m['username']; ?></td>
			<td><?php echo $m['deleted_at']; ?></td>

			
		</tr>
		<?php 

	

		$no++;
		endforeach; ?>
	
		
	</tbody>
</table>
