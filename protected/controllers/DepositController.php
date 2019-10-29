<?php

class DepositController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	// public $layout='//layouts/column2';
		public $layout='main2';


	/**
	 * @return array action filters
	 */
	

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */


	public static function getSaldoAkhir($tanggal){
		$queryKeluar  = "select 
		sum(si.item_modal * si.quantity_purchased ) keluar
		from 
		sales s inner join 
		sales_items si on s.id = si.sale_id inner join 
		sales_payment sp on sp.id = s.id inner join 
		items i on i.id = si.item_id 
		where 
		i.is_pulsa = 1
		and 
		s.status = 1 
		and
		date(s.date) < '{$tanggal}' " ;
		// echo $queryKeluar;
		// exit;
        $modelKeluar = Yii::app()->db->createCommand($queryKeluar)->queryRow();

        // untuk saldo utama
		$queryDeposit = "select sum(nominal) as masuk from deposit where date(created_at) < '{$tanggal}' and customer_id = '0' ";
        $modelDeposit = Yii::app()->db->createCommand($queryDeposit)->queryRow();

        // untuk penguranan agen
		$queryDepositAgen = "select sum(nominal) as keluar from deposit where date(created_at) < '{$tanggal}' and customer_id != '0' ";
        $modelDepositAgen = Yii::app()->db->createCommand($queryDepositAgen)->queryRow();




        // var_dump($tanggal);
        // exit;
        // var_dump($modelDeposit['masuk']);
        // echo "<br>";
        // var_dump($modelKeluar['keluar']);
        // exit;
        $sisa = intval($modelDeposit['masuk']) - intval($modelKeluar['keluar']) - intval($modelDepositAgen['keluar']);
        return $sisa;
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
		$model=new Deposit;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Deposit']))
		{
			$model->attributes=$_POST['Deposit'];
			$model->created_at = date("Y-m-d H:i:s");
	

			$username = Yii::app()->user->name;
			$user = Users::model()->find('username=:un',array(':un'=>$username));
			$model->created_by = $user->id;

			if($model->save())
				$this->redirect(array('sales/laporanpulsa','tanggal'=>date("Y-m-d") ));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionDeposit_agen()
	{
		$model=new Deposit;
		$model->scenario = "deposit_agen";

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Deposit']))
		{

			$model->attributes=$_POST['Deposit'];
			$model->created_at = date("Y-m-d H:i:s");
			$model->customer_id = $_POST['Deposit']['customer_id'];
			$model->customer_name = Customer::model()->findByPk($_POST['Deposit']['customer_id'])->nama;

			$username = Yii::app()->user->name;
			$user = Users::model()->find('username=:un',array(':un'=>$username));
			$model->created_by = $user->id;

			if($model->save())
				$this->redirect(array('sales/laporanpulsa','tanggal'=>date("Y-m-d") ));
		}

		$this->render('deposit_agen',array(
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

		if(isset($_POST['Deposit']))
		{
			$model->attributes=$_POST['Deposit'];
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
		$dataProvider=new CActiveDataProvider('Deposit');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Deposit('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Deposit']))
			$model->attributes=$_GET['Deposit'];

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
		$model=Deposit::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='deposit-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
