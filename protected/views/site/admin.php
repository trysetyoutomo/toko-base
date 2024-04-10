<?php
// phpinfo();
// echo strlen("1 x 6000 - 0% disc       7,200");
// $total_margin = 30;
// $string1 = "1 x 80.000";
// $string2 = "80.00";
// $len1 = strlen($string1);
// $len2 = strlen($string2);
// $space = $total_margin - $len1;
// echo $space;
// ECHO "<BR>";
// echo $string1."". str_pad($string2,$space," ",STR_PAD_LEFT);
// echo $string1."". str_repeat(" ",17).$string2;
?>

<style type="text/css">
  #test{
    margin-top:100px; 
    font-family: arial;
    text-align: center;
  }
  #test img{
    width: 150px;
    height: auto;
  }
</style>
    <?php  if (Yii::app()->user->level == "4"){ ?>
      <div id="test">

      <div class="row">
          <div class="col-lg-12">
          <img src="<?php echo Yii::app()->request->baseUrl; ?>/logo/35_POS_LOGO.png" alt="">
            <h1>Selamat Datang <b>SuperAdmin!</b></h1>
        </div>
      </div>
      </div>
    <?php }else if (Yii::app()->user->level == "2"){ ?>
      <div id="test">
    <?php $parameter = Parameter::model()->find("store_id = '".Yii::app()->user->store_id()."'");?>
    <img src="<?php echo Yii::app()->request->baseUrl; ?>/logo/<?php echo $parameter->gambar ?>" alt="">
    <br>
    <br> 
  <h1>
    <!-- <b><?php echo Stores::model()->findByPk(Yii::app()->user->store_id())->name;?> </b> -->
    <!-- <br>  -->
    <strong>
    <?php 
    $branch_id = Yii::app()->user->branch();
    $store_id =  Branch::model()->findByPk($branch_id)->store_id;
    echo Stores::model()->findByPk($store_id)->name;
    echo "<br>";
    ?>
    </strong>

  </h1>
  <div >
  <h4  class=" text-center;text-transform:lowercase"><?php echo Branch::model()->findByPk($branch_id)->address; ?></h4>
  </div>
    <br>
    </div>
      
    
    <?php }else{ ?>
      <div id="test">
    <?php $parameter = Parameter::model()->find("store_id = '".Yii::app()->user->store_id()."'");?>
    <img src="<?php echo Yii::app()->request->baseUrl; ?>/logo/<?php echo $parameter->gambar ?>" alt="">
    <br>
    <br> 
  <h1>
    <!-- <b><?php echo Stores::model()->findByPk(Yii::app()->user->store_id())->name;?> </b> -->
    <!-- <br>  -->
    <strong>
    <?php 
    $branch_id = Yii::app()->user->branch();
    $store_id =  Branch::model()->findByPk($branch_id)->store_id;
    echo Stores::model()->findByPk($store_id)->name;
    echo "<br>";
    ?>
    </strong>

  </h1>
  <div >
  <h4  class=" text-center;text-transform:lowercase"><?php echo Branch::model()->findByPk($branch_id)->address; ?></h4>
  </div>
    <br>
    </div>
    <?php } ?>
 


