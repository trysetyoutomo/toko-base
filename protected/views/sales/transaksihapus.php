
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
</form>
<br>
<table class="table">
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

		$sql  = "select  
		s.*,u.username from 
		sales s 
		left join users u on u.id = s.deleted_by

		where s.status = 2  and date(s.date)='$date' ";
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
<script>
// $(document).ready(function(e){
// 	$('.hapus').click(function(){
// 		var c = confirm("Yakin ?");

// 		var id = $(this).attr("user-id");
// 		var tanggal = $("#tanggal").val();

	

// 		if (!c){return false};

// 		var href = "index.php?r=setor/HapusByTanggal&id="+id+"&tanggal="+tanggal;
// 		window.location.assign(href);
		

// 		});
// 	$('.cetak').click(function(){
// 		var tanggal = $(this).attr("tanggal");
// 		var inserter = $(this).attr("inserter");
		
// 		if(tanggal==''){
// 			alert('Pilih tanggal terlebih dahulu');
// 			return false;
// 		}else{
// 			$.ajax({
// 				url:'<?php echo Yii::app()->createUrl("sales/cetakrekap") ?>',
// 				data:'tanggal_rekap='+tanggal+"&inserter="+inserter,
// 				success: function(data){
// 					// alert(data);
// 					var json = jQuery.parseJSON(data);
// 					// $('#hasiljson').html(data);
// 					print_rekap(json,false);
// 					// console.log(data);
					
// 				},
// 				error: function(data){
// 					alert('error');
// 				}
// 			});
// 		}
// 	});
// });
</script>