<?php

class PaketController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout= '//layouts/main2';
	public $defaultAction= 'admin';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
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
				'actions'=>array('index','view','delete','admin','create'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	public function actionView($id){
		if (isset($_REQUEST['cari'])){
			$value = $_REQUEST['cari'];
			$filter = " and nama_paket like '%$value%' ";
		}

		$filtersForm=new FiltersForm;
		if (isset($_GET['FiltersForm']))
		$filtersForm->filters=$_GET['FiltersForm'];
		$sql = " SELECT * FROM PAKET_DETAIL PD INNER JOIN PAKET P ON P.ID_PAKET = PD.PAKET_ID  
		INNER JOIN ITEMS I ON I.ID = PD.ITEM_ID
		WHERE PAKET_ID = $id ";
		$rawData = Yii::app()->db->createCommand($sql)->queryAll();
		$filteredData=$filtersForm->filter($rawData);
		$dataProvider=new CArrayDataProvider($filteredData,
			array(
				 'pagination'=>
				 	array(
						'pageSize'=>1000000,
				 	),
			)
		);
		$this->render('detail', array(
			'filtersForm' => $filtersForm,
			'model' => $dataProvider,
		));

		

	}
	public function actionAdmin()
	{
		if (isset($_REQUEST['cari'])){
			$value = $_REQUEST['cari'];
			$filter = " and nama_paket like '%$value%' ";
		}

		$filtersForm=new FiltersForm;
		if (isset($_GET['FiltersForm']))
		$filtersForm->filters=$_GET['FiltersForm'];

		$rawData = Yii::app()->db->createCommand()
		->select('*')
		->from('paket')
		// ->
		->where("  1=1  $filter   ")
		// ->group("i.id")
		// ->order("item_name")
		->queryAll();
		
		$filteredData=$filtersForm->filter($rawData);
		$dataProvider=new CArrayDataProvider($filteredData,
			array(
				 'pagination'=>
				 	array(
						'pageSize'=>1000000,
				 	),
			)
		);
		$this->render('admin', array(
			'filtersForm' => $filtersForm,
			'model' => $dataProvider,
		));

		

	}

	public function actionIndex(){
		// echo "masuk";
		$this->render("index",array());
	}
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Categories the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Categories::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Categories $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='categories-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
