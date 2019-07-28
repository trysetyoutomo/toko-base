<div id="full-screen"></div>
   <div id="wrapper-item-search">
      <p class="close">X</p>
      <h1 >Pencarian Item</h1>
     
      <?php echo CHtml::dropDownList('e1', '1', Items::model()->data_items("ALL"), array('prompt'=>'Silahkan pilih','style'=>'width:100%') ); ?>
      <input style="width: 100%;margin-top: 5px;" type="button" class="mybutton" name="tambah" value="Tambah" onclick="add_item($('#e1').val())">

   </div>