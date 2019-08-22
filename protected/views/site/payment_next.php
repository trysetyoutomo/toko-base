<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/alfa/autoNumeric.js" type="text/javascript"></script>


<style type="text/css">
	#kembalian{
		visibility: hidden;
	}
</style>
<script>
function getKembalian(){
	var bayar = $("#bayar").val();
	// var total = $("#tb2").html();
	var total = $("#cash").val();
	if (isNaN(bayar)){
		$("#kembali").val(0);
		return false;
	}

	//07 04 2017
	nilai = parseFloat($('#bayar').val());
	total  = parseFloat($('#sum_sale_total').html());
	if (nilai<0 || nilai<total ){
		$("#w-jp").show()
		// alert('Silahkan Cek Kembali Total Pembayaran !!! ');
	}else{
		$("#w-jp").hide()

	}
	//end date


	if (total>0){	
		var kembalian = parseFloat(bayar)-parseFloat(total);
		$("#kembali").attr("asli",kembalian);
		$("#kembali").val(numberWithCommas(kembalian));

		// $("#kembali").autoNumeric("init");
	}else{
		$("#kembali").val(0);
	}
	
}

function numberWithCommas(x) {
	var string = x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	return string;
}

function getSaleID(){
		nilai = parseFloat($('#bayar').val());
		total  = parseFloat($('#sum_sale_total').html());
		// alert(nilai);
		// alert(total);
		var isHutang  =  parseInt($("#cash").val())==0 && parseInt($("#edcbca").val())==0 && parseInt($("#edcniaga").val())==0 && parseInt($("#creditbca").val()) == 0 && parseInt($("#creditmandiri").val())==0 && parseInt($("#voucher").val()) == 0 && parseInt($("#compliment").val())==0 ;
		var isEDC  =  parseInt(  $("#dll").val())==0 && parseInt(  $("#cash").val())==0 && parseInt($("#voucher").val()) == 0 && parseInt($("#compliment").val())==0  ;
		// alert("EDC "+isEDC);
		// alert("Hutang "+isHutang);
		// if (!isHutang &&  !isEDC ){
		// 	if (nilai<0 || nilai<total ){
		// 		alert('Silahkan Cek Kembali Total Pembayaran !!! ');
		// 		return false;
		// 	}
		// }
		if (!isHutang &&  !isEDC ){
			if (nilai<0 || nilai<total ){
				$("#w-jp").show()
				alert('Uang Cash tidak tidak cukup, transaksi ini akan masuk ke dalam data hutang ');
				if ($("#namapel").val()==""){
					alert("Untuk Transaksi Hutang, nama customer wajib diisi");
					$("#dialog_bayar").dialog("close");
					$("#dialog_bayar2").dialog("close");
					$("#namapel").select2("open");

					return false;
				}
			}else{
				$("#w-jp").hide()

			}
		}



				// return false;
		var a = confirm("yakin simpan transaksi ?");
		if (a==true){
			$("#btnbayar").hide();
			$("#btnvoid").hide();
			//alert(data_detail);
			$.ajax({
				url:'<?=$this->createUrl('sales/getsaleid')?>',
				success: function(sale_id){
					// alert(sale_id);
					var number_meja= $("#tombol_meja").attr('value');
					number_meja =  number_meja.replace(/[^0-9]+/g, '');
					if(number_meja==""){
						sale_id="";
					}
					
					// alert(1+" - "+number_meja+ " - "+sale_id);
					bayar(1,number_meja,sale_id);
					// var id = bayar(1,number_meja,sale_id);
					// alert(id);
					// window.open("<?php //echo Yii::app()->createUrl("Sales/cetakfaktur") ?>&id="+id); 
					$('#bayar').val(0);
					return false;
				}
			});
		}
		$("#btnbayar").show();
		$("#btnvoid").show();
		// $("#cash").val(0);
		// $("#edcniaga").val(0);
		// $("#edcbca").val(0);
		// $("#compliment").val(0);
		// $("#dll").val(0);
		// $("#voucher").val(0);
		$('#dialog_bayar2').dialog('close');
		
	}


	//hasil edit
	$(document).ready(function(){
		
		$("#text1").autoNumeric("init");
		
		$(document).on("click","#btnkembali2",function(e){
			$("#dialog_bayar2").dialog("close");
		});

		$(document).on("keyup","#text1",function(e){
			// $("#text2").autoNumeric('get');
			// $("#text2").autoNumeric('get');
			var demoGet = $('#text1').autoNumeric('get');
	        $('#bayar').val(demoGet);
	        // $('#bayar').autoNumeric('set', demoGet);
	        // $("#text1").trigger("keyup");
	         // getKembalian();
	         $("#click").trigger("click");
		});

	});
</script>
<body onload="">
<div class="konten-bayar">
	   <div class="line" style="font-size:14px;font-weight:bold">Total Bayar : <label id="tb2">0</label></div>
 
		<div class="line"><label>
		<!--  baru -->

		<label>
			Uang Cash
		</label><br>
		<input type="text" id="text1" class="myinput" style="width:200px" onkeydown="getKembalian()" >
		<!--
		<div id="w-jp" style="float:right;margin-top: -20px;display:none">
			<label>Jatuh Tempo</label><br>
			<input value="<?php echo date('Y-m-d') ?>"   id="tanggal_jt" name="tanggal_jt" style="width:100px" >
		</div>
		-->

		<button style="display:none" id="click" onclick="getKembalian()">click()</button> 
 
	
		<input style="display:none" id="bayar" type="text" placeholder="Bayar"  onkeyup="getKembalian()" class="myinput" style="width:200px"></input>
		<!--  end baru -->

		</label></div>
		<div class="line"><label>
		Kembali/Sisa<br>
		
		<input id="kembali" value="0" type="text" placeholder="kembalian" onkeyup="getKembalian()" class="myinput" style="width:200px"></input></label></div>
    <input id="btnbayar" type="button" value="Bayar" onClick="getSaleID()" class="mybutton">
    <input id="btnkembali2" type="button" value="Kembali"  class="mybutton">
    <!--div style="clear:both"></div-->
    <!--input  type="button" value="Baru" onClick="baru()" class="mybutton"-->
    <?php 
		// $userlevel = Yii::app()->user->getLevel();
		// if($userlevel < 5){
	?>
		<!-- <input  type="button" value="Void" id="btnvoid" onClick="void_bayar(1,2,sale_id)" class="mybutton" style="margin-left:10px;"> -->
	<?php //}else{?>
		<!--input  type="button" value="Void" onClick="void_cek()" class="mybutton" style="margin-left:10px;"-->
		<!-- <input type="button" value="Void" onclick='$("#void_cek").dialog("open"); return false;' class="mybutton"> -->
	<?php	
	// }
	?>
</div>


<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'void_cek',
    // additional javascript options for the dialog plugin
    'options' => array(
        'title' => 'Cek User Void',
        'autoOpen' => false,
        'modal' => true
    ),
));
$model = new Users;
$this->renderPartial('user_void',array('model'=>$model));

$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
</body>


