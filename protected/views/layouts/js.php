<script type="text/javascript">
	 function getSatuan(selected=""){
           $.ajax({
               type: 'POST',
               url: '<?php echo Yii::app()->createAbsoluteUrl("ItemsSatuanMaster/GetSatuan"); ?>',

               success:function(data){
                // return data;
                // alert(data);
                  // alert
                  $("#Items_satuan_id").html(data).trigger("change");
                  // if (selected!=""){
                    // $("#namapel").val(selected).trigger("change");
                  // }
               },
               error:function(data){
                   // alert(data);
                   alert(JSON.stringify(data));
               },

               dataType:'html'
           });
      }


     function getkategori(selected=""){
         $.ajax({
             type: 'POST',
             url: '<?php echo Yii::app()->createAbsoluteUrl("Categories/GetKategori"); ?>',

             success:function(data){
                $("#Items_category_id").html(data).trigger("change");
                $("#Motif_category_id").html(data).trigger("change");

             },
             error:function(data){
                 // alert(data);
                 alert(JSON.stringify(data));
             },

             dataType:'html'
         });
      }

       function getSubkategori(selected=""){
         $.ajax({
             type: 'POST',
             url: '<?php echo Yii::app()->createAbsoluteUrl("Motif/GetKategori"); ?>',

             success:function(data){
                $("#Items_motif").html(data).trigger("change");
             },
             error:function(data){
                 // alert(data);
                 alert(JSON.stringify(data));
             },

             dataType:'html'
         });
      }

      $(document).ready(function(e){
      	   $("#tambah-satuan-form,#tambah-kategori-form,#tambah-subkategori-form").dialog({
           height: 200,
           width: 700
         });
         $("#tambah-satuan-form").dialog("close");
         $("#tambah-kategori-form").dialog("close");
         $("#tambah-subkategori-form").dialog("close");


    
       $(document).on("click",".tambah-satuan",function(e){
        e.preventDefault();
         var i = $(".tambah-satuan").index(this);
         $("#tambah-satuan-form").dialog("open");
       });



        $(document).on("click",".tambah-kategori",function(e){
          // alert("123");
        e.preventDefault();
         var i = $(".tambah-kategori").index(this);
         $("#tambah-kategori-form").dialog("open");
       });

        $(document).on("click",".tambah-subkategori",function(e){
          // alert("123");
        e.preventDefault();
         var i = $(".tambah-subkategori").index(this);
         $("#tambah-subkategori-form").dialog("open");
         getSubkategori();
       });

    $(document).on("submit","#motif-form",function(e){
           e.preventDefault();
            var data = $(this).serializeArray();
            // alert(data);
            data.push({ name: "isajax", value: "true" });
            // alert(data);

           $.ajax({
               type: 'POST',
               url: '<?php echo Yii::app()->createAbsoluteUrl("Motif/create"); ?>',
               data:data,
               success:function(data){
                   // alert(data);
                   if (data=="sukses"){
                      getSubkategori();
                        $("#tambah-subkategori-form").dialog("close");
                   }
                   else
                       alert(data);
               },
               error:function(data){
                   // alert(data);
                   alert(JSON.stringify(data));
               },

               dataType:'html'
           });
        });



		$(document).on("submit","#categories-form",function(e){
           e.preventDefault();
            var data = $(this).serializeArray();
            // alert(data);
            data.push({ name: "isajax", value: "true" });
            // alert(data);

           $.ajax({
               type: 'POST',
               url: '<?php echo Yii::app()->createAbsoluteUrl("Categories/CreateCategory"); ?>',
               data:data,
               success:function(data){
                   // alert(data);
                   if (data=="sukses"){
                      getkategori();
                        $("#tambah-kategori-form").dialog("close");
                   }
                   else
                       alert(data);
               },
               error:function(data){
                   // alert(data);
                   alert(JSON.stringify(data));
               },

               dataType:'html'
           });
        });


		 $(document).on("submit","#items-satuan-master-form",function(e){
           e.preventDefault();
            var data = $(this).serializeArray();
            // alert(data);
            data.push({ name: "isajax", value: "true" });
            // alert(data);

           $.ajax({
               type: 'POST',
               url: '<?php echo Yii::app()->createAbsoluteUrl("ItemsSatuanMaster/create"); ?>',
               data:data,
               success:function(data){
                   // alert(data);
                   if (data=="sukses"){
                    getSatuan();
                        $("#tambah-satuan-form").dialog("close");
                   }
                   else
                       alert(data);
               },
               error:function(data){
                   // alert(data);
                   alert(JSON.stringify(data));
               },

               dataType:'html'
           });
        });



      });
</script>
  <div id="tambah-satuan-form" title="Tambah Satuan" style="width: 500px;display: none;" >
        <?php
        $model = new ItemsSatuanMaster;
        $this->renderPartial('application.views.ItemsSatuanMaster._form',array("model"=>$model));
         ?>
      </div>

       <div id="tambah-kategori-form" title="Tambah Kateogri" style="width: 500px;display: none;" >
        <?php
        $model = new Categories;
        $this->renderPartial('application.views.categories._form',array("model"=>$model));
         ?>
  </div>

  <div id="tambah-subkategori-form" title="Tambah Sub Kategori" style="width: 500px;display: none;" >
        <?php
        $model = new Motif;
        $this->renderPartial('application.views.motif._form',array("model"=>$model));
         ?>
  </div>