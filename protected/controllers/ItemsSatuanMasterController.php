<?php

class ItemsSatuanMasterController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='main2';

	/**
	 * @return array action filters
	 */
	// public function filters()
	// {
	// 	return array(
	// 		'accessControl', // perform access control for CRUD operations
	// 	);
	// }

	// /**
	//  * Specifies the access control rules.
	//  * This method is used by the 'accessControl' filter.
	//  * @return array access control rules
	//  */
	// public function accessRules()
	// {
	// 	return array(
	// 		array('allow',  // allow all users to perform 'index' and 'view' actions
	// 			'actions'=>array('index','view'),
	// 			'users'=>array('*'),
	// 		),
	// 		array('allow', // allow authenticated user to perform 'create' and 'update' actions
	// 			'actions'=>array('create','update'),
	// 			'users'=>array('@'),
	// 		),
	// 		array('allow', // allow admin user to perform 'admin' and 'delete' actions
	// 			'actions'=>array('admin','delete'),
	// 			'users'=>array('@'),
	// 		),
	// 		array('deny',  // deny all users
	// 			'users'=>array('*'),
	// 		),
	// 	);
	// }
	public function actionGetSatuan()
	{
		?>
            <?php foreach (ItemsSatuanMaster::model()->findAll() as $c) {?>
                <option value="<?php echo $c->id ?>"><?php echo $c->nama_satuan ?></option>                            
            <?php } ?>
          <?php
	}

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
	public function actionCreate()
	{
		// print_r($_REQUEST);
		// exit;
		$model=new ItemsSatuanMaster;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ItemsSatuanMaster']))
		{
			$model->attributes=$_POST['ItemsSatuanMaster'];
			if($model->save()){
				if (isset($_REQUEST['isajax'])){	
					echo "sukses";
					exit;
				}
				else
					$this->redirect(array('admin','id'=>$model->id));
				// $this->redirect(array('admin','id'=>$model->id));
			}else{
					if (isset($_REQUEST['isajax']) ){
						foreach ($model->getErrors() as $key => $value) {
							// echo $value."<br>";
							foreach ($value as $z => $b) {
								echo " $b \n";
							}
						}
						exit;
					}
			}
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

		if(isset($_POST['ItemsSatuanMaster']))
		{
			$model->attributes=$_POST['ItemsSatuanMaster'];
			if($model->save())
				$this->redirect(array('admin','id'=>$model->id));
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
			$this->loadModel($id)->delete();
			$this->redirect(array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('ItemsSatuanMaster');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$filtersForm=new FiltersForm;
		if (isset($_GET['FiltersForm']))
		$filtersForm->filters=$_GET['FiltersForm'];

		if (isset($_REQUEST['cari'])){
			$value = $_REQUEST['cari'];
			$filter = " and nama_satuan like '%$value%' ";
		}

		// $idh = $_REQUEST['id'];
		$rawData = Yii::app()->db->createCommand()
		->select('*')
		->from('items_satuan_master')
		->where("1=1  $filter")
		// ->group("u.id")
		->queryAll();

		
		$filteredData=$filtersForm->filter($rawData);
		$dataProvider=new CArrayDataProvider($filteredData,	array(
				 'pagination'=>
				 	array(
						'pageSize'=>1000000,
				 	),
			));
		$this->render('admin', array(
			'filtersForm' => $filtersForm,
			'model' => $dataProvider,
		));
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=ItemsSatuanMaster::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='items-satuan-master-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
