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
    <?php $parameter = Parameter::model()->findByPk(1);?>
    <img src="<?php echo Yii::app()->request->baseUrl; ?>/logo/<?php echo $parameter->gambar ?>" alt="">
    <br>
    <br> 
  <h1>
    <!-- <b><?php echo Stores::model()->findByPk(Yii::app()->user->store_id())->name;?> </b> -->
    <!-- <br>  -->
    <?php 
    $branch_id = Yii::app()->user->branch();
    $store_id =  Branch::model()->findByPk($branch_id)->store_id;
    echo Stores::model()->findByPk($store_id)->name;
    echo "<br>";
    ?>
  </h1>
  <div style="width: 400px">
    
  <h4  class=" text-center;text-transform:lowercase"><?php echo Branch::model()->findByPk($branch_id)->address; ?></h4>
  </div>
    <br>
 
</div>

</center>