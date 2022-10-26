<script>
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


     function reloadItems()
      {
          $.ajax({
              type: 'POST',
              url: '<?php echo Yii::app()->createAbsoluteUrl("Items/getAllItems"); ?>',
              // data:data,
              success:function(data){
                  // alert(data);
                  $("#e1").html(data);
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

      function getSatuan(selected=""){
           $.ajax({
               type: 'POST',
               url: '<?php echo Yii::app()->createAbsoluteUrl("itemsSatuanMaster/GetSatuan"); ?>',

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

      function clearItems(){
        $("#Items_category_id").val(0);
        $("#Items_letak_id").val(0);
        $("#Items_motif").val(0);
        $("#Items_satuan_id").val(0);
        $("#Items_modal").val(0);

        $("#Items_item_name").removeAttr("value");
        $("#Items_description").val("-");
        $("#Items_modal").removeAttr("value");
        $("#Items_total_cost").removeAttr("value");
        $("#Items_stok_minimum").removeAttr("value");
        $("#Items_discount").removeAttr("value");
        // $("#is_generate").attr("checked","true");

        $("#head-meja").html("");
        $("#head-meja-nilai").html("");
      }

    $(document).on("submit", "#items-form", function (e) {
        e.preventDefault();
        var data = $(this).serializeArray();
        // alert(data);
        data.push({ name: "isajax", value: "true" });
        // alert(data);

        $.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createAbsoluteUrl("Items/create"); ?>',
            data: data,
            success: function (data) {
                // alert(data);
                if (data == "sukses") {
                    reloadItems();
                    clearItems();
                    $("#tambah-item-baru").modal("hide");
                    generateBarcodeAction();
                }
                else {
                    alert(data);
                }
            },
            error: function (data) {
                // alert(data);
                alert(JSON.stringify(data));
            },

            dataType: 'html'
        });
    });
    $(document).on("click","#tambah-pelanggan-2",function(e){
        e.preventDefault();
        
        // $("#tambah-paket-baru").modal("show");
            $("#tambah-pelanggan-form").modal("show");
            $("#Customer_nama").focus();
    });

  $(document).on("click",".tambah-satuan",function(e){
        e.preventDefault();
         var i = $(".tambah-satuan").index(this);
         $("#tambah-satuan-form").modal("show");
       });



        $(document).on("click",".tambah-kategori",function(e){
          // alert("123");
        e.preventDefault();
         var i = $(".tambah-kategori").index(this);
         $("#tambah-kategori-form").modal("show");
       });

        $(document).on("click",".tambah-subkategori",function(e){
          // alert("123");
        e.preventDefault();
         var i = $(".tambah-subkategori").index(this);
         $("#tambah-subkategori-form").modal("show");
         getSubkategori();
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
                        $("#tambah-kategori-form").modal("hide");
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
                        $("#tambah-subkategori-form").modal("hide");
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
               url: '<?php echo Yii::app()->createAbsoluteUrl("itemsSatuanMaster/create"); ?>',
               data:data,
               success:function(data){
                   // alert(data);
                   if (data=="sukses"){
                        getSatuan();
                        $("#tambah-satuan-form").modal("hide");
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

    
</script>