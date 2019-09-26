<?php

class BarangmasukController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='main2';

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
				'actions'=>array('create','update','requestmasuk'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	  // public static function generateKodeBMS() {
   //      $query = "SELECT
			// 		CONCAT(
			// 			'TMS',LPAD( MAX( SUBSTR(kode_trx, 4, 10))+1,10,'0')
			// 		) AS urutan
			// 	FROM
			// 		barangmasuk
   //               ";
   //      $model = Yii::app()->db->createCommand($query)->queryRow();
   //      return $model['urutan'];
   //                  // echo "$query";
   //                  // exit;

   //   }

  public static function generateKodePO() {
       	$store_id = Yii::app()->user->store_id();
   	  	$store_id2 = str_pad($store_id,3,"0",STR_PAD_LEFT);
		$kode = "POR";
        $query = "SELECT
				IFNULL(
					CONCAT(
						'{$store_id2}{$kode}',
						LPAD(
							MAX(SUBSTR(kode_trx, 7, 10)) + 1,
							10,
							'0'
						)
					),
					'{$store_id2}{$kode}0000000001'
				) AS urutan
			FROM
				purchase_order
				inner join 
				branch b on b.id = purchase_order.branch_id
				where store_id = '$store_id'
                 ";
        $model = Yii::app()->db->createCommand($query)->queryRow();
        return $model['urutan'];
   }
      public static function generateKodeBMS() {
       	$store_id = Yii::app()->user->store_id();
   	  	$store_id2 = str_pad($store_id,3,"0",STR_PAD_LEFT);
       	$kode = "35M";
        $query = "SELECT
				IFNULL(
					CONCAT(
						'{$store_id2}{$kode}',
						LPAD(
							MAX(SUBSTR(kode_trx, 7, 10)) + 1,
							10,
							'0'
						)
					),
					'{$store_id2}{$kode}0000000001'
				) AS urutan
			FROM
				barangmasuk
				inner join 
				branch b on b.id = barangmasuk.branch_id
				where store_id = '$store_id'
                 ";
        $model = Yii::app()->db->createCommand($query)->queryRow();
        return $model['urutan'];
   }

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionRequestmasuk(){
		$this->render('requestmasuk',array(
			// 'model'=>$this->loadModel($id),
		));
	}
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
	public function actionCreate()
	{
		$model=new Barangmasuk;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Barangmasuk']))
		{
			$model->attributes=$_POST['Barangmasuk'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
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

		if(isset($_POST['Barangmasuk']))
		{
			$model->attributes=$_POST['Barangmasuk'];
			$model->faktur=$_POST['Barangmasuk']['faktur'];
			if($model->save())
				$this->redirect(array('items/laporanmasuk','id'=>$model->id));
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
		$dataProvider=new CActiveDataProvider('Barangmasuk');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Barangmasuk('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Barangmasuk']))
			$model->attributes=$_GET['Barangmasuk'];

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
		$model=Barangmasuk::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='barangmasuk-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
