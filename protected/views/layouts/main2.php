<?php ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <!-- Meta, title, CSS, favicons, etc. -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="icon" type="image/x-icon" href="<?php echo Yii::app()->request->baseUrl; ?>/images/bandung.png">
      <?php 
      
      ?>
      <title><?php echo Yii::app()->name ?> </title>
      <script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/jquery/dist/jquery.min.js"></script>


      <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-custom/jquery-ui.min.css">


      <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/toast/src/jquery.toast.js"></script>
      <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/toast/src/jquery.toast.css">




         <!-- Toast -->
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/toast/src/main/javascript/jquery.toastmessage.js"></script>
        <link rel="Stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/toast/src/main/resources/css/jquery.toastmessage.css" />



      <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-custom/jquery-ui.min.js"></script>
      <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

      <link href="<?php echo Yii::app()->request->baseUrl; ?>/select2/select2.css" rel="stylesheet"/>
      <script src="<?php echo Yii::app()->request->baseUrl; ?>/select2/select2.js"></script>


<script>


    function blinker() {
      $('.blink_me').fadeOut(500).fadeIn(500);
    }

    // setInterval(blinker, 10000); //Runs every second
    // var audioElement = document.createElement('audio');
    function cek(){
      // alert("123");
      $.ajax({
          url: "<?php echo Yii::app()->createUrl("items/notifikasiJSON")?>",
          cache: false,
          success: function(msg){
            var data = jQuery.parseJSON(msg);

        // alert(data.count);
        if (data.count>0){      
            $(".btn-notif-stok").addClass("blink_me");
            // alert(JSON.stringify(data.model));
              var no = 1;

              // if (data.level=="2"){
                $.each( data.data, function( key, value ) {
                    // alert(JSON.stringify(value));
                var message = "Item "+value.item_name+" telah berada di bawah stok minimum ("+value.stok_minimum+")";

                // $().toastmessage('showToast', {
                //     text : message,
                //     sticky : false,
                //     type     : 'warning',
                //     position : 'top-left'
                // });
                // alert(value.nama);
                  $('#data tbody').append(
                    "<tr>"+
                    "<td>"+no+"</td>"+
                    "<td>"+value.barcode+"</td>"+
                    "<td>"+value.nama+"</td>"+
                    "<td>"+value.stok+"</td>"+
                    "<td>"+value.stok_minimum+"</td>"+
                    "</tr>"
                  );
                  no++;
                });
                // $('#notifikasi').fadeIn();
                // $('#full').fadeIn();

            //   }else{
            //     $('#notifikasi-peralatan').fadeIn();
            //     $('#full').fadeIn();
            //       $.each( data.model, function( key, value ) {
            //       $('#data tbody').append(
            //         "<tr>"+
            //         "<td>"+no+"</td>"+
            //         "<td>"+value.kode+"</td>"+
            //         "<td>"+value.nama_barang+"</td>"+
            //         "<td>"+value.tanggal+"</td>"+
            //         "<td>"+value.habis+"</td>"+
            //         "</tr>"
            //       );
            //       no++;
            //     });
            //    // alert("123");
            //    // notifikasi-peralatan
              
            // }


                
              // $("#notifikasi").fadeIn();
              // audioElement.setAttribute('src', '../sound/audio.mp3');
              // audioElement.setAttribute('autoplay', 'autoplay');
              // audioElement.setAttribute('loop', 'loop');
              //audioElement.load()

              // $.get();

              // audioElement.addEventListener("load", function() {
              //   audioElement.play();
              // }, true);

              // $('.play').click(function() {
              //   audioElement.play();
              // });

              // $('.pause').click(function() {
              //   audioElement.pause();
              // });
              // clearTimeout(waktu);
          
        }else{
          $('#count-notif').html('Maintenance'+'(0)');
          $('#count-notif').removeClass('blink_me');
          $(".btn-notif-stok").removeClass("blink_me");
        }
              // $("#notifikasi").html(msg);
        // alert('ada maintenance baru nih');
          }
      });
      // var waktu = setTimeout("cek()",3000);
    }

    



    $(document).ready(function(){
      $(".tobe-select2").select2();
      cek();
      // $(document).on("click",".fa-times",function(e){
        // $("#full").fadeOut();
        // $("#notifikasi").fadeOut();
        // $("#notifikasi-peralatan").fadeOut();
        // });

        



    });

    </script>
 <script type="text/javascript">
  $(document).ready(function(e){

      

    $(document).on("click",".hapus",function(e){
      if (!confirm("Yakin ? ")){
        return false;
      }

    });
    
    $(".hapus,.delete").click(function(e){
      // e.preventDefault();
      if (!confirm("Yakin ? ")){
        return false;
      }

    });

    $('a[href^="#"]').on('click',function (e) {
        e.preventDefault();

        var target = this.hash;
        var $target = $(target);

        $('html, body').stop().animate({
            'scrollTop': $target.offset().top
        }, 900, 'swing', function () {
            window.location.hash = target;
        });
    });
  });

</script>

      <!-- Bootstrap -->
      <link href="<?php echo Yii::app()->request->baseUrl; ?>/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
      <!-- Font Awesome -->
      <link href="<?php echo Yii::app()->request->baseUrl; ?>/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
      <!-- iCheck -->
      <link href="<?php echo Yii::app()->request->baseUrl; ?>/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
      <!-- bootstrap-progressbar -->
      <link href="<?php echo Yii::app()->request->baseUrl; ?>/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
      <!-- jVectorMap 
      <link href="<?php echo Yii::app()->request->baseUrl; ?>/production/css/maps/jquery-jvectormap-2.0.3.css" rel="stylesheet"/>
      -->
      <!-- Bootstrap -->
      <link href="<?php echo Yii::app()->request->baseUrl; ?>/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
      <!-- Font Awesome -->
      <link href="<?php echo Yii::app()->request->baseUrl; ?>/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
      <!-- iCheck -->
      <link href="<?php echo Yii::app()->request->baseUrl; ?>/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
      <!-- Datatables -->
      <link href="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
      <link href="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
      <link href="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
      <link href="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
      <link href="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
      <!-- Custom Theme Style -->
      <link href="<?php echo Yii::app()->request->baseUrl; ?>/production/css/custom.css" rel="stylesheet">
      <div class="">
      <!-- swipebox-->
      <link href="<?php echo Yii::app()->request->baseUrl; ?>/js/swipebox/css/swipebox.min.css" rel="stylesheet">
      <div class="">

      <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/swipebox/js/jquery.swipebox.min.js"></script>
      <!-- Custom Theme Style -->
      <link href="<?php echo Yii::app()->request->baseUrl; ?>/production/css/custom.css" rel="stylesheet">
      <style type="text/css">
         body input{
          text-transform: uppercase;

         }
      .grid-view table.items th {
          color: white;
          background: rgba(42, 63, 84,1);
          text-align: center;
      }
         @font-face{
         src:url("font/Raleway-Regular.ttf");
         font-family: "Raleway-Regular";
         }
         @font-face{
         src:url("font/Sahitya Regular.ttf");
         font-family: "Sahitya";
         }
         p,h1,h2,h3,h4,h5,a,td,th,label,body{
           font-family: 'Open Sans', sans-serif;
         }
         table.detail-view tr.odd {
         background: #dedede;
         }
         .widget-table{  
         width: 100%;
         overflow-x: auto;
         }
         a:hover{
         color:green;
         text-decoration: underline;
         }
         input{
         padding:5px;
         }
         #datatable_wrapper{
         overflow: auto;
         }
         .errorMessage{
         color: red;
         display: none;
         }
         input.error,select.error{
         border:1px solid red;
         }
         .modal-dialog {
         width:700px!important;
         }
         .fa,.glyphicon{
         cursor: pointer;
         }
         div.wide.form label
         {
         float: left;
         margin-right: 10px;
         position: relative;
         text-align: right;
         width: 100px;
         }
         div.wide.form .row
         {
         clear: left;
         padding: 4px;
         }
         div.wide.form .buttons, div.wide.form .hint, div.wide.form .errorMessage
         {
         clear: left;
         padding-left: 110px;
         }
         #notifikasi,#notifikasi-peralatan{
         width: 50%;
         height: 50%;
         position: absolute;
         background-color: #942321;
         color: white;
         border:2px solid black;
         z-index: 200;
         top: 0px;
         bottom: 0px;
         left: 0px;
         right: 0px;
         margin: auto;
         display: none;
         padding: 10px;
         overflow-y:auto; 
         }
         #full{
         display: none;
         position: fixed;
         width: 100vw;
         height: 100vh;
         background-color: white;
         top: 0px;
         bottom: 0px;
         left: 0px;
         right: 0px;
         margin: auto;
         z-index: 100;
         }
         #tanda-tangan,.tanda-tangan{
         margin-left:30px;
         display: none;
         }
         thead th{
         color: black;
         /*font-weight: bolder;*/
         }
         .sidebar-footer{
         display: none;
         }
         .loader{
         display: none;
         width: 100px;
         height: 100px;
         position: fixed;
         top: 0px;
         bottom: 0px;
         left: 0px;
         right: 0px;
         margin: auto;
         z-index: 101;
         }
         .loader img{
         width: 200px;
         }
         .table tr td,.table tr th{
         border: 1px solid gray!important;
         }
          /*.print{display: ;}*/
         table.table-bordered.dataTable{
         border-collapse: collapse!important;
         }
         body{
         color: black!important;
         }
         table .fa{
         color: black!important;
         }

         .print{
          display: none;
         }
         @media print{

         .fa,.breadcrumb{
         display: none!important;
         }

         .print{
          display: block!important;

         }
        

         }
      </style>
      <style type="text/css">
  
 
</style>

   </head>
   <body class="nav-md">
   <?php 
   // $branch = Yii::app()->user->branch();
   // var_dump($branch);
   ?>
      <div class="loader">
         <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/loader.gif">
      </div>
      <div id="full"></div>

      <!-- peralatan -->
      <div id="notifikasi-peralatan">
         <h4>Pemberitahuan Peralatan </h4>
         <div class="fa fa-times" 
            style="width: 10px;
            height: 10px;
            position: absolute;
            right: 10px;
            top: 10px;"
            ></div>
         <table id="data" class="table">
            <thead>
               <tr>
                  <td>No</td>
                  <td>Kode</td>
                  <td>Nama Barang</td>
                  <td>Tanggal Pembelian</td>
                  <td>Tanggal Habis</td>
               </tr>
            </thead>
            <tbody></tbody>
         </table>
      </div>
      <script>
         // function blinker() {
           // $('.blink_me').fadeOut(500).fadeIn(500);
         // }
         
         // setInterval(blinker, 10000); //Runs every second
         // var audioElement = document.createElement('audio');
         // function cek(){
         //   $.ajax({
         //       url: "<?php echo Yii::app()->createUrl("Masuk/notifikasi")?>",
         //       cache: false,
         //       success: function(msg){
         //         var data = jQuery.parseJSON(msg);
         
         //     // alert(data.count);
         //     if (data.count>0){        
         //         // alert(JSON.stringify(data.model));
         //           var no = 1;
         
         //           if (data.level=="2"){
         //             $.each( data.model, function( key, value ) {
         //             // alert("123");
         //               $.toast({
         //                 heading: 'Perhatian',
         //                 text: 'Barang : '+value.nama+" <br> Stok Minimum : "+value.stok_minimum+" <br> Stok Saat ini : "+value.stok+" <br> <a href='<?php echo Yii::app()->createUrl('perlengkapan/masuk') ?>'> Masukan Barang </a>",
         //                 position: 'top-right',
         //                 stack: false,
         //                 hideAfter : 10000,   
         //                 stack : 6,     
         //                 icon: 'error'
         //              });
         //               no++;
         //             });
         //               // $('#data tbody').append(
         //               //   "<tr>"+
         //               //   "<td>"+no+"</td>"+
         //               //   "<td>"+value.nama+"</td>"+
         //               //   "<td>"+value.stok+"</td>"+
         //               //   "<td>"+value.stok_minimum+"</td>"+
         //               //   "</tr>"
         //               // );
         //             // $('#notifikasi').fadeIn();
         //             // $('#full').fadeIn();
         
         //           }else{
         //             $('#notifikasi-peralatan').fadeIn();
         //             $('#full').fadeIn();
         //               $.each( data.model, function( key, value ) {
         //                   $.toast({
         //                     heading: 'Perhatian',
         //                     text: 'Barang : '+value.nama_barang+" <br> Tanggal Kadaluarsa : "+value.tanggal+" <br> Stok Saat ini : "+value.habis+" <br> <a href='<?php echo Yii::app()->createUrl('perlengkapan/masuk') ?>'> Masukan Barang </a>",
         //                     position: 'top-right',
         //                     stack: false,
         //                     hideAfter : 10000,   
         //                     stack : 6,     
         //                     icon: 'error'
         //                  });
         //               // $('#data tbody').append(
         //               //   "<tr>"+
         //               //   "<td>"+no+"</td>"+
         //               //   "<td>"+value.kode+"</td>"+
         //               //   "<td>"+value.nama_barang+"</td>"+
         //               //   "<td>"+value.tanggal+"</td>"+
         //               //   "<td>"+value.habis+"</td>"+
         //               //   "</tr>"
         //               // );
         //               no++;
         //             });
         //            // alert("123");
         //            // notifikasi-peralatan
                   
         //         }
         
         
                     
         //           // $("#notifikasi").fadeIn();
         //           audioElement.setAttribute('src', '../sound/audio.mp3');
         //           audioElement.setAttribute('autoplay', 'autoplay');
         //           audioElement.setAttribute('loop', 'loop');
         //           //audioElement.load()
         
         //           $.get();
         
         //           audioElement.addEventListener("load", function() {
         //             audioElement.play();
         //           }, true);
         
         //           $('.play').click(function() {
         //             audioElement.play();
         //           });
         
         //           $('.pause').click(function() {
         //             audioElement.pause();
         //           });
         //           clearTimeout(waktu);
               
         //     }else{
         //       $('#count-notif').html('Maintenance'+'(0)');
         //       $('#count-notif').removeClass('blink_me');
         //     }
         //           // $("#notifikasi").html(msg);
         //     // alert('ada maintenance baru nih');
         //       }
         //   });
         //   // var waktu = setTimeout("cek()",3000);
         // }
         
         $(document).ready(function(){
          // alert("123");
             
           $('.swipebox').swipebox();
         
           $(document).on("click",".fa-times",function(e){
             $("#full").fadeOut();
             $("#notifikasi").fadeOut();
             $("#notifikasi-peralatan").fadeOut();
             $("#wrapper-dp").fadeOut();
           });
         
         
          // $(document).on("submit", "#supplier-form", function(e) {
          //    e.preventDefault();
         
          //    // alert();
          //  if (!confirm(" Yakin ?")){
          //    return false;
          //  }
         
         
          //    $.ajax({
          //      url: $(this).attr("action"), 
          //      data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
          //      type: "POST", 
          //      contentType: false,       // The content type used when sending data to the server.
          //      cache: false,             // To unable request pages to be cached
          //      processData:false,    
          //                success: function(result) {
          //                  // alert(result);
                       
          //                  var c = '<?php echo Yii::app()->controller->id;  ?>';
          //                  var a  = '<?php echo Yii::app()->controller->action->id ?>';
          //                    var data = jQuery.parseJSON(result);
          //                    var result = data.status;
          //                    if (result == "1") {
         
          //                      if (c=='supplier' && a=="create"){
          //                        window.location.assign("index.php?r=supplier/admin");
          //                      }else if (c=='supplier' && a=="update"){
          //                        window.location.assign("index.php?r=supplier/admin");
          //                      }else{  
          //                        $("#wrapper-dp").hide();
          //                        $("#full").hide();
          //                        reloadListSupplier();
          //                      }
          //                    } else {
          //                      // alert(JSON.stringify(data.error));
          //                      $.each(data.error, function (index, value) {
          //                        // alert(value);
          //                          $.toast({
          //                            heading: 'Perhatian',
          //                            text: index + " : " + value,
          //                            position: 'top-right',
          //                            stack: true,
          //                            icon: 'error'
          //                         });
          //                      });
          //                    }
          //                },
          //                error: function(result) {
          //                    alert(result);
          //                    // alert(JSON.stringify(result));
          //                    // alert('data tidak boleh kosong');
          //                }
          //            });
          //                  // } else {
         
          // });
         function reloadListSupplier(){
         $.ajax({
           url: "<?php echo Yii::app()->createurl('supplier/getList'); ?>", 
             success: function(result) {
               $("#supplier").html(result);
               $("#supplier").select2();
               // alert("loaded");
             },
             error: function(result) {
             }
         });
         }
         function reloadListRuangan(){
         $.ajax({
                 url: "<?php echo Yii::app()->controller->createurl('divisi/getList'); ?>", 
                 success: function(result) {
                   $("#divisi_id").html(result);
                   $("#divisi_id").select2();
                   // alert("loaded");
                 },
                 error: function(result) {
                 }
             });
         }
         
         $(document).on("submit", "#divisi-form", function(e) {
             e.preventDefault();
           if (!confirm(" Yakin ?")){
             return false;
           }
         
         
             $.ajax({
               url: $(this).attr("action"), 
               data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
               type: "POST", 
               contentType: false,       // The content type used when sending data to the server.
               cache: false,             // To unable request pages to be cached
               processData:false,    
                         success: function(result) {
                           // alert(result);
                           var c = '<?php echo Yii::app()->controller->id;  ?>';
                           var a  = '<?php echo Yii::app()->controller->action->id ?>';
                             var data = jQuery.parseJSON(result);
                             var result = data.status;
                             if (result == "1") {
         
                               if (c=='divisi' && a=="create"){
                                 window.location.assign("index.php?r=divisi/admin");
                               }else if (c=='divisi' && a=="update"){
                                 window.location.assign("index.php?r=divisi/admin");
                               }else{  
                                 $("#wrapper-dp").hide();
                                 $("#full").hide();
                                 reloadListRuangan();
                               }
                             } else {
                               // alert(JSON.stringify(data.error));
                               $.each(data.error, function (index, value) {
                                 // alert(value);
                                   $.toast({
                                     heading: 'Perhatian',
                                     text: value,
                                     position: 'top-right',
                                     stack: true,
                                     icon: 'error'
                                  });
                               });
                             }
                         },
                         error: function(result) {
                             alert(result);
                             // alert(JSON.stringify(result));
                             // alert('data tidak boleh kosong');
                         }
                     });
                           // } else {
         
          });
         
         
         
         
         
           // cek();
         
         });
         
      </script>
      <style>
         .blink_me {
         -webkit-animation-name: blinker;
         -webkit-animation-duration: 1s;
         -webkit-animation-timing-function: linear;
         -webkit-animation-iteration-count: infinite;
         -moz-animation-name: blinker;
         -moz-animation-duration: 1s;
         -moz-animation-timing-function: linear;
         -moz-animation-iteration-count: infinite;
         animation-name: blinker;
         animation-duration: 1s;
         animation-timing-function: linear;
         animation-iteration-count: infinite;
         }
         @-moz-keyframes blinker {  
         0% { opacity: 1.0; }
         50% { opacity: 0.0; }
         100% { opacity: 1.0; }
         }
         @-webkit-keyframes blinker {  
         0% { opacity: 1.0; }
         50% { opacity: 0.0; }
         100% { opacity: 1.0; }
         }
         @keyframes blinker {  
         0% { opacity: 1.0; }
         50% { opacity: 0.0; }
         100% { opacity: 1.0; }
         }
      </style>
      <div class="container body">
         <div class="main_container">
            <div class="col-md-3 left_col">
               <div class="left_col scroll-view">
                  <div class="navbar nav_title" style="border: 0;">
                     <a href="#" class="site_title" style="text-align: center">
                       <?php 
                       $parameter = Parameter::model()->findByPk(1); ?>
                         <img style="width:40%;margin:0 auto" class="profile_img" src="<?php echo Yii::app()->request->baseUrl; ?>/logo/<?php echo $parameter->gambar_putih ?>" alt="">
                     </a>
                  </div>
                  <div class="clearfix"></div>
                  <!-- menu profile quick info -->
                  <div class="profile">
                     <div class="profile_pic">
                        <!-- <img style="visibility:hidden" src="<?php echo Yii::app()->request->baseUrl; ?>/production/images/img.jpg" alt="..." class="img-circle profile_img"> -->
                           

                     </div>
                   <!--   <div class="profile_info">
                        <span>Welcome,</span>
                        <h2><?php echo Yii::app()->user->name ?></h2>
                     </div> -->
                  </div>
                  <!-- /menu profile quick info -->
                  <br />
                  <!-- sidebar menu -->
                  <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                     <div class="menu_section">
                        <h3></h3>
                        <ul class="nav side-menu">
                        <li>
                              <a href="<?php echo Yii::app()->createUrl("site/admin") ?>"><i class="fa fa-home"></i> Beranda  </a>
                          </li>
                             <li>
                              <a class="btn-notif-stok" href="<?php echo Yii::app()->createUrl("items/notifikasi") ?>"><i class="fa fa-bell"></i> Pemberitahuan  </a>
                          </li>
                        <?php 

                        $role_id = Yii::app()->user->level();
                         
                        $sql = "select cmc.* from config_menu_category as cmc 
                        inner join config_menu cm on cm.category_menu_id = cmc.id 
                        INNER JOIN config_role_detail crd on crd.menu_id=cm.id
                        INNER JOIN config_role cr on cr.id = crd.role_id
                        where 
                        cr.id = '$role_id'




                        group by cmc.id
                        order by cmc.urutan asc
                         ";
                          $data = Yii::app()->db->createCommand($sql)->queryAll();

                        foreach ($data as $key => $value) {
                        ?>
                        <li>
                              <a><?php echo $value['icon'] ?><?php echo $value['category_name'] ?> <span class="fa fa-chevron-down"></span></a>
                              <ul class="nav child_menu" style="display: none">
                              <?php 
                               $sql = "SELECT
                                  cm.*  
                                FROM
                                  config_menu AS cm
                                INNER JOIN config_role_detail crd on crd.menu_id=cm.id
                                INNER JOIN config_role cr on cr.id = crd.role_id
                                where cm.category_menu_id = '$value[id]' and cm.hapus = 0 and cr.id = '$role_id'
                                ";
                              $detail = Yii::app()->db->createCommand($sql)->queryAll();
                              ?>
                              <?php 
                              foreach ($detail as $key2 => $value2) { ?>
                              
                                  <li><a href="<?php echo Yii::app()->createUrl("$value2[controllerID]/$value2[actionID]") ?>"><span><?php echo $value2['value'] ?></span></a></li>
                              <?php 
                              }
                              ?>
                                 

                              </ul>
                           </li>
                       

                        
                        <?php 
                          }
                        ?>
                        </ul>
                        
                     </div>
                  </div>
                  <!-- /sidebar menu -->
                  <!-- /menu footer buttons -->
                  <div class="sidebar-footer hidden-small">
                     <a style="visibility:hidden" data-toggle="tooltip" data-placement="top" title="Settings">
                     <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                     </a>
                     <a style="visibility:hidden" data-toggle="tooltip" data-placement="top" title="FullScreen">
                     <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                     </a>
                     <a style="visibility:hidden" data-toggle="tooltip" data-placement="top" title="Lock">
                     <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                     </a>
                     <!--              <a href="<?php echo Yii::app()->createUrl('site/logout') ?>" data-toggle="tooltip" data-placement="top" title="Logout">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                        </a> -->
                  </div>
                  <!-- /menu footer buttons -->
               </div>
            </div>
            <!-- top navigation -->
            <div class="top_nav">
               <div class="nav_menu">
                  <nav class="" role="navigation">
                     <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                     </div>
                     <ul class="nav navbar-nav navbar-right">
                        <li class="">
                           <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                           <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/bandung.png" alt=""><?php echo Yii::app()->user->name ?>
                           <span class=" fa fa-angle-down"></span>
                           </a>
                           <ul class="dropdown-menu dropdown-usermenu pull-right">
                              <li style="display:none"><a href="javascript:;">  Profile</a>
                              </li>
                              <li style="display:none">
                                 <a href="javascript:;">
                                 <span class="badge bg-red pull-right">50%</span>
                                 <span>Settings</span>
                                 </a>
                              </li>
                              <li style="display:none">
                                 <a href="javascript:;">Help</a>
                              </li>
                              <li> <a href="<?php echo Yii::app()->createUrl('site/logout') ?>"><i class="fa fa-sign-out pull-right"></i> Keluar</a>
                              </li>
                           </ul>
                        </li>
                    
                     </ul>
                  </nav>
               </div>
            </div>
            <!-- /top navigation -->
            <!-- jQuery
               <script   src="https://code.jquery.com/jquery-1.12.4.min.js"   integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="   crossorigin="anonymous"></script>
               -->
         
            <!-- Bootstrap
               -->
            <script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
            <!-- FastClick 
            <script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/fastclick/lib/fastclick.js"></script>
               -->
            <!-- NProgress
            <script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/nprogress/nprogress.js"></script>
               -->
            <!-- Custom Theme Scripts -->
            <script src="<?php echo Yii::app()->request->baseUrl; ?>/production/js/custom.js"></script>
            <!-- page content -->
            <div class="right_col" role="main" style="height: auto;min-height: 100vh;">
               <?php echo $content ?>
            </div>
            <!-- /page content -->
            <!-- footer content -->
            <footer style="display: none;">
               <div class="pull-right">
                  <!-- sadf// -->
               </div>
               <div class="clearfix"></div>
            </footer>
            <!-- /footer content -->
         </div>
      </div>
      <?php 
      include "js.php";
      ?>
   </body>
</html>