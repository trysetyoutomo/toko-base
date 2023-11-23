<link href="<?php echo Yii::app()->request->baseUrl; ?>/select2/select2.css" rel="stylesheet"/>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/select2/select2.js"></script>


<style type="text/css">
    #full-screen{
      display: none;
      width: 100%;
      height: 100%;
      z-index: 999;
      background-color: rgba(0,0,0,0.4);
      position: fixed;
      top: 0px;
      bottom: 0px;
      left: 0px;
      right: 0px;
      margin: auto;
      }
     #wrapper-item-search{
      display: none;
      background-color: white;
      width: 70%;
      height: 140px;
      border: 2px solid black;
      position: fixed;
      top: 0px;
      bottom: 0px;
      left: 0px;
      right: 0px;
      margin: auto;
      z-index: 1000;
      padding: 10px;
      }
      #wrapper-item-search h1{
      font-size:20px;
      font-weight: bolder;
      }
      #wrapper-item-search .close{
      position: absolute;
      top: 10px;
      right: 10px;
      cursor: pointer;
      }
      #s2id_e1{
        width: 100%;
      }
</style>
	
<script type="text/javascript">
	function list_action(act)
      {
      	 switch(act)
          {
          	   case 113 :

                  $("#full-screen").show();
                  $("#wrapper-item-search").show();
                  $("#e1").select2("open");

                  // alert('123');
                  break;
                case 27:

                  $("#full-screen").hide();
                  $("#wrapper-item-search").hide();
                break;
             }

      }

	$(document).ready(function(e){
		 $("#full-screen").click(function(){
            $("#full-screen").hide();
            $("#wrapper-item-search").hide();
            $("#input_items").focus();
         });


         $('body').keydown(function(event){
			var message = "";
	    // var message = '<BR>ada tombol yg di pencet gan!, keyCode = ' + event.keyCode + ' which = ' + event.which;
	    // alert(event.keyCode);
          if (event.keyCode>=0 || event.charCode>=0 || event.which>=0 ){
            // alert("123");
              // message = message + '<BR>F1 - F12 / enter pressed';
              list_action(event.keyCode);
          }else{
            // alert("456");
              // list_action_other(event.which);
              // message = message + '<BR>key other than F1 - F12 pressed';
          }

          //print pesan
          // $('#msg-keypress').html(message)

      });




         
	});
</script>
<div id="full-screen"></div>
	<div id="wrapper-item-search">
	  <p class="close">X</p>
	  <h1 >Pencarian Item</h1>
	 
	  <?php echo CHtml::dropDownList('e1', '1', $model, array('prompt'=>'Silahkan pilih','style'=>'width:100%') ); ?>
	  <input style="width: 100%;margin-top: 5px;" type="button" class="tambah-non-barcode mybutton btn-primary" name="Pilih" value="Tambah" onclick="add_item($('#e1').val())">

</div>