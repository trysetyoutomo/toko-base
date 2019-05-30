<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<style type="text/css">
	#input-voucher{
		position: absolute;
		padding: 20px;
		background: #D3D3D3;
		top: 0px;
		left: 0px;
		width: 100%;
	    height: 100%;
	    margin: 0px;
	    display: none;
	    color: black;

	}
	.metode label{
		width: 150px;
	}
</style>
<div id="input-voucher">
	<form id="form-iv">
		Masukan kode voucher <input name="id_v" id="id_v" style="color:black" type="text" >
		<input type="button" id="submit" value="ok">
		<input type="button" id="close-v" value="close">
	</form>
</div>
<script>
    $(document).ready( function(){
    	
    	$('#cash,#edcbca,#creditbca,#creditmandiri,#voucher,#dll,#compliment,#edcniaga').keypress(function (e) {
		  if (e.which == 13) {
		   $(".next_first").trigger("click");
		    return false;    //<---- Add this line
		  }
		});
		// $('#text1').keypress(function (e) {
		//   if (e.which == 13) {
		//   	getSaleID();
		//    // $("#btnbayar").trigger("click");
		//     return false;    //<---- Add this line
		//   }
		// });
		
    	$('#close-v').click(function(e){
    		$('#input-voucher').hide();
    		$('#id_v').val(" ");

    	});

    	$('#submit').click(function(e){
    		// e.preventDefault();
    		// alert('submit');
    		// alert('masuk');
    		$.ajax({
				url : "<?php echo Yii::app()->createUrl('voucher/getnominal') ?>",
				data : "id_v="+$('#id_v').val(),
				success: function(data){
					// alert(data);
		        	var obj = jQuery.parseJSON(data);
		        	// alert(JSON.stringify(obj));
		        	// alert(obj.error);
		        	if (obj.error=='error'){
						$('.error').html("Maaf, voucher tidak tersedia");
		        	}else{
		        		if (obj.jenis=='nominal'){	
							$('.tdk-lgsg').val(obj.nominal);
							changebayar();
							$('.error').html('Voucher '+obj.kategori+' Rp. '+obj.nominal+' ');
		        		}else if (obj.jenis=='persentase'){
		        			// alert('Diskon '+obj.persentase+' %')
		        			$('.error').html('Voucher '+obj.kategori+' '+obj.persentase+' %');
		        			var total = parseFloat($('#sum_sale_total').html());
		        			var total  = total  * (parseInt(obj.persentase)/100);  
		        			$('.tdk-lgsg').val(total);
							changebayar();
		        		}
		        		
		        	}
		        	$('.error').show();
					// if(data!='eror'){
					// }
					// else{
					// 	$('.error').show();
					// }
				},

				// 'dataType':""
    		});

    		$('#input-voucher').hide();

    	});

	  $('.langsung').click(function(){
            $("#bank-kredit").val(0);
            $("#bank-debit").val(0);
	  });
	  $('.metode').click(function(){
	  	$('.error').hide();
	  	var sst = parseFloat($('#sum_sale_total').html());
        // var isi = $(this).attr("isi");
        $('.langsung').val(0);
        // document.getElementById';(asd).asda.asd.asd.
        $(this).find('.langsung').val(sst);
        // alert(c);

        // var nilai = $('.line').find('.myinput').val();
        // alert(nilai);
        // console.log("masuk"+nilai);
   	 });
	  // $('.tdk-lgsg').click(function(){
	  // 	$('#input-voucher').show();
	  // 	// var nilai = prompt("masukan kode voucher");
	  // });



        $(".cb-enable").click(function(){
            var parent = $(this).parents('.switch');
            $('.cb-disable',parent).removeClass('selected');
            $(this).addClass('selected');
            $('.checkbox',parent).attr('checked', true);
        });
        $(".cb-disable").click(function(){
            var parent = $(this).parents('.switch');
            $('.cb-enable',parent).removeClass('selected');
            $(this).addClass('selected');
            $('.checkbox',parent).attr('checked', false);
        });
    });
	
	function baru()
	{
	   liveSearchPanel_SalesItems.store.removeAll();
		show_meja("Meja");
		
		kalkulasi1();
	}
	
</script>

<?php
// echo CHtml::textField('pembayaran', '0', array('onkeypress' => 'return runScript(event,"id_bayar")'));
// echo CHtml::dropDownList('payment', '0', Sales::model()->payment());
?>

<div class="konten-bayar">
	<style type="text/css">
	.line :hover{
		color: red;
		cursor: pointer;
	}
	.error{
		color: red;
		font-weight: bold;
		display: none;
	}
	</style>

    <div class="line " style="font-size:14px;font-weight:bold">Total Bayar : <label id="total_bayar">0</label></div>
    
	<div class="line metode" isi="cash">
		<label  style="font-size:20px">Cash
		</label>
			<input id="cash" value="0" onkeyup="changebayar()" type="text" placeholder="Bayar" class="myinput langsung" style="width:200px;height:50px;font-size:20px" >
	</div>

    <div class="line metode"  isi="bca"  > 
    	<label style="font-size:20px"> DEBIT
    	</label>
    		<input value="0" onkeyup="changebayar()" id="edcbca" type="text" placeholder="Bayar" class="myinput langsung" style="width:200px;height:50px;font-size:20px">
    		<input type="hidden" name="no_kartu_debit" id="no_kartu_debit">
    		<!-- <input type="" name=""> -->
    		<select id="bank-debit" style="width:150px;height:50px;font-size:20px" class="myinput">
    			<option value="0">Pilih</option>

    			<?php 
    			$m = Bank::model()->findAll("aktif=1");
    			foreach ($m as $key => $value) {
    				echo "<option  value='$value->nama'>$value->nama</option>";
    			} ?>
    		</select>
    		<!-- <input type="button" class="mybutton" value="Input No Kartu"> -->
	</div>

    <div class="line metode" isi="niaga"  >
	    <label style="font-size:20px">KREDIT
	    </label> 
		    <input value="0" onkeyup="changebayar()" id="edcniaga" type="text" placeholder="Bayar" class="myinput langsung" style="width:200px;height:50px;font-size:20px">
    		<input type="hidden" name="no_kartu_kredit" id="no_kartu_kredit">
    		<span style="margin-top: 10px;">
    		<select id="bank-kredit" style="width:150px;height:50px;font-size:20px" class="myinput">
    			<option value="0">Pilih</option>
    			<?php 
    			$m = Bank::model()->findAll("aktif=1");
    			foreach ($m as $key => $value) {
    				echo "<option  value='$value->nama'>$value->nama</option>";
    			} ?>
    		</select>
    			<div style="clear: both"></div>
    		</span>
			<!-- <input type="button" class="mybutton" value="Input No Kartu"> -->

    </div>

	<div    class="line metode"  isi="bca1" style="display: none;">
    	<label style="font-size:20px">TRANSFER MANDIRI
    	</label>
    		<input value="0" onkeyup="changebayar()" id="creditbca" type="text" placeholder="Bayar" class="myinput langsung" style="width:200px;height:50px;font-size:30px">
	</div>

	<div  style="display:none"  class="line metode"  isi="mandiri">
    	<label style="font-size:20px">CREDIT MANDIRI
    	</label>
    		<input value="0" onkeyup="changebayar()" id="creditmandiri" type="text" placeholder="Bayar" class="myinput langsung" style="width:200px;height:50px;font-size:30px">
	</div>

 	<div style="display:none" class="line metode" isi="voucher">
	    <label style="font-size:20px">Voucher 
	    	<input id="voucher" onkeyup="changebayar()" value="0" type="text" placeholder="Bayar" class="myinput tdk-lgsg" style="margin-right:20px;width:200px;height:50px;font-size:30px">
	    </label>
    </div>
     
    <div class="line metode" isi="compliment" style="display:none">
	    <label style="font-size:20px">Compliment
	    	<input id="compliment" type="text" placeholder="Bayar" class="myinput langsung" value="0" onkeyup="changebayar()" style="width:200px;height:50px;font-size:30px">
    	</label>
    </div>
    

    <div  style="display:none" class="line metode" isi="pending">
	    <label style="font-size:20px">Pending
	    	<input value="0"	 id="dll" onkeyup="changebayar()" type="text" placeholder="Bayar" class="myinput langsung" style="width:200px;height:50px;font-size:30px">	
	    </label>
    </div>
    <div class="line"><?php //echo CHtml::dropDownList('payment', '0', Sales::model()->payment(), array('style'=>'width:200px')); ?></div>
    <!--div style="clear:both"></div-->
    
	<p class="error"></p>	
    <div class="line" style=""><label><input type="text" class="myinput" readonly="readonly" placeholder="Kembalian" style="width:200px" id="kembalian"></input></label></div>
	<input id="btnbayar" type="button" value="selanjutnya" onclick="nextpayment()" class="mybutton next_first" />
   <!--input  type="button" value="Baru" onClick="baru()" class="mybutton"-->
</div>
<script>

	// function
	
	
	function nextpayment(){
		var kmb = $('#kembalian').val();
		var totaljual = $("#sum_sale_total").html();
		var totalpayment = parseInt($("#cash").val())+parseInt($("#edcbca").val())+parseInt($("#edcniaga").val())+parseInt($("#creditbca").val())+parseInt($("#creditmandiri").val())+parseInt($("#voucher").val())+parseInt($("#compliment").val())+parseInt($("#dll").val());
		 // alert(totaljual);
		 // alert(totalpayment);
		 $("#text1").select();
		 if (parseInt(totalpayment)==parseInt(totaljual)){
			$('#tb2').html(parseInt($('#total_bayar').html())-parseInt($('#voucher').val())-parseInt($('#edcbca').val())-parseInt($('#edcniaga').val())-parseInt($('#creditbca').val())-parseInt($('#creditmandiri').val())-parseInt($('#dll').val())-parseInt($('#compliment').val())   );
			var kembalian = estimate($("#tb2").html())-$("#tb2").html();
			// alert($('#tb2').html());
			if ($('#tb2').html()!=0){
				$('#bayar').val(estimate($('#tb2').html()));
				$('#text1').val(estimate($('#tb2').html()));
			}else{
				$('#bayar').val("100");
				//mulai get
					var bayar = Math.round($("#total_bayar").val());
					// var total = $("#tb2").html();
					var total = Math.round($("#sum_sale_total").html());
					var kembalian = parseInt(bayar)-parseInt(total);
					// alert(kembalian);
					$("#kembali").val(kembalian);
				//tutup get
			}
			
			$('#kembali').attr("asli",kembalian);
			$('#kembali').val(numberWithCommas(kembalian) );



			if ($("#edcbca").val()!="0" || $("#edcniaga").val()!="0" ){
					if ($("#bank-kredit").val()!="0" || $("#bank-debit").val()!="0" ){
						$("#text1").val(0);
						$("#kembali").val(0);
					}else{
						alert("Silahkan pilih bank ");
						return false;
					}
				

			}

			$('#dialog_bayar2').dialog('open');
			
			$("#text1").select();

		}else{
			alert("Jumlah tidak sesuai");
		}
	}

    function collect_data()
    {
        $(function(){
            alert($("#sum_sub_total").html());            
        });
    }
    
    function kalkulasi()
    {
        $("#kembalian").val();
    }
	
	
</script>

<!--input id="id_bayar" type="button" onclick='bayar(1,2,sale_id)' value="Bayar"-->