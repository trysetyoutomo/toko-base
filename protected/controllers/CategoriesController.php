<?php

class CategoriesController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/admin';

	/**
	 * @return array action filters
	 */
	// public function filters()
	// {
	// 	return array(
	// 		'accessControl', // perform access control for CRUD operations
	// 		'postOnly + delete', // we only allow deletion via POST request
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
	// 			'actions'=>array('index','view','delete','admin','create'),
	// 			'users'=>array('*'),
	// 		),
	// 		array('allow', // allow authenticated user to perform 'create' and 'update' actions
	// 			'actions'=>array('create','update'),
	// 			'users'=>array('@'),
	// 		),
	// 		array('allow', // allow admin user to perform 'admin' and 'delete' actions
	// 			'actions'=>array('admin','hapus'),
	// 			'users'=>array('@'),
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

	public function actionGetKategori()
	{
		?>
            <?php 
    	  	$store_id = Yii::app()->user->store_id();     
            foreach (Categories::model()->findAll(" store_id = '".$store_id."' ") as $c) {?>
                <option value="<?php echo $c->id ?>"><?php echo $c->category ?></option>                            
            <?php } ?>
          <?php
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
	// public function actionCreate()
	// {
	// 	$model=new Categories;

	// 	// Uncomment the following line if AJAX validation is needed
	// 	// $this->performAjaxValidation($model);

	// 	if(isset($_POST['Categories']))
	// 	{
	// 		$model->attributes=$_POST['Categories'];
	// 		if($model->save())
	// 			$this->redirect(array('admin','id'=>$model->id));
	// 	}

	// 	$this->render('create',array(
	// 		'model'=>$model,
	// 	));
	// }


	public function actionCreateCategory()
	{

		$model=new Categories;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Categories']))
		{
			$model->attributes=$_POST['Categories'];
			$model->store_id =Yii::app()->user->store_id();
			$model->category=strtoupper($_POST['Categories']['category']);
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


	public function actionCreate()
	{

		$model=new Categories;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Categories']))
		{
			$model->attributes=$_POST['Categories'];
			$model->category=strtoupper($_POST['Categories']['category']);
			$model->store_id = Yii::app()->user->store_id();

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

		if(isset($_POST['Categories']))
		{
			$model->attributes=$_POST['Categories'];
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
	public function actionHapus($id)
	{
		$this->loadModel($id)->delete();
		$this->redirect(array('admin','id'=>$model->id));

		// $model = $this->loadModel($id);
		// $model->status = 1;
		// $model->update();
		// // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		// if(!isset($_GET['ajax']))
		// 	$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Categories');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		
		$rawData = Yii::app()->db->createCommand()
		->select('*')
		->from('categories')
		->where("1=1 and store_id = '".Yii::app()->user->store_id()."' $filter")
		->queryAll();
		
		$this->render('admin', array(
			'rawData' => $rawData,
		));
	}

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
