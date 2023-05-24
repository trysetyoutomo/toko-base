<?php
class AkunController extends Controller
{

    public $layout='backend';

	public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array("admin","update","create","view"),
				'users' => array('@'),
            ),
			array('deny', // deny all users
                'users' => array('*'),
            ),
		);
	}

	public function actionHapus($id)
	{
		$this->loadModel($id)->delete();
		$this->redirect(array('admin','id'=>$id));
	}

	public function generateKode($subgroup_id){
		$store_id = Yii::app()->user->store_id();		
		$query = "SELECT IFNULL(
					CONCAT( ag.kode_subgroup, aas.kode_subgroup,LPAD( MAX( SUBSTR( kode_akun, 5, 7 ) ) + 1, 3, '0' ) ),
					CONCAT( ag.	kode_subgroup, aas.kode_subgroup, '001' ) 
					) AS urutan 
				FROM
					akuntansi_akun aa 
					INNER JOIN akuntansi_subgroup aas ON aas.id = aa.subgroup_id
					INNER JOIN akuntansi_group ag ON ag.id = aas.group_id 
				where 
				aa.store_id = '$store_id'
				and aa.subgroup_id = {$subgroup_id}
                 ";
        $model = Yii::app()->db->createCommand($query)->queryRow();

		return $model['urutan'];
	}


    public function actionSaldo(){
		$model = new AkuntansiAkun;
		$listAkun=AkuntansiAkun::model()->findAll();


		$this->render('saldo',array(
			'model'=>$model,
			'listAkun'=>$listAkun,
		));
	}

	public function actionCheck($kode){
		$sql = "select * from akuntansi_akun where kode_akun = '$kode' ";
		$data = Yii::app()->db->createCommand($sql)->queryRow();
		echo json_encode($data);

	}

    public function actionCreate(){
		$model = new AkuntansiAkun;		
		if(isset($_POST['AkuntansiAkun']))
		{
			$model->attributes=$_POST['AkuntansiAkun'];
			$model->kode_akun = $this->generateKode($_POST['AkuntansiAkun']['subgroup_id']);
			$model->created_at = date("Y-m-d H:i:s");
			$model->store_id = Yii::app()->user->store_id();
			$model->user_id = Yii::app()->user->id;
			if($model->save())
				$this->redirect(array('admin','id'=>$model->id));
		}
		
		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionView($id){
		$model=$this->loadModel($id);
		
		$this->render('admin',array(
			'model'=>$model,
		));
	}

    public function actionAdmin()
    {
		
		$rawData = Yii::app()->db->createCommand()
		->select('aa.*,ag.nama_subgroup nama_group , asg.nama_subgroup as nama_subgroup')
		->from('akuntansi_akun aa')
		->join('akuntansi_subgroup asg','asg.id = aa.subgroup_id')
		->join('akuntansi_group ag','ag.id = asg.group_id')
		->where("1=1 and aa.store_id = '".Yii::app()->user->store_id()."' $filter")
		->group("aa.id")
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