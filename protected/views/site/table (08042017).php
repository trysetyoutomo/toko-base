<style type="text/css">
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
<div id="w-jp" style="float:right;margin-bottom:30px">
    <label>Jatuh Tempo</label><br>
    <input  id="tanggal_jt"  class="tanggal_jt" name="tanggal_jt" style="width:100px" >
</div>
<br>
<br>
<div class="meja-modal" id="modal">
	
	<?php
	$username = Yii::app()->user->name;
	$user = Users::model()->find('username=:un',array(':un'=>$username));
	$model = Sales::model()->findAllByAttributes(array('status' => 0));
	$data = CHtml::listData($model, 'table', 'id');
	?>
	

	<div class="meja-list">
	<select style="float:left" id="meja" name="meja" multiple size=20 onKeypress="hold_bill_press(event)">
	<?php
		function getName($no){
			$model = Sales::model()->find(" t.table = $no and  status = 0 ");
			if ($model->nama!="")
				return $no.".".$model->nama;
			else
				return $no."."."Umum";

		}
		$sqlno_meja = "
		select no_meja from meja limit 50
	";
	
	$valno_meja = Yii::app()->db->createCommand($sqlno_meja)->queryAll();
	// for ($a = 1; $a <= 75; $a++) {
	foreach($valno_meja as $valno_meja){
		$a = $valno_meja['no_meja'];	
		if (isset($data[$a]))
			echo '<option value='.$a.'-1-'.$data[$a].'>'.getName($a).'</option>';
		else
			echo '<option value='.$a.'-0-0'.'>'.$a.'</option>';
	}
	// }
	?>
	</select>
	</form>
	</div>
	
	<div class="meja-tombol">
	<?php

	$valno_meja = Yii::app()->db->createCommand($sqlno_meja)->queryAll();
	// for ($a = 1; $a <= 75; $a++) {
	foreach($valno_meja as $valno_meja){
		$a = $valno_meja['no_meja'];
		$x = Sales::model()->find("t.table=$a and t.status = 0");
		// $x = SalesController::getSQLOmset(99,99999);




		if (isset($data[$a])){
			//echo '<input onClick="hold_bill(' . $a . ',1,' . $data[$a] . ')" type="button" value="' . $a . ' Terisi" data-dropdown="#dropdown-4" class="table-button" />';
			//echo '<input type="button"  value="' . $a . '" style="background:red;font-color:white" data-dropdown="#dropdown-4" class="table-button" />';
			if ($x->tanggal_jt=="0000-00-00"){
				$date = "-" ;
			}else{
				$date =date("d M y",strtotime($x->tanggal_jt));

			}

			echo '<div title=" Tanggal Jatuh Tempo '.$x->tanggal_jt.'" type="button"  

					style="font-size:10px;background:green;font-color:white" data-dropdown="#dropdown-4" 	class="table-button" >'.$a.'<br>'.$date.'- <br>'.$x->nama.'</div>';
					
		}
		else{
			
			echo '<div title=" Tanggal Jatuh Tempo '.$x->tanggal_jt.'" type="button"  
		

		style="font-size:10px;background:green;font-color:white" data-dropdown="#dropdown-4" 	class="table-button" >'.$a.'<br>'.$date.'- <br>'.$x->nama.'</div>';
		}
		// 	echo '<button  type="button"  

		// style="font-size:10px;background:gray;font-color:white" data-dropdown="#dropdown-4" 	class="table-button" >'.$a.'<br>'.$date.'</button>';

			//echo '<input onClick="hold_bill(' . $a . ',0,0)" type="button" value="' . $a . '" data-dropdown="#dropdown-4" class="table-button"  />';
			// echo '<input type="button" value="' . $a . '" data-dropdown="#dropdown-4" class="table-button"  />';
	}
	// }
	?>

	</div>
</div>
<script>
    function hold_bill(meja,mode,data)
    {
    	alert(" can't hold bill ");
//         if (mode===0)
//             bayar(0,meja);
//         else
//         {
//             load_bill(meja,data);
//             sale_id = data;
//             show_meja("Meja ("+meja+")");
//             $('#dialog_meja').dialog('close');
// //            alert(sale_id);
//         }
//         $('#dialog_meja').load('index.php?r=site/table');
    }
    function show_meja(val)
    {
        $("#tombol_meja").attr('value', val);
		$('#dialog_meja').load('index.php?r=site/table');
    }
	
	function cekisigrid(){
		var inc = 0;
		 liveSearchPanel_SalesItems.store.each(function (rec) {
			inc=inc+1;
		});
		// alert(inc);
		return inc;
	}
	
	function hold_bill_press(e){
		// alert(data);
		//cek isi grid
		
		
		
		var key;
		if (window.event)
			key = window.event.keyCode;     //IE
		else
			key = e.which;     //firefox
		
			//jika enter di tekan
				var meja = $("#meja").val().toString();
				var nilai = meja.split("-");
				no_meja = nilai[0];
				// alert(no_meja);
				meja_cetak = no_meja;
				mode = nilai[1];
				data = nilai[2];
			if (key == 13) {
				
				var number_meja= $("#tombol_meja").attr('value');
				number_meja =  number_meja.replace(/[^0-9]+/g, '');
				// alert(no_meja);
				// return false;
				if (mode==0)
				{
					var inc = 0;
					 liveSearchPanel_SalesItems.store.each(function (rec) {
						inc=inc+1;
					});
					
					if(inc == 0){
						alert('Tidak ada data, Silahkan isi Items');
						return false;
					}
					//cek meja kosong atau isi
					if(number_meja != ''){
						// alert('no meja tidak kosong\npindah meja dilakukan');
						var r=confirm("pindah meja ?")
						if (r==true)
						{
							$.ajax({
								url:'<?php echo $this->createUrl('sales/pindahmeja');?>',
								data:'meja='+no_meja,
								success: function(){
									// alert(data);
									// bayar(0,no_meja);
									show_meja("Meja");
									
									$("#dialog_bayar").dialog("close");
									liveSearchPanel_SalesItems.store.removeAll();
									$('#sum_sub_total').html(0);
									$('#sum_sale_discount').html(0);
									$('#sum_sale_service').html(0);
									$('#sum_sale_tax').html(0);
									$('#sum_sale_total').html(0);
									$('#pembayaran').val(0);
									$('#payment').val(0);
									$("#e1").select2("close");
									
								},
								error: function(data){
									alert(data);
								}
							});
						}else{
							return false;
							// alert("Meja sedang di buka  !");
						}
					}else{
						// alert("Meja sedang di buka  !");
						if ($("#tanggal_jt").val()!=""){	
							bayar(0,no_meja);
						}else{
							alert("Jatuh Tempo Wajib di isi");
						}
							show_meja("Meja");
					}
				}
				else if (number_meja == no_meja)
				{
					// alert("meja sama");
					alert("Slot sedang di buka");
					// alert("mode update holdbill");
					// bayar(0,no_meja,data);
					// show_meja("Meja");
				}
				else
				{
					alert("mode load holdbill");
					 $("#input_items").val("");
                        $("#input_items").focus();
					// alert(no_meja);
					// a = '<?php echo '+a1+' ?>';
					// alert(a);
					// nilaimeja = "<?php echo Meja::model()->findByPk("+no_meja+")->status ?>";
					// alert(nilaimeja);  
				
			        // var number_meja= $("#tombol_meja").attr('value');
					// //alert(number_meja);
										// sale_id = data;

					// if (number_meja != '')
					// {
// //						alert('bill');
						// bayar(0,no_meja,data);
						// show_meja("Meja");  

					// }else{
						load_bill(meja,data);
						$.ajax({
							url:'<?php echo $this->createUrl('sales/sessid')?>',
							data:'id='+data,
							success:function(data){
								// alert('triana');

								// Koding Triana
								$.ajax({
									url:'<?php echo $this->createUrl('sales/artimeja') ?>',
									data : {
										no_meja : no_meja,
									},
									success:function(data){
										// alert(data);
										//if (data = 4){
											// alert('Berhasil');
										//	owner();
										//}
										// Koding Triana##buka
										$.ajax({
											url:'<?php echo $this->createUrl('sales/artimeja') ?>',
											data : {
												no_meja : no_meja,
											},
											success:function(data){
												// alert(data);
												if (data == '4'){
													// alert('Berhasil');
													owner();
												}
											},
											error:function(){
												alert('Error');
											}
										});
										//##tutup
									},
									error:function(){
										alert('Error');
									}
								});


								// alert('success'+data);
							},
							error: function(){
								// alert('gagal'+data);
							}
							
						});
						show_meja("Sementara ("+no_meja+")");

					// }
				} 
				
				$('#dialog_meja').load('index.php?r=site/table');
				$("#dialog_meja").dialog('close');
			
		}
		<?php if (Yii::app()->user->getLevel()==2){ ?>
		else if (key == 32){
			var tanya = confirm('yakin hapus data pada meja ?');
		if (tanya==true){
			$.ajax({
			url:'<?php echo $this->createUrl('sales/del');?>',
			data:'id='+no_meja,
			success: function(){
				// alert('sukses hapus');
				// bayar(0,no_meja);
					show_meja("Meja");
					$('#dialog_meja').dialog('close');
				
			},
			error: function(data){
				alert(data);
			}
			});
		}
		
		
		}
		<?php } ?>
		
		
		// alert("asdasd");
	}
</script>
