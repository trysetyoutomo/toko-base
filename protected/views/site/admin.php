<center>
<style type="text/css">
  #test{
    margin-top:100px; 
    font-family: arial;
  }
  #test img{
    width: 150px;
    height: auto;
  }
</style>
<div id="test">
    <?php
    $parameter = Parameter::model()->findByPk(1);
    ?>
    <img src="<?php echo Yii::app()->request->baseUrl; ?>/logo/<?php echo $parameter->gambar ?>" alt="">
    <br>
    <br> 
  <h1>
    <?php 
         echo SiteController::getConfig("company_name");
    ?> 
    <br> 
    <?php 
    $branch_id = Yii::app()->user->branch();
    echo Branch::model()->findByPk($branch_id)->branch_name;
    echo "<br>";
    ?>
  </h1>
  <h2>
  <?php
    echo Branch::model()->findByPk($branch_id)->address;
    ?>
    </h2>
    <br>
 
</div>

</center>