<style type="text/css">
#dialog-meja{
	height: 400px;
}
	.meja-list{
		float:left;
		width:120px;
		margin-right:10px;
	}
	.meja-tombol{
		float:left;
		width:500px;
		margin-top:-20px;
	}
	#meja{
		width:120px;
	}
	#dialog_meja,.meja{
		/*background:red;*/
	}
	.table-button{
		width: 150px;
		height: 60px;
		/*margin:5px;*/
	}
	#table tr th{
		border: 1px solid black;
		font-weight:bolder;
	}
	#table tr td{
		border: 1px solid black;
	}
</style>
<script type="text/javascript">
	$(document).ready(function(){
	

		 $('#tanggal_jt').datepicker(
        { 
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true,
            minDate : 0,
			maxDate : "+3Y"

  	  });
		 // alert("123");
		// $('#modal').load('index.php?r=site/table');
	})
</script>

<br>
<br>
<div class="meja-modal" id="modal">
	
	<?php
	$username = Yii::app()->user->name;
	$user = Users::model()->find('username=:un',array(':un'=>$username));
	$model = Sales::model()->findAllByAttributes(array('status' => 0));
	$data = CHtml::listData($model, 'table', 'id');
	// print_r($);
	?>
	

	<div class="meja-list">
	<select style="float:left;margin-top:-20px;height: 500px" id="meja" name="meja" multiple size=20  onKeypress="hold_bill_press(event)">
	<?php
		function getName($no){
			$model = Sales::model()->find(" t.table = '$no' and  status = 0 ");
			if ($model->nama!="")
				return $no.".".$model->nama;
			else
				return $no."."."Umum";

		}
		$sqlno_meja = "
		select no_meja from meja limit 30
	";
	
	$valno_meja = Yii::app()->db->createCommand($sqlno_meja)->queryAll();
	// for ($a = 1; $a <= 75; $a++) {
	foreach($valno_meja as $valno_meja){
		$a = $valno_meja['no_meja'];	
		if (isset($data[$a]))
			echo '<option nilai='.$a.' value='.$a.'-1-'.$data[$a].'>'.getName($a).'</option>';
		else
			echo '<option nilai='.$a.' value='.$a.'-0-0'.'>'.$a.'</option>';
	}	
	// }
	?>
	</select>
	<div id="w-jp" style="float:left;display:none" >
	    <label>Jatuh Tempo</label><br>
	    <!-- <input value="<?php echo date('Y-m-d') ?>"  placeholder="0"  id="tanggal_jt"  class="tanggal_jt" name="tanggal_jt" style="width:100px" > -->
	    <br>
	    <br>
	    <div >
		    <label>Total Bayar</label>
		    <input placeholder="0"  id="total_hutang"  class="total_hutang" name="total_hutang" style="width:100px" >
	    </div>
	</div>


	</form>
	</div>
	
	<div class="meja-tombol">
	<table border="1" id="table" style="display:none" >
	<tr>
		<th>Nomor</th>
		<th>Jatuh Transaksi</th>
		<th>Jatuh Tempo</th>
		<th>Nama</th>
		<!-- <th>Total</th> -->
		<th>Bayar</th>
		<th>Aksi</th>
	</tr>

		
	
	<?php

	$valno_meja = Yii::app()->db->createCommand($sqlno_meja)->queryAll();
	// for ($a = 1; $a <= 75; $a++) {
	// foreach($valno_meja as $valno_meja){
	foreach($model as $a){

		?>
		<tr> 
			<td><?php echo $a->table ?></td>
			<td><?php echo date("d M Y, H:i",strtotime($a->date)); ?></td>
			<td><?php echo $a->tanggal_jt ?></td>
			<td><?php echo $a->nama; ?></td>
			<!-- <td>
			<input type="text" value='<?php echo SalesController::getTotalByID($a->id); ?>'
				
			/>
			</td> -->
			<td><?php echo $a->bayar ?></td>
			<td>
				<input data-id="<?php echo $a->id ?>" data-meja="<?php echo $a->table ?>"  type="button" class="mybutton pilih-no-meja" name="pilih" value="pilih">
			</td>
		</tr>
		<?php 
		// $a = $valno_meja['no_meja'];
		// $x = Sales::model()->find("t.table=$a and t.status = 0");
		// $x = SalesController::getSQLOmset(99,99999);



		if ($x->nama!="")
			$nama = $x->nama;
		else
			$nama = "Umum";
		// if (isset($data[$a])){
			//echo '<input onClick="hold_bill(' . $a . ',1,' . $data[$a] . ')" type="button" value="' . $a . ' Terisi" data-dropdown="#dropdown-4" class="table-button" />';
			//echo '<input type="button"  value="' . $a . '" style="background:red;font-color:white" data-dropdown="#dropdown-4" class="table-button" />';
			if ($x->tanggal_jt=="0000-00-00"){
				$date = "-" ;
			}else{
				$date =date("d M y",strtotime($x->tanggal_jt));

			}

		

		// echo '<div onclick="hold_bill_press(event)" value="'.$a.'" title=" Tanggal Jatuh Tempo '.$x->tanggal_jt.'" type="button"  

		// style="font-size:10px;background:green;font-color:white;float:left" data-dropdown="#dropdown-4" 	
		// class="table-button" >'.$a.' - '.$date.' <br>'.$nama.'<br>'.number_format($x->bayar).'</div>';
					
		// }
		// else{

		// echo '<div title=" Tanggal Jatuh Tempo '.$x->tanggal_jt.'" type="button"  	
		// style="font-size:10px;background:rgba(163,0,0,1);font-color:white;float:left" data-dropdown="#dropdown-4" 	
		// class="table-button" >'.$a.' - '.$x->tanggal_jt.' <br>'.$nama.'<br>'.number_format($x->bayar).'</div>';
		// }
		// 	echo '<button  type="button"  

		// style="font-size:10px;background:gray;font-color:white" data-dropdown="#dropdown-4" 	class="table-button" >'.$a.'<br>'.$date.'</button>';

			// echo '<input onClick="hold_bill(' . $a . ',0,0)" type="button" value="' . $a . '" data-dropdown="#dropdown-4" class="table-button"  />';
			// echo '<input type="button" value="' . $a . '" data-dropdown="#dropdown-4" class="table-button"  />';
	}
	//}
	?>
	</table>
	</div>
</div>
<script>
$(document).ready(function(e){

	$('.cetak').click(function(){
		var tanggal = $(this).attr("tanggal");
		var inserter = $(this).attr("inserter");
		
		if(tanggal==''){
			alert('Pilih tanggal terlebih dahulu');
			return false;
		}else{
			$.ajax({
				url:'<?php echo Yii::app()->createUrl("sales/cetakdKeluar") ?>',
				data:'tanggal_rekap='+tanggal+"&inserter="+inserter,
				success: function(data){
					// alert(data);
					var json = jQuery.parseJSON(data);
					// $('#hasiljson').html(data);
					print_rekap(json,false);
					// console.log(data);
					
				},
				error: function(data){
					alert('error');
				}
			});
		}
	});
});
</script>