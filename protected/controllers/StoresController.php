<?php

class StoresController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='backend';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','hapus'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView()
	{
		$this->render('view',array(
			// 'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */

	public function actionCreate()
	{
		$transaction= Yii::app()->db->beginTransaction();
		try
		{
		$model=new Stores;
		$u = new Users;

		if(isset($_POST['Stores']))
		{
			// $target_file = ;
			// $u->password = $_POST['Users']['password'];
			// $u->email = $_POST['Stores']['email'];

			$u->attributes=$_POST['Users'];
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
				$br->slogan = "Nothing";
				$br->is_utama = 1;
				$br->hapus = 0;
				$br->store_id = $model->id ;
				if ($br->save()){
					// simpan data user 
					$u->password = $u->password;
					$u->username = $model->email;
					$u->name = $model->email;
					$u->email = $model->email;
					$u->level = 2; // level admin
					$u->branch_id = $br->id;
					$u->store_id = $model->id;
					$u->status = 1;
					if ($u->save()){
						$Parameter=new Parameter;
						$Parameter->store_id = $model->id ;
						$Parameter->pajak = 0 ;
						$Parameter->service = 0 ;
						$Parameter->meja = 10 ;
						$Parameter->gambar = "35_POS_LOGO.png"; // default logo
						$Parameter->gambar_putih = "35_POS_LOGO_putih.png"; // default logo
						if ($Parameter->save()){
							$transaction->commit();
							$this->redirect(array('admin',"id"=>$br->id));
						}
						else{
							// print_r($Parameter->getErrors());
							exit;
						}
					}else{
						// print_r($u->getErrors());
						$message =  "Gagal Membuat user";
					}
				}else{
					$message =  "Gagal Membuat cabang utama";					
				}
			}else{
			}

			// }// end save
		}
		$this->render('create',array(
			'model'=>$model,
			'u'=>$u,
			'message'=>$message,
		));
		}
		catch(Exception $e)
		{
			echo $e;
			$transaction->rollback();
		}


	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	// public function actionUpdate($id)
	// {
	// 	$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		// if(isset($_POST['Stores']))
		// {
		// 	$model->attributes=$_POST['Stores'];
		// 	if($model->save())
		// 		$this->redirect(array('view','id'=>$model->id));
		// }

		// $this->render('update',array(
		// 	'model'=>$model,
		// ));
	// }

	public function actionUpdate($id)
	{
		$transaction= Yii::app()->db->beginTransaction();
		try
		{
		$model=$this::loadModel($id);
		$u = new Users;

		if(isset($_POST['Stores']))
		{
			// $target_file = ;
			// $u->password = $_POST['Users']['password'];
			// $u->email = $_POST['Stores']['email'];

			$u->attributes=$_POST['Users'];
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
				$br->slogan = "Nothing";
				$br->is_utama = 1;
				$br->hapus = 0;
				$br->store_id = $model->id ;
				if ($br->save()){
					$transaction->commit();
					$this->redirect(array('admin',"id"=>$br->id));
				}else{
					$message =  "Gagal Membuat cabang utama";					
				}
			}else{
			}

			// }// end save
		}
		$this->render('update',array(
			'model'=>$model,
			'u'=>$u,
			'message'=>$message,
		));
		}
		catch(Exception $e)
		{
			echo $e;
			$transaction->rollback();
		}


	}


	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */

	 public function actionHapus($id){
		$model = $this->loadModel($id);
		if ($model->delete()){
			$this->redirect(array('admin'));	
		}	
	}
	public function actionDelete($id)
	{
		if(Yii::app()->request->isGetRequest)
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
		$rawData = Yii::app()->db->createCommand()
		->select('*')
		->from('stores')
		// ->where("1=1 and store_id = ".Yii::app()->user->store_id()." $filter")
		->queryAll();
		$this->render('admin', array(
			'rawData' => $rawData,
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
