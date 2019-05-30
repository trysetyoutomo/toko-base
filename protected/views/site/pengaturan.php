

   <div class="container-fluid relative animatedParent animateOnce">
 <div class="container-fluid my-3">

  <!-- Include external CSS. -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.css">
 
  <!-- Include Editor style. -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.5.1/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.5.1/css/froala_style.min.css" rel="stylesheet" type="text/css" />



<div class="form wide">	


	<h1>Konfigurasi </h1>
	<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'config-form',
	'enableAjaxValidation'=>false,
)); ?>	
	   <p class="note">isian dengan bintang <span class="required">*</span> adalah wajib.</p>
		<?php 
	   	$i_jam = 0;
	   	$i_service = CentralConfig::model()->findAll(" t.group = 'service' ");
	   	$i_social = CentralConfig::model()->findAll(" t.group = 'socialmedia' ");
	   	$i_kelas = CentralConfig::model()->findAll(" t.group = 'class' ");
	   	$i_lokasi = CentralConfig::model()->findAll(" t.group = 'location' ");

	   	$c_service = 0;
	   	$c_social = 0;
	   	$c_kelas = 0;
	   	$c_lokasi = 0;

		foreach ($model as $key => $value) {

			

				

				if ($value['type']=="text"){

					?>
					<div class="row">
				      <label for="<?php echo $value->variable ?>" class="required"><?php echo $value->variable_display ?> <span class="required">*
				      <?php 
				      echo "<p style='display:inline;font-size:10px;color:red'>$value[keterangan]</p>";
				      ?>
				      </span>
				      </label>		
				      
				      <?php 
				      if ( empty($value['option'])){
				      ?>
				      <input size="50" maxlength="50" name="Config[<?php echo $value->variable ?>]" 
				      id="<?php echo $value->variable ?>" value="<?php echo $value->value ?>" type="text">

				      <?php 
				      // echo $value[keterangan];
				  }else{
				      	$option = explode(",", $value['option']);
				      	echo "<select name='Config[$value->variable]' class='form-control' style='width:150px;'>";
				      	foreach ($option as $key => $value2) {
				      		$value2 = trim($value2);
				      		$selected = "";
				      		if ($value->value==$value2){
				      			$selected = "selected";
				      		}else{
				      			$selected = "";
				      		}
				      		echo "<option $selected value='$value2'>$value2</option>";
				      	}
				      	echo "</select> ";

				       } ?>

				   </div>

				   <?php } else if ($value['type']=="array_location"){  

				   	$data = $value->value ;
				   	$data = explode(",", $data);
				   	?>
			   	   	
			   	   	<div class="row">
					      <label for="<?php echo $value->variable ?>" class="required"><?php echo $value->variable_display ?> <span class="required">*</span>
					      </label>		
					      
					      <input style="width:100px" size="50" maxlength="50" name="Config[location][<?php echo $value->variable ?>][latitude]" 
					      id="<?php echo $value->variable ?>" value="<?php echo $data[0] ?>" type="text">

					      <input style="width:100px" size="50" maxlength="50" name="Config[location][<?php echo $value->variable ?>][longitude]" 
					      id="<?php echo $value->variable ?>" value="<?php echo $data[1] ?>" type="text">
				   </div>



				   <?php } else if ($value['type']=="time"){  

				   	if ($i_jam==0){
				   		echo "<br><fieldset><legend><u>Jam Operasional</u></legend>";
				   	}
				   	$data = $value->value ;
				   	$data = explode("-", $data);
				 
				   	?>
				   	<div class="row">
				      <label for="<?php echo $value->variable ?>" class="required"><?php echo $value->variable_display ?> <span class="required">*</span>
				      </label>		
				      <input size="50" maxlength="50" name="Config[hari][<?php echo $value->variable ?>][awal]" 
				      id="<?php echo $value->variable ?>" value="<?php echo $data[0] ?>" type="time">

				      <input size="50" maxlength="50" name="Config[hari][<?php echo $value->variable ?>][akhir]" 
				      id="<?php echo $value->variable ?>" value="<?php echo $data[1] ?>" type="time">


				   </div>

				   <?php $i_jam ++;

				   if($i_jam==7){
				   		echo "</fieldset><hr>";
				   }

				   ?>

					<?php 
					


				}else if ($value['type']=="textarea"){  ?>
					<div class="row">
				      <label for="<?php echo $value->variable ?>" class="required"><?php echo $value->variable_display ?> <span class="required">*</span>
				      </label>		
				      <textarea class="edit" style="width: 400px;height: 150px;text-align:left" size="50" name="Config[<?php echo $value->variable ?>]" 
				      id="<?php echo $value->variable ?>" ><?php echo $value->value ?></textarea>
				   </div>
				<?php 
				} else if ($value['type']=="array_service"){ ?>

				<!-- 	<fieldset> -->
						<!-- <legend>Services</legend> -->
						<div class="row">
					      <label for="<?php echo $value->variable ?>" class="required"><?php echo $value->variable_display ?> <span class="required">*</span>
					      </label>		
				      	<?php  $data = explode(",",$value->value);?>
					      <select name="Config[<?php echo $value->variable ?>][]" multiple style="width: 300px">
					      	<?php foreach ($data as $key => $nilai) { 
									echo "<option  name='data_service' selected value='$nilai'>&nbsp;&nbsp;$nilai</option>";
					      	} ?>
					      </select>
					      <!-- <label style="width: 250px;text-align: left;">
						      <ul style="list-style: none;padding:0;margin:0">
						      </ul>
					      </label> -->
					   </div>
					<!-- </fieldset> -->
				<?php } else if ($value['type']=="file"){ ?>
					<input type="file" name="logo">
				<?php } ?>



		<?php } ?> 
		 <div class="row buttons">
	      <input class="btn btn-primary" type="submit" name="yt0" value="Simpan">	
	   </div>
<?php $this->endWidget(); ?>

	<!-- 
		   <div class="row">
		      <label for="Config_nama_company" class="required">Nama Perusahaan <span class="required">*</span></label>		<input size="50" maxlength="50" name="Config[nama_company]" id="Config_nama_company" type="text">			
		   </div>
		   <div class="row">
		      <label for="Config_alamat" class="required">Alamat Perusahaan <span class="required">*</span></label>		
		      <textarea style="width: 300px;height: 100px;" size="50" maxlength="50" name="Config[alamat]" id="Config_alamat" type="password" ></textarea>
		   </div>
		   <div class="row">
		      <label for="User_level">Level</label>		
		      <select style="width:250px" name="User[level]" id="User_level" tabindex="-1" class="select2-hidden-accessible" aria-hidden="true">
		         <option value="">Pilih</option>
		         <option value="1">Barang Pakai Tetap</option>
		         <option value="2">Barang Pakai Habis</option>
		      </select>
		      <span class="select2 select2-container select2-container--default" dir="ltr" style="width: 250px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-User_level-container"><span class="select2-selection__rendered" id="select2-User_level-container" title="Pilih">Pilih</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>			
		   </div> -->
	 
</div>
</div>
</div>
<a class="btn btn-primary" 
 onclick="return confirm('Degan menekan tomboll ini, maka data penjualan, pembelian, pengeluaran, akan terhapus, apakah anda yakin akan melanjutkan ? ')"
 href="<?php echo Yii::app()->createUrl("site/hapusAll") ?>">

		<i class="fa fa-times"></i>
		Kosongkan Data Transaksi </a>