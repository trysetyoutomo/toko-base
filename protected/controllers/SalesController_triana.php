<?php

class SalesController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    // public $layout = '//layouts/admin';
    public $layout = 'backend';
	public $comp ;
	public $adr ;
	public $tlp ;
	public $slg ;
	//global $i = 's';
	

    /**
     * @return array action filters
     */
    public function filters() {
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
	
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('getmenu','getsaleid2','getharga','datahutang','hutang','del','delete','hapusmeja','grafik','printbagihasil','salescashweekly','salesweekly','salesoutletweekly','index', 'view', 'bayar', 'load', 'void','Getsaleid','hanyacetak','cashreport','CetakReport','Pindahmeja','sessid','Uservoid','Cetakrekap','Export','Salesmonthly','Outletreport','Salesoutletmonthly','Salescashmonthly','detailitems','ex','printData','periode','periodereport','periodereportexport'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }
	
	 public function actionGetmenu(){
	 	$sql = "SELECT 
		item_name,si.quantity_purchased jumlah
		 FROM items i,  
		sales_items si, sales s
		WHERE
		si.item_id = i.id
		AND
		s.id = si.sale_id
		AND
		s.id = '$_REQUEST[id]'
		and
		i.lokasi = '$_REQUEST[lokasi]'
 		";
 		// echo $sql
 		$data = Yii::app()->db->createCommand($sql)->queryAll();
 		echo json_encode($data);


	 }
	 public function actionGetsaleid2(){
		echo $_SESSION['temp_sale_id'];
		// $_SESSION['sale_id'] = "";		
		// unset($_SESSION['sale_id']);
	}
	
	
	public function actionGetharga(){
	$id = $_REQUEST['id'];
	$d = Yii::app()->db->createCommand()
		->select('SUM(unit_price) t')
		->from('items')
		->where("id IN ($id)")		
		->queryRow();
	echo $d['t'];
	}
	
	public function actionDatahutang(){
        if (isset($_GET['Sales']['date'])) 
			$date =  $_GET['Sales']['date'];
		else
			$date =  date('Y-m-d');
			$username = Yii::app()->user->name;
			$user = Users::model()->find('username=:un',array(':un'=>$username));
			$user->id;
			$idk = $user->level; 
			if($idk=='')
				$this->redirect(array('site/login'));
			$data = new Sales;			
            $data->date = $_GET['Sales']['date'];			
			$sql  = "
			SELECT 
			s.id id,s.date tanggal_beli ,  a.tgl_bayar tanggal_bayar, u.username
			FROM sales s, acp a, users u
			WHERE
			s.id = a.sale_id
			AND
			s.inserter = u.id
			AND 
			s.status = 1
			and
			date(s.date)='$date'
			";
			$dataProvider = new CSqlDataProvider($sql, array(
				'totalItemCount'=>$count,
				'sort'=>array(
				'attributes'=>array(
				'desc'=>array('s.id'),
				),
				),
				'pagination'=>array(
				'pageSize'=>100000,
			),
			));
		$tgl = $_GET['Sales']['date'];
		if(empty($_GET['Sales']['date'])){
			$tgl = date('Y-m-d');
		}
		
        $model = new Sales;
        // $dataProvider = $data->search();
        $this->render('hutang', array(
            'dataProvider' => $dataProvider,
            'summary' => $summary,
			'tgl' => $tgl,
			// 'model'=>$model,
        ));
	}
	
	public function actionDel(){
		$meja = $_REQUEST['id'];
		$saleid = Sales::model()->find("t.table=$meja and t.status = 0");
		$id = $saleid->id;
		Sales::model()->UpdateByPk($id,array("status"=>2));
		// SalesItems::model()->deleteAllByAttributes(array('sale_id' => $id));
		echo "sukses";

	}
	
	public function actionGrafik(){
		$mode = $_GET['mode'];
		if (isset($_GET['Sales']['date']) and isset($_GET['Sales']['tgl']) ) {
			$tgl = $_GET['Sales']['date'];
			$tgl2 = $_GET['Sales']['tgl'];
		}
		else{
			$tgl = date('Y-m-d',strtotime('-7 days')); 
			//$tgl =  strtotime("+7 day", $tgl);
			$tgl2 = date('Y-m-d'); 
		}
		
		
		$connection = Yii::app()->db;
		if ($mode=='top'){
			$command = $connection->createCommand("
		select concat(o.nama_outlet,' - ',item_name) nama ,sum(quantity_purchased) jumlah
		from sales_items si, sales s,outlet o,items i
		where
		i.category_id != 5
		and
		si.item_id = i.id 
		and
		s.id = si.sale_id
		and
		o.kode_outlet = i.kode_outlet
		and
		s.status = 1
		and
		date(s.date) >= '$tgl' and date(s.date) <= '$tgl2'
		group by i.id
		order by jumlah desc
		limit 20
			
			");
		}else if ($mode=='bersih'){
		$command = $connection->createCommand("
			select nama_outlet n,
			sum(((si.item_price*si.quantity_purchased)-(si.item_discount*si.item_price/100)) * (o.persentase_hasil /100) ) b
			from sales s,sales_items si,outlet o,items i
			where 
			i.category_id != 5
			and
			si.item_id = i.id and 
			s.id=si.sale_id and 
			i.kode_outlet=o.kode_outlet and 
			s.status = 1
			and
			date(s.date) >= '$tgl' and date(s.date) <= '$tgl2'
			group by o.kode_outlet
			
			");
		}
		
		$row = $command->queryAll(); 

        $this->render('grafik',array(
		'databar'=>$row,
		'tgl'=>$tgl,
		'tgl2'=>$tgl2,
		'mode'=>$mode,
		));
		
		
	}
	public function actionPrintData(){
		$tgl1 = $_GET['tgl1'];
		$tgl2 = $_GET['tgl2'];
		$id = $_GET['id'];
		$jenis = $_GET['jenis'];
		
		$dataProvider = Yii::app()->db->createCommand()
		->select('sales.id as sid,sales.date as date,item_name ,item_price, item_discount,nama_outlet,	sum(if(item_price<0,-quantity_purchased,quantity_purchased)) jumlah, item_tax, sum((quantity_purchased*item_price)-((item_discount*item_price/100)*quantity_purchased)) total	')
		->from('sales_items, sales ,items,outlet')
		->where("
				sales.id = sales_items.sale_id and
				sales.status = 1 and
				sales_items.item_id = items.id and
				items.kode_outlet = outlet.kode_outlet  and 
				outlet.kode_outlet = $id and
				date(sales.date) >= '$tgl1' and date(sales.date) <= '$tgl2'
				")
		->group('sales.id,sales_items.item_id')
		->order('sales.id')
		
		->queryAll();

		$ambilData = Yii::app()->db->createCommand()
		->select('nama_outlet nm, persentase_hasil ps, sum((quantity_purchased*item_price)-(quantity_purchased*(item_discount*item_price/100)))  total , sum((quantity_purchased*item_price)-(quantity_purchased*(item_discount*item_price/100)))  * persentase_hasil /100 as bersih 	')
		->from('sales_items, sales ,items,outlet')
		->where("
				sales.id = sales_items.sale_id and
				sales.status = 1 and
				sales_items.item_id = items.id and
				items.kode_outlet = outlet.kode_outlet  and
				outlet.kode_outlet = $id and
				date(sales.date) >= '$tgl1' and date(sales.date) <= '$tgl2'
				")
		->queryRow();

		$x = new CArrayDataProvider($dataProvider,array(
			'pagination'=>array(
							'pageSize'=>1000,
						),
		));//dikonfersi ke CArrayDataProvider
		
		$tglheader  = date('d-M-Y',strtotime($tgl1));
		$tgl2header = date('d-M-Y',strtotime($tgl2));
	//$this->renderPartial('detailoutlet',array('summary'=>$outletsum,'bersih_d'=>$outletbersih_d));
		if ($jenis==1){
		$this->renderPartial('detailoutlet_print', array(
				'dataProvider' => $dataProvider,
				'data'=>$ambilData,
				'tglheader'=>$tglheader,
				'tgl2header'=>$tgl2header,
			));
		}else{
		$this->renderPartial('detailoutlet_print_v2', array(
				'dataProvider' => $dataProvider,
				'data'=>$ambilData,
				'tglheader'=>$tglheader,
				'tgl2header'=>$tgl2header,
				'tgl1'=>$tgl1,
				'tgl2'=>$tgl2,
				'id'=>$id,
			));
		
		
		}
		

			
	}
	

	
	public function actionEx(){
		//$this->renderPartial('expenseGridtoReport', array(
		//	'detailtransaksi' => $detailtransaksi,
		//));
		
		
		//echo $html;
		$tgl = $_GET['tanggal'];
		$tgl2 = $_GET['tanggal2'];
		
		//$tgl = date('Y-m-d');
		$itemdata = Outlet::model()->findAll();
		$jumlah = count($itemdata);

		//baru haha
		for ($a=1;$a<=$jumlah;$a++){
		$ulang = 'sum(if(i.kode_outlet='.$a.',si.item_price*si.quantity_purchased+(s.sale_service/s.total_items)*si.quantity_purchased+si.item_tax-(s.sale_discount/s.total_items)*si.quantity_purchased,0)) as o'.$a.',';
		$kata = $kata.' '.$ulang;}			
		$q = 's.id,time(s.date) time,'.$kata.'
		sum(si.item_price*si.quantity_purchased+(s.sale_service/s.total_items)*si.quantity_purchased+si.item_tax-(s.sale_discount/s.total_items)*si.quantity_purchased) as total			';		
		$kata = '';
		for ($a=1;$a<=$jumlah;$a++){
		$ulang = 'sum(if(netoutlet.kode_outlet='.$a.',netoutlet.net_item_outlet,0)) as o'.$a.',';
		$kata = $kata.' '.$ulang;}	
		$q2  = 	''.$kata.'sum(netoutlet.net_item_comp) as total_comp';	
		$bersih_d = Yii::app()->db->createCommand()
		->select($q2)
		->from('netoutlet,sales')
		->where('date(sales.date)>=:date and  date(sales.date)<=:date2 and sales.id=netoutlet.sale_id and sales.status=1', array(':date' => $tgl,':date2'=>$tgl2))
		->queryRow();

		
		//echo $tgl;
		
		
		$row = Yii::app()->db->createCommand()
		->select($q)
		->from('sales s,sales_items as si,items as 	i')
		->where('date(s.date)>=:date and  date(s.date)<=:date2 and s.id=si.sale_id and si.item_id= i.id and s.status=1', array(':date' => $tgl,':date2'=>$tgl2))
		->group('s.date')
		->queryAll();
		
		
		
		
		
		//untuk summary
		$kata = '';
		for ($a=1;$a<=$jumlah;$a++){
		$ulang = 'sum(if(i.kode_outlet='.$a.',si.item_price*si.quantity_purchased+(s.sale_service/s.total_items)*si.quantity_purchased+si.item_tax-(s.sale_discount/s.total_items)*si.quantity_purchased,0)) as o'.$a.',';
		$kata = $kata.' '.$ulang;}	
		$qSummary  = 	''.$kata.'sum(si.item_price*si.quantity_purchased+(s.sale_service/s.total_items)*si.quantity_purchased+si.item_tax-(s.sale_discount/s.total_items)*si.quantity_purchased) as total';			
		$summary = Yii::app()->db->createCommand()
		->select($qSummary)
		->from('sales s,sales_items as si,items as 	i')
		->where('date(s.date)>=:date and  date(s.date)<=:date2  and s.id=si.sale_id and si.item_id= i.id and s.status=1', array(':date' => $tgl,':date2'=>$tgl2))
		->queryRow();
		//akhir untuk summary

		
		//untuk bersih
		$kata = '';
		for ($a=1;$a<=$jumlah;$a++){
		$ulang = 'sum(if(netoutlet.kode_outlet='.$a.',netoutlet.net_item_outlet,0)) as o'.$a.',';
		$kata = $kata.' '.$ulang;}	
		$qBersih  = ''.$kata.'sum(netoutlet.net_item_comp) as total_comp';			
		$bersih = Yii::app()->db->createCommand()
		->select($qBersih)
		->from('netoutlet,sales')
		->where('date(sales.date)>=:date and  date(sales.date)<=:date2 and sales.id=netoutlet.sale_id and sales.status=1', array(':date' => $tgl,':date2'=>$tgl2))
		->queryRow();
		//akhir untuk bersih



		
		
		
		//Yii::import('application.extensions.MPDF52.*');
		Yii::import('application.extensions.giiplus.bootstrap.*');
		$session=new CHttpSession;
		$session->open();
		Yii::import('application.modules.admin.extensions.giiplus.bootstrap.*');
		require_once('tcpdf\tcpdf.php');
		require_once('tcpdf/config/lang/eng.php');
		$model = Outlet::model()->findAll();
		$cash = new CArrayDataProvider($row,array(
			'pagination'=>array(
							'pageSize'=>100,
						),
		));//dikonfersi ke CArrayDataProvider
		
		//echo "<pre>";
		//print_r($bersih);
		//echo "</pre>";
		// echo "hahay";
		
		$html = $this->renderPartial('expenseGridtoReport', 
		array('datacash' => $cash,'bersih_d'=>$bersih_d,'summary'=>$summary,'bersih'=>$bersih), true);
		//echo($html);
		$tglheader  = date('d_M_Y',strtotime($tgl));
		$tgl2header = date('d_M_Y',strtotime($tgl2));
		$tgl  = date('d/M/Y',strtotime($tgl));
		$tgl2 = date('d/M/Y',strtotime($tgl2));
		
		$pdf =  new TCPDF('L', 'mm', array(210,210), true, 'UTF-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor(Yii::app()->name);
		$pdf->SetTitle('Siswa Report');
		$pdf->SetSubject('Siswa Report');
		//$pdf->SetKeywords('example, text, report');
		$pdf->SetHeaderData('', 0, "Rekap Pendapatan Outlet & Tenant (Food Arena) Periode $tgl sampai $tgl2", '');
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, "Example Report by ".Yii::app()->name, "");
		$pdf->setHeaderFont(Array('helvetica', '', 8));
		$pdf->setFooterFont(Array('helvetica', '', 6));
		$pdf->SetMargins(15, 18, 15);
		$pdf->SetHeaderMargin(5);
		$pdf->SetFooterMargin(10);
		$pdf->SetAutoPageBreak(TRUE, 0);
		$pdf->SetFont('dejavusans', '', 5);
		$pdf->AddPage();
		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->LastPage();
		$pdf->Output("C:\Documents and Settings\surza focus\My Documents\Dropbox\Food Arena Report [$tglheader - $tgl2header].pdf", "F");
		
	
	}
	
	public function actionDetailitems(){	


		$row = Yii::app()->db->createCommand()
		->select('
				sales_items.id,
				sales.date as date,
				item_name name ,
				item_price price,
				(item_discount*(item_price*quantity_purchased)/100) idc,
				nama_outlet,
				sum(quantity_purchased) as qty,
				item_tax tax, 
				sum((item_price*quantity_purchased)+item_tax-((item_discount*item_price/100)*quantity_purchased)) total	
				')
		->from('sales_items, sales ,items,outlet')
		->where("
				sales.id = sales_items.sale_id and
				sales.status = 1 
				and items.category_id != 4 and
				sales_items.item_id = items.id and
				items.kode_outlet = outlet.kode_outlet  and 
				sales.id = ".$_GET['id']." 
				
				
				")
		->group('sales_items.id')
		->queryAll();
		// $model=new viewdetailtransaksi('search');
		// $model->unsetAttributes();
		// if(isset($_GET['penghuni']))
			// $model->attributes=$_GET['penghuni'];
		 $detailtransaksi = new CArrayDataProvider($row,
		array('pagination'=>array(
		'pageSize'=>100
		))
		);//dikonfersi ke CArrayDataProvider
		
		$username = Yii::app()->user->name;
		$user = Users::model()->find('username=:un',array(':un'=>$username));
		$idk = $user->level; 
		$a = true;
		if($idk < 5)
		$a = true;
		else
		$a = false;
		
		$this->render('detailtransaksi', array(
			'detailtransaksi' => $detailtransaksi,
			'a'=>$a,
		));
	}
	
	public function actionSalesmonthly(){
	
		if ($_POST['month']){
			$month = $_POST['month'];
			$year = $_POST['year'];
		}else{
			$month = intval(Date('m'));
			$year = intval(Date('Y'));
		}
		// exit;
		// $month = Date('m');
		$model = new sales;
		
		
		$cabang = Yii::app()->user->id;
		$user = Users::model()->find('username=:un',array(':un'=>$cabang));
		$cabang_id = $user->branch_id;
		// sales bulanan
		// select s.bayar,s.table,inserter, s.id,sum(si.quantity_purchased) as total_items, date,sum(si.item_price*si.quantity_purchased) sale_sub_total,s.sale_tax,s.sale_service, s.sale_discount, 
			// sum(((si.item_price*si.quantity_purchased)+(si.item_tax)-(si.item_discount*(si.item_price*si.quantity_purchased)/100)))  sale_total_cost,
			 // u.username inserter 
			 // from sales s,sales_items si , users u 
			 // where s.id = si.sale_id and date(s.date)='$date' and s.status=1 and inserter = u.id  and inserter = $user->id   group by s.id  ";

		$tot = Yii::app()->db->createCommand()
			->select('
				date tgl,
				sum(si.item_price*si.quantity_purchased) sst,
				sum(si.item_tax) tax,
				sum(s.sale_service) service , 
				sum(s.sale_discount) sd, 
				sum(((si.item_price*si.quantity_purchased)+(si.item_tax)-(si.item_discount*(si.item_price*si.quantity_purchased)/100)  )) stt
				

					')
			->from('sales s, sales_items si,items i')
			->where("i.category_id != 4 and i.id = si.item_id and month(date) =  '$month' and year(date)='$year'  and s.status=1  and s.id = si.sale_id")
			->group('date(s.date)')
			->order('date(s.date) asc')
			->queryAll();
			
			// $tot = new CArrayDataProvider($tot,array(
			// 'pagination'=>array(
							// 'pageSize'=>100,
						// ),
		//));
			
			
			$this->render('monthlysum',array(
				'model'=>$model,
				'tot'=>$tot,
				'month'=>$month,
				'year'=>$year,
			));
		
		
	}
	
public function actionSalesoutletmonthly(){	
		if ($_POST['month']){
			$month = $_POST['month'];
			$year = $_POST['year'];
		}
		else{
			$month = intval(Date('m'));
			$year = intval(Date('Y'));
		}
		$model = new sales;
		$cabang = Yii::app()->user->id;
		$user = Users::model()->find('username=:un',array(':un'=>$cabang));
		$cabang_id = $user->branch_id;
		$itemdata = Outlet::model()->findAll("kode_outlet!=:i",array(':i'=>27));
		$jumlah = count($itemdata);
		
		
		// foreach($itemdata as $key){
			// // $ulang = 'sum(if(i.kode_outlet='.$a.',si.item_price*si.quantity_purchased+(s.sale_service/s.total_items)*si.quantity_purchased+si.item_tax-(s.sale_discount/s.total_items)*si.quantity_purchased,0)) as o'.$a.',';
			// // $ulang = 'sum(if(i.kode_outlet='.$a.',si.item_price*si.quantity_purchased+si.item_tax-si.item_discount*si.quantity_purchased,0)) as o'.$a.',';

			// $kata = $kata.' '.$ulang;
			// }	
		
		$kata_bersih_d = '';
		foreach($itemdata as $key){
			// $ulang = 'sum(if(i.kode_outlet='.$key["kode_outlet"].',si.item_price*si.quantity_purchased-(si.item_discount*si.item_price/100)*si.quantity_purchased,0)) as "'.$key["kode_outlet"].'",';
			$ulang =  'sum(if(o.kode_outlet='.$key["kode_outlet"].',((si.item_price*si.quantity_purchased)-(si.item_discount*si.item_price/100)*si.quantity_purchased) * (o.persentase_hasil / 100),0)) as "'.$key["kode_outlet"].'",';
			$kata_bersih_d = $kata_bersih_d.' '.$ulang;
		}	
		
		$q2 = $kata_bersih_d . 'sum(o.kode_outlet)' ;
		$bersih  = Yii::app()->db->createCommand()
			->select("date(s.date) tgl,sum(((si.item_price*si.quantity_purchased)-(si.item_discount*si.item_price/100*si.quantity_purchased))) as sales,sum(((si.item_price*si.quantity_purchased)-(si.item_discount*si.item_price/100*si.quantity_purchased)) * (100-o.persentase_hasil) / 100) as ba,".$q2)
			->from('sales_items si,outlet o,sales s,items i')
			->where('i.category_id != 5  and si.item_id = i.id and s.id=si.sale_id and i.kode_outlet=o.kode_outlet and s.status = 1 and month(s.date)= :date and  year(s.date)= :date2  ', array(':date' => $month,':date2'=>$year))
			->group('day(s.date)')
		->queryAll();

		$tots = new CArrayDataProvider($bersih,array(
			'pagination'=>array(
			'pageSize'=>100,
		),
		));
		
		$this->render('monthlyoutletsum',array(
			'tot'=>$tots,
			'month'=>$month,
			'year'=>$year,
			'itemdata'=>$itemdata,
		));		
	}


public function actionSalesoutletweekly(){	
		
		$tgl = $_GET['Sales']['date'];
		$tgl2 = $_GET['Sales']['tgl'];
		
		if (isset($_GET['Sales']['date']) and isset($_GET['Sales']['tgl']) ) {
		$tgl = $_GET['Sales']['date'];
		$tgl2 = $_GET['Sales']['tgl'];
		
		}
		else{
		$tgl = date('Y-m-d'); 
		$tgl2 = date('Y-m-d'); 
		}
		
		$model = new sales;
		$cabang = Yii::app()->user->id;
		$user = Users::model()->find('username=:un',array(':un'=>$cabang));
		$cabang_id = $user->branch_id;
		$itemdata = Outlet::model()->findAll("kode_outlet!=:i",array(':i'=>27));
		$jumlah = count($itemdata);
		
		$kata_bersih_d = '';
		// for ($a=1;$a<=$jumlah;$a++){
			// $ulang =  'sum(if(o.kode_outlet='.$a.',((si.item_price*si.quantity_purchased)-(si.item_discount*si.item_price/100)) * (o.persentase_hasil / 100),0)) as o'.$a.',';
			// $kata_bersih_d = $kata_bersih_d.' '.$ulang;
		// }
		foreach($itemdata as $key){
			// $ulang = 'sum(if(i.kode_outlet='.$key["kode_outlet"].',si.item_price*si.quantity_purchased-(si.item_discount*si.item_price/100)*si.quantity_purchased,0)) as "'.$key["kode_outlet"].'",';
			$ulang =  'sum(if(o.kode_outlet='.$key["kode_outlet"].',((si.item_price*si.quantity_purchased)-(si.item_discount*si.item_price/100)*si.quantity_purchased) * (o.persentase_hasil / 100),0)) as "'.$key["kode_outlet"].'",';
			$kata_bersih_d = $kata_bersih_d.' '.$ulang;
		}
		
		$q2 = $kata_bersih_d . 'sum(o.kode_outlet)' ;
		$bersih  = Yii::app()->db->createCommand()
			->select("sum(((si.item_price*si.quantity_purchased)-(si.item_discount*si.item_price/100*si.quantity_purchased))) as sales,date(s.date) tgl,sum(((si.item_price*si.quantity_purchased)-(si.item_discount*si.item_price/100*si.quantity_purchased)) * (100-o.persentase_hasil) / 100) as ba,".$q2)
			->from('sales_items si,outlet o,sales s,items i')
			->where('i.category_id != 5  and si.item_id = i.id and s.id=si.sale_id and i.kode_outlet=o.kode_outlet and s.status = 1 and date(s.date) and date(s.date)>= :date and  date(s.date)<= :date2
		', array(':date' => $tgl,':date2'=>$tgl2))
		
			->group('day(s.date)')
		
		->queryAll();

		$tots = new CArrayDataProvider($bersih,array(
			'pagination'=>array(
			'pageSize'=>100,
		),
		));
		
		$this->render('weeklyoutletsum',array(
			'tot'=>$tots,
			'tgl' => $tgl,
			'tgl2'=>$tgl2,
			'itemdata'=>$itemdata
		
		));		
	}

	
	public function actionExport(){
		Yii::import('ext.ECSVExport');
		
		$cabang = Yii::app()->user->id;
		$user = Users::model()->find('username=:un',array(':un'=>$cabang));
		$cabang_id = $user->branch_id;
		
		$date = $_GET['tanggal'];
		// $date = '2013-03-14';
		
		$fileDir="C:/Users/Admin/Dropbox/penjualan/";
		$filename = $date."_sales_".$cabang.".csv";
		$file = $fileDir.$filename;
		
		// $tot = Yii::app()->db->createCommand()
			// ->select('date(s.date) as tgl, paidwith_id, s.branch, sum(si.quantity_purchased*si.item_price) as sst, 
					// sum(si.item_discount) as sd, sum(s.sale_service) as service, 
					// sum((si.quantity_purchased*si.item_price)-si.item_discount) as net, sum(si.item_total_cost) as stc, sum(si.item_tax) as tax')
			// ->from('sales s')
			// ->join('sales_items si','si.sale_id=s.id')
			// ->where("date(date) =  '$date' and branch='$cabang_id' and status=1")
			// ->group('paidwith_id, date(s.date), s.branch')
			// ->order('date(s.date) desc, branch')
			// ->queryAll();
			
		$tot = Yii::app()->db->createCommand()
			->select('date(s.date) as tgl, 
					s.paidwith_id, 
					s.branch, 
					sum(s.sale_sub_total) as sst, 
					sum(s.sale_discount) as sd, 
					sum(s.sale_service) as service, 
					sum(s.sale_tax) as tax,
					sum(s.sale_total_cost) as stt')
			->from('sales s')
			->where("date(date) =  '$date' and branch='$cabang_id' and status=1")
			->group('paidwith_id, date(s.date), s.branch')
			->order('date(s.date) desc, branch')
			->queryAll();
			
		$arr = array();
		foreach($tot as $a){
			$field['tanggal'] = $a['tgl'];
			$field['paidwith_id'] = $a['paidwith_id'];
			$field['branch'] = $cabang_id;
			$field['sale_sub_total'] = $a['sst'];
			$field['sale_discount'] = $a['sd'];
			$field['sale_service'] = $a['service'];
			$field['sale_tax'] = $a['tax'];
			$field['net'] = $a['sst']-$a['sd']+$a['service'];
			$field['sale_total_cost'] = $a['sst']-$a['sd']+$a['service']+$a['tax'];
			// $field['sale_total_cost'] = $a['sst']-$a['sd'];
			$arr[] = $field;
		}
		
		// echo "<pre>";
		// print_r($arr);
		// echo "</pre>";
		
		// exit;
		$csv = new ECSVExport($arr);
		// $csv = new ECSVExport(Sales::model()->findAll(
			// "Date(date)='$date' AND status=1"
			// ));
		// $csv->setOutputFile();
		$content = $csv->toCSV($file);
		//Yii::app()->getRequest()->sendFile($filename, $content, "text/csv", false);

		
		$filename2 = $date."_sales_items_".$cabang.".csv";
		$file2 = $fileDir.$filename2;
		
		$q = Yii::app()->db->createCommand()
			->select('si.sale_id, i.item_name, b.branch_name, sum(si.quantity_purchased) as qp, sum(si.item_tax) as it, sum(si.item_price) as ip, sum(si.item_discount) as sid, sum(si.item_total_cost) as itc, date(s.date) as sd')
			->from('sales_items si')
			->join('sales s', 's.id=si.sale_id')
			->join('items i', 'i.id=si.item_id')
			->join('branch b', 'b.id=s.branch')
			->where("date(date) =  '$date' and branch='$cabang_id'")
			->group('date(s.date), branch, i.item_name')
			->order('date(s.date) desc, branch')
			->queryAll();
		
		// $q = SalesItems::model()
				// ->with(array(
					// 'sales'=>array('select'=>'date, branch','together'=>true),
					// 'items'=> array('select'=>'item_name','together'=>true)
					// ))
				// ->findAll("sales.status=1 AND date(sales.date) = '$date'");

		$data = array();
		foreach($q as $val){
			// $result['id'] = $val->id;
			$result['sale_id'] = $val['sale_id'];
			$result['item_id'] = $val['item_id'];
			$result['item_name'] = $val['item_name'];
			$result['quantity_purchased'] = $val['qp'];
			$result['item_tax'] = $val['it'];
			$result['item_price'] = $val['ip'];
			$result['item_discount'] = $val['sid'];
			$result['item_total_cost'] = $val['itc'];
			$result['branch'] = $val['branch_name'];
			$result['date'] = $val['sd'];
			$data[] = $result;
		}
		
		$csv2 = new ECSVExport($data);
		$csv2->toCSV($file2);
		
		exit();
	}
	
	public function actionPindahmeja($meja){
		echo "success : ".$_SESSION['temp_sale_id'];
		
		$id = $_SESSION['temp_sale_id'];
		//update sales
		$model = Sales::model()->findByPk($id);
		$model->table = $meja;
		$model->save();
		// Sales::model()->updateByPk($id, 'table = :meja', array(':meja'=>$meja));
		
		
		$_SESSION['temp_sale_id'] = '';
		unset($_SESSION['temp_sale_id']);
	}
	
	public function actionSessid($id){
		$_SESSION['temp_sale_id'] = $id;
		echo $_SESSION['temp_sale_id'];
	}
	
	public function actionUservoid(){
		if (isset($_POST['Users'])) {
			$un = $_POST['Users']['username'];
			$pass = $_POST['Users']['password'];
			//cari dari database yg usename & passwordnya dikirim
			$user = Users::model()->find("username=:un AND  password=:pass",array(":un"=>$un, ":pass"=>$pass));
			$userLevel = $user['level'];
			//echo "<br>".$username = $user->name;
			if(isset($userLevel)){
				if($userLevel<5){
					//jika userlevel < 5 maka eksekusi void_bayar
					echo "authorized";
				}else{
					echo "unauthorized";
				}
			}else{
				echo "unauthorized";
			}
		}
	}
	
    public function actionVoid() {
        $data = $_GET['data'];
		$data_detail = $_GET['data_detail'];
		$data_payment = $_GET['data_payment'];

		


        if (isset($_GET['data'])) {
			foreach ($data_detail as $detail) {
				 if ($detail['quantity_purchased']>1)
					$jumlah1 = $jumlah1 + $detail['quantity_purchased'];
				else
					$jumlah2 = $jumlah2 + $detail['quantity_purchased'];
				}
			$jumlahakhir =  $jumlah1 + $jumlah2;
				$sales = new Sales();
            $sales->customer_id = 1;
            $sales->sale_sub_total = $data['subtotal'] * -1;
            $sales->sale_discount = $data['discount'] * -1;
            $sales->sale_service = $data['service'] * -1;
            $sales->sale_tax = $data['tax'] * -1;
            $sales->sale_total_cost = $data['total_cost'] * -1;
            $sales->sale_payment = 1;//$data['payment'] * -1;
            $sales->paidwith_id = 1 ;
            $sales->total_items = $jumlahakhir * 1;
            $sales->branch = 1;
            $sales->user_id = 1;
            $sales->table = $data['table'];
            $sales->status = 1;//$data['status'];
			
			$username = Yii::app()->user->name;
			$user = Users::model()->find('username=:un',array(':un'=>$username));
			
			
            $sales->inserter = $user->id;//$data['status'];
			
            if ($sales->save()) {

                SalesItems::model()->deleteAllByAttributes(array('sale_id' => $sales->id));
                $data_detail = $_GET['data_detail'];


                foreach ($data_detail as $detail) {
                    $di = new SalesItems();
                    $di->sale_id = $sales->id;
                    $di->item_id = $detail['item_id'];
                    $di->quantity_purchased = $detail['quantity_purchased'] * 1;
                    $di->item_tax = $detail['item_tax'] * -1;
                    $di->item_discount = $detail['item_discount'] * -1;
                    $di->item_price = $detail['item_price'] * -1;
                    $di->item_total_cost = $detail['item_total_cost'] * -1;
					$di->save();
                    
					$net = new Netoutlet();
					$net->sale_id = $sales->id;
					//query untuk mencari kode outlet dan persenan
					$summary = "";
					$summary = Yii::app()->db->createCommand()
					->select('outlet.kode_outlet as k,outlet.persentase_hasil as p')
					->from('outlet,sales_items,items')
					->where('sales_items.item_id=:id and outlet.kode_outlet=items.kode_outlet and sales_items.item_id=items.id ',array(':id' => $detail['item_id']))
					->queryRow();
					
					//query untuk mencari laba kotor
					$itc = Yii::app()->db->createCommand()
					->select('si.id,sum(si.item_price*si.quantity_purchased+(s.sale_service/s.total_items)*si.quantity_purchased+si.item_tax-(s.sale_discount/s.total_items)*si.quantity_purchased) as total')
					->from('sales s,sales_items as si,items as 	i')
					->where('s.id=si.sale_id and si.item_id= i.id and si.id='.$di->id.'')
					->queryRow();
					
		
					
					$net->kode_outlet = $summary['k'];
					$net->date =$di->id;
					
					$net->net_item_outlet = $itc['total']*($summary['p']/100) *1	;
					$persen_comp = 100 - $summary['p'];
					$net->net_item_comp = $itc['total']* ($persen_comp/100) *1 	;
					$net->save();
					
					
					
					//test
					// $net->kode_outlet = $summary['k'];
					// $net->date =$di->id;
					
					// $net->net_item_outlet = $itc['total'] * $summary['p']/100	;
					// $persen_comp = 100 - $summary['p'];
					// $net->net_item_comp = $itc['total']* $persen_comp/100	;
					// $net->save();
					//test
                }
				
					$sp = new SalesPayment();
					$sp->id = $sales->id;
					$sp->cash = $data_payment['cash'] *-1;	
					$sp->voucher= $data_payment['voucher']*-1;
					$sp->compliment= $data_payment['compliment']*-1;
					$sp->edc_bca= $data_payment['edcbca']*-1;
					$sp->edc_niaga= $data_payment['edcniaga']*-1;
					$sp->dll= $data_payment['dll']*-1;
					$sp->save();

				
                echo "success";
            } else {
                print_r($sales->getErrors());
                ;
            }
        }
    }

    public function actionLoad($id) {
        $data = array();
        $sales = Sales::model()->findByPk($id);
        $si = SalesItems::model()->with('items')->findAllByAttributes(array('sale_id' => $id));
        $data['sales'] = $sales;
//        $data['si'] = $si;
        $temps = array();
        foreach ($si as $val) {
            $temp = array();
            $temp['nama'] = $sales->nama;
            $temp['id'] = $val->id;
            $temp['sale_id'] = $val->sale_id;
            $temp['item_id'] = $val->item_id;
            $temp['item_name'] = $val->items->item_name;
            $temp['item_price_tipe'] = $val->id;
            $temp['quantity_purchased'] = $val->quantity_purchased;
            $temp['item_tax'] = $val->item_tax;
            $temp['item_price'] = $val->item_price;
            $temp['item_discount'] = $val->item_discount;
            $temp['item_total_cost'] = $val->item_total_cost;
            $temp['lokasi'] = $val->items->lokasi;
            $temp['permintaan'] = $val->permintaan;
            $temps[] = $temp;
        }
		$_SESSION['sale_id'] = $id;
        $data['si'] = $temps;
        echo CJSON::encode($data);
    }
	
	public function actionGetsaleid(){
		echo $_SESSION['sale_id'];
		$_SESSION['sale_id'] = "";		
		unset($_SESSION['sale_id']);
	}

    public function actionBayar() {
	
		// date_default_timezone_set("Asia/Jakarta");
	//	echo date("Y/m/d H:i:s");

        $data = $_REQUEST['data'];
		$data_detail = $_REQUEST['data_detail'];
		$data_payment = $_REQUEST['data_payment'];
		// var_dump($data_payment);
		
        if (isset($_REQUEST['data']) and isset($_REQUEST['data_detail']) ) {
		//mencari QTY		
			foreach ($data_detail as $detail) {
			 if ($detail['quantity_purchased']>1)
				$jumlah1 = $jumlah1 + $detail['quantity_purchased'];
			else
				$jumlah2 = $jumlah2 + $detail['quantity_purchased'];
			}
			$jumlahakhir =  $jumlah1 + $jumlah2;
		
            if (isset($data['sale_id']))
                $sales = $this->load_model($data['sale_id']);
            else
                $sales = new Sales();
			
//            $sales->date = date('Y-m-d hh:nn:ss');//$data['custype'];
            $sales->customer_id = 1;//$data['custype'];
            $sales->sale_sub_total  = $data['subtotal'];
            $sales->sale_discount = round($data['discount']);
            $sales->sale_service = round($data['service']);
            $sales->sale_tax = round($data['tax']);
            $sales->sale_total_cost = round($data['total_cost']);
            $sales->bayar = round($data['bayar']);
			$sales->total_items = $jumlahakhir;

            
            if (isset($_data['payment']))   
                $sales->sale_payment = $data['payment'];
            else
                $sales->sale_payment = 0;
                
            $sales->paidwith_id = $data['paidwith'];
				//mencari qty
		
			//$service_si  = round($data['service'])/$jumlahakhir;
			$service_si  = round($data['service'])/$jumlahakhir;
			$discount_si = round($data['discount'])/$jumlahakhir;

			
			//ambil data dari user yg login
			$username = Yii::app()->user->name;
			$user = Users::model()->find('username=:un',array(':un'=>$username));
			
			
            $sales->branch = 1;//$user->branch_id;
            $sales->user_id = 1;
            $sales->table = $data['table'];
            $sales->status = $data['status'];
            $sales->inserter = $user->id;
			
            $hit=0;
            if ($sales->save()) {
            $sales->table = $data['table'];
            $sales->status = $data['status'];

                SalesItems::model()->deleteAllByAttributes(array('sale_id' => $sales->id));
                Netoutlet::model()->deleteAllByAttributes(array('sale_id' => $sales->id));
                
				//akhir mencari qty
				$data_detail = $_REQUEST['data_detail'];
                foreach ($data_detail as $detail){
					$di = new SalesItems();
					if ($sales->status == 1){ //jika akan bayar 
						// echo $detail['item_id'];
						$paket = Paket::model()->findAll("id_paket=:i",array(':i'=>$detail['item_id']));
						// echo $paket->id_item;
						// echo count($paket);
						if (count($paket)!=0){
								//untuk simpan nama paket
								$di->sale_id = $sales->id;
								$di->item_id = $detail['item_id'];
								$di->quantity_purchased = $detail['quantity_purchased'];
								$di->item_tax = $detail['item_tax'];
								$di->item_discount = $detail['item_discount'];
								$di->item_price = $detail['item_price'];
								// $di->item_discount = $detail['item_discount'];;
								$di->item_service =  1;
								$di->item_total_cost =  $detail['item_total_cost'];
								$di->permintaan =  $detail['permintaan'];
								//$di->item_net_outlet = count($detail);
								$di->save();
								//akhir
							foreach ($paket as $p){ //dari paket ke table
								$di = new SalesItems();
								// echo "for dalam for"."<br>";
								// echo "nilai".$p->id_item;
								$di->sale_id = $sales->id;
								$di->item_id = $p->id_item;
								$di->quantity_purchased = $detail['quantity_purchased'];
								$di->item_tax = $detail['item_tax'];
								$di->item_discount = $detail['item_discount'];
								$harga = Items::model()->findByPk($p->id_item);
								// echo $harga->unit_price."<br>";
								$di->item_price = $harga->unit_price;
								// $di->item_discount = $detail['item_discount'];;
								$di->item_service =  1;
								$di->item_total_cost =  $detail['item_total_cost'];
								//$di->item_net_outlet = count($detail);
								$di->permintaan =  $detail['permintaan'];
								$di->save();
							}
						}
						else{
								$di->sale_id = $sales->id;
								$di->item_id = $detail['item_id'];
								$di->quantity_purchased = $detail['quantity_purchased'];
								$di->item_tax = $detail['item_tax'];
								$di->item_discount = $detail['item_discount'];
								$di->item_price = $detail['item_price'];
								// $di->item_discount = $detail['item_discount'];;
								$di->item_service =  1;
								$di->item_total_cost =  $detail['item_total_cost'];
								$di->permintaan =  $detail['permintaan'];
								//$di->item_net_outlet = count($detail);
								$di->save();
						
						}
					}else{//jika simpan ke dalam meja
					
					$di->sale_id = $sales->id;
                    $di->item_id = $detail['item_id'];
					$di->quantity_purchased = $detail['quantity_purchased'];
                    $di->item_tax = $detail['item_tax'];
                    $di->item_discount = $detail['item_discount'];
                    $di->item_price = $detail['item_price'];
                    // $di->item_discount = $detail['item_discount'];;
					$di->item_service =  1;
                    $di->item_total_cost =  $detail['item_total_cost'];
                    $di->permintaan =  $detail['permintaan'];
                    //$di->item_net_outlet = count($detail);
                    $di->save();
					//akhir
				// $datapayment = array('cash','edc','')
				//insert ke table sales_payment	
					}
					
					
                    $hit++;
					}
					SalesPayment::model()->deleteAllByAttributes(array('id' => $sales->id));
					#menyimpan ke table salespayment
					$sp = new SalesPayment();
					$sp->id = $sales->id;
					$sp->cash = $data_payment['cash'];	
					$sp->voucher= $data_payment['voucher'];
					$sp->compliment= $data_payment['compliment'];
					$sp->edc_bca= $data_payment['edcbca'];
					$sp->edc_niaga= $data_payment['edcniaga'];
					$sp->dll= $data_payment['dll'];
					$sp->save();
				
					
				
                ///if ($di->save()) {
				if ($sales->status==1 && $di->save()){
					if ($data['id_voucher']!=''){
						$voucher = Voucher::model()->findByPk($data['id_voucher']);
						if (count($voucher)!=0){	
							if ($voucher->fixed==0){
								$voucher->status = 1;
							}
							else if ($voucher->fixed==1){
								$voucher->status = 0;
							}
							$voucher->update();
						}
					}

                    $this->cetak($_REQUEST['data'],$_REQUEST['data_detail'],$hit, $sales->id,1);	
                   // $this->cetakLagi($sales->id);	
				}
                else {
					// print_r($di->getErrors());
                    $return['sale_id'] = $sales->id;
                    $return['status'] = 0;
                    echo json_encode($return);
                //}
                }//jika berhasil save
            }else{
                print_r($sales->getErrors());
            }
        }
    }
    
	
    private function spasi($banyak, $karakter) {
        $kar = "";
        for ($idx1 = 0; $idx1 < $banyak; $idx1++) {
            $kar = $kar . $karakter;
        }
        return $kar;
    }

    private function spacebar($banyak) {
        $kar = "";
        for ($idx1 = 0; $idx1 < $banyak; $idx1++) {
            $kar = $kar . " ";
        }
        return $kar;
    }

    private function set_spasi($kalimat, $param0, $param1) {
        $penulisan = "";
        if (strlen($kalimat) < $param0) {
            $sisa_spasi = $param0 - strlen($kalimat);
            switch ($param1) {
                case "kanan" :
                    $penulisan = $this->spasi($sisa_spasi, "&nbsp;") . $kalimat;
                    break;
                case "tengah" :
                    $penulisan = $this->spasi(round($sisa_spasi / 2), "&nbsp;") . $kalimat;
                    break;
            }
        }
        return $penulisan;
    }

    private function set_spacebar($kalimat, $param0, $param1) {
        $penulisan = "";
        if (strlen($kalimat) < $param0) {
            $sisa_spasi = $param0 - strlen($kalimat);
            switch ($param1) {
                case "kanan" :
                    $penulisan = $this->spasi($sisa_spasi, " ") . $kalimat;
                    break;
                case "tengah" :
                    $penulisan = $this->spasi(round($sisa_spasi / 2), " ") . $kalimat;
                    break;
            }
        }
        return $penulisan;
    }
	
    public function cetak($data,$detail,$hit, $id, $cd) {
		$this->comp = Branch::model()->findByPk(1)->branch_name;
		$this->adr =  Branch::model()->findByPk(1)->address;
		$this->tlp =  Branch::model()->findByPk(1)->telp;
		$this->slg =  Branch::model()->findByPk(1)->slogan;
        $model = Sales::model()->findByPk($id);

        $total_margin = 40;
        $pembatas = 10;
        $temp_data = array();
        
        // $temp_data['logo'] = $this->set_spacebar($this->comp, $total_margin, "tengah");
        $temp_data['sale_id'] = $id;
        $temp_data['status'] = 1;
		$temp_data['alamat'] = $this->set_spacebar($this->adr, $total_margin, "tengah");
        $temp_data['hit'] = $hit;
        $temp_data['id'] = $id;
        $temp_data['trx_tgl'] = date('d M Y, H:i:s', strtotime($model['date']));
		$temp_data['logo'] = $this->comp;
        $temp_data['alamat'] = $this->adr;
        $temp_data['no_telp'] = "Telp. ".$this->tlp;
        // $temp_data['no_telp'] = $this->set_spacebar("Telp. ".$this->tlp, $total_margin, "tengah");
        // // $kota = set_spacebar ("Bandung", $total_margin, "tengah");
        // // $temp_data['no_telp'] = $this->set_spacebar("Telp. (022)6042148/6042147", $total_margin, "tengah");
         $temp_data['no_nota'] = $id;
        // $temp_data['trx_tgl'] = $this->set_spacebar(date('d M Y, h:i:s', strtotime($model['date'])), $total_margin, "tengah");
        // $temp_data['alamat'] = $this->set_spacebar($this->adr, $total_margin, "tengah");
        // $temp_data['no_telp'] = $this->set_spacebar("Telp. ".$this->tlp, $total_margin, "tengah");
        // $temp_data['no_nota'] = $this->set_spacebar("No Nota : Belum Bayar", "", "tengah");
        // $temp_data['trx_tgl'] = $this->set_spacebar(date('d  M  Y ',strtotime($date)), $total_margin, "tengah");
        // $temp_data['trx_tgl'] = date('d  M  Y ',strtotime($date));
        $pjg_ket = $total_margin - 13;
        // fwrite($fh, "" . "\r \n");
        // fwrite($fh, "Meja   : " . $this->set_spacebar($model['table'], $pjg_ket, "tengah") . "\r\n");
//		fwrite($fh, ""."\r\n");
		//$nama = Users::model()->find('username=:un',array(':un'=>$username));
        $inserter = Sales::model()->find('id=:ids',array(':ids'=>$id));
		$nama = Users::model()->find('id=:ids',array(':ids'=>$inserter['inserter']));
	
		$temp_data['no_meja']=  "Meja   : " ;
		
		$temp_data['mejavalue']=  $model['table'];
         // $temp_data['kasir']=   "Kasir  : " . $this->set_spacebar($nama['name'], $pjg_ket, "tengah") . "\r";
          $temp_data['kasir']=   "Kasir  : " .$nama['name'];
  
		$temp_data['pembatas'] = $this->spasi($total_margin, "-");
		//$detail = $detail;
     //   $hit = count($data_detail['quantity_purchased']);
		$temp2 = array();
		
			$z=0;
		
		asort($detail);
		foreach ($detail as $val_det) {
			$detail2[$z] = $val_det; 
			$z++;
		}
		
		
        for ($a = 0; $a < $hit; $a++) {
            $nama_item = Items::model()->find("id=:id ", array(':id' => $detail2[$a]['item_id']));
            
			$panjang1 = strlen($nama_item['item_name']);
            $panjang2 = strlen(number_format($detail2[$a]['item_total_cost']));
			$panjang3 = strlen($tot_qty) + strlen($detail2[$a]['quantity_purchased']) + strlen($detail2[$a]['item_price']) + strlen($detail2[$a]['item_discount']) + strlen("% disc")+5;
			
            $banyakspasi = $total_margin - $panjang3 - $panjang2;
			$temp = array();
			//------begin-----
			//cek apakah ID yg sedang di proses sama dengan ID yg sebelumnya masuk ke dalam array
					if ($detail2[$a]['item_discount'] == '') 
					$x = '0';
					else
					$x = $detail2[$a]['item_discount'];
				if($detail2[$a]['item_id']==$detail2[$a-1]['item_id']){
					$panjang3 = strlen($tot_qty) + strlen($detail2[$a]['item_price']) + strlen($detail2[$a]['item_discount']) + strlen("% disc")+9;
					$banyakspasi = $total_margin - $panjang3 - $panjang2;
					
					$curr_price = $detail2[$a]['quantity_purchased']*$detail2[$a]['item_price'];
					$last_price += $detail2[$a-1]['quantity_purchased']*$detail2[$a-1]['item_price'];
					$tot_price = $curr_price+$last_price;
					
					$qty_last += $detail2[$a-1]['quantity_purchased'];
					$tot_qty = $qty_last+$detail2[$a]['quantity_purchased'];
					
					$temp['nama_item'] = $nama_item['item_name'];
					
				
					
					//$temp['quantity'] = $tot_qty . " x " . $detail2[$a]['item_price'] . " - " . $x . "% disc". " " . $this->spacebar($banyakspasi - 1) . number_format($tot_price);
					$temp['quantity'] = $tot_qty . " x " . $detail2[$a]['item_price'] . " - " . $x . "% disc". "" . $this->set_spacebar(number_format($tot_price), 20, "kanan");
					//hapus nilai array yg sebelumnya agar tidak ada item name double
					unset($temp2[$a-1]);
					
				}else{
					$panjang3 = strlen($detail2[$a]['quantity_purchased']) + strlen($detail2[$a]['item_price']) + strlen($detail2[$a]['item_discount']) + strlen("% disc")+9;
					$banyakspasi = $total_margin - $panjang3 - $panjang2;
					
					$last_price = $tot_price = $tot_qty = $qty_last = 0;
					// $temp['nama_item'] = $nama_item['item_name'] . " " . $this->spacebar($banyakspasi - 1) . number_format($detail2[$a]['quantity_purchased']*$detail2[$a]['item_price']);
					$temp['nama_item'] = $nama_item['item_name'];
					// $temp['quantity'] = $detail2[$a]['quantity_purchased'] . " x " . $detail2[$a]['item_price'] . " - " . $x . "% disc" . " " . $this->spacebar($banyakspasi - 1) . number_format($detail2[$a]['quantity_purchased']*$detail2[$a]['item_price']);
					$temp['quantity'] = $detail2[$a]['quantity_purchased'] . " x " . $detail2[$a]['item_price'] . " - " . $x . "% disc" . "" .  $this->set_spacebar(number_format($detail2[$a]['quantity_purchased']*$detail2[$a]['item_price']), 20, "kanan");
				}
			//------end------
			
			$baris2 = $nama_item['item_name'] . "" . $this->spasi($banyakspasi, "&nbsp;") . number_format($detail2[$a]['item_total_cost']);
            $data['baris2a'] = $nama_item['item_name'] . " " . $this->spacebar($banyakspasi - 1) . number_format($detail2[$a]['item_total_cost']);

			$temp2[] = $temp;        }
			
			
		$temp_data['detail'] = $temp2;

        // fwrite($fh, $pembatas . "\r \n");
        $subtotal = number_format($data['subtotal']);
        $discount = number_format($data['discount']);
        // $sblmpajak = number_format(300000);
        $pajak = number_format($data['tax']);
        $service = number_format($data['service']);
        $total = number_format($data['total_cost']);
		
		 // $kmb = Yii::app()->db->createCommand()
		// ->select("(s.bayar-(s.sale_total_cost-sp.voucher)) kembali")
		// ->from("sales s,sales_payment sp ")
		// ->where("s.id = sp.id and s.id = $id")
		// ->queryRow();
		
		$kmb = Yii::app()->db->createCommand()
		->select('(s.bayar-sum(s.sale_total_cost)+sp.voucher) kembali')
		->from('sales s,sales_payment sp')
		->where("s.id = $id and s.id = sp.id")
		->group("s.id")
		->queryRow();
	
		 $vo = Yii::app()->db->createCommand()
		->select("voucher ")
		->from("sales_payment sp ")
		->where("id = $id")
		->queryRow();
	
			
        $bayar = number_format($data['bayar']); //number_format($_GET['Sales']['payment']);
        // $bayar = number_format($kmb->); //number_format($_GET['Sales']['payment']);
        // $kembali = number_format($data['bayar'] - $data['total_cost']); //number_format($_GET['Sales']['payment']-$_GET['Sales']['sale_total_cost']);
        $kembali = number_format($kmb['kembali']); //number_format($_GET['Sales']['payment']-$_GET['Sales']['sale_total_cost']);
        $pjg_ket = $total_margin - 13;


        // fwrite($fh, "SubTotal   : " . $this->set_spacebar($subtotal, $pjg_ket, "kanan") . "\r\n");
        $temp_data['subtotal'] = "SubTotal   : " . $this->set_spacebar($subtotal, $pjg_ket, "kanan") . "\r\n";
        // fwrite($fh, "Discount   : " . $this->set_spacebar($discount, $pjg_ket, "kanan") . "\r\n");
        $temp_data['discount'] = "Discount   : " . $this->set_spacebar($discount, $pjg_ket, "kanan") . "\r\n";
        // fwrite($fh, "Service 5% : " . $this->set_spacebar($sblmpajak, $pjg_ket, "kanan") . "\r\n");
        // fwrite($fh, "Ppn 10%    : " . $this->set_spacebar($pajak, $pjg_ket, "kanan") . "\r\n");
        $temp_data['ppn'] = "Ppn 10%    : " . $this->set_spacebar($pajak, $pjg_ket, "kanan") . "\r\n";
        $temp_data['service'] = "service    : " . $this->set_spacebar($service, $pjg_ket, "kanan") . "\r\n";
        // fwrite($fh, $pembatas . "\r\n");
        $temp_data['pembatas2'] = $pembatas . "\r\n";
        // fwrite($fh, "Total      : " . $this->set_spacebar($total, $pjg_ket, "kanan") . "\r\n");
        $temp_data['total'] = "Total      : " . $this->set_spacebar($total, $pjg_ket, "kanan") . "\r\n";
        // fwrite($fh, "Bayar      : " . $this->set_spacebar($bayar, $pjg_ket, "kanan") . "\r\n");
        $temp_data['bayar'] =   "Bayar      : " . $this->set_spacebar($bayar, $pjg_ket, "kanan") . "\r\n";
        $temp_data['voucher'] = "Voucher    : " . $this->set_spacebar('('.number_format($vo['voucher']).')', $pjg_ket, "kanan") . "\r\n";
        // fwrite($fh, "Kembali    : " . $this->set_spacebar($kembali, $pjg_ket, "kanan") . "\r\n");
        $temp_data['kembali'] = "Kembali    : " . $this->set_spacebar($kembali, $pjg_ket, "kanan") . "\r\n";
        // fwrite($fh, "" . "\r\n");
        $temp_data['line_bawah'] = "" . "\r\n";
        $temp_data['slogan'] = $this->set_spacebar($this->slg, $total_margin, "tengah") . "\r\n";
        // fwrite($fh, $this->set_spacebar("(c) Pak Chi Met - 2013", $total_margin, "tengah") . "\r\n");
        $temp_data['pcm'] = $this->set_spacebar("Terimakasih atas kunjunganya ", $total_margin, "tengah") . "\r\n";
        // $temp_data['pcm'] = $this->set_spacebar("(c) ".$this->comp." - ".date("Y"), $total_margin, "tengah") . "\r\n";
        if ($cd==1)
			$temp_data['cd'] = 1;
		else
			$temp_data['cd'] = 0;
		
        // fclose($fh);
        echo json_encode($temp_data);
		// echo "<pre>";
			// print_r($temp_data);
		// echo "</pre>";
    }
	
	public function actionHutang(){
		if (isset($_REQUEST['id'])){
			$id = $_REQUEST['id'];
			$data = Sales::model()->find("id=$id and status = 1");
			if (count($data)==0)
				 echo  "kosong";
			else if ($data->bayar ==0){
				Sales::model()->updateByPk($id, array("bayar"=>$data->sale_total_cost));
				$acp = new Acp;
				$acp->sale_id = $id;
				$acp->tgl_bayar = date("Y-m-d H:i:s");
				$acp->status = 1;
				if($acp->save()){
					$connection = Yii::app()->db;
					$uang = $data->sale_total_cost;
					$que = "update sales_payment set  dll = 0, cash = $uang where id =$id ";
					Yii::app()->db->createCommand($que)->execute();

					//mulai cetak
					$saleid = Yii::app()->db->createCommand()
					->select('
					s.bayar,
					sum(si.item_price*si.quantity_purchased) sale_sub_total,
					sum(si.item_tax) sale_tax,
					sum(si.item_service) sale_service,
					sum(si.item_discount*(si.item_price*si.quantity_purchased)/100) sale_discount,
					sum(((si.item_price*si.quantity_purchased)+(si.item_tax)-(si.item_discount*(si.item_price*si.quantity_purchased)/100))) sale_total_cost
					')
					->from('sales s, sales_items si')
					->where("s.id = si.sale_id and s.id = $_REQUEST[id] ")
					->group("s.id")
					->queryRow();
					// $saleid = Sales::model()->findByPk($_GET['id']);
					$arr_sales = array();
					$arr_detail = array();
					$arr_sales['subtotal'] = $saleid['sale_sub_total'];
					$arr_sales['discount'] = $saleid['sale_discount'];
					$arr_sales['tax'] = $saleid['sale_tax'];
					// $arr_sales['service'] = $saleid['sale_service'];
					$arr_sales['service'] = 0;
					$arr_sales['total_cost'] = $saleid['sale_total_cost'];
					$arr_sales['payment'] = $saleid['sale_payment'];
					$arr_sales['bayar'] = $saleid['bayar'];
					$hit = 0;
					$itemdata = SalesItems::model()->findAll('sale_id=:id',array(':id'=>$_REQUEST['id']));
					foreach($itemdata as $row){
					// echo '<br>'.$row['id'];
						$arr_detail[$hit]['item_id']=$row['item_id'];
						$arr_detail[$hit]['quantity_purchased']=$row['quantity_purchased'];
						$arr_detail[$hit]['item_tax']=$row['item_tax'];
						$arr_detail[$hit]['item_price']=$row['item_price'];
						$arr_detail[$hit]['item_total_cost']=$row['item_total_cost'];
						$hit++;
					}
					$this->cetak($arr_sales,$arr_detail,$hit, $id,0);
				}
			}else if ($data->bayar!=0){
				 echo "already";
			}else{
				 echo "hihi";
			}
			
		}
		// $this->renderPartial("hutang");
	} 
	public function actionCetakReport($id){
		// $saleid = Yii::app()->db->createCommand()
		// 	->select('
		// 	s.bayar,
		// 	sum(si.item_price*si.quantity_purchased) sale_sub_total,
		// 	sum(si.item_tax) sale_tax,
		// 	sum(si.item_service) sale_service,
		// 	sum(si.item_discount*(si.item_price*si.quantity_purchased)/100) sale_discount,
		// 	sum(((si.item_price*si.quantity_purchased)+(si.item_tax)-(si.item_discount*(si.item_price*si.quantity_purchased)/100))) sale_total_cost
		
		// ')
		// 	->from('sales s, sales_items si, items i')
		// 	->where("i.id = si.item_id and i.category_id != 4 and s.id = si.sale_id and s.id = $_GET[id] ")
		// 	->group("s.id")
		// 	->queryRow();
		$saleid = Sales::model()->findByPk($_GET['id']);
		$arr_sales = array();
		$arr_detail = array();
		$arr_sales['subtotal'] = $saleid['sale_sub_total'];
		$arr_sales['discount'] = $saleid['sale_discount'];
		$arr_sales['tax'] = $saleid['sale_tax'];
		$arr_sales['service'] = $saleid['sale_service'];
		// $arr_sales['service'] = 0;
		$arr_sales['total_cost'] = $saleid['sale_total_cost'];
		$arr_sales['payment'] = $saleid['sale_payment'];
		$arr_sales['bayar'] = $saleid['bayar'];
		$hit = 0;
		// $itemdata = SalesItems::model()->findAll('sale_id=:id',array(':id'=>$_GET['id']));
		$itemdata = Yii::app()->db->createCommand()
			->select('*')
			->from('sales s, sales_items si, items i')
			->where("i.id = si.item_id and i.category_id != 4 and s.id = si.sale_id and s.id = $_GET[id] ")
			->group("si.id")
			->queryAll();
		// print_r($itemdata);
		// echo "<pre>";
			// print_r($itemdata);
			// echo count($itemdata);
		// echo "</pre>";
		foreach($itemdata as $row){
			// echo '<br>'.$row['id'];
			$arr_detail[$hit]['item_id']=$row['item_id'];
			$arr_detail[$hit]['quantity_purchased']=$row['quantity_purchased'];
			$arr_detail[$hit]['item_tax']=$row['item_tax'];
			$arr_detail[$hit]['item_price']=$row['item_price'];
			$arr_detail[$hit]['item_total_cost']=$row['item_total_cost'];
			$hit++;
		}
		// echo "hit".$hit;
		$this->cetak($arr_sales,$arr_detail,$hit, $_GET['id'],0);
	}
	
	public function actionHanyacetak(){
		$this->comp = Branch::model()->findByPk(1)->branch_name;
		$this->adr =  Branch::model()->findByPk(1)->address;
		$this->tlp =  Branch::model()->findByPk(1)->telp;
		$this->slg =  Branch::model()->findByPk(1)->slogan;
        $pembatas = 20;
		//print_r($_REQUEST['data']);
		$model = $_REQUEST['data'];
		$detail = $_REQUEST['data_detail'];
// echo "<pre>";
       // print_r($detail);
// echo "</pre>";
		
        $total_margin = 40;

       // $myFile = "c:\\epson\\cetakbarujual.txt";
        //s$fh = fopen($myFile, 'w') or die("can't open file");
        $temp_data = array();
		$temp_data['logo'] = $this->comp;
        $temp_data['alamat'] = $this->adr;
        $temp_data['no_telp'] = "Telp. ".$this->tlp;
        // $temp_data['hit'] = $hit;
        // $temp_data['id'] = $id;
        // $kota = set_spacebar ("Bandung", $total_margin, "tengah");
		// $temp_data['no_telp'] = $this->set_spacebar("Telp. ".$this->tlp, $total_margin, "tengah");
        $temp_data['no_nota'] = "Belum Bayar";
        $temp_data['trx_tgl'] = date('d M Y');
		//$temp_data['nama']=   "Nama  : ". $model['namapelanggan'];

        $pjg_ket = $total_margin - 13;
        //fwrite($fh, "" . "\r \n");
        //fwrite($fh, "Meja   : " . $this->set_spacebar($model['table'], $pjg_ket, "tengah") . "\r\n");
//		fwrite($fh, ""."\r\n");

		$username = Yii::app()->user->name;
		$nama = Users::model()->find('username=:un',array(':un'=>$username));
		$temp_data['no_meja']=  "Meja   : " ;
		$temp_data['mejavalue']=  null;
        $temp_data['kasir']=   "Kasir  : ". $nama['name'];
        $temp_data['namapelanggan']=   "Nama  : ". $model['namapelanggan'];
		
		// $temp_data['no_meja']= "Meja   : " . $this->set_spacebar($model['table'], $pjg_ket, "tengah") . "\r";
		
		
        // $temp_data['no_meja'] = "Meja   : " . $this->set_spacebar($model['table'], $pjg_ket, "tengah") . "\r";
        $temp_data['pembatas'] = $this->spasi($total_margin, "-");
        $temp_data['pembatas'] = $this->spasi($total_margin, "-");
		//$detail = $detail;
     //   $hit = count($data_detail['quantity_purchased']);
		$temp2 = array();
                $subtotal = 0;
				
					//------begin-----
		//untuk mengurutkan data array secara ascending berdasarkan ID
		$z=0;
		
		asort($detail);
		foreach ($detail as $val_det) {
			$detail2[$z] = $val_det; 
			$z++;
		}
		//------end------		
				
      for ($a = 0; $a < count($detail); $a++) {
            $nama_item = Items::model()->find("id=:id", array(':id' => $detail2[$a]['item_id']));
            
			$panjang1 = strlen($nama_item['item_name']);
            $panjang2 = strlen(number_format($detail2[$a]['item_total_cost']));
			$temp = array();
			//------begin-----
			//cek apakah ID yg sedang di proses sama dengan ID yg sebelumnya masuk ke dalam array
				if($detail2[$a]['item_id']==$detail2[$a-1]['item_id']){
					$panjang3 = strlen($tot_qty) + strlen($detail2[$a]['item_price']) + strlen($detail2[$a]['item_discount']) + strlen("% disc")+6;
					$banyakspasi = $total_margin - $panjang3 - $panjang2;
					
					$curr_price = $detail2[$a]['quantity_purchased']*$detail2[$a]['item_price'];
					$last_price += $detail2[$a-1]['quantity_purchased']*$detail2[$a-1]['item_price'];
					$tot_price = $curr_price+$last_price;
					
					$qty_last += $detail2[$a-1]['quantity_purchased'];
					$tot_qty = $qty_last+$detail2[$a]['quantity_purchased'];
					
					$temp['nama_item'] = $nama_item['item_name'];
					// $temp['quantity'] = $tot_qty . " x " . $detail2[$a]['item_price'] . " - " . $detail2[$a]['item_discount'] . "% disc". " " . $this->spacebar($banyakspasi - 1).number_format($tot_price);
					$temp['quantity'] = $tot_qty . " x " . $detail2[$a]['item_price'] . " - " . $detail2[$a]['item_discount'] . "% disc". " " .  $this->set_spacebar(number_format($tot_price), 20.5, "kanan");
					//hapus nilai array yg sebelumnya agar tidak ada item name double
					unset($temp2[$a-1]);
					
				}else{
					$panjang3 = strlen($detail2[$a]['quantity_purchased']) + strlen($detail2[$a]['item_price']) + strlen($detail2[$a]['item_discount']) + strlen("% disc")+6;
					$banyakspasi = $total_margin - $panjang3 - $panjang2;
					
					$last_price = $tot_price = $tot_qty = $qty_last = 0;
					// $temp['nama_item'] = $nama_item['item_name'] . " " . $this->spacebar($banyakspasi - 1) . number_format($detail2[$a]['quantity_purchased']*$detail2[$a]['item_price']);
					$temp['nama_item'] = $nama_item['item_name'];
					// $temp['quantity'] = $detail2[$a]['quantity_purchased'] . " x " . $detail2[$a]['item_price'] . " - " . $detail2[$a]['item_discount'] . "% disc" . " " . $this->set_spacebar(number_format($detail2[$a]['quantity_purchased']*$detail2[$a]['item_price']), $pjg_ket, "kanan") . "\n"; 
					// $temp['quantity'] = $tot_qty . " x " . $detail2[$a]['item_price'] . " - " . $detail2[$a]['item_discount'] . "% disc". " " . $this->spacebar($banyakspasi - 1) . number_format($tot_price);
					$temp['quantity'] = $detail2[$a]['quantity_purchased'] . " x " . $detail2[$a]['item_price'] . " - " . $detail2[$a]['item_discount'] . "% disc" . "" .  $this->set_spacebar(number_format($detail2[$a]['quantity_purchased']*$detail2[$a]['item_price']), 20, "kanan");
			
				}
			//------end------
			
			$baris2 = $nama_item['item_name'] . " " . $this->spasi($banyakspasi, "&nbsp;") . number_format($detail2[$a]['item_total_cost']);
            $data['baris2a'] = $nama_item['item_name'] . " " . $this->spacebar($banyakspasi - 1) . number_format($detail2[$a]['item_total_cost']);

			$temp2[] = $temp;        }
			
			
			/*
		 for ($a = 0; $a < $hit; $a++) {
            $nama_item = Items::model()->find("id=:id", array(':id' => $detail2[$a]['item_id']));
            
			$panjang1 = strlen($nama_item['item_name']);
            $panjang2 = strlen(number_format($detail2[$a]['item_total_cost']));
			$panjang3 = strlen($tot_qty) + strlen($detail2[$a]['quantity_purchased']) + strlen($detail2[$a]['item_price']) + strlen($detail2[$a]['item_discount']) + strlen("% disc")+5;
			
            $banyakspasi = $total_margin - $panjang3 - $panjang2;
			$temp = array();
			//------begin-----
			//cek apakah ID yg sedang di proses sama dengan ID yg sebelumnya masuk ke dalam array
					if ($detail2[$a]['item_discount'] == '') 
					$x = '0';
					else
					$x = $detail2[$a]['item_discount'];
				if($detail2[$a]['item_id']==$detail2[$a-1]['item_id']){
					$panjang3 = strlen($tot_qty) + strlen($detail2[$a]['item_price']) + strlen($detail2[$a]['item_discount']) + strlen("% disc")+9;
					$banyakspasi = $total_margin - $panjang3 - $panjang2;
					
					$curr_price = $detail2[$a]['quantity_purchased']*$detail2[$a]['item_price'];
					$last_price += $detail2[$a-1]['quantity_purchased']*$detail2[$a-1]['item_price'];
					$tot_price = $curr_price+$last_price;
					
					$qty_last += $detail2[$a-1]['quantity_purchased'];
					$tot_qty = $qty_last+$detail2[$a]['quantity_purchased'];
					
					$temp['nama_item'] = $nama_item['item_name'];
					
				
					
					//$temp['quantity'] = $tot_qty . " x " . $detail2[$a]['item_price'] . " - " . $x . "% disc". " " . $this->spacebar($banyakspasi - 1) . number_format($tot_price);
					$temp['quantity'] = $tot_qty . " x " . $detail2[$a]['item_price'] . " - " . $x . "% disc". " " . $this->set_spacebar(number_format($tot_price), 20, "kanan");
					//hapus nilai array yg sebelumnya agar tidak ada item name double
					unset($temp2[$a-1]);
					
				}else{
					$panjang3 = strlen($detail2[$a]['quantity_purchased']) + strlen($detail2[$a]['item_price']) + strlen($detail2[$a]['item_discount']) + strlen("% disc")+9;
					$banyakspasi = $total_margin - $panjang3 - $panjang2;
					
					$last_price = $tot_price = $tot_qty = $qty_last = 0;
					// $temp['nama_item'] = $nama_item['item_name'] . " " . $this->spacebar($banyakspasi - 1) . number_format($detail2[$a]['quantity_purchased']*$detail2[$a]['item_price']);
					$temp['nama_item'] = $nama_item['item_name'];
					// $temp['quantity'] = $detail2[$a]['quantity_purchased'] . " x " . $detail2[$a]['item_price'] . " - " . $x . "% disc" . " " . $this->spacebar($banyakspasi - 1) . number_format($detail2[$a]['quantity_purchased']*$detail2[$a]['item_price']);
					$temp['quantity'] = $detail2[$a]['quantity_purchased'] . " x " . $detail2[$a]['item_price'] . " - " . $x . "% disc" . " " .  $this->set_spacebar(number_format($detail2[$a]['quantity_purchased']*$detail2[$a]['item_price']), 20, "kanan");
				}
			//------end------
			
			$baris2 = $nama_item['item_name'] . " " . $this->spasi($banyakspasi, "&nbsp;") . number_format($detail2[$a]['item_total_cost']);
            $data['baris2a'] = $nama_item['item_name'] . " " . $this->spacebar($banyakspasi - 1) . number_format($detail2[$a]['item_total_cost']);

			$temp2[] = $temp;        }
			*/
		$temp_data['detail'] = $temp2;

//        fwrite($fh, $pembatas . "\r \n");
        $subtotal = number_format($model['subtotal']);
        $discount = number_format($model['discount']);
        $service = number_format($model['service']);
        // $sblmpajak = number_format(300000);
        $pajak = number_format($model['tax']);
        $total = number_format($model['total_cost']);
        
        $bayar = "";
        $kembali = ""; //number_format($_GET['Sales']['payment']-$_GET['Sales']['sale_total_cost']);
        $pjg_ket = $total_margin - 13;


        // fwrite($fh, "SubTotal   : " . $this->set_spacebar($subtotal, $pjg_ket, "kanan") . "\r\n");
        $temp_data['subtotal'] = "SubTotal   : " . $this->set_spacebar($subtotal, $pjg_ket, "kanan") . "\r\n";
        // fwrite($fh, "Discount   : " . $this->set_spacebar($discount, $pjg_ket, "kanan") . "\r\n");
        $temp_data['discount'] = "Discount   : " . $this->set_spacebar($discount, $pjg_ket, "kanan") . "\r\n";
        $temp_data['service'] = "Service    : " . $this->set_spacebar($service, $pjg_ket, "kanan") . "\r\n";
        // fwrite($fh, "Service 5% : " . $this->set_spacebar($sblmpajak, $pjg_ket, "kanan") . "\r\n");
        // fwrite($fh, "Ppn 10%    : " . $this->set_spacebar($pajak, $pjg_ket, "kanan") . "\r\n");
        $temp_data['ppn'] = "Ppn 10%    : " . $this->set_spacebar($pajak, $pjg_ket, "kanan") . "\r\n";
        // fwrite($fh, $pembatas . "\r\n");
        $temp_data['pembatas2'] = $pembatas . "\r\n";
        $temp_data['total'] = "Total      : " . $this->set_spacebar($total, $pjg_ket, "kanan") . "\r\n";
        $temp_data['kembali'] = "Kembali    : " . $this->set_spacebar($kembali, $pjg_ket, "kanan") . "\r\n";
        $temp_data['line_bawah'] = "" . "\r\n";
        $temp_data['bayar'] = "Bayar      : " . $this->set_spacebar("", $pjg_ket, "kanan") . "\r\n";
        $temp_data['slogan'] = $this->set_spacebar($this->slg, $total_margin, "tengah") . "\r\n";
        // fwrite($fh, $this->set_spacebar("(c) Pak Chi Met - 2013", $total_margin, "tengah") . "\r\n");
        //$temp_data['pcm'] = $this->set_spacebar("(c) ".$this->comp." - ".date("Y"), $total_margin, "tengah") . "\r\n";
		$temp_data['pcm'] = $this->set_spacebar("Terimakasih atas kunjunganya ", $total_margin, "tengah") . "\r\n";
		//$temp_data['nama'] = $this->set_spacebar("Nama  : ". $model['namapelanggan'], $total_margin, "tengah") . "\r\n";
		$temp_data['cd'] = 0;
        // fclose($fh);
       echo json_encode($temp_data);
		 // echo "<pre>";
			 // print_r($temp_data);
		 // echo "</pre>";
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Sales;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Sales'])) {
            $model->attributes = $_POST['Sales'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Sales'])) {
            $model->attributes = $_POST['Sales'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }


    /**
     * Lists all models.
     */
	 
	 public function actionSalescashmonthly(){
	
		if ($_POST['month']){
			$month = $_POST['month'];
			$year = $_POST['year'];
		}else{
			$month = intval(Date('m'));
			$year = intval(Date('Y'));
		}
		// exit;
		// $month = Date('m');
		$model = new sales;
		
		
		$cabang = Yii::app()->user->id;
		$user = Users::model()->find('username=:un',array(':un'=>$cabang));
		$cabang_id = $user->branch_id;
		//(SUM(cash)+SUM(edc_bca)+SUM(compliment)+SUM(edc_niaga)+SUM(voucher)+SUM(dll)) grandtotal,
		$tot = Yii::app()->db->createCommand()
			->select('date(s.date) tanggal,s.id,s.date,sum(cash)cash,sum(edc_bca)edc_bca,sum(edc_niaga)edc_niaga,sum(compliment)compliment,sum(dll)dll,sum(voucher)voucher, SUM(IFNULL(cash,0)+IFNULL(edc_bca,0)+IFNULL(edc_niaga,0)+IFNULL(compliment,0)+IFNULL(dll,0)+IFNULL(voucher,0)) grandtotal	')
			->from('sales s,sales_payment ')
			->where("month(date) =  '$month' and year(date)='$year' and status=1 and sales_payment.id = s.id")
			->group('day(s.date)')
			->queryAll();
			

			
			$this->render('monthlycashsum',array(
				'model'=>$model,
				'tot'=>$tot,
				'month'=>$month,
				'year'=>$year,
			));
		
		
	}

	 public function actionSalescashweekly(){
		$tgl = $_GET['Sales']['date'];
		$tgl2 = $_GET['Sales']['tgl'];
		if (isset($_GET['Sales']['date']) and isset($_GET['Sales']['tgl']) ) {
			$tgl = $_GET['Sales']['date'];
			$tgl2 = $_GET['Sales']['tgl'];
		}
		else{
			$tgl = date('Y-m-d'); 
			$tgl2 = date('Y-m-d'); 
		}
		// exit;
		// $month = Date('m');
		$model = new sales;
		
		
		$cabang = Yii::app()->user->id;
		$user = Users::model()->find('username=:un',array(':un'=>$cabang));
		$cabang_id = $user->branch_id;
		
		$tot = Yii::app()->db->createCommand()
				->select('(SUM(cash)+SUM(edc_bca)+SUM(compliment)+SUM(edc_niaga)+SUM(voucher)+SUM(dll)) grandtotal,date(s.date) tanggal,s.id,s.date,sum(cash)cash,sum(edc_bca)edc_bca,sum(edc_niaga)edc_niag,sum(compliment)compliment,sum(dll)dll,sum(voucher)voucher, SUM(cash+edc_bca+edc_niaga+compliment+dll+voucher) total')
				->from('sales s,sales_payment ')
				->where("s.id = sales_payment.id and date(date)>= '$tgl' and  date(date)<= '$tgl2' and status=1")
				->group('day(s.date)')
				->queryAll();
		// ->where("")
			
		// ->group('day(sales.date)')
		// ->queryAll();
		
		$this->render('weeklycashsum',array(
			'model'=>$model,
			'tot'=>$tot,
			'tgl'=>$tgl,
			'tgl2'=>$tgl2,
		));
		
		
	}
	
	 
	public function actionCashReport(){
		$username = Yii::app()->user->name;
		$user = Users::model()->find('username=:un',array(':un'=>$username));
		$user->id;
		$idk = $user->level; 
		if (isset($_GET['Sales']['date'])) 
			$date = $_GET['Sales']['date'];
		else
			$date = date('Y-m-d');
			
		
			if ($idk == 2){
			$row = Yii::app()->db->createCommand()
					->select('s.id,s.date,cash,edc_bca,edc_niaga,compliment,dll,voucher , SUM(cash+edc_bca+edc_niaga+compliment+dll+voucher) total')
					->from('sales s,sales_payment ')
					->where('date(s.date)=:date and s.status=1 and s.id = sales_payment.id', array(':date' => $date))
					->group('s.id')
					->queryAll();
					
			$summary = Yii::app()->db->createCommand()
					->select('(SUM(cash)+SUM(edc_bca)+SUM(compliment)+SUM(edc_niaga)+SUM(voucher)+SUM(dll)) grandtotal,SUM(cash) totalcash,SUM(voucher) totalvou,SUM(compliment) totalcomp,SUM(edc_bca) totalbca,SUM(edc_niaga) totalniaga ,sum(dll) totaldll')
					->from('sales,sales_payment')
					->where('sales.id = sales_payment.id and date(date)=:date and status=1', array(':date' => $date))
					->queryRow();
			}
			else{
			$row = Yii::app()->db->createCommand()
					->select('s.id,s.date,cash,edc_bca,edc_niaga,compliment,dll,voucher , SUM(cash+edc_bca+edc_niaga+compliment+dll+voucher) total')
					->from('sales s,sales_payment ')
					->where('date(s.date)=:date and s.inserter=:i and  s.status=1 and s.id = sales_payment.id', array(':date' => $date, ':i'=> $user->id))
					->group('s.id')
					->queryAll();
					
			$summary = Yii::app()->db->createCommand()
					->select('(SUM(cash)+SUM(edc_bca)+SUM(compliment)+SUM(edc_niaga)+SUM(voucher)+SUM(dll)) grandtotal,SUM(cash) totalcash,SUM(voucher) totalvou,SUM(compliment) totalcomp,SUM(edc_bca) totalbca,SUM(edc_niaga) totalniaga ,sum(dll) totaldll')
					->from('sales,sales_payment')
					->where('sales.id = sales_payment.id and date(date)=:date and status=1 and inserter=:i', array(':date' => $date, ':i'=> $user->id))
					->queryRow();
			}
			
	
		$tgl = $_GET['Sales']['date'];
		if(empty($_GET['Sales']['date'])){
			$tgl = date('Y-m-d');
		}
		
		$cash = new CArrayDataProvider($row,array(
			'pagination'=>array(
							'pageSize'=>100,
						),
		));//dikonfersi ke CArrayDataProvider
		$this->render('cash', array(
			'datacash' => $cash,
			'cashsum' => $summary,
			'tgl' => $tgl,
		));
	}
	 
	 
	public function actionOutletreport(){
		$tgl = $_GET['Sales']['date'];
		$tgl2 = $_GET['Sales']['tgl'];
		if (isset($_GET['Sales']['date']) and isset($_GET['Sales']['tgl']) ) {
			$tgl = $_GET['Sales']['date'];
			$tgl2 = $_GET['Sales']['tgl'];
		}
		else{
			// $tgl = date('Y-m-d'); 
			$tgl = date('Y-m-d',strtotime('-7 days')); 
			$tgl2 = date('Y-m-d'); 
		}
		
		$itemdata = Outlet::model()->findAll("kode_outlet!=26");
		$jumlah = count($itemdata);
		
		// echo ($jumlah);
			// echo $key["kode_outlet"]."-";
			// }
		
			//baru haha
			//for ($a=1;$a<=$jumlah;$a++){
			foreach($itemdata as $key){
			// $ulang = 'sum(if(i.kode_outlet='.$a.',si.item_price*si.quantity_purchased+(s.sale_service/s.total_items)*si.quantity_purchased+si.item_tax-(s.sale_discount/s.total_items)*si.quantity_purchased,0)) as o'.$a.',';
			// $ulang = 'sum(if(i.kode_outlet='.$a.',si.item_price*si.quantity_purchased+si.item_tax-si.item_discount*si.quantity_purchased,0)) as o'.$a.',';

			$ulang = 'sum(if(i.kode_outlet='.$key["kode_outlet"].',si.item_price*si.quantity_purchased-(si.item_discount*si.item_price/100)*si.quantity_purchased,0)) as "'.$key["kode_outlet"].'",';
			$kata = $kata.' '.$ulang;
			}			
			$q = 's.id,s.date time,'.$kata.'sum(si.item_price*si.quantity_purchased+(s.sale_service/s.total_items)*si.quantity_purchased-(si.item_price*si.item_discount/100)*si.quantity_purchased) as total';
			
			// echo $q;
			$row = Yii::app()->db->createCommand()
			->select($q)
			->from('sales s,sales_items as si,items as 	i')
			->where('
			i.category_id != 5 and
			date(s.date)>= :date and  date(s.date)<= :date2
			and
			s.id=si.sale_id
			and 
			si.item_id= i.id
			and
			s.status=1', 
			array(':date' => $tgl,':date2'=>$tgl2))
			->group('s.date')
			->queryAll();
		
		

			$bersih = Yii::app()->db->createCommand()
			->select("sum(((si.item_price*si.quantity_purchased)-((si.item_discount*si.item_price/100)*si.quantity_purchased) ) * (100-o.persentase_hasil) / 100) as total_comp")
			->from('sales_items si,outlet o,sales s,items i')
			->where('
			i.category_id != 5 and
			
			si.item_id = i.id
			and
			s.id=si.sale_id
			and
			i.kode_outlet=o.kode_outlet
			and
			s.status = 1
			and
			date(s.date)>= :date and  date(s.date)<= :date2 
			', array(':date' => $tgl,':date2'=>$tgl2))
			->queryRow();
					
				
			//detail bersih
			$kata_bersih_d = '';
			// for ($a=1;$a<=$jumlah;$a++){
			// $ulang = 'sum(if(netoutlet.kode_outlet='.$a.',netoutlet.net_item_outlet,0)) as o'.$a.',';
			foreach($itemdata as $key){
				$ulang =  'sum(if(o.kode_outlet='.$key['kode_outlet'].',((si.item_price*si.quantity_purchased)-(si.item_discount/100*(si.item_price*si.quantity_purchased))  ) * (o.persentase_hasil / 100),0)) as "'.$key["kode_outlet"].'",';
				// echo $ulang;
				$kata_bersih_d = $kata_bersih_d.' '.$ulang;
			}	
			$q2 = $kata_bersih_d . 'sum(o.kode_outlet)' ;
			$bersih_d = Yii::app()->db->createCommand()
			->select($q2)
			->from('sales_items si,outlet o,sales s,items i')
			->where('
			i.category_id != 5 and
		
			si.item_id = i.id
			and
			s.id=si.sale_id
			and
			i.kode_outlet=o.kode_outlet
			and
			s.status = 1
			and
			date(s.date)>= :date and  date(s.date)<= :date2 
			', array(':date' => $tgl,':date2'=>$tgl2))
			->queryRow();

					
					
			
			// SUMMARY
			$katas = "";
			for ($z=1;$z<=$jumlah;$z++){
			// $ulang = 	"sum(if(i.kode_outlet=$z,si.item_price*si.quantity_purchased+(s.sale_service/s.total_items)*si.quantity_purchased+si.item_tax-(s.sale_discount/s.total_items)*si.quantity_purchased,0)) as o$z,";
			$ulang = 	"sum(if(i.kode_outlet=$z,si.item_price*si.quantity_purchased+(s.sale_service/s.total_items)*si.quantity_purchased-(si.item_discount*si.item_price/100)*si.quantity_purchased,0)) as o$z,";
			// echo $ulang;
			$katas = $katas.' '.$ulang;
			}			
			$q4 = '
			'.$katas.'
			sum(si.item_price*si.quantity_purchased-(si.item_discount*si.item_price/100)*si.quantity_purchased) as total						
			';								
			$summary = Yii::app()->db->createCommand()
			->select($q4)
			->from('sales s,sales_items as si,items as 	i')

			->where('
			i.category_id != 5 and
			
			date(s.date)>= :date and  date(s.date)<= :date2 
			and
			s.id=si.sale_id
			and
			si.item_id= i.id
			and
			s.status=1', array(':date' => $tgl,':date2'=>$tgl2)
			)
			->queryRow();
	
		
		$cash = new CArrayDataProvider($row,array(
			'pagination'=>array(
				'pageSize'=>1000,
			),
		));//dikonfersi ke CArrayDataProvider
		$this->render('outlet', array(
			'datacash' => $cash,
			'outletsum' => $summary,
			'outletbersih' => $bersih,
			'outletbersih_d' => $bersih_d,
			'tgl' => $tgl,
			'tgl2'=>$tgl2
		));
	} 
	
	 
	 
    public function actionIndex() {
        
        if (isset($_GET['Sales']['date'])) 
			$date =  $_GET['Sales']['date'];
		else
			$date =  date('Y-m-d');
			
			//echo $_GET['Sales']['date'];
			//get yii user
			$username = Yii::app()->user->name;
			$user = Users::model()->find('username=:un',array(':un'=>$username));
			$user->id;
			$idk = $user->level; 
			if($idk=='')
				$this->redirect(array('site/login'));
			$data = new Sales;
			//$date = addcslashes($_GET['Sales']['date'], '%_');
			
            $data->date = $_GET['Sales']['date'];
            // $dataProvider = $data->search();
			
			if ($idk != 2){ //jika bukan admin
			
			$sql  = "select waiter,s.bayar,s.table,inserter, s.id,sum(total_items) as total_items, date,
			sum(sale_sub_total) sale_sub_total,

			s.sale_tax,s.sale_service, s.sale_discount, 
			sum(sale_total_cost)  sale_total_cost,
			u.username inserter 
			from sales s,  users u
			where 
			  

			  
			date(s.date)='$date' and s.status=1 and inserter = u.id  and inserter = $user->id   group by s.id  ";
			
			$dataProvider = new CSqlDataProvider($sql, array(
				'totalItemCount'=>$count,
				'sort'=>array(
				'attributes'=>array(
				'desc'=>array('s.id'),
				),
				),
				'pagination'=>array(
				'pageSize'=>100000,
			),
			));
			
			
     //        $summary = Yii::app()->db->createCommand()
     //                ->select('sum(((si.item_price*si.quantity_purchased)+(si.item_tax)-(si.item_discount*(si.item_price*si.quantity_purchased)/100)))   
					// stc, sum(sale_discount) sd , sum(si.item_price*si.quantity_purchased) sst, sum(item_tax) t, sum(sale_service) svc')
     //                ->from('sales s, sales_items si, items i')
     //                ->where('
					//   i.id = si.item_id and i.category_id != 4 and 
			
					// si.sale_id = s.id and date(s.date)=:date AND s.status=:status and inserter =:ins ', array(':date' => $date, ':status' => 1,"ins"=>$user->id))
     //                // ->where('date(date)=:date AND status=:status', array(':date' => $_GET['Sales']['date'], ':status' => 1))
     //                // ->where("date='".$_GET['Sales']['date']."'")
     //                ->queryRow();
			}
			else{
			

			$sql  = "select waiter,s.bayar,s.table,inserter, s.id,sum(total_items) as total_items, date,
			sum(sale_sub_total) sale_sub_total,

			s.sale_tax,s.sale_service, s.sale_discount, 
			sum(sale_total_cost)  sale_total_cost,
			u.username inserter 
			from sales s,  users u
			where 
			  

			  
			date(s.date)='$date' and s.status=1 and inserter = u.id     group by s.id  ";

			$dataProvider = new CSqlDataProvider($sql, array(
				'totalItemCount'=>$count,
				'sort'=>array(
				'attributes'=>array(
				'desc'=>array('s.id'),
				),
				),
				'pagination'=>array(
				'pageSize'=>100000,
			),
			));
						
     //        $summary = Yii::app()->db->createCommand()
     //                ->select('sum(((si.item_price*si.quantity_purchased)+(si.item_tax)-(si.item_discount*(si.item_price*si.quantity_purchased)/100)))   
					// stc, sum(sale_discount) sd , sum(si.item_price*si.quantity_purchased) sst, sum(item_tax) t, sum(sale_service) svc')
     //                ->from('sales s, sales_items si,items i')
     //                ->where('
					// i.id = si.item_id and i.category_id != 4 and 
			
					// s.id = si.sale_id and date(s.date)=:date AND s.status=:status  ', array(':date' => $date, ':status' => 1))
     //                // ->where('date(date)=:date AND status=:status', array(':date' => $_GET['Sales']['date'], ':status' => 1))
     //                // ->where("date='".$_GET['Sales']['date']."'")
     //                ->queryRow();	
		}			
		$tgl = $_GET['Sales']['date'];
		if(empty($_GET['Sales']['date'])){
			$tgl = date('Y-m-d');
		}
		
        $model = new Sales;
        // $dataProvider = $data->search();
        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'summary' => $summary,
			'tgl' => $tgl,
			'model'=>$model,
        ));
    }
	public function actionperiode() {
			
	        $model = new Sales;
	        $tgl = date('Y-m-d');
	        // $dataProvider = $data->search();
	        $this->render('periode', array(
	            // 'dataProvider' => $dataProvider,
	            // 'summary' => $summary,
				'tgl' => $tgl,
				'model'=>$model,
	        ));
	    }

	    public function actionperiodereport(){
	    	$model = new Sales;
	    	$tanggalawal = $_POST['tanggal_awal'];
			$tanggalakhir = $_POST['tanggal_akhir'];
			$tal = $tanggalawal.' 00:00:01';
			$tak = $tanggalakhir.' 23:59:59';
			$sql = "
				select
				id, date, sale_sub_total, sale_discount, sale_service, sale_tax, sale_total_cost, paidwith_id, `table` as aaa, waiter
				from sales
				where date
				between '$tal' and '$tak'
				and status = '1'
			";
			$sqlval = Yii::app()->db->createCommand($sql)->queryAll();
			$this->render('periodereport', array(
				'tgl' => $tgl,
				'model'=> $model,
				'tanggal_awal'=> $tanggalawal,
				'tanggal_akhir'=> $tanggalakhir,
				'sqlval'=> $sqlval,
				'tal'=> $tal,
				'tak'=> $tak,
	        ));
	    }

	    public function actionperiodereportexport(){
		    	$model = new Sales;
		    	$tanggalawal = $_POST['tanggal_awal'];
				$tanggalakhir = $_POST['tanggal_akhir'];
				$tal = $tanggalawal.' 00:00:01';
				$tak = $tanggalakhir.' 23:59:59';
				$sql = "
					select
					id, date, sale_sub_total, sale_discount, sale_service, sale_tax, sale_total_cost, paidwith_id, `table` as aaa, waiter
					from sales
					where date
					between '$tal' and '$tak'
					and status = '1'
				";
				$sqlval = Yii::app()->db->createCommand($sql)->queryAll();
				// $this->render('periodereport', array(
				// 	'tgl' => $tgl,
				// 	'model'=> $model,
				// 	'tanggal_awal'=> $tanggalawal,
				// 	'tanggal_akhir'=> $tanggalakhir,
				// 	'sqlval'=> $sqlval,
				// 	// 'tal'=> $tal,
		  //       ));
		        Yii::app()->request->sendFile(date('YmdHis').'.xls',
					$this->renderPartial('periodereportexport', 
						array(
							'tgl' => $tgl,
							'model'=> $model,
							'tanggal_awal'=> $tanggalawal,
							'tanggal_akhir'=> $tanggalakhir,
							'sqlval'=> $sqlval,
							'tal'=> $tal,
							'tak'=> $tak,
						),
						 true)
				);
		    }


		
		public function actionSalesweekly() {
			$tgl = $_GET['Sales']['date'];
			$tgl2 = $_GET['Sales']['tgl'];		
			if (isset($_GET['Sales']['date']) and isset($_GET['Sales']['tgl']) ) {
				$tgl = $_GET['Sales']['date'];
				$tgl2 = $_GET['Sales']['tgl'];		
			}
			else{
				$tgl = date('Y-m-d'); 
				$tgl2 = date('Y-m-d'); 
			}
				
				//echo $_GET['Sales']['date'];
				//get yii user
				$username = Yii::app()->user->name;
				$user = Users::model()->find('username=:un',array(':un'=>$username));
				$user->id;
				$idk = $user->level; 
				$data = new Sales;
				//$date = addcslashes($_GET['Sales']['date'], '%_');
				
	            $data->date = $_GET['Sales']['date'];
	            // $dataProvider = $data->search();
				
				//seleksi menampilkan data
				if ($idk != 2){
				$sql  = "select s.bayar,s.table,inserter, s.id,sum(si.quantity_purchased) as total_items, date,sum(si.item_price*si.quantity_purchased) sale_sub_total,s.sale_tax,s.sale_service, s.sale_discount, 
				sum(((si.item_price*si.quantity_purchased)+(si.item_tax)-(si.item_discount*(si.item_price*si.quantity_purchased)/100)))  sale_total_cost,
				 u.username inserter 
				 from sales s,sales_items si , users u , items i
				 
				 where 
				  i.id = si.item_id and i.category_id != 4 and
				 s.id = si.sale_id and date(s.date)>='$tgl' and date(s.date) <= '$tgl2' and s.status=1 and inserter = u.id  and inserter = $user->id   group by s.id  ";
				$dataProvider = new CSqlDataProvider($sql, array(
					'totalItemCount'=>$count,
					'sort'=>array(
					'attributes'=>array(
					'desc'=>array('s.id'),
					),
					),
					'pagination'=>array(
					'pageSize'=>100000,
				),
				));
				
				
	            $summary = Yii::app()->db->createCommand()
	                    ->select('sum(((si.item_price*si.quantity_purchased)+(si.item_tax)-(si.item_discount*(si.item_price*si.quantity_purchased)/100)))   
						stc, sum(sale_discount) sd , sum(si.item_price*si.quantity_purchased) sst, sum(item_tax) t, sum(sale_service) svc')
	                    ->from('sales s, sales_items si,items i')
						
	                    ->where('
						 i.id = si.item_id and i.category_id != 4 and
						si.sale_id = s.id and date(s.date)>=:date and date(s.date)<=:date2 AND s.status=:status and inserter =:ins ', array(':date' => $tgl, ':date2'=>$tgl2,':status' => 1,"ins"=>$user->id))
	                    ->queryRow();
				}
				else{
				
				$sql  = "select s.bayar,s.table,inserter, s.id,sum(si.quantity_purchased) as total_items, date,sum(si.item_price*si.quantity_purchased) sale_sub_total,s.sale_tax,s.sale_service, s.sale_discount, 
				sum(((si.item_price*si.quantity_purchased)+(si.item_tax)-(si.item_discount*(si.item_price*si.quantity_purchased)/100)))  sale_total_cost,
				 u.username inserter 
				 from sales s,sales_items si , users u ,items i
				 where 
				  i.id = si.item_id and i.category_id != 4 and
				 s.id = si.sale_id and date(s.date)>='$tgl' and date(s.date) <= '$tgl2'  and s.status=1 and inserter = u.id  group by s.id  ";
				$dataProvider = new CSqlDataProvider($sql, array(
					'totalItemCount'=>$count,
					'sort'=>array(
					'attributes'=>array(
					'desc'=>array('s.id'),
					),
					),
					'pagination'=>array(
					'pageSize'=>100000,
				),
				));
							
	            $summary = Yii::app()->db->createCommand()
	                    ->select('sum(((si.item_price*si.quantity_purchased)+(si.item_tax)-(si.item_discount*(si.item_price*si.quantity_purchased)/100)))   
						stc, sum(sale_discount) sd , sum(si.item_price*si.quantity_purchased) sst, sum(item_tax) t, sum(sale_service) svc')
	                    ->from('sales s, sales_items si,items i')
	                    ->where('
						 i.id = si.item_id and i.category_id != 4 and 
						s.id = si.sale_id and date(s.date)>=:date and date(s.date)<=:date2 AND s.status=:status  ', array(':date' => $tgl,':date2'=>$tgl2 ,':status' => 1))
	                    // ->where('date(date)=:date AND status=:status', array(':date' => $_GET['Sales']['date'], ':status' => 1))
	                    // ->where("date='".$_GET['Sales']['date']."'")
	                    ->queryRow();
				
				
				
				
				}	
			$tgl = $_GET['Sales']['date'];
			if(empty($_GET['Sales']['date'])){
				$tgl = date('Y-m-d');
			}
			
	        $model = new Sales;
	        // $dataProvider = $data->search();
	        $this->render('salesweekly', array(
	            'dataProvider' => $dataProvider,
	            'summary' => $summary,
				'tgl' => $tgl,
				'tgl2'=>$tgl2
	        ));
	    }

	
	public function actionCetakrekap(){
	$this->comp = Branch::model()->findByPk(1)->branch_name;
	$this->adr =  Branch::model()->findByPk(1)->address;
	$this->tlp =  Branch::model()->findByPk(1)->telp;
	$this->slg =  Branch::model()->findByPk(1)->slogan;
		$date = $_GET['tanggal_rekap'];
		
			// $data1 = Yii::app()->db->createCommand()
		// ->select('SUM( sales.sale_tax ) as tax, sum( sales.sale_sub_total ) as cost, sum(sales.sale_discount ) as tot_disc, 
				// sum(sales.sale_total_cost) as stc, sum( if( paidwith_id =12,sales.sale_total_cost ,0 )) as compliment, sum(sales.sale_service) as svc')
		// ->from('sales')
		// ->where("date(date) = '".$date."' and status = 1" )
		// ->queryAll();
		$username = Yii::app()->user->name;
		$user = Users::model()->find('username=:un',array(':un'=>$username));
		$idk = $user->level;
		
		if ($idk ==2){
		
		
		// $data1 = Yii::app()->db->createCommand()
		// ->select('SUM( s.sale_tax ) as tax, sum( s.sale_sub_total ) as cost, sum(s.sale_discount ) as tot_disc, 
			// sum(s.sale_total_cost) as stc  ,s.id,s.date,sum(cash)cash,sum(edc_bca)edc_bca,sum(edc_niaga)edc_niaga,sum(compliment)compliment,sum(dll)dll,sum(voucher)voucher , SUM(cash+edc_bca+edc_niaga+compliment+dll+voucher) total ')
		// ->from('sales s,sales_payment ')
		// ->where('
		// date(s.date)=:date 
		// and  s.status=1 
	
		// and s.id = sales_payment.id', array(':date' => $date))
		// ->queryAll();
		
		

		$data1 = Yii::app()->db->createCommand()
		->select('
		SUM(s.sale_tax ) as tax, 	
		sum(sale_sub_total) as cost, 
		sum(sale_discount)  as tot_disc,
		sum(sale_total_cost) as stc, 
		sum(sale_service) as service
		,s.id
		,s.date
		')
		->from('sales s ,users u')
		->where('
			date(s.date)=:date 
			and  s.status=1 ', array(':date' => $date))
		->queryAll();
		
		$payment = Yii::app()->db->createCommand()
		->select('
		sum(cash)cash,sum(edc_bca)edc_bca,sum(edc_niaga)edc_niaga,sum(compliment)compliment,sum(dll)dll,sum(voucher)voucher , 
		 SUM(cash+edc_bca+edc_niaga+compliment+dll+voucher) total
		')
		->from('sales s,sales_payment ')
		->where('
		date(s.date)=:date 
		and  s.status=1 
		and s.id = sales_payment.id', array(':date' => $date))
		->queryAll();
		// $data1 = Yii::app()->db->createCommand()
		// ->select('SUM( sales.sale_tax ) as tax, sum( sales.sale_sub_total ) as cost, sum(sales.sale_discount ) as tot_disc, 
				// sum(sales.sale_total_cost) as stc, sum( if( paidwith_id =12,sales.sale_total_cost ,0 )) as compliment, sum(sales.sale_service) as svc,sum( if( paidwith_id =1,sales.sale_total_cost ,0 )) as netcash,sum( if( paidwith_id =3,sales.sale_total_cost ,0 )) as BCA, sum( if( paidwith_id =4,sales.sale_total_cost ,0 )) as mandiri, sum( if( paidwith_id =5,sales.sale_total_cost ,0 )) as niaga')
		// ->from('sales')
		// ->where("date(date) = '".$date."' and status = 1" )
		// ->queryAll();
	
		$data = Yii::app()->db->createCommand()
		->select('outlet.nama_outlet nm,items.item_name item_name,item_price harga,sales_items.item_price * sum(if(sales_items.item_price<0,-quantity_purchased,quantity_purchased)) price,sum(if(sales_items.item_price<0,-quantity_purchased,quantity_purchased)) qp')
		->from('sales_items')
		->join('sales', 'sales.id=sales_items.sale_id')
		->join('items', 'items.id=sales_items.item_id')
		->join('outlet', 'outlet.kode_outlet = items.kode_outlet')
		// ->where('date(date=:date)', array(':date'=>date('2013-03-05')))
		->where("date(date) = '".$date."' and sales.status = 1 ")
		->group('item_id')
		->order('outlet.kode_outlet')
		->queryAll();
		}
		else
		{
		
		$data1 = Yii::app()->db->createCommand()
		->select('
		SUM(sale_tax) as tax, 	
		sum(sale_sub_total) as cost, 
		sum(sale_discount)  as tot_disc,
		sum(sale_total_cost) as stc, 
		sum(sale_service) as service 
		,s.id
		,s.date
		')
		->from('sales s ')
		->where('

		date(s.date)=:date 
		and  s.status=1 
		 and s.inserter=:i', array(':date' => $date,':i'=> $user->id))
		// ->group('i.id')
		->queryAll();
		
		
		// $data1 = Yii::app()->db->createCommand()
		// ->select('SUM( s.sale_tax ) as tax, sum( s.sale_sub_total ) as cost, sum(s.sale_discount ) as tot_disc, 
			// sum(s.sale_total_cost) as stc  ,s.id,s.date,sum(cash)cash,sum(edc_bca)edc_bca,sum(edc_niaga)edc_niaga,sum(compliment)compliment,sum(dll)dll,sum(voucher)voucher , SUM(cash+edc_bca+edc_niaga+compliment+dll+voucher) total ')
		// ->from('sales s,sales_payment ')
		// ->where('date(s.date)=:date and s.inserter=:i and  s.status=1 and s.id = sales_payment.id', array(':date' => $date, ':i'=> $user->id))
		// ->queryAll();
		
		$payment = Yii::app()->db->createCommand()
		->select('
		sum(cash)cash,sum(edc_bca)edc_bca,sum(edc_niaga)edc_niaga,sum(compliment)compliment,sum(dll)dll,sum(voucher)voucher , 
		 SUM(cash+edc_bca+edc_niaga+compliment+dll+voucher) total
		')
		->from('sales s,sales_payment ')
		->where('
		date(s.date)=:date 
		and  s.status=1 
		 and s.inserter=:i
		and s.id = sales_payment.id', array(':date' => $date,':i'=> $user->id))
		->queryAll();
	
		$data = Yii::app()->db->createCommand()
		//->select('outlet.nama_outlet nm,items.item_name item_name,sales_items.item_price * sum(quantity_purchased) price,sum(quantity_purchased) qp')
		->select('outlet.nama_outlet nm,items.item_name item_name,item_price harga,sales_items.item_price * sum(if(sales_items.item_price<0,-quantity_purchased,quantity_purchased)) price,sum(if(sales_items.item_price<0,-quantity_purchased,quantity_purchased)) qp')
		
		->from('sales_items')
		->join('sales', 'sales.id=sales_items.sale_id')
		->join('items', 'items.id=sales_items.item_id')
		->join('outlet', 'outlet.kode_outlet = items.kode_outlet')
		// ->where('date(date=:date)', array(':date'=>date('2013-03-05')))
		->where("date(date) = '".$date."' and sales.status = 1  and sales.inserter = ".$user->id." and items.category_id != 5 ")
		->group('item_id')
		->order('outlet.kode_outlet')
		->queryAll();
		}
		//untuk hutang
		//untuk hutang
		$hutang = Yii::app()->db->createCommand()
		->select('s.id id,s.date tanggal_beli ,  a.tgl_bayar tanggal_bayar, u.username, sale_total_cost stc')		
		->from('sales s, acp a, users u')
		->where(" s.id = a.sale_id AND s.inserter = u.id AND  s.status = 1 and s.bayar = 0  ")
		//sales.inserter = ".$user->id." 
		->queryAll();
		// echo "<pre>";
		// print_r($hutang);
		// echo "</pre>";
		$tmp5 = array();
		
		foreach ($hutang as $htg)
		{
			$tmp5['faktur'] = "Nota  : ".$htg['id'];
			$tmp5['bayar'] =  "Total : ".number_format($htg['stc']);
			$gt+=$htg['stc'];
			$hihi[] = $tmp5;
			// $ctgr = "";
			// echo $rows['category']." - ".$ctgr."<BR>";
			
			// if($rows['nm']!=$ctgr){
				// $ctgr = $rows['nm'];
				// $wCtgr = "TENANT : ".strtoupper($rows['nm'])."\r";
				// $tmp4['dept'] = $wCtgr;
				// $tmp4['pembatas'] = $this->spasi($total_margin, "_");
			// }else{
				// $tmp4['dept'] = "";
				// $tmp4['pembatas'] = "";
			// }
			// $tmp3 = $tmp4;
			// $tmp4 = array();
			// $table = "- ".strtoupper($rows['
			// ']). "";
			// // $table .= "\t ".strtoupper($rows['qp'])." Item :".$this->set_spacebar(number_format($rows['price']),$pjg_ket, "kanan"). "\n";
			// $table .= " : ".strtoupper($rows['harga'])." x ".strtoupper($rows['qp'])."  = ";
			
			// $totalItems += $rows['qp'];
			// $totalPrice += $rows['price'];
			
			// $tmp3['table'] = $table;
			// $tmp3['harga'] = number_format($rows['price']);;
			// $temp[] = $tmp3;
		}
			$tmp5['grandtotal'] =  "Grand Total  : ".$gt;
		//akhir
		// echo "<pre>";
		// print_r($tmp5);
		// echo "</pre>";
		
		$pembatas = 20;
		$model = $_GET['data'];
		$detail = $_GET['data_detail'];
		
        $total_margin = 40;

        // $myFile = "c:\\epson\\cetakbarujual.txt";
        $temp_data = array();
        // $temp_data['logo'] = $this->set_spacebar($this->comp, $total_margin, "tengah");
        $temp_data['logo'] = $this->comp;
        // $temp_data['alamat'] = $this->set_spacebar($this->adr, $total_margin, "tengah");
        $temp_data['alamat'] = $this->adr;
        // $temp_data['no_telp'] = $this->set_spacebar("Telp. ".$this->tlp, $total_margin, "tengah");
        $temp_data['no_telp'] = "Telp. ".$this->tlp;
        // $temp_data['no_nota'] = $this->set_spacebar("No Nota : Belum Bayar", "", "tengah");
        // $temp_data['trx_tgl'] = $this->set_spacebar(date('d  M  Y ',strtotime($date)), $total_margin, "tengah");
        $temp_data['trx_tgl'] = date('d  M  Y ',strtotime($date));
        $pjg_ket = $total_margin - 13;
        // $temp_data['no_meja'] = "Meja   : " . $this->set_spacebar($model['table'], $pjg_ket, "tengah") . "\r";
		$username = Yii::app()->user->name;
		//$user = Users::model()->find('username=:un',array(':un'=>$username));
		//$user->branch_id;
			
		
		//$inserter = Sales::model()->find('id=:ids',array(':ids'=>$id));
		//$nama = Users::model()->find('id=:ids',array(':ids'=>$inserter['inserter']));
		
		
		
		$temp_data['kasir']=   "Kasir          : " .$username;
			// $tglheader  = date('d_M_Y',strtotime($tgl));
        $temp_data['tgl_cetak']=   "Tanggal Cetak  : " . date('d-M-Y H:i:s') . "\r";

		
		
		$temp_data['pembatas'] = $this->spasi($total_margin, "-");

		$tmp = array();
		$kosong = array();
		$tmpt = array();
		foreach($data1 as $dt1){
			//gross sales
			// $net_sales = $dt1['cost'] - $dt1['tot_disc']-$dt1['tax'];
			//$net_sales = $dt1['cost'];
			$tmp['gross'] ="Net Sales      \t:";
			$tmp['grossvalue'] = number_format($dt1['stc'])."\n";
			
			$tmp['net'] =  "Gross Sales    \t:";
			$tmp['netvalue'] = number_format($dt1['cost'])."\n";

			$tmp['disc'] = "Discount       \t:";
			$tmp['discvalue'] = number_format($dt1['tot_disc'])."\n";

			$tmp['svc'] = "Service        \t:";
			$tmp['svcvalue'] = number_format($dt1['service'])."\n";
			
			$tmp['tax'] = "Tax            \t:";
			$tmp['taxvalue'] = number_format($dt1['tax'])."\n";
			$tmp2 = $tmp;
			
			//------------------------------------------------------------
		}	
		foreach($payment as $dt1){
			$tmpt['total'] =     "Total Payment \t:";
			$tmpt['totalvalue'] = "".number_format($dt1['total'])."\n";
			
			$tmpt['comp'] =     "Compliment    \t:";
			$tmpt['compvalue'] = "".number_format($dt1['compliment'])."\n";
			
			$tmpt['netcash'] =  "Net Cash      \t:";
			$tmpt['netcashvalue'] = "".number_format($dt1['cash'])."\n";

			$tmpt['bca'] =      "BCA           \t:";
			$tmpt['bcavalue'] = "".number_format($dt1['edc_bca'])."\n";

			$tmpt['mandiri'] =  "Niaga         \t:";
			$tmpt['mandirivalue'] = "".number_format($dt1['edc_niaga'])."\n";
			
			$tmpt['niaga'] =    "Voucher       \t:";
			$tmpt['niagavalue'] = "".number_format($dt1['voucher'])."\n";
			
			$tmpt['dll'] =    "Pending       \t:";
			$tmpt['dllvalue'] = "".number_format($dt1['dll'])."\n";
			$tmp3 = $tmpt;
		}
			// array_push($tmp2,$tmpt);
		// echo "<pre>";
		// echo "hasil";
		// print_r($tmp3);
		// echo "</pre>";
		// echo "hihi";
		$temp_data['detail'] = $tmp2;
		$temp_data['detailpay'] = $tmp3;
		$temp_data['hutang'] = $hihi;
		
		// $tmp_hutang['']
		
		$pjg_ket = $total_margin - 20;
				
		$jml_item = 18;
		
		//for($c=0;$c<$jml_item;$c++)
		$temp = array();
		foreach ($data as $rows)
		{
			// $ctgr = "";
			// echo $rows['category']." - ".$ctgr."<BR>";
			
			if($rows['nm']!=$ctgr){
				$ctgr = $rows['nm'];
				$wCtgr = "TENANT : ".strtoupper($rows['nm'])."\r";
				$tmp4['dept'] = $wCtgr;
				$tmp4['pembatas'] = $this->spasi($total_margin, "_");
			}else{
				$tmp4['dept'] = "";
				$tmp4['pembatas'] = "";
			}
			$tmp3 = $tmp4;
			$tmp4 = array();
			$table = "- ".strtoupper($rows['item_name']). "";
			// $table .= "\t ".strtoupper($rows['qp'])." Item :".$this->set_spacebar(number_format($rows['price']),$pjg_ket, "kanan"). "\n";
			$table .= " : ".strtoupper($rows['harga'])." x ".strtoupper($rows['qp'])."  = ";
			
			$totalItems += $rows['qp'];
			$totalPrice += $rows['price'];
			
			$tmp3['table'] = $table;
			$tmp3['harga'] = number_format($rows['price']);;
			$temp[] = $tmp3;
		}
		// echo "<pre>";
		// print_r($hihi);
		// echo "</pre>";
		
		$temp_data['detail2'] = $temp;
		
		$temp_data['pembatas'] = $this->spasi($total_margin, "_");
		
		
		$temp_data['total'] = "\t ".$totalItems." ITEMS\t\t ".number_format($totalPrice)."\r\n";
		$temp_data['footer'] = $this->set_spacebar($this->slg, $total_margin, "tengah") . "\r\n";
		$temp_data['footer2'] =  $this->set_spacebar("(c) ".$this->comp." - ".date("Y"), $total_margin, "tengah") . "\r\n";
		
		
		echo json_encode($temp_data);
		// echo "<pre>";
		// print_r($temp_data);
		// echo "</pre>";
		
		
	}
	
	public function actionPrintnPrintrekap(){
	
		// $pembatas = 20;
		// // $model = $_GET['data'];
		// $detail = $_GET['data_detail'];
		// $hit = 2;
		
        // $total_margin = 40;

        // for ($a = 0; $a < count($detail); $a++) {
            // $nama_item = Items::model()->find("id=:id", array(':id' => $detail[$a]['item_id']));
            
			// $panjang1 = strlen($nama_item['item_name']);
            // $panjang2 = strlen(number_format($detail[$a]['item_total_cost']));
            // $banyakspasi = $total_margin - $panjang1 - $panjang2;
			
			// $temp = array();
            // $temp['quantity'] = $detail[$a]['quantity_purchased'] . " x " . $detail[$a]['item_price'] . " - " . $detail[$a]['item_discount'] . "% disc";
            // // fwrite($fh, $baris1 . "\r\n");
			// $temp['nama_item'] = $nama_item['item_name'] . " " . $this->spacebar($banyakspasi - 1) . number_format($detail[$a]['item_total_cost']);

            // // echo "---->".$banyakspasi."=".$total_margin."-".$panjang1."-".$panjang2."<BR>";
            // $baris2 = $nama_item['item_name'] . " " . $this->spasi($banyakspasi, "&nbsp;") . number_format($detail[$a]['item_total_cost']);
            // $data['baris2a'] = $nama_item['item_name'] . " " . $this->spacebar($banyakspasi - 1) . number_format($detail[$a]['item_total_cost']);

          // //  $subtotal += $detail[$a]['item_total_cost'];
			// $temp2[] = $temp;
        // }
		// $temp_data['detail'] = $temp2;
        // echo json_encode($temp_data);
	}
	
    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Sales('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Sales']))
            $model->attributes = $_GET['Sales'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Sales the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Sales::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function load_model($id) {
        $model = Sales::model()->findByPk($id);
        if ($model === null)
            return new Sales();
        else
            return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Sales $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'sales-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
	
	// public function __construct()
	// {
	 	// $this->comp = Branch::model()->findByPk(1)->branch_name;
		// $this->adr =  Branch::model()->findByPk(1)->address;
		// $this->tlp =  Branch::model()->findByPk(1)->telp;
	// }
	// public function beforeAction(cetak){
        // $this->comp = Branch::model()->findByPk(1)->branch_name;
		// $this->adr =  Branch::model()->findByPk(1)->address;
		// $this->tlp =  Branch::model()->findByPk(1)->telp;
	// }
	
}
