<?php

class SettingController extends Controller
{

	public $layout='backend';
	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionParameter()
	{
		$this->render('parameter');
	}
	public function actionSetpermission(){
		// echo "set permission";
		try{
		$user =  $_REQUEST['user'];
		$permission_id =  $_REQUEST['per_id'];
		$bool =  $_REQUEST['bool'];
		// echo $bool;
		if ($bool=="true"){
			$bool = 1;

			// $ =  $_REQUEST['per_id'];
			$model = ConfigRoleDetail::model()->count("role_id = '$user' and menu_id = '$permission_id' ");
			if ($model==0){
				$p = new ConfigRoleDetail;
				$p->role_id = $user;
				$p->menu_id = $permission_id;
				// $p->status = 1;
				if ($p->save()){
					echo json_encode(array("status"=>200));
					// echo "added";
				}
				else{
					print_r($p->getErrors());
				}

				
			}
			// else{
			// 	$p = ConfigRoleDetail::model()->find("role_id = '$user' and menu_id = '$permission_id' ");
			// 	$p->status = $bool;
			// 	if ($p->save())
			// 		echo "updated";
			// 	else
			// 		print_r($p->getErrors());
			// }
		}else{
			$p = ConfigRoleDetail::model()->find("role_id = '$user' and menu_id = '$permission_id' ");
			if ($p->delete()){
				echo json_encode(array("status"=>200));
				// echo "deleted";
			}
		}


	}catch(Exception $e){
		echo $e;
	}


	}

	public function actionSavePrinter(){
		$main_printer = $_REQUEST['main_printer'];  
		$qty_cetak = $_REQUEST['qty_cetak'];  
		$cetak_kategori = $_REQUEST['cetak_kategori'];  
		$drawer = $_REQUEST['drawer'];  
		// print_r($)
		$setting = Parameter::model()->findByPk(1);
		$setting->printer_utama = $main_printer;
		$setting->qty_cb = $qty_cetak;
		$setting->cetak_per_ketegori = $cetak_kategori;
		$setting->drawer = $drawer;
		if ($setting->update()){
			echo "sukses";
		} 
	}
	public function actionUploadDB(){
		if(isset($_POST["submit"])) {
			$files = $_FILES["database"];
			// print_r($files);
			$text =  file_get_contents($_FILES['database']['tmp_name']);
			// echo $text;

			$connection=new CDbConnection('mysql:host=127.0.0.1;dbname=phpmyadmin','root','');
	 		$connection->active=true; // open connection

	 		$command=$connection->createCommand("create database toko ")->query();
	 		if  ($command){
		 		$command=$connection->createCommand("use toko ")->query();
	 			if  ($command){
		 			$x=$connection->createCommand($text)->query();
			 		if ($x)
			 			$this->redirect(array('site/login'));
		 				// echo "Berhasil Export Database";
		 			else{
		 				echo "Gagal Export Database";
		 			}
	 			}
	 		}else{
	 			echo "error"; 			
	 		}
 		}


	}
	public function actionUpload(){
		$target_dir = "logo/";
		$target_file = $target_dir . basename($_FILES["gambar"]["name"]);
		// $target_file = $target_dir . "logo.png";
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		// Check if image file is a actual image or fake image
		if(isset($_POST["submit"])) {
		    $check = getimagesize($_FILES["gambar"]["tmp_name"]);
		    if($check !== false) {
		        // echo "File is an image - " . $check["mime"] . ". <br>";
		        $uploadOk = 1;
		    }
		     else {
		        echo "File is not an image. <br>";
		        $uploadOk = 0;
		    }
		}
		// Check if file already exists
		// if (file_exists($target_file)) {
		//     echo "Sorry, file already exists.";
		//     $uploadOk = 0;
		// }
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 500000) {
		    echo "Sorry, your file is too large.";
		    $uploadOk = 0;
		}

		if(
				$imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
		    echo "Maaf, hanya JPG, JPEG, PNG & GIF files yang di izinkan. <b";
		    $uploadOk = 0;
		}

		if ($uploadOk == 0) {
		    echo "Maaf, Gambar tidak bisa di uplod.";
		// if everything is ok, try to upload file
		} else {
		    if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
		    	$model = Parameter::model()->findByPk(1);
		    	$model->gambar = $_FILES['gambar']['name'];
		    	if ($model->update())
		    		$this->redirect(array('setting/index'));

		    } else {
		        echo "Sorry, there was an error uploading your file. <br>";
		    }
		}
				

	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}