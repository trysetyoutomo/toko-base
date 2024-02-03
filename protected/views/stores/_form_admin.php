<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  
<style>
	/* 
	p,h1,h2,h3,h4,h5,a,td,th,label,body{
		font-family: 'Open Sans', sans-serif;
	}
	.container{
		background-color: #ffff;
		position: relative;
		top:4rem;
		padding-bottom: 5rem;
	}
	*/

	
	 .container input, .container select, .container textarea{
		border-radius: 5px;
		border:1px gray solid;
		padding:5px;
	} 
	.container .row{
		margin-top:10px;
	} 

	#Users_password::after{
		color:red;
	}
</style>
<script>
    function togglePasswordVisibility() {
      var passwordInput = document.getElementById("Users_password");
      var icon = document.getElementById("togglePasswordIcon");

      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
      } else {
        passwordInput.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
      }
    }
  </script>
<div class="form wide" style="margin-left:1rem" >
	<div class="row">
		<div class="col-sm-6">
		<div class="container mt-20" >
			
			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'stores-form',
				'enableAjaxValidation'=>false,
				'htmlOptions'=>array(
					'enctype'=>"multipart/form-data"
					)
				)); ?>
		<?php if (!empty($message)): ?>
			<div class="alert alert-danger" role="alert">
				<?=$message?>
			</div>
			<?php endif; ?>
		<?php echo $form->errorSummary($model); ?>

		<p class="note mt-10 mb-10">Isian dengan <span class="required">*</span> wajib disi.</p>

		<div class="row-group d-block">
		<div class="row">
		<?php echo $form->labelEx($model,'name',array('class'=>"col-sm-2")); ?> 	
		<?php echo $form->textField($model,'name',array('class'=>'col-sm-10','size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'name'); ?>
		</div>

		<?php 
		function getJenisUsahaOptions()
		{
			$jenisUsahaUMKM = $jenisUsahaUMKM = array(
				'Kuliner' => array(
					'warungMakan' => 'Warung Makan',
					'katering' => 'Katering',
					'kueRoti' => 'Kue dan Roti',
					// Tambahkan jenis usaha kuliner lainnya sesuai kebutuhan
				),
				'Pertanian' => array(
					'pertanianSayuran' => 'Pertanian Sayuran',
					'peternakan' => 'Peternakan',
					'perkebunan' => 'Perkebunan',
					// Tambahkan jenis usaha pertanian lainnya sesuai kebutuhan
				),
				'Jasa' => array(
					'jasaKebersihan' => 'Jasa Kebersihan',
					'jasaPengiriman' => 'Jasa Pengiriman',
					'jasaPerawatanTanaman' => 'Jasa Perawatan Tanaman',
					// Tambahkan jenis usaha jasa lainnya sesuai kebutuhan
				),
				'Pakaian dan Aksesoris' => array(
					'produksiPakaian' => 'Produksi dan Penjualan Pakaian',
					'tokoAksesoris' => 'Toko Aksesoris',
					'jasaJahit' => 'Jasa Jahit',
					// Tambahkan jenis usaha pakaian dan aksesoris lainnya sesuai kebutuhan
				),
				'Kecantikan dan Kesehatan' => array(
					'salonKecantikan' => 'Salon Kecantikan',
					'tokoKosmetik' => 'Toko Kosmetik',
					'jasaPijat' => 'Jasa Pijat atau Spa',
					// Tambahkan jenis usaha kecantikan dan kesehatan lainnya sesuai kebutuhan
				),
				'Teknologi Informasi dan Komunikasi (TIK)' => array(
					'jasaPembuatanWebsite' => 'Jasa Pembuatan Website',
					'penjualanElektronik' => 'Penjualan dan Perbaikan Perangkat Elektronik',
					'pengembanganAplikasi' => 'Pengembangan Aplikasi',
					// Tambahkan jenis usaha TIK lainnya sesuai kebutuhan
				),
				'Kreatif dan Desain' => array(
					'desainGrafis' => 'Desain Grafis',
					'produksiKerajinan' => 'Produksi Kerajinan Tangan',
					'fotografi' => 'Fotografi',
					// Tambahkan jenis usaha kreatif dan desain lainnya sesuai kebutuhan
				),
				'Pendidikan dan Pelatihan' => array(
					'lesPrivat' => 'Les Privat',
					'pelatihanKeterampilan' => 'Pusat Pelatihan Keterampilan',
					'jasaPenerjemah' => 'Jasa Penerjemah',
					// Tambahkan jenis usaha pendidikan dan pelatihan lainnya sesuai kebutuhan
				),
				'Pariwisata' => array(
					'pengelolaanHomestay' => 'Pengelolaan Homestay',
					'jasaPemanduWisata' => 'Jasa Pemandu Wisata',
					'jualanSouvenir' => 'Jual Beli Souvenir Lokal',
					// Tambahkan jenis usaha pariwisata lainnya sesuai kebutuhan
				),
				'Perdagangan' => array(
					'tokoKelontong' => 'Toko Kelontong',
					'tokoBahanBangunan' => 'Toko Bahan Bangunan',
					'tokoPakaianSecondHand' => 'Toko Pakaian Second-Hand',
					// Tambahkan jenis usaha perdagangan lainnya sesuai kebutuhan
				),
				'Perikanan' => array(
					'budidayaIkan' => 'Usaha Budidaya Ikan',
					'pengolahanHasilPerikanan' => 'Usaha Pengolahan Hasil Perikanan',
					'jualanHasilPerikanan' => 'Penjualan Hasil Perikanan',
					// Tambahkan jenis usaha perikanan lainnya sesuai kebutuhan
				),
				'Manufaktur' => array(
					'produksiKerajinan' => 'Produksi Kerajinan Tangan',
					'produksiMakananOlahan' => 'Produksi Makanan Olahan',
					'industriKecilMenengah' => 'Industri Kecil dan Menengah',
					// Tambahkan jenis usaha manufaktur lainnya sesuai kebutuhan
				),
				'Pertukangan' => array(
					'bengkelMotor' => 'Bengkel Motor',
					'bengkelTukangKayu' => 'Bengkel Tukang Kayu',
					'jasaReparasi' => 'Jasa Reparasi Alat-alat Rumah Tangga',
					// Tambahkan jenis usaha pertukangan lainnya sesuai kebutuhan
				),
				'E-commerce' => array(
					'penjualanOnline' => 'Penjualan Online',
					'tokoOnline' => 'Toko Online di Platform Tertentu',
					'dropshipping' => 'Dropshipping',
					// Tambahkan jenis usaha e-commerce lainnya sesuai kebutuhan
				),
			);
			
			
			return $jenisUsahaUMKM;
		}
		?>
		<div class="row">
		<?php echo $form->labelEx($model,'store_type',array('class'=>"col-sm-2")); ?>
		<?php echo $form->dropdownList($model,'store_type',getJenisUsahaOptions(),array('class'=>'col-sm-10','maxlength'=>50,'prompt'=>"Pilih jenis usaha")); ?>
		<?php echo $form->error($model,'store_type'); ?>
		</div>

	

		<div class="row">
		<?php echo $form->labelEx($model,'email',array('class'=>"col-sm-2")); ?>
		<?php echo $form->textField($model,'email',array('class'=>'col-sm-10','size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'email'); ?>
		</div>

		<div class="row">
		<?php echo $form->labelEx($model,'phone',array('class'=>"col-sm-2")); ?>
		<?php echo $form->textField($model,'phone',array('class'=>'col-sm-10','size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'phone'); ?>
		</div>

		<div class="row">
		<?php echo $form->labelEx($model,'address1',array('class'=>"col-sm-2")); ?>
		<?php echo $form->textArea($model,'address1',array('size'=>200,'maxlength'=>200,'class'=>'col-sm-10')); ?>
		<?php echo $form->error($model,'address1'); ?>
		</div>


		<div class="row">
		<?php echo $form->labelEx($model,'postal_code',array('class'=>"col-sm-2")); ?>
		<?php echo $form->textField($model,'postal_code',array('class'=>'col-sm-10','size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'postal_code'); ?>
		</div>
		</div>
		<?php if ($model->isNewRecord): ?>
		<div class="row" style="position:relative" >
			<?php echo $form->labelEx($u,'password',array('class'=>"col-sm-2")); ?>
			<?php echo $form->passwordField($u,'password',array('class'=>'col-sm-10','size'=>50,'maxlength'=>50)); ?>
			<i style="position:absolute;right:1.5rem;top:13px;" id="togglePasswordIcon" class="fas fa-eye icon" onclick="togglePasswordVisibility()"></i> 
			<?php echo $form->error($u,'password'); ?>
		</div>
		<?php endif ?>



		<div class="row" style="margin-top:2rem" >
		<label> </label>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Simpan' : 'Daftar ',array("class"=>"btn btn-primary","style"=>"min-width:150px")); ?>
		</div>

		<?php $this->endWidget(); ?>

		</div>
		<!-- form -->
		<div>
	</div>
</div>