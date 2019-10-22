<?php

class StoresController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='layout-single';

	/**
	 * @return array action filters
	 */
	// public function filters()
	// {
	// 	return array(
	// 		'accessControl', // perform access control for CRUD operations
	// 	);
	// }

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	// public function accessRules()
	// {
	// 	return array(
	// 		array('allow',  // allow all users to perform 'index' and 'view' actions
	// 			'actions'=>array('abc','index','view'),
	// 			'users'=>array('*'),
	// 		),
	// 		array('allow', // allow authenticated user to perform 'create' and 'update' actions
	// 			'actions'=>array('create','update'),
	// 			'users'=>array('*'),
	// 		),
	// 		array('allow', // allow admin user to perform 'admin' and 'delete' actions
	// 			'actions'=>array('admin','delete'),
	// 			'users'=>array('*'),
	// 		),
	// 		array('deny',  // deny all users
	// 			'users'=>array('*'),
	// 		),
	// 	);
	// }

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAbc(){
		echo '123';
	}
	public function actionCreate()
	{
		$transaction= Yii::app()->db->beginTransaction();
		try
		{
		$model=new Stores;
		// echo "123";
		// echo "<pre>";
		// print_r($_FILES['Stores[logo]']);
		// echo "</pre>";
		// var_dump($_FILES['Stores']['logo']);
		// exit;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Stores']))
		{
			// $target_file = ;
			$model->attributes=$_POST['Stores'];
			$random = md5(date("YmdHis"));
			$model->code = rand(5,99999999999999999999); 
			if ($model->logo = CUploadedFile::getInstance($model,'logo')) {
				$model->logo->saveAs("/logo/{$random}.jpg");
			}else{
			}
			if($model->save()){
				$br = new Branch;
				$br->company = $model->name ;
				$br->branch_name = "Pusat" ;
				$br->address = $model->address1;
				$br->telp = $model->phone;
				$br->slogan = "Noting";
				$br->is_utama = 1;
				$br->hapus = 0;
				$br->store_id = $model->id ;
				if ($br->save(false)){

					// simpan data user 
					$u = new Users;
					$u->username = $model->email;
					$u->name = $model->email;
					$u->email = $model->email;
					$u->password = $model->email;
					$u->level = 2; // level admin
					$u->branch_id = $br->id;
					if ($u->save(false)){

						$Parameter=new Parameter
						$Parameter->store_id = $model->id ;
						$Parameter->pajak = 0 ;
						$Parameter->service = 0 ;
						$Parameter->gambar = "35_POS_LOGO.png";
						$Parameter->gambar_putih = "35_POS_LOGO_putih.png";
						if ($Parameter->save()){
							$transaction->commit();
							$this->redirect(array('view','id'=>$model->id));
						}
					}else{
						echo "Gagal Membuat user";

					}
				}else{
					echo "Gagal Membuat cabang utama";
				}
			}
				
				
				
				
				
				// $msg = "Hai ".$_POST['Stores']['name'].", Verifikasi akun anda dengan cara klik link dibawah ini \n 
				// <a href='verifikasi email.php'>Verifikasi email</a>
				// ";
				// $msg = wordwrap($msg,70);
				// $kirimEmail = mail($_POST['Stores']['email'],"Verifikasi Email",$msg);
				
				// if ($kirimEmail)
				// 	$this->redirect(array('view','id'=>$model->id));
				// else{
				// 	echo "Email tidak terkirim";
				// }

			// }// end save
		}

		$this->render('create',array(
			'model'=>$model,
		));
		

		}
		catch(Exception $e)
		{
			echo $e;
			// $transaction->rollback();
		}


	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Stores']))
		{
			$model->attributes=$_POST['Stores'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		echo "123";
		// $dataProvider=new CActiveDataProvider('Stores');
		// $this->render('index',array(
		// 	'dataProvider'=>$dataProvider,
		// ));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Stores('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Stores']))
			$model->attributes=$_GET['Stores'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Stores::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='stores-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
