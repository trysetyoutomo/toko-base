<?php
class AkunController extends Controller
{

    public $layout='main2';


    public function actionAdmin()
    {
		
		$rawData = Yii::app()->db->createCommand()
		->select('*')
		->from('akuntansi_akun')
		->where("1=1 and store_id = '".Yii::app()->user->store_id()."' $filter")
		->queryAll();
		
		$this->render('admin', array(
			'rawData' => $rawData,
		));
	}

    public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['AkuntansiAkun']))
		{
			$model->attributes=$_POST['AkuntansiAkun'];
			if($model->save())
				$this->redirect(array('admin','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

    public function loadModel($id)
	{
		$model=AkuntansiAkun::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

}

?>