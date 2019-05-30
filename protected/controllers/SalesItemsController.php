<?php

class SalesItemsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/admin';

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
				'actions'=>array('hapus','index','view','update','admin','delete'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('tukarbarang','create','update','list'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array(''),
				'users'=>array(''),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionTukarbarang($id){
		if (isset($_REQUEST['PenukaranItems'])) {
			$branch_id = Yii::app()->user->branch();
			// echo "123";
			$p = Salespayment::model()->findByPk(SalesItems::model()->findByPk($id)->sale_id ) ;
			// $kes = $p->cash != 0 &&  $p->compliment==0 &&  $p->edc_bca==0 &&  $p->edc_niaga==0 &&  $p->dll==0;
			// if ($kes){
				$model = new PenukaranItems;
				$model->attributes = $_REQUEST['PenukaranItems']; 
				$model->tanggal = date("Y-m-d H:i:s");
				$model->keterangan = "keterangan";
				if ($model->save()){
					//pengupdate an inventori


					//tambah stok barang lama
					$brg = Items::model()->findByPk($model->item_id_asal);
					// $brg->stok = $brg->stok + $model->item_qty_asal;
					// if (!$brg->update())
					// 	return;

					//kurangi stok barang yang baru
					$brg = Items::model()->findByPk($model->item_id_baru);
					$brg = ItemsSatuan::model()->find("barcode = '$model->item_id_baru' ");
					// $brg->stok = $brg->stok - $model->item_qty_baru;
					// if (!$brg->update())
					// 	return;



					//perbaharui sales items
					// var_dump($model->item_id_baru);
					// exit;
					$si = Salesitems::model()->findByPk($id);
					$si->item_id = $brg->id;
					$si->item_satuan_id = $_REQUEST['item_satuan_id'];
					$si->quantity_purchased = intval($model->item_qty_baru);
					$si->item_tax = 0;
					$si->item_price = $_REQUEST['harga_baru'];
					$si->item_discount = 0;
					$si->item_total_cost = $_REQUEST['harga_baru'] * intval($model->item_qty_baru) ;
					$si->item_service = 0;
					$si->item_modal = ItemsController::getAverage($brg->item_id,$_REQUEST['item_satuan_id'],$branch_id);
					if ($si->update()){
						
						//ubah sales payment
						
							$total = $this->getTotal($si->sale_id);
							$voucher = $p->voucher;								//koding tambahan baru
							$totcash = $total-$voucher;							//koding tambahan baru
							$p->cash = $totcash;
							if ($p->update())
								$this->redirect(array('sales/detailitems',"id"=>$si->sale_id ) );
						}else{
							print_r($si->getErrors());
						}
					
				// }else{}


				// echo "sukses";


				}
				else{
					echo "<h2>silahkan perbaiki</h2><br> : ";
					foreach ($model->getErrors() as $m) {
						foreach ($m as $x) {
							echo $x."<br>";
						}
						// echo "123";
					}
					// print_r($model->getErrors());
				}
			// }else{
			// 	echo "penukaran hanya boleh dilakukan apabila pembayaran via cash";
			// }
		}else{
			$this->render('tukarbarang',array(
				'model'=>Salesitems::model()->findByPk($id),
			));
		}
	}
	public function actionAdmin()
	{
		$model=new Salesitems('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Salesitems']))
			$model->attributes=$_GET['Salesitems'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}


	public function actionList($page, $limit, $query = NULL) {

        $crit = new CDbCriteria();
		if ($query != NULL) {
$crit->compare('id', $query,true,"or");
$crit->compare('sale_id', $query,true,"or");
$crit->compare('item_id', $query,true,"or");
$crit->compare('quantity_purchased', $query,true,"or");
$crit->compare('item_tax', $query,true,"or");
$crit->compare('item_price', $query,true,"or");
$crit->compare('item_discount', $query,true,"or");
$crit->compare('item_total_cost', $query,true,"or");
		}
        if ($page > 0)
            $page--;

        $model = new CActiveDataProvider('SalesItems',
                        array(
                            'criteria' => $crit,
                            'pagination' => array('pageSize' => $limit, 'currentPage' => $page),
                        )
        );
        $return = array();

        if (!$model == null) {
            $temp = CJSON::encode($model->getData());
            echo '{"success":true,"data":' . $temp . ',"totalCount":' . $model->getTotalItemCount() . '}';
        }
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
		$model=new SalesItems;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST))
		{
			$model->attributes=$_POST;
			if($model->save())
				echo '{"success":true}';
			else {
                $temp = CJSON::encode($model->getErrors());
                echo '{"success":false,"msg":' . $temp . '}';
			}
            
		}
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	// public function actionUpdate($id)
	// {
		// $model=$this->loadModel($id);
		// $temp='';

		// // Uncomment the following line if AJAX validation is needed
		// // $this->performAjaxValidation($model);

		// if(isset($_POST))
		// {
			// $model->attributes=$_POST;
			// if($model->save())
				// echo '{"success:true"}';
			// else
			// {
                // $temp = CJSON::encode($model->getErrors());
                // echo '{"success":false,"msg":' . $temp . '}';
            // }
				
		// }
	// }

	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['SalesItems']))
		{
			$model->attributes=$_POST['SalesItems'];

			$si  = Salesitems::model()->findByPk($id);
			$sale_id = $si->sale_id;

			$p = Salespayment::model()->findByPk($sale_id);

			$bool = $p->cash != 0 &&  $p->compliment==0 &&  $p->edc_bca==0 &&  $p->edc_niaga==0 &&  $p->dll==0;


			if ($bool){
				if($model->save()){

					$jmlh = $model->quantity_purchased;					//koding tambahan baru
					$harga = $model->item_price;						//koding tambahan baru
					$sttl = $jmlh * $harga;								//koding tambahan baru
					$ser = Parameter::model()->findByPk(1)->service;	//koding tambahan baru
					$valser = $ser / 100;								//koding tambahan baru
					$tax = Parameter::model()->findByPk(1)->pajak;		//koding tambahan baru
					$valtax = $tax / 100;								//koding tambahan baru
					$model->item_tax = $sttl * $valtax;					//koding tambahan baru
					$model->item_service = $sttl * $valser;				//koding tambahan baru
					$model->save();										//koding tambahan baru

					$total = $this->getTotal($sale_id);
					$voucher = $p->voucher;								//koding tambahan baru
					$totcash = $total-$voucher;							//koding tambahan baru
					$p->cash = $totcash;
					if ($p->update());
						$this->redirect(array('sales/index','id'=>$model->id));
						// echo "sukses";

					
				}
			}else{
				echo "<script>alert('tidak bisa hapus karena  terdapat pembayaran edc')</script>";
			}
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

	public function getTotal($id){
			$subtotal = "si.item_price*si.quantity_purchased" ; 
			$sale_service = "si.item_service" ; 
			$sql  = "select s.bayar,s.table,inserter, 
			s.id,sum(si.quantity_purchased) as total_items, 
			date,
			s.waiter waiter,

			sum($subtotal) sale_sub_total,
			
			sum($sale_service) sale_service,
			sum(si.item_tax) sale_tax,
			 s.sale_discount, 
			
			sum((

				($subtotal) + $sale_service + (si.item_tax)-  (si.item_discount*($subtotal)/100)
				))  
			sale_total_cost,

			 u.username inserter 
			 from sales s,sales_items si , users u , items i
			 where 
			  
			  i.id = si.item_id  and
			  
			 s.id = si.sale_id and s.id='$id' and s.status=1 and inserter = u.id ";
			 $data = Yii::app()->db->createCommand($sql)->queryRow();
			 return $data['sale_total_cost'];
	}	
	public function actionHapus($id)
	{
		// if(Yii::app()->request->isPostRequest)
		// {
			// we only allow deletion via POST request
			// $m = Salesitems::model()->findByPk($id);
			// $model->delete();



			$model  = Salesitems::model()->findByPk($id);
			$sale_id = $model->sale_id;
			$p = Salespayment::model()->findByPk($sale_id);

			$bool = $p->cash != 0 &&  $p->compliment==0 &&  $p->edc_bca==0 &&  $p->edc_niaga==0 &&  $p->dll==0;

			if ($bool){
				$model->delete();
				// echo "sukses";
				$total = $this->getTotal($sale_id);
				$p->cash = $total;
				if ($p->update())
					echo "sukses";


			}else{
				echo "tidak bisa hapus karena  terdapat pembayaran edc";
				// throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
				  // throw new CHttpException(404, 'gagal karena pembayaran pada bill ini menggunakan edc.');
			}
			// $data = Yii::app()->db->createCommand()
			// ->select('
			// 	sum(((si.item_price*si.quantity_purchased)+(si.item_tax)-(si.item_discount*(si.item_price*si.quantity_purchased)/100))) stt
			// 		')
			// ->from('sales s, sales_items si')
			// ->where("s.status=1  and s.id = si.sale_id and s.id = $sale_id ")
			// ->group("s.id")
			// ->queryRow();
			// // // echo 'test'.$data['stt'];
			// $payment = Salespayment::model()->updateByPk($sale_id,array("cash" => $data['stt']));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('SalesItems');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=SalesItems::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='sales-items-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
