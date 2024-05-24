<?php

class SalesController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    // public $layout = '//layouts/admin';
    public $layout = 'backend';
	public $comphead ;
	public $comp ;
	public $adr ;
	public $tlp ;
	public $slg ;
	public $status_bayar  = ""  ;
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
    public function GenerateSalesFakturMethod(){

    	$branch_id_real = Yii::app()->user->branch();
       	$branch_id = str_pad($branch_id_real,3,"0",STR_PAD_LEFT);
     	$kode = '35J';
        $query = "SELECT
				IFNULL(
					CONCAT(
						'$branch_id',
						'$kode',
						LPAD(
							MAX(SUBSTR(faktur_id, 7, 6)) + 1,
							6,
							'0'
						)
					),
					'{$branch_id}{$kode}000001'
				) AS urutan
			FROM
				sales
			WHERE branch = '$branch_id_real' and sales.status = 1
                 ";
                 // echo $query;
        $model = Yii::app()->db->createCommand($query)->queryRow();
        return   $model['urutan'];
    }
  	public function actionGenerateSalesFaktur() {
  		echo $this->GenerateSalesFakturMethod();
   }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
	
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('hapusregister','GetOmsetByUser','laporanpulsa','TransaksiHapus','GenerateSalesFaktur','CetakStok','CetakMasuk','CetakKeluar','Rekapdetail','rekap','laporanstok','cetakfaktur','cetakfaktur_mini','bayarhutang','grafikpenjualan','cetakreportall','artimeja','periode','periodereport','periodereportexport','getmenu','getsaleid2','getharga','datahutang','hutang','del','delete','hapusmeja','grafik','printbagihasil','salescashweekly','salesweekly','salesoutletweekly', 'view', 'bayar', 'load', 'void','Getsaleid','hanyacetak','cashreport','CetakReport','Pindahmeja','sessid','Uservoid','Cetakrekap','Export','Salesmonthly','Outletreport','Salesoutletmonthly','Salescashmonthly','detailitems','ex','printData','bestseller','reportbestseller','reportbestsellerexport','pengunjung','periodepengungjung','bsgrafik','reportbsgrafik','reportbsgrafikexport','penggrafik','penggrafikreport','laporan_hutang','getSaleData','bayartagihan','CetakBillTerakhir'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('reopenregister','adminjson','index','tutupregister','lunasin','labarugi','hapus','rekapmenu','create', 'update','grafikmember','GetCustomer2'),
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

      public function actionHapusregister(){
      	if (isset($_REQUEST['tanggal_rekap'])){	
	      	$setor = Setor::model()->find("tanggal = '$_REQUEST[tanggal_rekap]' and user_id='$_REQUEST[inserter]' ");
	      	if ($setor->delete()){
				$this->redirect(array('sales/rekap'));
	      	}
      	}
      }

	  public function actionReopenregister(){
		if (isset($_REQUEST['tanggal_rekap'])){	
			$setor = Setor::model()->find("tanggal = '$_REQUEST[tanggal_rekap]' and user_id='$_REQUEST[inserter]' ");
			$setor->is_closed = 0;
			$setor->total = 0;
			if ($setor->update()){
			  $this->redirect(array('sales/rekap'));
			}
		}
	}

	   public function actionTutupregister(){
      	if (isset($_REQUEST['tanggal_rekap'])){	
	      	$setor = Setor::model()->find("tanggal = '$_REQUEST[tanggal_rekap]' and user_id='$_REQUEST[inserter]' ");
	      	$setor->total = $_REQUEST['must'];
	      	$setor->is_closed = 1;
	      	$setor->closed_date = date('Y-m-d H:i:s');
			  if ($setor->update()){
				$this->redirect(array('sales/rekap'));
	      	}
      	}
      }



      public function actionGetOmsetByUser($date,$user_id){
	
		$sql = "select 
		nama_user,
		total_awal,
		sum(sale_total_cost) total_omset,
		sum(adt_cost) as total_biaya,
		sum(cash) as cash,
		sum(edc_bca) as cashless,
		sum(voucher) as voucher,
		sum(total_fisik) as total_fisik,
		sum(total_fisik) - sum(sale_total_cost) as selisih	

		from 
		(

		SELECT
		total_awal,
		sp.voucher voucher,
		sp.cash cash,
		sp.edc_bca edc_bca,
		#SUM(adt_cost)/total_items as total_biaya,
		adt_cost,
		u.username nama_user,
		s.inserter sia,
		se.id seid,
		u.id userid,
		se.total total_fisik,
		s.bayar,
		s.TABLE,
		inserter,
		s.id,
		sum(si.quantity_purchased) AS total_items,
		date AS tanggal,
		sum(
		si.item_price * si.quantity_purchased
		) sale_sub_total,
		s.sale_tax,
		s.sale_service,
		s.sale_discount,
		sum(
		(
			(
				si.item_price * si.quantity_purchased
			) + (si.item_service) + (si.item_tax) - (
				si.item_discount * (
					si.item_price * si.quantity_purchased
				) / 100
			)
		)
		) sale_total_cost
		FROM
		sales s
		INNER JOIN sales_items si ON s.id = si.sale_id
		INNER JOIN users u ON s.inserter = u.id
		INNER JOIN items i ON i.id = si.item_id
		INNER JOIN sales_payment sp ON sp.id = s.id
		LEFT JOIN (
		SELECT DISTINCT
		tanggal,
		user_id,
		total,
		total_awal,
		id
		FROM
		setor
		) AS se ON se.tanggal = date(s.date)
		AND se.user_id = s.inserter
		WHERE
		 date(s.date) = '$date'
		AND s. STATUS = 1
		GROUP BY
		s.inserter,s.id
		) as A
		where A.userid = '$user_id'
		group by A.nama_user

		";



		$dta = Yii::app()->db->createCommand($sql)->queryRow();
		$username = Yii::app()->user->name;
		$query_pengeluaran  = "select sum(total) as total_pengeeluaran  from pengeluaran where 1=1 and date(tanggal)='$date' and user='$username' order by tanggal desc";
		$data_pengeluaran = Yii::app()->db->createCommand($query_pengeluaran)->queryRow();		
		echo json_encode(array(
			"pengeluaran"=>$data_pengeluaran['total_pengeeluaran'] === null ? 0 : $data_pengeluaran['total_pengeeluaran'],
			"potongan"=>$dta['voucher'],
			"cash"=>$dta['cash'],
			"total_awal"=>$dta['total_awal'],
			"cashless"=>$dta['cashless']
		));




    }

    
    public function actionCetakBillTerakhir(){
		$id = Yii::app()->user->id;
		$user = Users::model()->find("username = '$id'");
		$query = "select max(id) as max from sales where inserter = '$user->id' ";
		// var_dump($que)
		$data = Yii::app()->db->createCommand($query)->queryRow();
		$max = $data['max'];
		// var_dump($max);
		// exit;
		$this->actionCetakReport($max);
	}
		// echo $query;
		// exit;
		// var_dump($max);
		// exit;
		// echo $max;

    public function actionLaporanstok(){
    	 $this->render('laporanstok' ) ;

    }
    public function actionCetakfaktur($id){
		 $this->renderPartial('cetakfaktur', array('id'=>$id) ) ;

    }
    public function actionCetakfaktur_mini($id){
		 $this->renderPartial('cetakfaktur_mini', array('id'=>$id) ) ;

    }

   
    public function GetOmset($month,$year,$status){
			if (!isset($month)){
				$month = intval(Date('m'));
				$year = intval(Date('Y'));
			}

			$subtotal = "si.item_price*si.quantity_purchased" ; 
			$submodal = "item_modal *si.quantity_purchased" ; 
			$untung = " ($subtotal) - ($submodal) " ; 
			$sale_service = "si.item_service" ; 
			$sql  = "select nama,
			if (bayar<sum($subtotal) or bayar=0 ,'Kredit','lunas') as sb, 
			s.bayar,s.table,inserter, s.comment comment, 
			s.id,sum(si.quantity_purchased) as total_items, 
			date,tanggal_jt,
			s.waiter waiter,

			sum($untung) untung,
			sum($subtotal) sale_sub_total,
			sum($submodal) sale_sub_modal,
			
			sum($sale_service) sale_service,
			sum(si.item_tax) sale_tax,
			sp.voucher voucher,
			sum( si.item_discount/100 * ($subtotal) )  sale_discount,
			
			sum((
				($subtotal) + $sale_service + (si.item_tax)-  ( si.item_discount/100 * ($subtotal)) 
			)) - (sp.voucher)  
			sale_total_cost,

			 u.username inserter 
			 from sales s,sales_items si , users u , items i, sales_payment sp 
			 where 
			  sp.id = s.id 
			  and
			  i.id = si.item_id  and
			  
			 s.id = si.sale_id and month(s.date)='$month' and year(s.date)='$year'  

			
			 and inserter = u.id  
			 group by month(s.date)
			  ";
			 $model = Yii::app()->db->createCommand($sql)->queryRow();
			 // print_r($model);
			 if ($status=="modal")
				 return $model['sale_sub_modal'];
			 else if ($status=="omset")
				 return $model['sale_sub_total'];

    }
   //  public function GetModel($month,$year,$status){
			// if (isset($month)){
			// 	$month = $_POST['month'];
			// 	$year = $_POST['year'];
			// }else{
			// 	$month = intval(Date('m'));
			// 	$year = intval(Date('Y'));
			// }
 
			// $sql  = " select * from barangmasuk bm inner join barangmasuk_detail bmd
			// on bmd.head_id = bm.id 
		  
			// where month(tanggal)='$month' and year(tanggal)='$year'  

			//  and s.status=1 
			//  and inserter = u.id  
			//  group by month(s.date)
			//   ";
			//  $model = Yii::app()->db->createCommand($sql)->queryRow();
			//  // print_r($model);
			//  if ($status=="modal")
			// 	 return $model['sale_sub_modal'];
			//  else if ($status=="omset")
			// 	 return $model['sale_sub_total'];

   //  }
	
	// Koding Triana##buka
    public function actionLabaRugi(){
	 	if (isset($_POST['month'])){
			$month = $_POST['month'];
			$year = $_POST['year'];
		}else{
			$month = intval(Date('m'));
			$year = intval(Date('Y'));
		}

		 $this->render('labarugi3', array(
            // 'dataProvider' => $dataProvider,
            // 'summary' => $summary,
			// 'tgl' => $date,
			// 'tgl2' => $date2,
			'month'=>$month,
			'year'=>$year,
        ));

    }
    public function actionartimeja()
    {
    	$asal = $_REQUEST['no_meja'];
    	$statusmeja = Meja::model()->findByPk($asal)->status;
    	echo($statusmeja);
    }
    //##tutup

    public function actionRekapmenu(){
    	$this->renderPartial('detailoutlet_print_v2', array());
    }
    public function actionRekap(){
    	// $this->layout = "backend";
    	$this->render('rekapkasir', array());
    }

	public function actionLaporanPulsa(){
    	// $this->layout = "backend";
    	$this->render('laporanpulsa', array());
    }

     public function actionRekapdetail(){
    	// $this->layout = "backend";
    	$this->render('rekapkasirdetail', array());
    }

    public function actionpenggrafik()
    {
    	$model = new Sales;
        $tgl = date('Y-m-d');
        // $dataProvider = $data->search();
        $this->render('penggrafik', array(
            // 'dataProvider' => $dataProvider,
            // 'summary' => $summary,
			'tgl' => $tgl,
			'model'=>$model,
        ));
    }

    public function actionpenggrafikreport()
    {
    	$tanggalawal = $_POST['tanggal_awal'];
		$tanggalakhir = $_POST['tanggal_akhir'];
		$tal = $tanggalawal.' 00:00:01';
		$tak = $tanggalakhir.' 23:59:59';
		$sql00 = "select sum(status) jumlah from sales where time(date) between '00:00:00' and '00:59:59' and date between '$tal' and '$tak'";
		$sql01 = "select sum(status) jumlah from sales where time(date) between '01:00:00' and '01:59:59' and date between '$tal' and '$tak'";
		$sql02 = "select sum(status) jumlah from sales where time(date) between '02:00:00' and '02:59:59' and date between '$tal' and '$tak'";
		$sql03 = "select sum(status) jumlah from sales where time(date) between '03:00:00' and '03:59:59' and date between '$tal' and '$tak'";
		$sql04 = "select sum(status) jumlah from sales where time(date) between '04:00:00' and '04:59:59' and date between '$tal' and '$tak'";
		$sql05 = "select sum(status) jumlah from sales where time(date) between '05:00:00' and '05:59:59' and date between '$tal' and '$tak'";
		$sql06 = "select sum(status) jumlah from sales where time(date) between '06:00:00' and '06:59:59' and date between '$tal' and '$tak'";
		$sql07 = "select sum(status) jumlah from sales where time(date) between '07:00:00' and '07:59:59' and date between '$tal' and '$tak'";
		$sql08 = "select sum(status) jumlah from sales where time(date) between '08:00:00' and '08:59:59' and date between '$tal' and '$tak'";
		$sql09 = "select sum(status) jumlah from sales where time(date) between '09:00:00' and '09:59:59' and date between '$tal' and '$tak'";
		$sql10 = "select sum(status) jumlah from sales where time(date) between '10:00:00' and '10:59:59' and date between '$tal' and '$tak'";
		$sql11 = "select sum(status) jumlah from sales where time(date) between '11:00:00' and '11:59:59' and date between '$tal' and '$tak'";
		$sql12 = "select sum(status) jumlah from sales where time(date) between '12:00:00' and '12:59:59' and date between '$tal' and '$tak'";
		$sql13 = "select sum(status) jumlah from sales where time(date) between '13:00:00' and '13:59:59' and date between '$tal' and '$tak'";
		$sql14 = "select sum(status) jumlah from sales where time(date) between '14:00:00' and '14:59:59' and date between '$tal' and '$tak'";
		$sql15 = "select sum(status) jumlah from sales where time(date) between '15:00:00' and '15:59:59' and date between '$tal' and '$tak'";
		$sql16 = "select sum(status) jumlah from sales where time(date) between '16:00:00' and '16:59:59' and date between '$tal' and '$tak'";
		$sql17 = "select sum(status) jumlah from sales where time(date) between '17:00:00' and '17:59:59' and date between '$tal' and '$tak'";
		$sql18 = "select sum(status) jumlah from sales where time(date) between '18:00:00' and '18:59:59' and date between '$tal' and '$tak'";
		$sql19 = "select sum(status) jumlah from sales where time(date) between '19:00:00' and '19:59:59' and date between '$tal' and '$tak'";
		$sql20 = "select sum(status) jumlah from sales where time(date) between '20:00:00' and '20:59:59' and date between '$tal' and '$tak'";
		$sql21 = "select sum(status) jumlah from sales where time(date) between '21:00:00' and '21:59:59' and date between '$tal' and '$tak'";
		$sql22 = "select sum(status) jumlah from sales where time(date) between '22:00:00' and '22:59:59' and date between '$tal' and '$tak'";
		$sql23 = "select sum(status) jumlah from sales where time(date) between '23:00:00' and '23:59:59' and date between '$tal' and '$tak'";
		$nsql00 = Yii::app()->db->createCommand($sql00)->queryRow();
		$nsql01 = Yii::app()->db->createCommand($sql01)->queryRow();
		$nsql02 = Yii::app()->db->createCommand($sql02)->queryRow();
		$nsql03 = Yii::app()->db->createCommand($sql03)->queryRow();
		$nsql04 = Yii::app()->db->createCommand($sql04)->queryRow();
		$nsql05 = Yii::app()->db->createCommand($sql05)->queryRow();
		$nsql06 = Yii::app()->db->createCommand($sql06)->queryRow();
		$nsql07 = Yii::app()->db->createCommand($sql07)->queryRow();
		$nsql08 = Yii::app()->db->createCommand($sql08)->queryRow();
		$nsql09 = Yii::app()->db->createCommand($sql09)->queryRow();
		$nsql10 = Yii::app()->db->createCommand($sql10)->queryRow();
		$nsql11 = Yii::app()->db->createCommand($sql11)->queryRow();
		$nsql12 = Yii::app()->db->createCommand($sql12)->queryRow();
		$nsql13 = Yii::app()->db->createCommand($sql13)->queryRow();
		$nsql14 = Yii::app()->db->createCommand($sql14)->queryRow();
		$nsql15 = Yii::app()->db->createCommand($sql15)->queryRow();
		$nsql16 = Yii::app()->db->createCommand($sql16)->queryRow();
		$nsql17 = Yii::app()->db->createCommand($sql17)->queryRow();
		$nsql18 = Yii::app()->db->createCommand($sql18)->queryRow();
		$nsql19 = Yii::app()->db->createCommand($sql19)->queryRow();
		$nsql20 = Yii::app()->db->createCommand($sql20)->queryRow();
		$nsql21 = Yii::app()->db->createCommand($sql21)->queryRow();
		$nsql22 = Yii::app()->db->createCommand($sql22)->queryRow();
		$nsql23 = Yii::app()->db->createCommand($sql23)->queryRow();
    	$this->render('penggrafikreport', array(
			'tanggal_awal'=> $tanggalawal,
			'tanggal_akhir'=> $tanggalakhir,
			'nsql00'=>$nsql00,
			'nsql01'=>$nsql01,
			'nsql02'=>$nsql02,
			'nsql03'=>$nsql03,
			'nsql04'=>$nsql04,
			'nsql05'=>$nsql05,
			'nsql06'=>$nsql06,
			'nsql07'=>$nsql07,
			'nsql08'=>$nsql08,
			'nsql09'=>$nsql09,
			'nsql10'=>$nsql10,
			'nsql11'=>$nsql11,
			'nsql12'=>$nsql12,
			'nsql13'=>$nsql13,
			'nsql14'=>$nsql14,
			'nsql15'=>$nsql15,
			'nsql16'=>$nsql16,
			'nsql17'=>$nsql17,
			'nsql18'=>$nsql18,
			'nsql19'=>$nsql19,
			'nsql20'=>$nsql20,
			'nsql21'=>$nsql21,
			'nsql22'=>$nsql22,
			'nsql23'=>$nsql23,
        ));
    }

    public function actionbsgrafik()
    {
    	$model = new Sales;
	    $tgl = date('Y-m-d');
    	$this->render('bsgrafik', array(
    		'tgl'=>$tgl,
			'model'=>$model,
    		));
    }

    public function actionreportbsgrafik()
    {
    	$tanggalawal = $_POST['tanggal_awal'];
		$tanggalakhir = $_POST['tanggal_akhir'];
		$kategori = $_POST['kategori'];
		$tal = $tanggalawal.' 00:00:01';
		$tak = $tanggalakhir.' 23:59:59';
		if($kategori==""){
			$sql = "
				select sum(si.quantity_purchased) jumlah, i.item_name 
				from sales_items si, items i, sales s
				where i.id = si.item_id
				and s.id = si.sale_id
				and s.date between '$tal' and '$tak'
				and s.status = '1'
				group by i.item_name
				order by jumlah desc
				limit 10
			";
		}
		else if($kategori=="makanan"){
			$sql = "
				select sum(si.quantity_purchased) jumlah, i.item_name 
				from sales_items si, items i, sales s
				where i.id = si.item_id
				and s.id = si.sale_id
				and i.category_id = 2
				and s.date between '$tal' and '$tak'
				and s.status = '1'
				group by i.item_name
				order by jumlah desc
				limit 10
			";
		}
		else if($kategori=="minuman"){
			$sql = "
				select sum(si.quantity_purchased) jumlah, i.item_name 
				from sales_items si, items i, sales s
				where i.id = si.item_id
				and s.id = si.sale_id
				and i.category_id = 1
				and s.date between '$tal' and '$tak'
				and s.status = '1'
				group by i.item_name
				order by jumlah desc
				limit 10
			";
		}
		$sqlv = Yii::app()->db->createCommand($sql)->queryAll();
		$this->render('reportbsgrafik', array(
			'sqlv'=>$sqlv,
			'tanggal_awal'=> $tanggalawal,
			'tanggal_akhir'=> $tanggalakhir,
			'kategori'=> $kategori,
			));
    }

    public function actionreportbsgrafikexport()
    {
    	$tanggalawal = $_POST['tanggal_awal'];
		$tanggalakhir = $_POST['tanggal_akhir'];
		$kategori = $_POST['kategori'];
		$tal = $tanggalawal.' 00:00:01';
		$tak = $tanggalakhir.' 23:59:59';
		if($kategori==""){
			$sql = "
				select sum(si.quantity_purchased) jumlah, i.item_name 
				from sales_items si, items i, sales s
				where i.id = si.item_id
				and s.id = si.sale_id
				and s.date between '$tal' and '$tak'
				and s.status = '1'
				group by i.item_name
				order by jumlah desc
				limit 10
			";
		}
		else if($kategori=="makanan"){
			$sql = "
				select sum(si.quantity_purchased) jumlah, i.item_name 
				from sales_items si, items i, sales s
				where i.id = si.item_id
				and s.id = si.sale_id
				and i.category_id = 2
				and s.date between '$tal' and '$tak'
				and s.status = '1'
				group by i.item_name
				order by jumlah desc
				limit 10
			";
		}
		else if($kategori=="minuman"){
			$sql = "
				select sum(si.quantity_purchased) jumlah, i.item_name 
				from sales_items si, items i, sales s
				where i.id = si.item_id
				and s.id = si.sale_id
				and i.category_id = 1
				and s.date between '$tal' and '$tak'
				and s.status = '1'
				group by i.item_name
				order by jumlah desc
				limit 10
			";
		}
		$sqlv = Yii::app()->db->createCommand($sql)->queryAll();
		Yii::app()->request->sendFile(date('YmdHis').'.xls',
					$this->renderPartial('reportbsgrafik', 
						array(
							'sqlv'=>$sqlv,
							'tanggal_awal'=> $tanggalawal,
							'tanggal_akhir'=> $tanggalakhir,
							'kategori'=> $kategori,
						),
						 true)
				);
    }

    public function actionpengunjung()
    {
    	$model = new Sales;
        $tgl = date('Y-m-d');
        // $dataProvider = $data->search();
        $this->render('pengunjung', array(
            // 'dataProvider' => $dataProvider,
            // 'summary' => $summary,
			'tgl' => $tgl,
			'model'=>$model,
        ));
    }


    public function actionperiodepengungjung()
    {
    	$tanggalawal = $_POST['tanggal_awal'];
		$tanggalakhir = $_POST['tanggal_akhir'];
		$tal = $tanggalawal.' 00:00:01';
		$tak = $tanggalakhir.' 23:59:59';
		$sql00 = "select sum(status) jumlah from sales where time(date) between '00:00:00' and '00:59:59' and date between '$tal' and '$tak'";
		$sql01 = "select sum(status) jumlah from sales where time(date) between '01:00:00' and '01:59:59' and date between '$tal' and '$tak'";
		$sql02 = "select sum(status) jumlah from sales where time(date) between '02:00:00' and '02:59:59' and date between '$tal' and '$tak'";
		$sql03 = "select sum(status) jumlah from sales where time(date) between '03:00:00' and '03:59:59' and date between '$tal' and '$tak'";
		$sql04 = "select sum(status) jumlah from sales where time(date) between '04:00:00' and '04:59:59' and date between '$tal' and '$tak'";
		$sql05 = "select sum(status) jumlah from sales where time(date) between '05:00:00' and '05:59:59' and date between '$tal' and '$tak'";
		$sql06 = "select sum(status) jumlah from sales where time(date) between '06:00:00' and '06:59:59' and date between '$tal' and '$tak'";
		$sql07 = "select sum(status) jumlah from sales where time(date) between '07:00:00' and '07:59:59' and date between '$tal' and '$tak'";
		$sql08 = "select sum(status) jumlah from sales where time(date) between '08:00:00' and '08:59:59' and date between '$tal' and '$tak'";
		$sql09 = "select sum(status) jumlah from sales where time(date) between '09:00:00' and '09:59:59' and date between '$tal' and '$tak'";
		$sql10 = "select sum(status) jumlah from sales where time(date) between '10:00:00' and '10:59:59' and date between '$tal' and '$tak'";
		$sql11 = "select sum(status) jumlah from sales where time(date) between '11:00:00' and '11:59:59' and date between '$tal' and '$tak'";
		$sql12 = "select sum(status) jumlah from sales where time(date) between '12:00:00' and '12:59:59' and date between '$tal' and '$tak'";
		$sql13 = "select sum(status) jumlah from sales where time(date) between '13:00:00' and '13:59:59' and date between '$tal' and '$tak'";
		$sql14 = "select sum(status) jumlah from sales where time(date) between '14:00:00' and '14:59:59' and date between '$tal' and '$tak'";
		$sql15 = "select sum(status) jumlah from sales where time(date) between '15:00:00' and '15:59:59' and date between '$tal' and '$tak'";
		$sql16 = "select sum(status) jumlah from sales where time(date) between '16:00:00' and '16:59:59' and date between '$tal' and '$tak'";
		$sql17 = "select sum(status) jumlah from sales where time(date) between '17:00:00' and '17:59:59' and date between '$tal' and '$tak'";
		$sql18 = "select sum(status) jumlah from sales where time(date) between '18:00:00' and '18:59:59' and date between '$tal' and '$tak'";
		$sql19 = "select sum(status) jumlah from sales where time(date) between '19:00:00' and '19:59:59' and date between '$tal' and '$tak'";
		$sql20 = "select sum(status) jumlah from sales where time(date) between '20:00:00' and '20:59:59' and date between '$tal' and '$tak'";
		$sql21 = "select sum(status) jumlah from sales where time(date) between '21:00:00' and '21:59:59' and date between '$tal' and '$tak'";
		$sql22 = "select sum(status) jumlah from sales where time(date) between '22:00:00' and '22:59:59' and date between '$tal' and '$tak'";
		$sql23 = "select sum(status) jumlah from sales where time(date) between '23:00:00' and '23:59:59' and date between '$tal' and '$tak'";
		$nsql00 = Yii::app()->db->createCommand($sql00)->queryRow();
		$nsql01 = Yii::app()->db->createCommand($sql01)->queryRow();
		$nsql02 = Yii::app()->db->createCommand($sql02)->queryRow();
		$nsql03 = Yii::app()->db->createCommand($sql03)->queryRow();
		$nsql04 = Yii::app()->db->createCommand($sql04)->queryRow();
		$nsql05 = Yii::app()->db->createCommand($sql05)->queryRow();
		$nsql06 = Yii::app()->db->createCommand($sql06)->queryRow();
		$nsql07 = Yii::app()->db->createCommand($sql07)->queryRow();
		$nsql08 = Yii::app()->db->createCommand($sql08)->queryRow();
		$nsql09 = Yii::app()->db->createCommand($sql09)->queryRow();
		$nsql10 = Yii::app()->db->createCommand($sql10)->queryRow();
		$nsql11 = Yii::app()->db->createCommand($sql11)->queryRow();
		$nsql12 = Yii::app()->db->createCommand($sql12)->queryRow();
		$nsql13 = Yii::app()->db->createCommand($sql13)->queryRow();
		$nsql14 = Yii::app()->db->createCommand($sql14)->queryRow();
		$nsql15 = Yii::app()->db->createCommand($sql15)->queryRow();
		$nsql16 = Yii::app()->db->createCommand($sql16)->queryRow();
		$nsql17 = Yii::app()->db->createCommand($sql17)->queryRow();
		$nsql18 = Yii::app()->db->createCommand($sql18)->queryRow();
		$nsql19 = Yii::app()->db->createCommand($sql19)->queryRow();
		$nsql20 = Yii::app()->db->createCommand($sql20)->queryRow();
		$nsql21 = Yii::app()->db->createCommand($sql21)->queryRow();
		$nsql22 = Yii::app()->db->createCommand($sql22)->queryRow();
		$nsql23 = Yii::app()->db->createCommand($sql23)->queryRow();
    	$this->render('periodepengungjung', array(
			'tanggal_awal'=> $tanggalawal,
			'tanggal_akhir'=> $tanggalakhir,
			'nsql00'=>$nsql00,
			'nsql01'=>$nsql01,
			'nsql02'=>$nsql02,
			'nsql03'=>$nsql03,
			'nsql04'=>$nsql04,
			'nsql05'=>$nsql05,
			'nsql06'=>$nsql06,
			'nsql07'=>$nsql07,
			'nsql08'=>$nsql08,
			'nsql09'=>$nsql09,
			'nsql10'=>$nsql10,
			'nsql11'=>$nsql11,
			'nsql12'=>$nsql12,
			'nsql13'=>$nsql13,
			'nsql14'=>$nsql14,
			'nsql15'=>$nsql15,
			'nsql16'=>$nsql16,
			'nsql17'=>$nsql17,
			'nsql18'=>$nsql18,
			'nsql19'=>$nsql19,
			'nsql20'=>$nsql20,
			'nsql21'=>$nsql21,
			'nsql22'=>$nsql22,
			'nsql23'=>$nsql23,
        ));
    }

    public function actionbestseller()
    {
	    $model = new Sales;
	    $tgl = date('Y-m-d');
    	$this->render('bestseller', array(
    		'tgl'=>$tgl,
			'model'=>$model,
    		));
    }

    public function actionreportbestseller()
    {
    	$tanggalawal = $_POST['tanggal_awal'];
		$tanggalakhir = $_POST['tanggal_akhir'];
		$kategori = $_POST['kategori'];
		$tal = $tanggalawal.' 00:00:01';
		$tak = $tanggalakhir.' 23:59:59';
		if($kategori==""){
			$sql = "
				select sum(si.quantity_purchased) jumlah, i.item_name 
				from sales_items si, items i, sales s
				where i.id = si.item_id
				and s.id = si.sale_id
				and s.date between '$tal' and '$tak'
				and s.status = '1'
				group by i.item_name
				order by jumlah desc
			";
			$tot = "
				select sum(si.quantity_purchased) jumlah, i.item_name 
				from sales_items si, items i, sales s
				where i.id = si.item_id
				and s.id = si.sale_id
				and s.date between '$tal' and '$tak'
				and s.status = '1'
			";
		}
		else if($kategori=="makanan"){
			$sql = "
				select sum(si.quantity_purchased) jumlah, i.item_name 
				from sales_items si, items i, sales s
				where i.id = si.item_id
				and s.id = si.sale_id
				and i.category_id = 2
				and s.date between '$tal' and '$tak'
				and s.status = '1'
				group by i.item_name
				order by jumlah desc
			";
			$tot = "
				select sum(si.quantity_purchased) jumlah, i.item_name 
				from sales_items si, items i, sales s
				where i.id = si.item_id
				and s.id = si.sale_id
				and i.category_id = 2
				and s.date between '$tal' and '$tak'
				and s.status = '1'
			";
		}
		else if($kategori=="minuman"){
			$sql = "
				select sum(si.quantity_purchased) jumlah, i.item_name 
				from sales_items si, items i, sales s
				where i.id = si.item_id
				and s.id = si.sale_id
				and i.category_id = 1
				and s.date between '$tal' and '$tak'
				and s.status = '1'
				group by i.item_name
				order by jumlah desc
			";
			$tot = "
				select sum(si.quantity_purchased) jumlah, i.item_name 
				from sales_items si, items i, sales s
				where i.id = si.item_id
				and s.id = si.sale_id
				and i.category_id = 1
				and s.date between '$tal' and '$tak'
				and s.status = '1'
			";
		}
		$sqlv = Yii::app()->db->createCommand($sql)->queryAll();
		$totv = Yii::app()->db->createCommand($tot)->queryAll();
		$this->render('reportbestseller', array(
			'sqlv'=>$sqlv,
			'totv'=>$totv,
			'tanggal_awal'=> $tanggalawal,
			'tanggal_akhir'=> $tanggalakhir,
			'kategori'=> $kategori,
			));
    }

    public function actionreportbestsellerexport()
    {
    	$tanggalawal = $_POST['tanggal_awal'];
		$tanggalakhir = $_POST['tanggal_akhir'];
		$kategori = $_POST['kategori'];
		$tal = $tanggalawal.' 00:00:01';
		$tak = $tanggalakhir.' 23:59:59';
		if($kategori==""){
			$sql = "
				select sum(si.quantity_purchased) jumlah, i.item_name 
				from sales_items si, items i, sales s
				where i.id = si.item_id
				and s.id = si.sale_id
				and s.date between '$tal' and '$tak'
				and s.status = '1'
				group by i.item_name
			";
			$tot = "
				select sum(si.quantity_purchased) jumlah, i.item_name 
				from sales_items si, items i, sales s
				where i.id = si.item_id
				and s.id = si.sale_id
				and s.date between '$tal' and '$tak'
				and s.status = '1'
			";
		}
		else if($kategori=="makanan"){
			$sql = "
				select sum(si.quantity_purchased) jumlah, i.item_name 
				from sales_items si, items i, sales s
				where i.id = si.item_id
				and s.id = si.sale_id
				and i.category_id = 2
				and s.date between '$tal' and '$tak'
				and s.status = '1'
				group by i.item_name
			";
			$tot = "
				select sum(si.quantity_purchased) jumlah, i.item_name 
				from sales_items si, items i, sales s
				where i.id = si.item_id
				and s.id = si.sale_id
				and i.category_id = 2
				and s.date between '$tal' and '$tak'
				and s.status = '1'
			";
		}
		else if($kategori=="minuman"){
			$sql = "
				select sum(si.quantity_purchased) jumlah, i.item_name 
				from sales_items si, items i, sales s
				where i.id = si.item_id
				and s.id = si.sale_id
				and i.category_id = 1
				and s.date between '$tal' and '$tak'
				and s.status = '1'
				group by i.item_name
			";
			$tot = "
				select sum(si.quantity_purchased) jumlah, i.item_name 
				from sales_items si, items i, sales s
				where i.id = si.item_id
				and s.id = si.sale_id
				and i.category_id = 1
				and s.date between '$tal' and '$tak'
				and s.status = '1'
			";
		}
		$sqlv = Yii::app()->db->createCommand($sql)->queryAll();
		$totv = Yii::app()->db->createCommand($tot)->queryAll();
		Yii::app()->request->sendFile(date('YmdHis').'.xls',
					$this->renderPartial('reportbestseller', 
						array(
							'sqlv'=>$sqlv,
							'totv'=>$totv,
							'tanggal_awal'=> $tanggalawal,
							'tanggal_akhir'=> $tanggalakhir,
							'kategori'=> $kategori,
						),
						 true)
				);
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
			select s.id, s.table, s.date, s.waiter, s.inserter, sum(si.item_price * si.quantity_purchased) ssubtotal, s.sale_discount, sum(si.item_service) sservice, sum(si.item_tax) stax, sum(((si.item_price * si.quantity_purchased) +  (si.item_service) + (si.item_tax) - (si.item_discount*(si.item_price * si.quantity_purchased)/100))) stotal 
			from sales s, sales_items si
			where s.id = si.sale_id
			and date between '$tal' and '$tak'
			and status = '1'
			GROUP BY s.id
			";
			// $sql = "
			// 	select
			// 	id, date, sale_sub_total, sale_discount, sale_service, sale_tax, sale_total_cost, paidwith_id, table as aaa, waiter
			// 	from sales
			// 	where date
			// 	between '$tal' and '$tak'
			// 	and status = '1'
			// ";
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
				select s.id, s.table, s.date, s.waiter, s.inserter, sum(si.item_price * si.quantity_purchased) ssubtotal, s.sale_discount, sum(si.item_service) sservice, sum(si.item_tax) stax, sum(((si.item_price * si.quantity_purchased) +  (si.item_service) + (si.item_tax) - (si.item_discount*(si.item_price * si.quantity_purchased)/100))) stotal 
				from sales s, sales_items si
				where s.id = si.sale_id
				and date between '$tal' and '$tak'
				and status = '1'
				GROUP BY s.id
				";
				// 	select
				// 	id, date, sale_sub_total, sale_discount, sale_service, sale_tax, sale_total_cost, paidwith_id, table as aaa, waiter
				// 	from sales
				// 	where date
				// 	between '$tal' and '$tak'
				// 	and status = '1'
				// ";
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
		$saleid->status = 2;
		if ($saleid->update()){
			echo "sukses";
		}
			
		// $id = $saleid->id;
		// Sales::model()->UpdateByPk($id,array("status"=>2));
		// SalesItems::model()->deleteAllByAttributes(array('sale_id' => $id));

	}
	// public function actionHapus(){
	// 	$meja = $_REQUEST['id'];
	// 	$saleid = Sales::model()->find("t.id=$meja and t.status = 0");
	// 	$saleid->delete();


	// }
	

	public function queryTopPenjualan($filter){
		$querySelect = $filter['querySelect'];
		$where_branch = $filter['where_branch'];
		$groupBy = $filter['groupBy'];
		$tgl = $filter['tgl'];
		$tgl2 = $filter['tgl2'];
		$filterKategori = $filter['filterKategori'];
		return  "
		SELECT
		{$querySelect}
		sum(quantity_purchased) jumlah
		FROM
		sales s
		INNER JOIN sales_items si ON s.id = si.sale_id
		left JOIN items i ON si.item_id = i.id
		INNER JOIN items_satuan iss on iss.item_id = i.id 
		INNER JOIN branch b  on b.id = s.branch 
		INNER JOIN stores st  on st.id = b.store_id 
		WHERE
		s.status = 1
		{$where_branch}
		and 
		date(s.date) >= '$tgl' and date(s.date) <= '$tgl2'
		and is_sales_item_bahan is NULL
		$filterKategori
		and st.id = ".Yii::app()->user->store_id()."
		{$groupBy}
		order by jumlah desc
		limit 20
	";
	}
	public function actionGrafik(){
		$mode = $_REQUEST['mode'];
		if (isset($_REQUEST['Sales']['date']) && isset($_REQUEST['Sales']['tgl']) ) {
			$tgl = $_REQUEST['Sales']['date'];
			$tgl2 = $_REQUEST['Sales']['tgl'];
		}
		else{
			$tgl = date('Y-m-d',strtotime('-7 days')); 
			$tgl2 = date('Y-m-d'); 
		}
		$mode = "top";
		$connection = Yii::app()->db;
		$store_id = Yii::app()->user->store_id();
		// if (Yii::app()->user->level() === "1"){
		// 	$bcdefault = Yii::app()->user->branch();
		// 	$where_branch = " and s.branch='$bcdefault'";
		// }else{
		// 	$where_branch = " ";
		// }
			
		if (isset($_REQUEST['kategori'])){

			if ($_REQUEST['kategori']=='semua')
				$filterKategori = " ";
			else
				$filterKategori = " and category_id = '$_REQUEST[kategori]' ";
		}

		if ($_REQUEST['kelompok']=='cabang'){
			$querySelect = "CONCAT(SUBSTR(b.branch_name, 1, 50)) nama,";
			$groupBy = "group by b.id";
		}else{
			$querySelect = "CONCAT(SUBSTR(i.item_name, 1, 50),'-',iss.nama_satuan) nama,";
			$groupBy = "group by i.id";
		}

		$filter = [
			'querySelect' => $querySelect,
			'where_branch' => $where_branch,
			'groupBy' => $groupBy,
			'tgl' => $tgl,
			'tgl2' => $tgl2,
			'filterKategori' => $filterKategori
		];

		$query = $this->queryTopPenjualan($filter);		
		$command = $connection->createCommand($query);
		$row = $command->queryAll(); 
        $this->render('grafik',array(
			'databar'=>$row,
			'tgl'=>$tgl,
			'tgl2'=>$tgl2,
			'mode'=>$mode,
		));
	}

	public function actionGrafikmember(){
		$mode = $_GET['mode'];
		if (isset($_GET['Sales']['date']) and isset($_GET['Sales']['tgl']) ) {
			$tgl = $_GET['Sales']['date'];
			$tgl2 = $_GET['Sales']['tgl'];
		}
		else{
			$tgl = date('Y-m-d',strtotime('-7 days')); 
			$tgl2 = date('Y-m-d'); 
		}

		$connection = Yii::app()->db;
		if (isset($_REQUEST['limit'])){	
			$limit = " limit $_REQUEST[limit]";
		}
		// if ($mode=='top'){
			// if (isset($_REQUEST['limit'])){	
			// }

			// if (isset($_REQUEST['kategori'])){

			// if ($_REQUEST['kategori']=='semua')
			// 	$filter = " ";
			// else
			// 	$filter = " and category_id = '$_REQUEST[kategori]' ";
			// }
		
		// and 
		$command = $connection->createCommand("

		select s.nama nama,s.nama nama,sum(quantity_purchased) jumlah
		from 
		sales_items si, sales s,items i, customer c
		where
		c.nama = s.nama 
		and

		si.item_id = i.id 
		and
		s.id = si.sale_id		
		and
		s.status = 1
		#and date(s.date) >= '$tgl' and date(s.date) <= '$tgl2'
		$filter
		GROUP BY s.nama
		order by jumlah desc
		$limit
		
		");
		// }
		// else if ($mode=='bersih'){
		// $command = $connection->createCommand("
		// 	select nama_outlet n,
		// 	sum(((si.item_price*si.quantity_purchased)-(si.item_discount*si.item_price/100)) * (o.persentase_hasil /100) ) b
		// 	from sales s,sales_items si,outlet o,items i
		// 	where 
			
		// 	si.item_id = i.id and 
		// 	s.id=si.sale_id and 
		// 	i.kode_outlet=o.kode_outlet and 
		// 	s.status = 1
		// 	and
		// 	date(s.date) >= '$tgl' and date(s.date) <= '$tgl2'
		// 	group by o.kode_outlet
			
		// 	");
		// }
		
		$row = $command->queryAll(); 

        $this->render('grafikmember',array(
		'databar'=>$row,
		'tgl'=>$tgl,
		'tgl2'=>$tgl2,
		'mode'=>$mode,
		));
		
		
	}

	public function actionGrafikPenjualan(){
		$where_branch = "";
		$cabang = $_REQUEST['cabang'];
		$bcdefault = Yii::app()->user->branch();
		$month  = $_REQUEST['month'];
		$year  = $_REQUEST['year'];
		if (isset($_REQUEST['year']) && isset($_REQUEST['month']) ){
			$month = $_REQUEST['month'];
			$year = $_REQUEST['year'];
		}else{
			$year = date('Y');
			$month = "";
		}
		

		$connection = Yii::app()->db;

		$store_id = Yii::app()->user->store_id();
			
		if (isset($cabang) && $cabang !== ""){
			$where_branch = " having branch = '$_REQUEST[cabang]'";
		}else{
			$where_branch = " ";
		}


		if ($month === ""){ // jika yearly then outcome is months 
			
			$query = "
			select *, month_name as label from (
			SELECT  sum( sale_sub_total) omzet, MONTH ( date )  bulan, year( date ) tahun 
			FROM ({$this->sqlSales()} {$where_branch}) AS satu group by MONTH ( date ) , year ( date )  
			) AS satu
			RIGHT JOIN ( SELECT `month`, month_name FROM time_dimension GROUP BY MONTH ) AS dua ON satu.bulan  = dua.`MONTH` and tahun='$year' 
			
				";
		}else{ // if daily then outcome is list of days

			
			$query = "
			select omzet , 
			
			CONCAT(
				CASE DAYOFWEEK(db_date)
				WHEN 1 THEN 'Minggu'
				WHEN 2 THEN 'Senin'
				WHEN 3 THEN 'Selasa'
				WHEN 4 THEN 'Rabu'
				WHEN 5 THEN 'Kamis'
				WHEN 6 THEN 'Jumat'
				WHEN 7 THEN 'Sabtu'
			  END,
			  ', ',

				DAY(db_date),
				' ',
				CASE MONTH(db_date)
				  WHEN 1 THEN 'Januari'
				  WHEN 2 THEN 'Februari'
				  WHEN 3 THEN 'Maret'
				  WHEN 4 THEN 'April'
				  WHEN 5 THEN 'Mei'
				  WHEN 6 THEN 'Juni'
				  WHEN 7 THEN 'Juli'
				  WHEN 8 THEN 'Agustus'
				  WHEN 9 THEN 'September'
				  WHEN 10 THEN 'Oktober'
				  WHEN 11 THEN 'November'
				  WHEN 12 THEN 'Desember'
				END,
				' ',
				YEAR(db_date)
			  ) AS label
			
			from (
			SELECT  sum( sale_sub_total) omzet,	date(date) as date

			FROM ({$this->sqlSales()} {$where_branch}) AS satu group by date ( date )
			) AS satu
			RIGHT JOIN ( SELECT `db_date`, day_name FROM time_dimension WHERE YEAR ( db_date ) = '$year' AND MONTH ( db_date ) = '$month' ) AS dua ON date(satu.date) = date(dua.db_date)
		
			order by db_date asc
			";
		}

		$command = $connection->createCommand($query);
		$row = $command->queryAll(); 
		
		$this->render('grafikpenjualan',array(
			'databar'=>$row,
			'month'=>$month,
			'year'=>$year,
			'cabang'=>$cabang
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
				sales.id = sales_items.sale_id  and
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
				sales.id = sales_items.sale_id  and
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
	
	public function actionDetailitems($id){	

		// $subtotal = "item_price*quantity_purchased";
		// $submodal = "item_modal*quantity_purchased";
		// $row = Yii::app()->db->createCommand()
		// ->select("
		// 		sales_items.id,
		// 		sales.date as date,
		// 		item_name name ,
		// 		item_price price,
		// 		item_modal price_modal,
		// 		$subtotal subtotal,
		// 		$submodal submodal,
		// 		(item_discount * ($subtotal)/100) idc,
				
		// 		sum(quantity_purchased) as qty,
		// 		sales_items.item_tax tax, 
		// 		item_service service, 
		// 		sum(
		// 			($subtotal)
		// 			+
		// 			sales_items.item_tax
		// 			+
		// 			item_service
		// 			-
		// 			((item_discount*item_price/100)*quantity_purchased)) total	
		// 		")
		// ->from('sales_items, sales ,items')
		// ->where("
		// 		sales.id = sales_items.sale_id 
				 
		// 		and 
		// 		sales_items.item_id = items.id and
			
		// 		sales.id = ".$_GET['id']." 
				
				
		// 		")
		// ->group('sales_items.id')
		// ->queryAll();
		$sql  = self::sqlSalesDetail($id);
		// echo $sql;
		// exit;
		$row = Yii::app()->db->createCommand($sql)->queryAll();
	
		// echo $sql;
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
		// echo "123";
		$sales = Sales::model()->findByPk($id);
		$this->render('detailtransaksi', array(
			'detailtransaksi' => $detailtransaksi,
			'a'=>$a,
			'sales'=>$sales
		));
	}

	public function sqlSalesDetail($id){
			$sql_d = "
		
				SELECT
				sales_items.id id,
				'0' as is_paket,
				sales_items.item_id item_id,
				
				iss.nama_satuan as nama_satuan,
				sales_items.item_satuan_id item_satuan_id,
				sales.id sale_id,
				iss.barcode barcode,
				sales.date as date,
				sales_item_name name ,
				item_discount item_discount ,
				item_price price,
				item_modal price_modal,
				item_price*quantity_purchased subtotal,
				item_modal*quantity_purchased submodal,
				(item_discount * (item_price*quantity_purchased)/100) idc,
				item_discount,
				permintaan,

				sum(quantity_purchased) as qty,
				sales_items.item_tax tax,
				item_service service,
				sum(
				(item_price*quantity_purchased)
				+
				sales_items.item_tax
				+
				item_service
				-
				((item_discount*item_price/100)*quantity_purchased)) total

				FROM  

				sales inner join sales_items on sales.id = sales_items.sale_id

				left join items on sales_items.item_id = items.id 

				left join items_satuan iss on  iss.item_id = items.id and sales_items.item_satuan_id = iss.id
				WHERE
				

				sales.id = $id and sales_items.is_sales_item_bahan is NULL
				#and
				#(items.has_bahan = 1 || (items.has_bahan = 0 and items.is_bahan=0 ))
				group by sales_items.id
			
			



			";
			return $sql_d;
			// echo $sql_d;
			/*
			UNION ALL 

				SELECT
				'1' as is_paket,
				p.id_paket item_id,
				sales.id sale_id,
				p.id_paket barcode,
				sales.date as date,
				nama_paket name ,
				item_discount item_discount ,
				item_price price,
				item_modal price_modal,
				item_price*quantity_purchased subtotal,
				item_modal*quantity_purchased submodal,
				(item_discount * (item_price*quantity_purchased)/100) idc,
				item_discount,

				sum(quantity_purchased) as qty,
				sip.item_tax tax,
				item_service service,
				sum(
				(item_price*quantity_purchased)
				+
				sip.item_tax
				+
				item_service
				-
				((item_discount*item_price/100)*quantity_purchased)) total

				FROM sales_items_paket as sip, sales, paket p
				WHERE
				sales.id = sip.sale_id

				and
				sip.item_id = p.id_paket and

				sales.id = $id 
				group by sip.id
			*/


	}

	public static function getTotalByID($id){
		$table = self::sqlSales();
		// $sql.= ""
		$sql = " SELECT * FROM ($table) AS  D 
		where D.id = '$id' 
		group by D.id
			";
		// echo $sql2;
		// exit;
		$tot = Yii::app()->db->createCommand($sql)->queryAll();
		// echo $sql2;
		// var_dump($sql);
		// var_dump($tot);
		// print_r($tot);

		// $sum = 0;
		// echo "123";
		// var_dump($id);
		// var_dump($tot);
		// echo "<pre>";
		// print_r($tot);
		// echo "</pre>";
		// foreach ($tot as $key => $value) {
		// 	$sum+= $value['sale_total_cost'];
		// }
		// return $tot[0]['sale_total_cost'];

		// return $sql;

	}


	public function sqlSales(){
			$branch_id = Yii::app()->user->branch();
			// if ($branch_id!=""){
			// 	$where_branch = " and A.branch = '$branch_id' ";
			// }
			$subtotal = "si.item_price*si.quantity_purchased" ; 
			$submodal = "item_modal *si.quantity_purchased" ; 
			$untung = " ($subtotal) - ($submodal) " ; 	
			$stt = "
					(
						(si.item_price*si.quantity_purchased)
							+
						(si.item_tax)
							+
						(si.item_service)
							-
						(si.item_discount*($subtotal)/100)
					)";
			// $stt = "if ($sql_stt)<0,0,1)";	
			$sql  ="
			SELECT 
						
				edc_bca,
				cash,
				refno,
				pembayaran_via,
				status,
				branch,
				ID AS id,
				faktur_id,
				tanggal_jt,
				sb,
				bayar,
				inserter,
				date,
				untung,
				sum(sale_sub_total) sale_sub_total,
				sum(ttm) sale_sub_modal,
				sum(voucher) voucher,
				sum(tax) tax,
				sum(service) service,
				sum(sd) sale_discount,
				sum(stt) sale_total_cost,
				sum(total_items) total_items,
				pembulatan,
				nama
			FROM (
			SELECT 
				sp.edc_bca,
				sp.cash,
				ifnull(s.id,'') ID,
				faktur_id,
				refno,
				pembayaran_via,
				ifnull(tanggal_jt,'') tanggal_jt,
				ifnull(nama,'') as nama,
				if (bayar< (sum($stt)-sp.voucher),'Kredit','Lunas') as sb, 
				ifnull(s.bayar,'') bayar,
				ifnull(s.table,'') 'table',
				ifnull(u.username,'') inserter, 
				ifnull(s.comment,'') 'comment', 
				ifnull(s.status,'') 'status', 
				ifnull(s.branch,'') 'branch', 
				date as date,
				sum(si.quantity_purchased) as total_items,

				sum($untung) untung,
				sum($subtotal) sale_sub_total,
				sum($submodal) sale_sub_modal,
				sum(si.item_modal*si.quantity_purchased) ttm,
				sp.voucher voucher,
				sum(si.item_tax) tax,
				sum(si.item_service) service , 
				s.pembulatan pembulatan , 
				sum( si.item_discount/100 * ($subtotal) )  sd,

				if ( (sum($stt)-sp.voucher)<0,0,(sum($stt)-sp.voucher+ s.pembulatan ) ) stt
				FROM 
				sales s 
				inner join sales_items si on  s.id = si.sale_id
				left join items i on  i.id = si.item_id
				inner join sales_payment sp  on  s.id = sp.id
				left join  users u on u.id = s.inserter 
				inner join stores st on st.id = i.store_id
				where st.id = ".Yii::app()->user->store_id()."
				group by s.id

		) AS A

		WHERE A.date!='' and A.status = 1 
		$where_branch
		GROUP BY A.ID

		";
		return $sql;

		// echo $sql;
		// exit;
	}
	/*
				UNION 
				
				SELECT 
				ifnull(s.id,'') ID,
				ifnull(tanggal_jt,''),
				ifnull(nama,'') as nama,
				if (bayar<sum($subtotal) or bayar=0 ,'Kredit','Lunas') as sb, 
				ifnull(s.bayar,'') bayar,
				ifnull(s.table,'') 'table',
				ifnull(inserter,'') inserter, 
				ifnull(s.comment,'') 'comment',
				ifnull(s.status,'') 'status',  
				ifnull(s.branch,'') 'branch', 
				date(date) date,
				sum(si.quantity_purchased) as total_items,

				sum($untung) untung,
				sum($subtotal) sale_sub_total,
				sum($submodal) sale_sub_modal,
				sum(si.item_modal*si.quantity_purchased) ttm,
				sum(sp.voucher) voucher ,
				sum(si.item_tax) tax,
				sum(si.item_service) service , 
				sum( si.item_discount/100 * ($subtotal) )  sd,

				sum(
					(
						(si.item_price*si.quantity_purchased)
						+
						(si.item_tax)
						+
						(si.item_service)
						-
						(si.item_discount*($subtotal)/100))
						-
						sp.voucher
				) stt

				FROM 

				sales s 
				inner join sales_items_paket si on  s.id = si.sale_id
				inner join paket i on  i.id_paket = si.item_id
				inner join  sales_payment sp on sp.id = s.id 
				group by s.id
				*/
	
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
		$model = new Sales;
		
		
		$cabang = Yii::app()->user->id;
		$user = Users::model()->find('username=:un',array(':un'=>$cabang));
		$cabang_id = $user->branch_id;
		// sales bulanan
		// select s.bayar,s.table,inserter, s.id,sum(si.quantity_purchased) as total_items, date,sum(si.item_price*si.quantity_purchased) sale_sub_total,s.sale_tax,s.sale_service, s.sale_discount, 
			// sum(((si.item_price*si.quantity_purchased)+(si.item_tax)-(si.item_discount*(si.item_price*si.quantity_purchased)/100)))  sale_total_cost,
			 // u.username inserter 
			 // from sales s,sales_items si , users u 
			 // where s.id = si.sale_id and date(s.date)='$date' and s.status=1 and inserter = u.id  and inserter = $user->id   group by s.id  ";
		// $subtotal = "si.item_price*si.quantity_purchased";
		$table = SalesController::sqlSales();
		// echo $table;
		// exit;
		// exit;
		$sql = "SELECT ID AS id,
				tanggal_jt,
				sb,
				bayar,
				inserter,
				date,
				untung,
				sum(sale_sub_total) sale_sub_total ,
				sum(sale_sub_modal) sale_sub_modal,
				sum(voucher) voucher,
				sum(tax) tax,
				sum(service) service,
				sum(sale_discount) sale_discount,
				sum(sale_total_cost) sale_total_cost,
				sum(total_items) total_items FROM ($table) as A where  month(A.date)='$month' and year(A.date)='$year' group by date(A.date) 
		 		order by date(A.date) asc";
		$tot = Yii::app()->db->createCommand($sql)->queryAll();
			// print_r($tot);
			// exit;
			
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
		$model = new Sales;
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
			->where(' si.item_id = i.id and s.id=si.sale_id and i.kode_outlet=o.kode_outlet and s.status = 1 and month(s.date)= :date and  year(s.date)= :date2  ', array(':date' => $month,':date2'=>$year))
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
		
		$model = new Sales;
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
			->where(' si.item_id = i.id and s.id=si.sale_id and i.kode_outlet=o.kode_outlet and s.status = 1 and date(s.date) and date(s.date)>= :date and  date(s.date)<= :date2
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
			// ->where("date(date) =  '$date' and branch='$cabang_id' ")
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
			->where("date(date) =  '$date' and branch='$cabang_id' ")
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
			// "Date(date)='$date' "
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
	
	public function actionPindahmeja($meja,$meja_now){
		// echo "success : ".$_SESSION['temp_sale_id'];
		
		// $id = $_SESSION['temp_sale_id'];
		//update sales
		$model = Sales::model()->find("t.table = '$meja_now' and status='0' ");
		$model->table = $meja;
		// $model = Sales::model()->findByPk($id);
		// $model->table = $meja;
		if ($model->update()){
			echo "sukses";
		}
		// Sales::model()->updateByPk($id, 'table = :meja', array(':meja'=>$meja));
		
		
		// $_SESSION['temp_sale_id'] = '';
		// unset($_SESSION['temp_sale_id']);
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

        $sql = $this->sqlSalesDetail($id);
        $si = Yii::app()->db->createCommand($sql)->queryAll();

        $data['sales'] = $sales;
//        $data['si'] = $si;
        $temps = array();
        $price_type =  ItemsSatuanPrice::model()->find(" item_satuan_id = '$val[item_satuan_id]' and price = '$val[price]' ")->price_type;
        if (empty($price_type)){
        	$price_type = "-";
        }else{
        	$price_type = $price_type;
        }
        foreach ($si as $val) {
            $temp = array();
            $temp['kode'] = $val[barcode];
            $temp['nama'] = $sales->nama;
            $temp['id'] = $val[id];
            $temp['sale_id'] = $val[sale_id];
            $temp['item_id'] = $val[item_id];
            $temp['item_name'] = $val[name];
            $temp['quantity_purchased'] = $val[qty];
            $temp['item_tax'] = $val[tax];
            $temp['item_service'] = $val[service];
            $temp['item_price'] = $val[price];
            $temp['item_discount'] = $val[item_discount];
            $temp['item_total_cost'] = $val[total];
            $temp['lokasi'] = $val[lokasi];
            $temp['permintaan'] = $val[permintaan];
            $temp['is_paket'] = $val[is_paket];
            $temp['nama_satuan'] = $val[nama_satuan];
            $temp['item_satuan_id'] = $val[item_satuan_id];
            $temp['item_price_tipe'] =$price_type;
            $temp['item_barcode'] =$price_type;
            $temps[] = $temp;
        }
		// $_SESSION['sale_id'] = $id;
        $data['date'] = date("Y-m-d",strtotime($sales->date));
        $data['si'] = $temps;
        // echo "<pre>";
        // print_r($data['si']);
        // echo "</pre>";
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        echo json_encode($data);
        // echo CJSON::encode($data);
    }
	
	public function actionGetsaleid(){
		echo $_SESSION['sale_id'];
		$_SESSION['sale_id'] = "";		
		unset($_SESSION['sale_id']);
	}

    public function actionBayar() {
	$transaction = Yii::app()->db->beginTransaction();
	$metode_stok = SiteController::getConfig("metode_stok");
	try {
		// cek akses
		$username = Yii::app()->user->name;
		$user = Users::model()->find('username=:un',array(':un'=>$username));
		$idk = $user->level; 
		if ($idk == "2"){
			$return['error'] = "Browser yang sedang anda gunakan sedang login menggunakan akun admin, Transaksi penjualan hanya diperbolehkan oleh akun kasir, silahkan refresh halaman ini";
			$return['status'] = 0;
			echo json_encode($return);
			exit;
		}


        $data = $_REQUEST['data'];
        $data['date']  = date("Y-m-d H:i:s");
		$data_detail = $_REQUEST['data_detail'];
		$data_payment = $_REQUEST['data_payment'];
		
        if (isset($_REQUEST['data']) and isset($_REQUEST['data_detail']) ) {
		//mencari QTY		
			foreach ($data_detail as $detail) {
			 if ($detail['quantity_purchased']>1)
				$jumlah1 = $jumlah1 + $detail['quantity_purchased'];
			else
				$jumlah2 = $jumlah2 + $detail['quantity_purchased'];
			}
			$jumlahakhir =  $jumlah1 + $jumlah2;

			// check whether data come from temporary data or create new record
			if (isset($data['table']) && !empty($data['table'])){
				$sales = Sales::model()->find("t.table = '$data[table]' and status='0' ");
				// var_dump(" masuk sini ".$sales->id);
				// exit;
	            if (count($sales)>0)
	                $sales = Sales::model()->findByPk($sales->id);
	            else
	                $sales = new Sales();
				
			}else{
				$sales = new Sales();
			}
        	
        
            $sales->customer_id = 1; 
            $sales->jenis = "keluar"; // additional information for stok
			$sales->date = $data['date'];  // take date from input user
			
			if (!empty($data['tanggal_jt'])){	// use jt date if any
				$sales->tanggal_jt = $data['tanggal_jt'];
			}
            
            $sales->sale_sub_total  = $data['subtotal'];
            $sales->sale_discount = round($data['discount']);
            $sales->sale_service = round($data['service']);
            $sales->sale_tax = round($data['tax']);
    		$sales->sale_total_cost = round($data['total_cost']);
	        $sales->bayar_real = round($data['bayar']);
	        $sales->sisa = round($data['belum_bayar']);
	        $sales->pembulatan = round($data['pembulatan']);
    		
            if ($data['status']=="1"){	
	            $sales->bayar = intval(round($data['bayar']));
	            $fd =  $this->GenerateSalesFakturMethod();
	            $sales->faktur_id = $fd;	
            	$_REQUEST['data']['faktur_id'] = $fd;
            	$data['faktur_id'] = $fd;	
            }else{
	            $sales->bayar = 0;    		
            }
			
			$sales->total_items = $jumlahakhir;
			$sales->pembayaran_via = strtoupper($data['bayar_via']);
			$data['pembayaran_via'] = $data['bayar_via'];

            
            if (isset($_data['payment']))   
                $sales->sale_payment = $data['payment'];
            else
                $sales->sale_payment = 0;
                
            $sales->paidwith_id = $data['paidwith']; // set payment by
			//ambil data dari user yg login
			$username = Yii::app()->user->name;
			$user = Users::model()->find('username=:un',array(':un'=>$username));
			
			
            // $sales->branch = 1;//$user->branch_id;
            $branch_id = Yii::app()->user->branch();
            $sales->branch = $branch_id;

            $sales->user_id = 1;
            $sales->table = $data['table'];
            $sales->status = $data['status'];
            $sales->keterangan = $data['keterangan'];
            $sales->refno = $data['refno'];
            if (!empty($data['namapelanggan'])){		
	            $sales->nama = $data['namapelanggan'];
            }else{
	            $sales->nama = 'umum';
            }
            $sales->inserter = $user->id;
            $sales->nama_kasir = Users::model()->findByPk($user->id)->username;
            $sales->kembali = $data['kembali'];
            $sales->comment = "Pendapatannjualan ";
            $sales->sale_voucher = $data_payment['voucher'];
			
            $hit=0;
            $sales->table = $data['table'];
            $sales->status = $data['status'];
            if ($sales->save()) {
            	$lunasin = "";
				// remove all sales items first
                SalesItems::model()->deleteAllByAttributes(array('sale_id' => $sales->id));
				// remove all sales items paket then
                SalesItemsPaket::model()->deleteAllByAttributes(array('sale_id' => $sales->id));

				$data_detail = $_REQUEST['data_detail'];
				$total_cost = 0;

				if ($sales->status=="1"){
					$modelh = new BarangKeluar;
					$modelh->tanggal = date("Y-m-d H:i:s");
					$modelh->user = Yii::app()->user->name;
					$modelh->sumber = "sumber ";
					$modelh->jenis = "keluar";
					$modelh->jenis_keluar = "penjualan";
					$modelh->keterangan = "-";
					$modelh->sales_id = $sales->id;
					$modelh->status_keluar = 1;
					$modelh->kode_trx = BarangKeluarController::generateKodeBKS();
					$modelh->branch_id = Yii::app()->user->branch();
					$modelh->keluar_ke = Yii::app()->user->branch();
					$modelh->save();
				}

					// save each items
					$total_equity = 0;
	                foreach ($data_detail as $detail){
	                	$hm = 0;
						if ($detail['is_paket']=="1"){
							$di = new SalesItemsPaket();
							$di->item_modal = "0";
						}else{
							$di = new SalesItems();
							//   $hargabeli = ItemsController::getAverage($value['item_id'],$value['id'], Yii::app()->user->branch());
							// }else{
								//   $hargabeli = $value['harga_beli'];
								// }
								
								// if ($model_average<=0){
									// }else{
									if ($metode_stok == "average"){
										$model_average = ItemsController::getAverage($detail['item_id'],$detail['item_satuan_id'],$branch_id);
										$di->item_modal = $model_average;
										$hm = $model_average;
									}else{
										$a = ItemsController::GetModal2($detail['item_id'],$detail['item_satuan_id'],$branch_id);
										$di->item_modal = $a;
										$hm = $a;
									}
									$total_equity+=$di->item_modal * $detail['quantity_purchased']; // get modal times qty
								// }
							// }
						}

						$total_cost = ($detail['item_price']*$detail['quantity_purchased']) + $detail['item_tax'] + $detail['item_service'];
						$di->sale_id = $sales->id;
	                    $di->item_id = $detail['item_id'];
	                    $di->sales_item_name = $detail['item_name'];
	                    $getSatuanID = ItemsSatuan::model()->find("   nama_satuan = '$detail[item_satuan]' and item_id = '$detail[item_id]' ")->id;

	                    // get satuan now
	                    $satuanUtama1 = ItemsSatuan::model()->find("   id = '$getSatuanID' ");
						$satuanUtamaID = $satuanUtama1->id;
						$satuanUtama_jumlah_1 = $satuanUtama1->satuan;



	                    // cek satuan utama
	                    $satuanUtama2 = ItemsSatuan::model()->find(" is_default = '1' and item_id ='$detail[item_id]' ");
						$satuanUtamaID_default = $satuanUtama2->id;
						$satuanUtamaKode_default = $satuanUtama2->item_id;
						$satuanUtama_jumlah_2 = $satuanUtama2->satuan;


						$satuan_total_keluar = $satuanUtama_jumlah_1 * $satuanUtama_jumlah_2;
						if ($satuanUtama_jumlah_2==1){
							$satuan_total_keluar = $satuanUtama_jumlah_1*$detail['quantity_purchased'];
						}

						// set untuk multi satuan 
						if ($sales->status=="1"){
							$bkl = new BarangKeluarDetail;
							// simpan detail barang keluar
							$bkl->kode = $satuanUtamaKode_default ;
							$bkl->jumlah = $satuan_total_keluar;
							// $bkl->harga = intval($hm);
							if ($metode_stok == "average"){
								$harga_mdl = ItemsController::getAverage($detail[item_id],$satuanUtamaID_default,Yii::app()->user->branch());
								if ($harga_mdl<=0){ //jika di
									$satuanharga = ItemsSatuan::model()->find("item_id = '".$detail['item_id']."' and id='".$detail['item_satuan_id']."' ")->harga_beli;
									$harga_mdl = $satuanharga;
								}	
							}else {
								$satuanharga = ItemsSatuan::model()->find("item_id = '".$detail['item_id']."' and id='".$detail['item_satuan_id']."' ")->harga_beli;
								$harga_mdl = $satuanharga;
							}						
							$bkl->harga = $harga_mdl;
							$bkl->head_id = $modelh->id;
							// $bkl->satuan = $satuanUtamaID_default;
							$bkl->satuan = $detail['item_satuan_id'];
							$bkl->save();	

							// set source of item
							$items = Items::model()->findByPk($detail['item_id']);
							if ($items->has_bahan=="1"){
								$this->kalkulasiBahanBaku($detail,$sales,$modelh);
							}
						}	
											

	                    $di->item_satuan_id = $detail['item_satuan_id'];
	                    // $di->item_satuan_id = $satuanUtamaID;
	                    // $di->quantity_purchased = round($detail['quantity_purchased']/$satuanNowSatuan,2)*$detail['quantity_purchased'];
	                    //lama 
						$di->quantity_purchased = $detail['quantity_purchased'];
	                    $di->item_tax = $detail['item_tax'];
	                    $di->item_discount = $detail['item_discount'];
						$di->item_service =  $detail['item_service'];
	                    $di->item_price = $detail['item_price'];
	                	$di->item_total_cost =  $total_cost;
	                    $di->permintaan =  $detail['permintaan'];
	                    // $di->permintaan =  "-";
	                    if (!$di->save()){
	                    	echo json_encode($di->getErrors());
							$transaction->rollback();
	                    	exit;
	                    }
	                    $hit++;
					} // end looping of detail data 
					// put total equity of this transaction
					// echo $total_equity;
					// exit;
					$sales->sale_equity = $total_equity;
					$sales->update(); // update after update equity

					SalesPayment::model()->deleteAllByAttributes(array('id' => $sales->id));
					#menyimpan ke table salespayment
					$sp = new SalesPayment();
					$sp->id = $sales->id;
					if ($sales->status==1){

						if ($sales->bayar>=$sales->sale_total_cost){
							$sp->cash = $data_payment['cash'];	
						}else{
							$sp->cash = $sales->bayar;	
						}
						$sp->voucher= $data_payment['voucher'];
						$sp->compliment= $data_payment['compliment'];
						$sp->edc_bca= $data_payment['edcbca'];
						$sp->edc_niaga= $data_payment['edcniaga'];
						$sp->credit_bca= $data_payment['creditbca'];
						$sp->credit_mandiri= $data_payment['creditmandiri'];
						$sp->dll= $data_payment['dll'];
					
					}else{
						$sp->cash = 0;	
						$sp->voucher= 0;
						$sp->compliment= 0;
						$sp->edc_bca= 0;
						$sp->edc_niaga= 0;
						$sp->credit_bca= 0;
						$sp->credit_mandiri= 0;
						$sp->dll= 0;
					}
					if ($sp->save()){
		               
						if ($sales->status==1 && $di->save()){
							$lunasin = CustomerController::Lunasin($data['namapelanggan'],$sales->bayar,$sales->id,$data_payment['voucher']);
							
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

							 JurnalController::createSalesTransaction($sales, $sp); // journal posting
							 $transaction->commit();
							 // sleep(3);
		                    $this->cetak($data,$data_detail,$data_payment,$hit, $sales->id,1);	
		                   // $this->cetakLagi($sales->id);	
						}else if ($sales->status==0){ //jika hutang
							// alert("masuk");
							 $transaction->commit();
							$return['id'] = $sales->id;
							$return['status'] = 0;
							$return['ishutang'] = 1;
		                    echo json_encode($return);
		                    exit;
						}
		                else {
		                    $return['modal'] = ItemsController::getAverage($di->item_id,$di->item_satuan_id,$branch_id);
		                    $name = Items::model()->findByPk($di->item_id)->item_name;
		                    $return['error'] = "Harga Modal $name Belum ditentukan ";
		                    $return['status'] = $sales->status;
		                    $return['sale_id'] = $sales->id;
		                    $return['status'] = 0;
		                    echo json_encode($return);
		                    exit;
		                //}
		                }//jika berhasil save
		          }else{// show error sales payment
		          	echo json_encode($sp->getErrors());
		          	exit;
		          }
            }else{
                print_r($sales->getErrors());
                exit;
            }
        }

		// print_r($_REQUEST);

		// echo "masuk 2";
        //end try
        }catch(Exception $err){
			echo "masuk 3";
			echo $err;
			// $transaction->rollback();
		}
    }
    
	private function kalkulasiBahanBaku($detail,$sales, $modelh){
		$sql = "select * from items_source where item_menu = '$detail[item_id]' ";
		$data = Yii::app()->db->createCommand($sql)->queryAll();
		if (count($data) > 0):
		foreach ($data as $key => $value) {

			$satuanUtama = ItemsSatuan::model()->find(" is_default = '1' and item_id='$value[item_id]' ");
			$satuanUtamaID = $satuanUtama->id;	

			// ambil nilai satuan
			$satuanNow= ItemsSatuan::model()->find(" id='$value[satuan]' ");
			$satuanNowSatuan = $satuanNow->satuan;
			$satuanNowNilai = $satuanNow->jumlah;
			
			// simpan ke detail penjualan [start]
			$i = new SalesItems();
			$i->sale_id = $sales->id;
            $i->item_id = $value['item_id'];
			// $i->quantity_purchased = $value['jumlah'];
			// if ($satuanNowNilai>0){	
			// $i->quantity_purchased = round($value['jumlah']/$satuanNowSatuan,2)*$detail['quantity_purchased'];
			// }else{
				$i->quantity_purchased = $value['jumlah'];
			// }
            $i->item_tax = 0;
            $i->item_discount = 0;
			$i->item_service =  0;
            $i->item_price = 0;
        	$i->item_total_cost = 0 ;
        	$i->item_modal = 0 ;
        	// $i->item_satuan_id = $value['satuan'] ;
        	$i->item_satuan_id = $satuanUtamaID ;
        	$i->sales_item_name = Items::model()->findByPk($value['item_id'])->item_name ; // simpan nama item_name
			$i->is_sales_item_bahan = 1;
        	if (!$i->save()){
        		array_push($i->getErrors(),"table Sales Items");
        		echo json_encode($i->getErrors());
        		exit;
        	}		
			// 	simpan ke detail penjualan [end]	
			

			// simpan ke detail barang keluar [start]
			$bkl = new BarangKeluarDetail;
			$bkl->kode =  $value['item_id'] ;
			$bkl->jumlah = $value['jumlah'];					
			// $bkl->jumlah = round($value['jumlah']/$satuanNowSatuan,2)*$detail['quantity_purchased'];					
			$bkl->harga = 0;
			$bkl->head_id = $modelh->id;
			$bkl->satuan = $satuanUtamaID;
			$bkl->is_sales_item_bahan = 1;
			if (!$bkl->save()){
        		array_push($i->getErrors(),"table Sales Items");
        		echo json_encode($i->getErrors());
        		exit;
        	}		
			// simpan ke detail barang keluar [end]
		 } 
		endif;
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
	
    public function cetak($data,$detail,$payment,$hit, $id, $cd) {
		// echo "<pre>";
		// print_r($data);
		// echo "</pre>";
		// exit;
		// exit;
		$branch_id = Yii::app()->user->branch();
		$this->comphead = Branch::model()->findByPk($branch_id)->company;
		$this->comp = Branch::model()->findByPk($branch_id)->branch_name;
		$this->adr =  Branch::model()->findByPk($branch_id)->address;
		$this->tlp =  Branch::model()->findByPk($branch_id)->telp;
		$this->slg =  Branch::model()->findByPk($branch_id)->slogan;

        // $total_margin = 30; // 58mm
        $total_margin = 42; // 80mm
        // $total_margin = 30;
        $pembatas = 10; 
        $temp_data = array();
        
      
    	$temp_data['sale_id'] = $id;
        $temp_data['status'] = 1;
		$temp_data['alamat'] = $this->set_spacebar($this->adr, $total_margin, "tengah");
        $temp_data['hit'] = $hit;
        $temp_data['id'] = $id;
        $temp_data['trx_tgl'] =  $this->set_spacebar(date('d M Y, H:i', strtotime($data['date'])), $total_margin, "tengah"); ;
		$temp_data['logo'] = $this->set_spacebar( $this->comp, $total_margin, "tengah");
        $temp_data['no_telp'] =   $this->set_spacebar( "Telp. ".$this->tlp, $total_margin, "tengah");
   	    $temp_data['no_nota'] = $this->set_spacebar($data['faktur_id'], $total_margin, "tengah");
        	

        $pjg_ket = $total_margin - 13;
  	
		$temp_data['no_meja']=  "Meja   : " ;		
		$temp_data['mejavalue']=  $data['table'];
		$temp_data['kasir']=           "Kasir       : " .$data['nama_kasir'];
		$temp_data['namapelanggan']=   "Pelanggan   : ". ($data['namapelanggan']=="" ? "umum" : $data['namapelanggan']);
		$temp_data['pembatas'] = $this->spasi($total_margin, "-");
		$temp2 = array();
	
		$z=0;		
		asort($detail);
		foreach ($detail as $val_det) {
			$detail2[$z] = $val_det; 
			$z++;
		}
		
		
        for ($a = 0; $a < $hit; $a++) {
            // $nama_item = Items::model()->find("id=:id ", array(':id' => $detail2[$a]['item_id']));
			// $panjang3 = strlen($tot_qty) + strlen($detail2[$a]['quantity_purchased']) + strlen($detail2[$a]['item_price']) + strlen($detail2[$a]['item_discount']) + strlen("% disc")+15;
            $nama_item =$detail2[$a]['item_name'];
			$item_1 = $nama_item;
			$item_2 = number_format($detail2[$a]['item_total_cost']);

			$x = $detail2[$a]['item_discount'];
			if ($detail2[$a]['item_discount'] == '') 
				$str_disc = "";
			else
				$str_disc = "-" . $x . "% ";

			$item_3 = $detail2[$a]['quantity_purchased'] ." x ".$detail2[$a]['item_price'] .$str_disc;
			$temp = array();

			$len1 = strlen($item_3);
			$len2 = strlen($item_2);
			$space = $total_margin - $len1;
			$stringLast =  $item_3."". str_pad($item_2,$space," ",STR_PAD_LEFT);

			 $temp['quantity'] =  $stringLast;


			// end bau
	
					$banyakspasi = $total_margin - $panjang3 - $panjang2;					
					$last_price = $tot_price = $tot_qty = $qty_last = 0;
					$temp['nama_item'] = strtolower($nama_item);
		

			
			$temp2[] = $temp;        }
			
			
		$temp_data['detail'] = $temp2;

        // fwrite($fh, $pembatas . "\r \n");
        $subtotal = number_format($data['subtotal']);
        $discount = number_format($data['discount']);
        // $sblmpajak = number_format(300000);
        $pajak = number_format($data['tax']);
        $service = number_format($data['service']);
        $total = number_format($data['total_cost']);
		
	
	
			
        $bayar = number_format($data['bayar']); //number_format($_GET['Sales']['payment']);
        $kembali = number_format($data['kembali']); //    $pjg_ket = $total_margin - 13;


        $temp_data['subtotal'] = "SubTotal   : " . $this->set_spacebar($subtotal, $pjg_ket, "kanan") . "\r\n";
        $temp_data['discount'] = "Discount   : " . $this->set_spacebar($discount, $pjg_ket, "kanan") . "\r\n";
        $temp_data['ppn']      = "PPN        : " . $this->set_spacebar($pajak, $pjg_ket, "kanan") . "\r\n";
        $temp_data['service'] =  "service    : " . $this->set_spacebar($service, $pjg_ket, "kanan") . "\r\n";
        $temp_data['pembatas2'] = $pembatas . "\r\n";
        $temp_data['total'] =   "Total      : " . $this->set_spacebar($total, $pjg_ket, "kanan") . "\r\n";
        $temp_data['bayar'] =   "Bayar      : " . $this->set_spacebar($bayar, $pjg_ket, "kanan") . "\r\n";
        $temp_data['voucher'] = "Potongan   : " . $this->set_spacebar('('.number_format($payment['voucher']).')', $pjg_ket, "kanan") . "\r\n";
        
		if ($data['pembayaran_via'] == "0")
			$temp_data['kembali'] = "Kembali    : " . $this->set_spacebar($kembali, $pjg_ket, "kanan") . "\r\n";
		else
			$temp_data['kembali'] = "Kembali    : " . $this->set_spacebar(0, $pjg_ket, "kanan") . "\r\n";
		
        $temp_data['line_bawah'] = "" . "\r\n";
        $temp_data['slogan'] = $this->set_spacebar($this->slg, $total_margin, "tengah") . "\r\n";
		$temp_data['pcm'] = $this->set_spacebar(" ", $total_margin, "tengah") . "\r\n";
		
         if ($cd==1)
			$temp_data['cd'] = 1;
		else
			$temp_data['cd'] = 0;
		// echo "<pre>";
		// print_r($temp_data);
		// echo "</pre>";

		if ( $data['is_pajak']!="")
			return $temp_data;
		else
	        echo json_encode($temp_data);
    }
	private function setKiri2($string){
		return str_pad($string,25," ",STR_PAD_RIGHT);
	}
	private function setKananAngka($string){
		return str_pad($string,15," ",STR_PAD_LEFT);
	}
	
	public function actionHutang(){
		if (isset($_REQUEST['id'])){
			$id = $_REQUEST['id'];
			$data = Sales::model()->find("id=$id ");
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


public function actionCetakReportAll(){
			// $id = 256;
			$criteria=new CDbCriteria;
			$criteria->addInCondition('id',$_REQUEST[data]);
			$saleid = Sales::model()->findAll($criteria);
			// echo count($saleid);
			// echo "<pre>";
			// print_r($saleid);
			// echo "</pre>";
			// $saleid = Sales::model()->findByPk($_GET['id']);
			// $sql
			// print_r($_REQUEST[data]);
			$data_bill = array();
			// for ($x=1;$x<=10;$x++){
		$no_fake = $_REQUEST[no_fake];
		foreach ($saleid as $saleid) {
				$arr_sales = array();
				$arr_detail = array();
				$arr_sales['is_pajak'] = $_REQUEST[pajak];
				$arr_sales['no_fake'] = $no_fake ;

				$arr_sales['subtotal'] = $saleid->sale_sub_total;
				$arr_sales['discount'] = $saleid->sale_discount;
				$arr_sales['tax'] = $saleid->sale_tax;
				$arr_sales['service'] = $saleid->sale_service;
				// $arr_sales['service'] = 0;
				$arr_sales['total_cost'] = $saleid->sale_total_cost;
				$arr_sales['payment'] = $saleid->sale_payment;
				$arr_sales['namapelanggan'] = $saleid->nama;
				$arr_sales['bayar'] = $saleid->bayar;
				$hit = 0;
				// $itemdata = SalesItems::model()->findAll('sale_id=:id',array(':id'=>$_GET['id']));
				$itemdata = Yii::app()->db->createCommand()
					->select('*')
					->from('sales s, sales_items si, items i')
					->where("i.id = si.item_id  and s.id = si.sale_id and s.id = $saleid->id ")
					->group("si.id")
					->queryAll();
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
				$ar_hasil = $this->cetak($arr_sales,$arr_detail,$hit, $saleid->id,0) ;
				array_push($data_bill,$ar_hasil);
				// echo "<pre>";
				// print_r($xx);
				// echo "<pre>";
				// echo $saleid->id."<br>";
				$no_fake++;
			}
			echo json_encode($data_bill);
			// echo "<pre>";
			// print_r($data_bill);
			// echo "</pre>";
	}

	public function actionCetakReport($id){
		$saleid = Sales::model()->findByPk($id);

		$sales_items = "select sum(si.item_price*si.quantity_purchased) total from sales_items si where sale_id = '$saleid->id' ";
		$query = Yii::app()->db->createCommand($sales_items)->queryRow();

		// $sp = "select sum(voucher) total from sales_payment sp where id = '$saleid->id' ";
		// $qsp = Yii::app()->db->createCommand($sp)->queryRow();


		$arr_sales = array();
		$arr_detail = array();
		$arr_sales['is_pajak'] = $_REQUEST[pajak];
		$arr_sales['no_fake'] = $_REQUEST[no_fake];

		$arr_sales['subtotal'] = $query['total'];
		$arr_sales['discount'] = $saleid['sale_discount'];
		$arr_sales['tax'] = $saleid['sale_tax'];
		$arr_sales['service'] = $saleid['sale_service'];

		$arr_sales['total_cost'] = $query['total']-$qsp['total'];
		$arr_sales['payment'] = $saleid['sale_payment'];
		$arr_sales['namapelanggan'] = $saleid['nama'];
		$arr_sales['nama_kasir'] = $saleid['nama_kasir'];
		$arr_sales['bayar'] = $saleid['bayar'];
		$arr_sales['faktur_id'] = $saleid->faktur_id;
		$arr_sales['date'] = $saleid->date;
		$arr_sales['table'] = $saleid->table;
		$arr_sales['kembali'] = $saleid->kembali;
		$arr_sales['pembayaran_via'] = $saleid->pembayaran_via;
		
		// $arr_sales['meja'] = "Meja ";
		$hit = 0;
		$sql = "SELECT * from sales_items where sale_id = $id";
		$itemdata = Yii::app()->db->createCommand($sql)->queryAll();

		$sql_payment = "select * from sales_payment where id = '$id' ";
		$data_payment = Yii::app()->db->createCommand($sql_payment)->queryRow();



		foreach($itemdata as $row){
			// echo '<br>'.$row['id'];
			$arr_detail[$hit]['satuan']= ItemsSatuan::model()->findByPk($row['item_satuan_id'])->nama_satuan;
			$arr_detail[$hit]['item_name']=$row['sales_item_name'];
			$arr_detail[$hit]['item_id']=$row['item_id'];
			$arr_detail[$hit]['quantity_purchased']=intval($row['quantity_purchased']);
			$arr_detail[$hit]['item_tax']=$row['item_tax'];
			$arr_detail[$hit]['item_price']=$row['item_price'];
			$arr_detail[$hit]['item_total_cost']=$row['item_total_cost'];
			$hit++;
		}

		// echo "hit".$hit;
		$this->cetak($arr_sales,$arr_detail,$data_payment,$hit, $id,0);
	}
	
	public function actionHanyacetak(){
		// print_r($_REQUEST);
		$branch_id = Yii::app()->user->branch();
		$this->comphead = Branch::model()->findByPk($branch_id)->company;
		$this->comp = Branch::model()->findByPk($branch_id)->branch_name;
		$this->adr =  Branch::model()->findByPk($branch_id)->address;
		$this->tlp =  Branch::model()->findByPk($branch_id)->telp;
		$this->slg =  Branch::model()->findByPk($branch_id)->slogan;
        $pembatas = 20;
		//print_r($_REQUEST['data']);
		$model = $_REQUEST['data'];
		$detail = $_REQUEST['data_detail'];
// echo "<pre>";
       // print_r($detail);
// echo "</pre>";
		
        $total_margin = 30;

       // $myFile = "c:\\epson\\cetakbarujual.txt";
        //s$fh = fopen($myFile, 'w') or die("can't open file");
        $temp_data = array();
			$temp_data['logo'] = $this->comphead." \n ".$this->comp;

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
		$temp_data['mejavalue']=  $model['table'];
        $temp_data['kasir']=           "Kasir  : ". $nama['name'];
        $temp_data['namapelanggan']=   "Nama   : ". $model['namapelanggan'];
		
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
			// $temp['nama_item'] = $nama_item['item_name'];
			if 	(!empty($nama_item['item_name'] ) ){
			//------begin-----
			//cek apakah ID yg sedang di proses sama dengan ID yg sebelumnya masuk ke dalam array
				if($detail2[$a]['item_id']==$detail2[$a-1]['item_id'] && $detail2[$a]['item_satuan_id']==$detail2[$a-1]['item_satuan_id']   ){
					$panjang3 = strlen($tot_qty) + strlen($detail2[$a]['item_price']) + strlen($detail2[$a]['item_discount']) + strlen("% disc")+6;
					$banyakspasi = $total_margin - $panjang3 - $panjang2;
					
					$curr_price = $detail2[$a]['quantity_purchased']*$detail2[$a]['item_price'];
					$last_price += $detail2[$a-1]['quantity_purchased']*$detail2[$a-1]['item_price'];
					$tot_price = $curr_price+$last_price;
					
					$qty_last += $detail2[$a-1]['quantity_purchased'];
					$tot_qty = $qty_last+$detail2[$a]['quantity_purchased'];
					// if ($nama_item['item_name']!=""){
					// $temp['quantity'] = $tot_qty . " x " . $detail2[$a]['item_price'] . " - " . $detail2[$a]['item_discount'] . "% disc". " " . $this->spacebar($banyakspasi - 1).number_format($tot_price);
					$temp['quantity'] = $tot_qty . " x " . $detail2[$a]['item_price'] . " - " . $detail2[$a]['item_discount'] . "% disc". " " .  $this->set_spacebar(number_format($tot_price), 20.5, "kanan");
					// }
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

			$temp2[] = $temp;       
			}

		 }
			
			
			
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
        $temp_data['ppn'] = "Ppn        : " . $this->set_spacebar($pajak, $pjg_ket, "kanan") . "\r\n";
        // fwrite($fh, $pembatas . "\r\n");
        $temp_data['pembatas2'] = $pembatas . "\r\n";
        $temp_data['total'] = "Total      : " . $this->set_spacebar($total, $pjg_ket, "kanan") . "\r\n";
        $temp_data['kembali'] = "Kembali    : " . $this->set_spacebar($kembali, $pjg_ket, "kanan") . "\r\n";
        $temp_data['line_bawah'] = "" . "\r\n";
        $temp_data['bayar'] = "Bayar      : " . $this->set_spacebar("", $pjg_ket, "kanan") . "\r\n";
        // fwrite($fh, $this->set_spacebar("(c) Pak Chi Met - 2013", $total_margin, "tengah") . "\r\n");
        //$temp_data['pcm'] = $this->set_spacebar("(c) ".$this->comp." - ".date("Y"), $total_margin, "tengah") . "\r\n";
        $temp_data['slogan'] = $this->set_spacebar($this->slg, $total_margin, "tengah") . "\r\n";
		$temp_data['pcm'] = $this->set_spacebar(" ", $total_margin, "tengah") . "\r\n";
		// $temp_data['pcm'] = $this->set_spacebar("Terimakasih atas kunjunganya ", $total_margin, "tengah") . "\r\n";
		//$temp_data['nama'] = $this->set_spacebar("Nama  : ". $model['namapelanggan'], $total_margin, "tengah") . "\r\n";
		$temp_data['cd'] = 0;
        // fclose($fh);
       echo json_encode($temp_data);
		 // echo "<pre>";
			//  print_r($temp_data);
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
    public function actionHapus($id) {
        $model = $this->loadModel($id);
        $model->status = 2;


		//ambil data dari user yg login
		$username = Yii::app()->user->name;
		$user = Users::model()->find('username=:un',array(':un'=>$username));
		
        $model->deleted_by = $user->id;
        $model->deleted_at = date("Y-m-d H:i:s");
        if ($model->update()){
        	$keluar = BarangKeluar::model()->find(" sales_id = '$id' ");
			if ($keluar){
				$keluar->status_keluar = 2;
				$keluar->update();
			}
			// $sd = SalesItems::model()->findAll("sale_id = '$id' ");
			// foreach($sd as $s){
			// 	$brg = Items::model()->findByPk($s->item_id);
			// 	$brg->stok = $brg->stok + $s->quantity_purchased;
			// 	$brg->update();		
			// }
		}
		$this->redirect(array('sales/index'));	
    }


    /**
     * Lists all models.
     */
	 
	 public function actionSalescashmonthly(){
		$username = Yii::app()->user->name;

		$mode = $_REQUEST['mode'];
		if (isset($_REQUEST['Sales']['date']) && isset($_REQUEST['Sales']['tgl']) ) {
			$tgl = $_REQUEST['Sales']['date'];
			$tgl2 = $_REQUEST['Sales']['tgl'];
		}
		else{
			$tgl = date('Y-m-d',strtotime('-7 days')); 
			$tgl2 = date('Y-m-d'); 
		}
		$mode = "top";
		$connection = Yii::app()->db;
		$store_id = Yii::app()->user->store_id();
		if (Yii::app()->user->level() === "1"){
			$bcdefault = Yii::app()->user->branch();
			$where_branch = " and s.branch='$bcdefault'";
		}else{
			$where_branch = " ";
		}
			
		if (isset($_REQUEST['kategori'])){

			if ($_REQUEST['kategori']=='semua')
				$filterKategori = " ";
			else
				$filterKategori = " and category_id = '$_REQUEST[kategori]' ";
		}

		if ($_REQUEST['kelompok']=='cabang'){
			$querySelect = "CONCAT(SUBSTR(b.branch_name, 1, 50)) nama,";
			$groupBy = "group by b.id";
		}else{
			$querySelect = "CONCAT(SUBSTR(i.item_name, 1, 50),'-',iss.nama_satuan) nama,";
			$groupBy = "group by i.id";
		}



		$filter = [
			'querySelect' => $querySelect,
			'where_branch' => $where_branch,
			'groupBy' => $groupBy,
			'tgl' => $tgl,
			'tgl2' => $tgl2,
			'filterKategori' => $filterKategori
		];

		$query = $this->queryTopPenjualan($filter);
		
		
		$query = "SELECT 
		s.pembayaran_via as bank,
		sum(sale_total_cost) as total
		 FROM  (".$this->sqlsales().") as s
		INNER JOIN  
		bank as b  on b.nama = s.pembayaran_via
		 where  date(s.date) between '$tgl' and '$tgl2' 
		 group by b.id
		  ";
		  
		$command = $connection->createCommand($query);
		$row = $command->queryAll(); 
        $this->render('salescashmonthly',array(
			'databar'=>$row,
			'tgl'=>$tgl,
			'tgl2'=>$tgl2,
			'mode'=>$mode,
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
		$model = new Sales;
		
		
		$cabang = Yii::app()->user->id;
		$user = Users::model()->find('username=:un',array(':un'=>$cabang));
		$cabang_id = $user->branch_id;
		
		$tot = Yii::app()->db->createCommand()
				->select('(SUM(cash)+SUM(edc_bca)+SUM(compliment)+SUM(edc_niaga)+SUM(voucher)+SUM(dll)) grandtotal,date(s.date) tanggal,s.id,s.date,sum(cash)cash,sum(edc_bca)edc_bca,sum(edc_niaga)edc_niag,sum(compliment)compliment,sum(dll)dll,sum(voucher)voucher, SUM(cash+edc_bca+edc_niaga+compliment+dll+voucher) total')
				->from('sales s,sales_payment ')
				->where("s.id = sales_payment.id and date(date)>= '$tgl' and  date(date)<= '$tgl2' ")
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
		// if (isset($_GET['Sales']['date'])){
		// 	$date = $_GET['Sales']['date'];
		// 	$date2 = $_GET['Sales']['date2'];
		// }
		// else{
		// 	$date = date('Y-m-d');
		// 	$date2 = date('Y-m-d');
			
		// }
		if ($_POST['month']){
			$month = $_POST['month'];
			$year = $_POST['year'];
		}else{
			$month = intval(Date('m'));
			$year = intval(Date('Y'));
		}

		
		
			if ($idk == '2'){
				$filter  = "inserter='$user->id' and";
			}else if ($idk == '6'){
				$filter  = " ";
			}
			$branch_id = Yii::app()->user->branch();
			$row = Yii::app()->db->createCommand()


			->select('faktur_id,pembayaran_via,s.id,s.date,cash,edc_bca,edc_niaga,compliment,dll,voucher ,credit_mandiri, credit_bca, SUM(cash+edc_bca+edc_niaga+compliment+dll+credit_mandiri+credit_bca) total')
			->from('sales s,sales_payment ')
			->where("   year(s.date)=$year and month(s.date)=$month    and s.id = sales_payment.id and s.status = 1 and s.branch='$branch_id' ")
			->group('s.id')
			->queryAll();

			$summary = Yii::app()->db->createCommand()
			->select('pembayaran_via,
			(SUM(cash)+SUM(edc_bca)+SUM(compliment)+SUM(edc_niaga)+SUM(dll)+SUM(credit_mandiri)+SUM(credit_bca)) grandtotal,SUM(cash) totalcash,SUM(compliment) totalcomp,SUM(edc_bca) totalbca,SUM(edc_niaga) totalniaga ,sum(dll) totaldll,sum(credit_bca) credit_bca,sum(credit_mandiri) credit_mandiri')
			->from('sales,sales_payment')
			->where("sales.id = sales_payment.id 
				and  year(date)=$year and month(date)=$month and sales.status = 1 and sales.branch = '$branch_id' ")
			->queryRow();
			
		$cash = new CArrayDataProvider($row,array(
			'pagination'=>array(
							'pageSize'=>100,
						),
		));

		//dikonfersi ke CArrayDataProvider
		$this->render('cash', array(
			'datacash' => $cash,
			'cashsum' => $summary,
			'year' => $year,
			'month' => $month
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
	
	 public function getSQLOmset($month, $year){
	 		$username = Yii::app()->user->name;
			$user = Users::model()->find('username=:un',array(':un'=>$username));
			$user->id;
			$idk = $user->level; 
			// if($idk=='')
			// 	$this->redirect(array('site/login'));

			$data = new Sales;
			if ($month<13 || $year<20100){	
				$filter_year =   " and month(s.date)='$month' and year(s.date)<='$year'";  
			}else{
				$filter_year = " ";
			}
	 		$subtotal = "si.item_price*si.quantity_purchased" ; 
			$submodal = "item_modal *si.quantity_purchased" ; 
			$untung = " ($subtotal) - ($submodal) " ; 
			$sale_service = "si.item_service" ; 

			$sql  = "select nama,
			if (bayar<sum($subtotal) or bayar=0 ,'Kredit','Lunas') as sb, 
			s.bayar,s.table,inserter, s.comment comment, 
			s.id,sum(si.quantity_purchased) as total_items, 
			date,tanggal_jt,
			s.waiter waiter,

			sum($untung) untung,
			sum($subtotal) sale_sub_total,
			sum($submodal) sale_sub_modal,
			
			sum($sale_service) sale_service,
			sum(si.item_tax) sale_tax,
			sp.voucher voucher,
			sum( si.item_discount/100 * ($subtotal) )  sale_discount,
			
			sum((
				($subtotal) + $sale_service + (si.item_tax)-  ( si.item_discount/100 * ($subtotal)) 
			)) - (sp.voucher)  
			sale_total_cost,

			 u.username inserter 
			 from sales s,sales_items si , users u , items i, sales_payment sp 
			 where 
			  sp.id = s.id 
			  and
			  i.id = si.item_id  and
			  
			 s.id = si.sale_id 
			 $filter_year

			
			 and s.status!=2 
			 and inserter = u.id  
			 $filter    group by s.id 






			 $this->status_bayar
			  ";
			  return $sql;
	 }
	 
    public function actionBayartagihan() {
    	// echo "123";
    	if (isset($_REQUEST['tanggal_bayar'])){
    		$model = new SalesBayar;
    		$model->sales_id = $_REQUEST['id'];
    		$model->pembayaran_via = $_REQUEST['pembayaran_via'];
    		$model->waktu = $_REQUEST['tanggal_bayar'];
    		$model->bayar = $_REQUEST['total_bayar'];
    		if ($model->save()){
    			$sales = Sales::model()->findByPk($model->sales_id);
    			$sales->bayar += $model->bayar;
    			$sales->update();
    			echo json_encode(array("status"=>200));
    		}else{
    			echo json_encode(array("status"=>500));
    		}

    	}
    }
    public function actionGetsaledata($id) {
		$table = $this->sqlSales();
		$sql = " SELECT * FROM ($table) AS  D 
		where D.id = '$id' and D.status = 1
		group by D.id
		";
		$data =  Yii::app()->db->createCommand($sql)->queryRow();
		echo json_encode($data);



    }
	 public static function getHutangByCustomer2($name) {
    	// return $name;
    	// exit;
    	$table = self::sqlSales();
		$sql = " SELECT * FROM ($table) AS  D 
		where D.nama = '$name'  
		group by D.id

		";
		$data =  Yii::app()->db->createCommand($sql)->queryAll();
		// echo "<pre>";
		// print_r($data)
		// echo "</pre>";
		// var_dump($data);
		$hutang = 0;
		foreach ($data as $key => $value) {
			if ($value['bayar']<$value['sale_total_cost']  ){	
				$hutang += intval($value['sale_total_cost']) - intval($value['bayar']);
			}
		}
		return $hutang;
    }
    public static function getHutangByCustomer($name,$id) {
    	$table = self::sqlSales();
		$sql = " SELECT * FROM ($table) 	AS  D 
		where D.nama = '$name'  and D.status = 1
		group by D.id

		";
		$data =  Yii::app()->db->createCommand($sql)->queryAll();
		// var_dump($data)
		$hutang = 0;
		foreach ($data as $key => $value) {
			if ($value['bayar']<$value['sale_total_cost']){	
				$hutang += intval($value['sale_total_cost']) - intval($value['bayar']);
			}
		}

		return $hutang;
    }
    public function actionLaporan_hutang() {
    	
			if ($_REQUEST['month']){
				$day2 = $_REQUEST['day'];
				$month = $_REQUEST['month'];
				$year = $_REQUEST['year'];
			}else{
				$day2 = intval(Date('d'));
				$month = intval(Date('m'));
				$year = intval(Date('Y'));
			}
			
			if ($idk != 2) //jika kasir
				$filter = "";
			 else
			 	$filter = " ";
				// $filter = "and inserter = $user->id";

			 // if(isset($_REQUEST['status'])){
			 	
			 // 	if ($_REQUEST['status']!='semua'){		
			 	// $this->status_bayar = "  having sb = '$_REQUEST[status]'";
			 $this->status_bayar = "  having sb = 'Kredit'";
			 // 	}else{
				//  	$this->status_bayar = "";
			 // 	}

			 // }else{
			 // 	$this->status_bayar = "";
			 // }
			
			$table = $this->sqlSales();
			$cabang = $_REQUEST['cabang'];
			if (!empty($cabang)){
				$where_branch = " and branch='$cabang'";
			}else{
				// $where_branch = " ";
				$bcdefault = Yii::app()->user->branch();
				$where_branch = " and branch='$bcdefault'";
			}


			$customer = $_REQUEST['customer'];
			$where_customer = "";
			if (!empty($customer)){
				$where_customer = " and nama='$customer' ";
			}

			$refno = $_REQUEST['refno'];
			$where_refno = "";
			if (!empty($refno)){
				$where_refno = " and trim(refno)=trim('$refno') ";
			}

			 // month(D.date)='$month' and year(D.date)='$year' and day(D.date)='$day2'
			$sql = " SELECT * FROM ($table) AS  D 
			where 1=1 
			 $where_branch $where_customer $where_refno
			group by D.id
			$this->status_bayar
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
        $model = new Sales;
        // $dataProvider = $data->search();
        $this->render('laporan_hutang', array(
            'dataProvider' => $dataProvider,
            // 'summary' => $summary,
			// 'tgl' => $date,
			// 'tgl2' => $date2,
			'month'=>$month,
			'year'=>$year,
			'day2'=>$day2,
			'refno'=>$refno,
        ));

    }
    public function actionIndex() {
			$username = Yii::app()->user->name;
			$user = Users::model()->find('username=:un',array(':un'=>$username));
			$idk = $user->level; 

		

			if ($_REQUEST['month']){
				$day2 = $_REQUEST['day'];
				$month = $_REQUEST['month'];
				$year = $_REQUEST['year'];
			}else{
				$day2 = intval(Date('d'));
				$month = intval(Date('m'));
				$year = intval(Date('Y'));
			}
			
				if($idk != 2) //jika kasir
				$filter = " and inserter = '$user->username'";
				else
				$filter = "";
			 	// $filter = " ";

			 if(isset($_REQUEST['status'])){
			 	
			 	if ($_REQUEST['status']!='semua'){		
				 	$this->status_bayar = "  having sb = '$_REQUEST[status]'";
			 	}else{
				 	$this->status_bayar = "";
			 	}

			 }else{
			 	$this->status_bayar = "";
			 }
			
			$table = $this->sqlSales();
			//  echo $table;
			//  exit;
			$cabang = $_REQUEST['cabang'];
			if (!empty($cabang)){
				$where_branch = " and branch='$cabang'";
			}else{
				// $where_branch = " ";
				$bcdefault = Yii::app()->user->branch();
				$where_branch = " and branch='$bcdefault'";
				// echo $where_branch;
			}

			$sql = "  SELECT * FROM ($table) AS  D 
			where month(D.date)='$month' and year(D.date)='$year' and day(D.date)='$day2' $where_branch 
				$filter
			group by D.id
			$this->status_bayar

			order by D.date asc
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
        $model = new Sales;
        // $dataProvider = $data->search();
        $this->render('index', array(
            'dataProvider' => $dataProvider,
            // 'summary' => $summary,
			// 'tgl' => $date,
			// 'tgl2' => $date2,
			'month'=>$month,
			'year'=>$year,
			'day2'=>$day2,
			'cabang'=>$cabang,
        ));
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
			  i.id = si.item_id  and
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
					stc, sum(sale_discount) sd , sum(si.item_price*si.quantity_purchased) sst, sum(item_tax) t, sum(sale_service) svc, sum(pembulatan)')
                    ->from('sales s, sales_items si,items i')
					
                    ->where('
					 i.id = si.item_id  and
					si.sale_id = s.id and date(s.date)>=:date and date(s.date)<=:date2 AND s.status=:status and inserter =:ins ', array(':date' => $tgl, ':date2'=>$tgl2,':status' => 1,"ins"=>$user->id))
                    ->queryRow();
			}
			else{
			
			$sql  = "select s.bayar,s.table,inserter, s.id,sum(si.quantity_purchased) as total_items, date,sum(si.item_price*si.quantity_purchased) sale_sub_total,s.sale_tax,s.sale_service, s.sale_discount, 
			sum(((si.item_price*si.quantity_purchased)+(si.item_tax)-(si.item_discount*(si.item_price*si.quantity_purchased)/100)))  sale_total_cost,
			 u.username inserter 
			 from sales s,sales_items si , users u ,items i
			 where 
			  i.id = si.item_id  and
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
					stc, sum(sale_discount) sd , sum(si.item_price*si.quantity_purchased) sst, sum(item_tax) t, sum(sale_service) svc,  sum(pembulatan)')
                    ->from('sales s, sales_items si,items i')
                    ->where('
					 i.id = si.item_id  and 
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


	function actionCetakRekap(){
		$noprint = $_REQUEST['noprint'];
		$html_noprint = "";
		if (isset($_REQUEST['inserter'])){
			$branch_id = Users::model()->findByPk($_REQUEST['inserter'])->branch_id;
		}else{
			$branch_id = Yii::app()->user->branch();
		}
		
		$date = $_GET['tanggal_rekap'];
		
		if (isset($_REQUEST['inserter'])){
			$username = $_REQUEST['inserter'];
			$username = Users::model()->findByPk($username)->username;
		}else{
			$username = Yii::app()->user->name;
		}		
		
		$user = Users::model()->find('username=:un',array(':un'=>$username));
		$idk = $user->level;
		

		if (isset($_REQUEST['uangmasuk'])){//jika closing
			$setor = Setor::model()->find("user_id = '$user->id' and  tanggal='$_REQUEST[tanggal_rekap]' ");
			if ($setor){
				$setor->total = $_REQUEST['uangmasuk'];
				$setor->comment = $_REQUEST['comment'];
				$setor->is_closed = 1;
				$setor->updated_at = date("Y-m-d H:i:s");
				$setor->save();
			}else{
				http_response_code(404);
				echo json_encode(["error"=>true,"message"=>"Anda belum melakukan register kasir"]);
				exit;
			}
		}
		$this->comp = Branch::model()->findByPk($branch_id)->branch_name;
		$this->adr =  Branch::model()->findByPk($branch_id)->address;
		$this->tlp =  Branch::model()->findByPk($branch_id)->telp;
		$this->slg =  Branch::model()->findByPk($branch_id)->slogan;

		$html_noprint .= "<table class='x' style='width:100%;font-family:courier!important'  border='0' cellpadding='0' >
		<tr>
			<td align='center'><h2>$this->comp</h2></td>
		</tr>
		<tr>
			<td align='center'>{$this->adr}{$this->tlp}</td>
		</tr>
		<tr>
			<td align='center'>{$this->slg}</td>
		</tr>
		";


		$inserter  = $user->id;
		// if ($idk==6){
			$filter =  " and sales.inserter = '$user->id' ";
			$filter2 =  " and s.inserter = '$user->id' ";
		// }else{
		// 	$filter =  " ";
		// 	$filter2 =  " ";
		// }
		$total_adt_cost = "
		select sum(adt_cost) adt_cost from sales where 1=1 and status= 1  
		and date(date)='$date' 
	    $filter  
		";		

		$total_adt_cost =  Yii::app()->db->createCommand($total_adt_cost)->queryRow();

	
		$query = $this->sqlSales();

		$query = "select 
		sum(sale_sub_total) gross,
		sum(pembulatan) pembulatan,
		SUM(tax) AS tax,
		SUM(service) AS svc,
		SUM(sale_total_cost) AS stc,
		SUM(sale_discount) AS tot_disc
		from
		({$query}) as X 
		where
		DATE(date) = '{$date}' and
		inserter = '{$username}'
		"   ;
		$data1 = Yii::app()->db->createCommand($query)->queryAll();

		
		
		$sqlGetTotal = "SELECT  
		outlet.kode_outlet ,
		items.category_id ,
		motif.nama nama_motif,
		SUM(sales_items.item_price * IF(sales_items.item_price<0,-quantity_purchased,quantity_purchased))   total
		FROM sales_items
		JOIN sales ON sales.id=sales_items.sale_id
		JOIN items ON items.id=sales_items.item_id
		JOIN motif on motif.id = items.motif
		JOIN outlet ON outlet.kode_outlet = items.kode_outlet
		WHERE DATE(DATE) = '$date' AND sales.status = 1
		$filter
		GROUP BY motif.id		
		ORDER BY motif.id ";
		// var_dump($filter);
		// var_dump($sqlGetTotal);

		$arrayGetTotal = array();
		$getTotal = Yii::app()->db->createCommand($sqlGetTotal)->queryAll();
		foreach ($getTotal as $gt) {
			$arrayGetTotal[$gt[nama_motif]] = $gt[total];
		}
		// print_r($arrayGetTotal);
		// exit;
		
		// if ($idk != 2){ //jika bukan admin
			$filterX = " and inserter = $user->id ";
		// }else{
		// 	$filterX = "  ";				
		// }
		$payment = Yii::app()->db->createCommand()
		->select('
		 sum(cash)cash,sum(edc_bca)edc_bca,sum(edc_niaga)edc_niaga,sum(compliment)compliment,sum(dll)dll,sum(voucher)voucher , 
		 SUM(cash+edc_bca+edc_niaga+compliment+dll) total
		')
		->from('sales s,sales_payment ')
		->where("
		date(s.date)=:date 
		and  s.status=1 
		and s.id = sales_payment.id $filterX", array(':date' => $date))
		->queryAll();
	
		$data = Yii::app()->db->createCommand()
		->select('
			m.nama nama_motif,
			c.category nm,
			items.item_name item_name,
			item_price harga,
			sales_items.item_price * sum(if(sales_items.item_price<0,-quantity_purchased,quantity_purchased)) price,sum(if(sales_items.item_price<0,-quantity_purchased,quantity_purchased)) qp')
		->from('sales_items')
		->join('sales', 'sales.id=sales_items.sale_id')
		->join('items', 'items.id=sales_items.item_id')
		// ->join('outlet', 'outlet.kode_outlet = items.kode_outlet')
		->join('categories c', 'c.id = items.category_id')
		->leftjoin('motif m', 'm.id = items.motif')
		// ->join('categor', 'outlet.kode_outlet = items.kode_outlet')
		// ->where('date(date=:date)', array(':date'=>date('2013-03-05')))
		->where("date(date) = '".$date."' and sales.status = 1  $filterX")
		->group('item_id,sales_items.item_price')
		->order('items.motif')	
		->queryAll();


		$query_gratis = "
		SELECT
			categories.category nm,
			items.item_name item_name,
			bkd.harga harga,
			bkd.jumlah jumlah 
		FROM
			barangkeluar_detail bkd
		INNER JOIN barangkeluar bk ON bk.id = bkd.head_id
		INNER JOIN items ON items.id = bkd.kode
		INNER JOIN items_satuan iss ON iss.id  = bkd.satuan and items.id = iss.item_id
		INNER JOIN categories ON categories.id = items.category_id
		WHERE bk.user = '$user->username'

			and date(tanggal)= '$date'
			and jenis_keluar='gratis (compliment)'
		GROUP BY
			bkd.kode,bkd.harga
		ORDER BY categories.category asc
		";
		// echo $query_gratis;
		$data_gratis = Yii::app()->db->createCommand($query_gratis)->queryAll();
		// echo "<pre>";
		// print_r($data_gratis);
		// echo "</pre>";

		$query_pengeluaran  = "select * from pengeluaran where 1=1 and date(tanggal)='$date' and user='$user->username'order by tanggal desc";
		$data_pengeluaran = Yii::app()->db->createCommand($query_pengeluaran)->queryAll();
		
		$temp_data['pengeluaran'] = array();
		foreach ($data_pengeluaran as $key => $value) {
			$nama = " - ".substr(strtoupper($value['jenis_pengeluaran']) ,0,30);
			$totals = number_format($value['total']);
			$total =  ($totals);
			$array = array("nama"=>$nama,"total"=>$total);
			$htmlpengeluaran .= "<tr><td>".$nama."  :  ".$total."</td></tr>";

			$temp_data['pengeluaran'][] = $array;
			$totalkeluar+=$value['total'];
		}


		$pembatas = 20;
		$model = $_GET['data'];
		$detail = $_GET['data_detail'];
		
        // $total_margin = 30; // 58mm
        $total_margin = 42; // 80mm
        $temp_data = array();
        $temp_data['logo'] = $this->comp;
        $temp_data['alamat'] = $this->adr;
        $temp_data['no_telp'] = "Telp. ".$this->tlp;
        $temp_data['trx_tgl'] = date('Y.m.d',strtotime($date));
        $html_noprint .= "<tr><td align='center'>".$temp_data['trx_tgl']."/".$username." </td></tr>";
        $html_noprint .= "<tr><td align='center'>Print-time:".date('Y.m.d H:i:s')." </td></tr>";
        $pjg_ket = $total_margin - 13;
		
		
		$temp_data['kasir']=   "Kasir          : " .$username;
        // $html_noprint .= "<tr><td align='center'>".$temp_data['kasir']."</td></tr>";


        $temp_data['tgl_cetak']=   "Tanggal Cetak  : " . date('d-M-Y H:i:s') . "\r";
        // $html_noprint .= "<tr><td align='center'>".$temp_data['tgl_cetak']."</td></tr>";

		
		
		$temp_data['pembatas'] = $this->spasi($total_margin, "-");
		$html_noprint .= '<tr><td style="border:1px dashed white;padding:0"><div style="width:100%;border-bottom: 1px dashed black;"></div></td></tr>';

		$tmp3 = array();
		$tmp = array();
		$kosong = array();
		$tmpt = array();
		// echo "<pre>";
		// print_r($data1);
		// echo "</pre>";
		// var_dump($payment);

		foreach($payment as $dt1){
			$tmpt['total'] =     "Total Payment :";
			$tmpt['totalvalue'] = "\r".number_format($dt1['total'])."\r\n";
	        // $html_noprint .= "<tr><td>".$tmpt['total'].$tmpt['totalvalue']."</td></tr>";

			
			$tmpt['comp'] =     "Compliment    :";
			$tmpt['compvalue'] = "\r".number_format($dt1['compliment'])."\r\n";
	        // $html_noprint .= "<tr><td>".$tmpt['comp'].$tmpt['compvalue']."</td></tr>";

			
			$tmpt['netcash'] =  "Net Cash      :";
			$tmpt['netcashvalue'] = "\r".number_format($dt1['cash'])."\r\n";
			$cash = $dt1['cash'];
	        // $html_noprint .= "<tr><td>".$tmpt['netcash'].$tmpt['netcashvalue']."</td></tr>";


			$tmpt['bca'] =      "GO PAY        :";
			$tmpt['bcavalue'] = "\r".number_format($dt1['edc_bca'])."\r\n";
	        // $html_noprint .= "<tr><td>".$tmpt['bca'].$tmpt['bcavalue']."</td></tr>";

			$tmpt['niaga'] =    "EDC BCA       :";
			$tmpt['niagavalue'] = "\r".number_format($dt1['edc_niaga'])."\r\n";
	        // $html_noprint .= "<tr><td>".$tmpt['niaga'].$tmpt['niagavalue']."</td></tr>";

			
			$tmpt['voucher'] =  "Voucher        :";
			$voucher = $dt1['voucher'];
			$uangcash = $dt1['cash'];
			$tmpt['vouchervalue'] = "\r(".number_format($voucher).")\r\n";

			

			$tmpt['total_pembayaran_label'] = "Total Akhir       :";
			$tmpt['total_pembayaran'] = "\r".number_format($dt1['cash'] + $dt1['edc_bca'] + $dt1['edc_niaga'])."\r\n";
			 // $html_noprint .= "<tr><td>".$tmpt['total_pembayaran_label'].$tmpt['total_pembayaran']."</td></tr>";
			$tmpt['uangmasuk'] =              "Uang Cash    :";
			$tmpt['sisa_label'] =             "Sisa/Minus    :";

			 // $html_noprint .= "<tr><td>".$tmpt['total_pembayaran_label'].$tmpt['total_pembayaran']."</td></tr>";
			
			$tmp3 = $tmpt;
			
		}

		// $html_noprint .= "<tr><td>".$temp_data['pembatas']."</td></tr>";

		// print_r($data1);
		// exit;
		foreach($data1 as $dt1){
			//gross sales
			// $net_sales = $dt1['cost'] - $dt1['tot_disc']-$dt1['tax'];
			//$net_sales = $dt1['cost'];
			$tmp['gross'] ="Total Bersih   :";
			$tmp['grossvalue'] = "\r".number_format($dt1['stc'])."\r\n";
			// $html_noprint .= "<tr><td>".$tmp['gross'].$tmp['grossvalue']."</td></tr>";

			
			$html_noprint .= "<tr><td><b>RINCIAN PENJUALAN</b></td></tr>";	
			$html_noprint .= '<tr><td style="border:1px dashed white;padding:0"><div style="width:100%;border-bottom: 1px dashed black;"></div></td></tr>';
	
			$tmp['net'] =  "Total Kotor    :";
			$tmp['netvalue'] = "\r".number_format($dt1['gross'])."\r\n";
			$html_noprint .= "<tr><td>".$tmp['net'].$tmp['netvalue']."</td></tr>";

			$tmp['disc'] = "Discount       :";
			$tmp['discvalue'] = " (".number_format($dt1['tot_disc']).") \r\n";
			$html_noprint .= "<tr><td>".$tmp['disc'].$tmp['discvalue']."</td></tr>";
	        $html_noprint .= "<tr><td>".$tmpt['voucher'].$tmpt['vouchervalue']."</td></tr>";


			$tmp['svc'] = "Service        :";
			$tmp['svcvalue'] = "\r".number_format($dt1['svc'])."\r\n";
			$html_noprint .= "<tr><td>".$tmp['svc'].$tmp['svcvalue']."</td></tr>";
			
			$tmp['tax'] =      "Tax            :";
			$tmp['taxvalue'] = "\r".number_format($dt1['tax'])."\r\n";
			$html_noprint .= "<tr><td>".$tmp['tax'].$tmp['taxvalue']."</td></tr>";

			$tmp['adt_cost'] =    "Biaya Bank     :";
			$tmp['adt_costvalue'] = "\r".number_format($total_adt_cost['adt_cost'])."\r\n";
			$html_noprint .= "<tr><td>".$tmp['adt_cost'].$tmp['adt_costvalue']."</td></tr>";

			$tmp['pembulatan_label'] =    "Pembulatan     :";
			$tmp['pembulatan_value'] = "\r".number_format($dt1['pembulatan'])."\r\n";
			$html_noprint .= "<tr><td>".$tmp['pembulatan_label'].$tmp['pembulatan_value']."</td></tr>";


			$final =  ($dt1['stc']-$voucher)+$total_adt_cost['adt_cost'];
			$tmp['total_final'] = "Total Akhir    :";
			$tmp['total_final_value'] = "\r".number_format($final)."\r\n";	
			$html_noprint .= "<tr><td>".$tmp['total_final'].$tmp['total_final_value']."</td></tr>";

			$tmp2 = $tmp;

			$html_noprint .= '<tr><td style="border:1px dashed white;padding:0"><div style="width:100%;border-bottom: 1px dashed black;"></div></td></tr>';

			
			//------------------------------------------------------------
		}	

		$cek = Setor::model()->count(" user_id = '$user->id' and date(tanggal)='$date' ");
		if ($cek==0){	
			// echo "wkwkw";
			$masuk = $_REQUEST['uangmasuk'];
			$awal = 0;
			$tmp3['uangmasukvalue'] = "\r".number_format($_REQUEST['uangmasuk'])."\r\n";
		}else{
			$model = Setor::model()->find(" user_id = '$user->id' and date(tanggal)='$date' ");
			$masuk = $model->total;
			$awal = $model->total_awal;

			$tmp3['uangmasukvalue'] = "\r".number_format($model->total)."\r\n";
		}


		$tmpt['pembayaran_penjualan_label'] = "Total Transaksi Cash       :\n";
		$tmpt['pembayaran_penjualan'] =  number_format($uangcash);
	
		


		$hrs_ada = $uangcash+$awal-$totalkeluar;
		$tmp3['harus_ada'] =  "\r".number_format($hrs_ada)."\r\n";
		$tmp3['harus_ada_label'] =  "\r Total Cash Harus Ada :\r\n";

		$tmp3['saldo_awal'] =  "\r".number_format($awal)."\r\n";
		$tmp3['saldo_awal_label'] =  "\rSaldo Cash Awal :\r\n";
		
		$html_noprint .= "<tr><td><b>RINCIAN TRANSAKSI CASH</b></td></tr>";	
		$html_noprint .= '<tr><td style="border:1px dashed white;padding:0"><div style="width:100%;border-bottom: 1px dashed black;"></div></td></tr>';

		$html_noprint .= "<tr><td>".$tmp3['saldo_awal_label'].$tmp3['saldo_awal']."</td></tr>";
		$html_noprint .= "<tr><td>Total Pengeluaran : ".number_format($totalkeluar)."</td></tr>";
		$html_noprint .= "<tr><td>".$tmp3['harus_ada_label'].$tmp3['harus_ada']."</td></tr>";
		$html_noprint .= "<tr><td>".$tmpt['pembayaran_penjualan_label'].$tmpt['pembayaran_penjualan']."</td></tr>";

		// var_dump($tmp3['sisa']);
		$html_noprint .= "<tr><td>".$tmp3['uangmasuk'].$tmp3['uangmasukvalue']."</td></tr>";
		
		$tmp3['sisa'] =  "\r".number_format(($masuk)-$hrs_ada)."\r\n";

		$tmp3['sisa_label'] =  "\rLebih/Kurang :\r\n";
		$html_noprint .= "<tr><td>".$tmp3['sisa_label'].$tmp3['sisa']."</td></tr>";

		$temp_data['detail'] = $tmp2;
		$temp_data['detailpay'] = $tmp3;
		$temp_data['hutang'] = $hihi;

		// $html_noprint .= '<tr><td style="border:1px dashed white;padding:0"><div style="width:100%;border-bottom: 1px dashed black;"></div></td></tr>';

		$queryKredit = "SELECT 
		s.pembayaran_via as bank,
		sum(sale_total_cost) as total,
		b.persentasi as persentasi 
		 FROM  (".$this->sqlsales().") as s
		INNER JOIN  
		bank as b  on b.nama = s.pembayaran_via
		 where s.bayar <=0  and date(s.date) = '$date'  and inserter = '$username'
		 group by b.id
		  ";
		// listing pembayaran cashless
		$kredit = Yii::app()->db->createCommand($queryKredit)->queryAll();
		if ($totalkeluar >0  ){
			$html_noprint .= "<tr><td><b>DETAIL PENGELUARAN</b></td></tr>";
			$html_noprint .= "<tr><td>".$htmlpengeluaran."</td></tr>";
			$html_noprint .= '<tr><td style="border:1px dashed white;padding:0"><div style="width:100%;border-bottom: 1px dashed black;"></div></td></tr>';
		}



		$html_noprint .= '<tr><td style="border:1px dashed white;padding:0"><div style="width:100%;border-bottom: 1px dashed black;"></div></td></tr>';		
		$html_noprint .= "<tr><td><b>RINCIAN TRANSAKSI CASHLESS</b></td></tr>";	
		$html_noprint .= '<tr><td style="border:1px dashed white;padding:0"><div style="width:100%;border-bottom: 1px dashed black;"></div></td></tr>';	
		
		$total_kredit = 0;
		foreach ($kredit as $key => $value) {
			$kredit_bagihasil = $value['total']*($persen/100);
			$x = str_pad($value['bank'],19," ",STR_PAD_RIGHT)." : ".number_format($value['total']);
			$temp_data['summary_totalgratis'][] = $x;
			// $temp_data['summary_all'] =    		   "TOTAL OMZET        : ".number_format($total_kredit+$cash);	
			$html_noprint .= "<tr><td>".$x."</td></tr>";
			$total_kredit+=$kredit_bagihasil;
		}



		$html_noprint .= '<tr><td style="border:1px dashed white;padding:0"><div style="width:100%;border-bottom: 1px dashed black;"></div></td></tr>';		
		$html_noprint .= "<tr><td><b>RINCIAN PRODUK PENJUALAN</b></td></tr>";	
		$html_noprint .= '<tr><td style="border:1px dashed white;padding:0"><div style="width:100%;border-bottom: 1px dashed black;"></div></td></tr>';

		// $tmp_hutang['']
		
		$pjg_ket = $total_margin - 20;
				
		$jml_item = 18;
		
		//for($c=0;$c<$jml_item;$c++)
		$temp = array();
		$total_exist = 0;
		$tenan_tmp = "";
		// var_dump(count($data));
		foreach ($data as $rows)
		{
			$tenan = $rows['nm'];

			// $ctgr = "";
			// echo $rows['category']." - ".$ctgr."<BR>";
			$tmp4 = array();




			if($rows['nama_motif']!=$ctgr){
				$ctgr = $rows['nama_motif'];
				// $tenan = $rows['nm'];

				$wCtgr = "KATEGORI  : ".strtoupper($rows['nama_motif'])."\r";
				// if ($rows['kode_outlet']!="27"){ // jika ubukan paket
					$tmp4['dept'] = $wCtgr;
					$tmp4['totalpertenan'] = number_format( $arrayGetTotal[$rows['nama_motif']] ); 
					$tmp4['totalperkategori'] = "";
				// }else{
				// 	$mPaket = Paket::model()->

				// }
				
				$tmp4['pembatas'] = $this->spasi($total_margin, "_");
				$html_noprint .= "<tr><td>".$tmp4['dept']."</td></tr>";
				$html_noprint .= "<tr><td>".$tmp4['totalpertenan']."</td></tr>";

			}else{
				$tmp4['totalperkategori'] = number_format( $total234); 
				$total234 = 0;

				$tmp4['dept'] = "";
				$tmp4['pembatas'] = "";
				$html_noprint .= "<tr><td>".$tmp4['dept']."</td></tr>";
				$html_noprint .= "<tr><td>".$tmp4['totalpertenan']."</td></tr>";

				// $html_noprint .= "<tr><td>".$ctgr.$wCtgr."</td></tr>";
				// $tmp4['totalpertenan'] = 'tidak ada  judul';
				// $total_exist = 0;
			}
			$tmp3 = $tmp4;
			// $tmp5 = array();
			// $ttp += ($rows['harga']*$rows['qp']);
			$table = " - ".substr(strtoupper($rows['item_name']) ,0,30);
			// $total = $rows['harga']*$rows['qp'];
			// $total
			$total234+= $rows['harga']*$rows['qp'];
			$table .= " \n ".strtoupper($rows['harga'])." x ".strtoupper( $rows['qp'])."  = ";
			// $table .= "\t ".strtoupper($rows['qp'])." Item :".$this->set_spacebar(number_format($rows['price']),$pjg_ket, "kanan"). "\n";
			
			// $total_exist += $rows['price'];

			$totalItems += $rows['qp'];
			$totalPrice += $rows['price'];			
			$tmp3['table'] = $table;
			$tmp3['harga'] = number_format($rows['price']);
			$html_noprint .= "<tr><td>".$tmp3['table']."</td></tr>";
			$html_noprint .= "<tr><td>".$tmp3['harga']."</td></tr>";


			$temp[] = $tmp3;
		}
		$html_noprint .= '<tr><td style="border:1px dashed white;padding:0"><div style="width:100%;border-bottom: 1px dashed black;"></div></td></tr>';		

		$temp_data['total'] = "\t ".$totalItems." PRODUK TERJUAL:\t\t ".number_format($totalPrice)."\r\n";
		$html_noprint .= "<tr><td> ".$temp_data['total']."</td></tr>";


		// $html_noprint .= "<tr><td>".$temp_data['pembatas']."</td></tr>";
		// $html_noprint .= "<tr><td>COMPLIMENT</td></tr>";
		// $html_noprint .= "<tr><td>".$temp_data['pembatas']."</td></tr>";

		$temp_data['gratis'] = array();
		// var_dump($data_gratis);
		$data_gratis = array();
 		foreach ($data_gratis as $key => $value) {
			// $ttp += ($value['harga']*$value['qp']);
			$nama = " - ".substr(strtoupper($value['item_name']) ,0,30);
			$totals = $value['harga']*$value['jumlah'];
			$total = "  ".strtoupper($value['harga'])." x ".strtoupper( $value['jumlah'])."  = ". ($totals);
			$array = array("nama"=>$nama,"total"=>$total);
			$html_noprint .= "<tr><td>".$array."</td></tr>";

			$temp_data['gratis'][] = $array;
			$totalgratis+=$totals;
			$totalgratis_qty+=$value['jumlah'];

		}
		$temp_data['total_gratis'] = "ITEM($totalgratis_qty) : ".number_format($totalgratis)."\r\n";
		// $html_noprint .= "<tr><td>".$temp_data['total_gratis']."</td></tr>";


		
			
		$dataPengeluaranTotalKeluar = 0;
		$temp_data['pengeluaran'] = array();
		foreach ($data_pengeluaran as $key => $value) {
			$nama = " - ".substr(strtoupper($value['jenis_pengeluaran']) ,0,30);
			$totals = number_format($value['total']);
			$total =  ($totals);
			$array = array("nama"=>$nama,"total"=>$total);
			$html_noprint .= "<tr><td>".$nama."  :  ".$total."</td></tr>";

			$temp_data['pengeluaran'][] = $array;
			// $totalkeluar+=$value['total'];
			$dataPengeluaranTotalKeluar+=$value['total'];
		}

	
		$temp_data['total_pengeluaran'] = "TOTAL : ".number_format($dataPengeluaranTotalKeluar)."\r\n";
		// $html_noprint .= "<tr><td>".$temp_data['total_pengeluaran']."</td></tr>";

		// $html_noprint .= "<tr><td>".$temp_data['pembatas']."</td></tr>";
		$temp_data['nilaisaldoawal'] =         "SALDO AWAL          : ".number_format($awal);	
		$temp_data['summary_totalpenjualan'] = "TOTAL CASH          : ".number_format($cash);	
		// $html_noprint .= "<tr><td>".$temp_data['summary_totalpenjualan']."</td></tr>";
		$temp_data['summary_totalgratis'] = array();
		// $total_kredit = 0;
		// foreach ($kredit as $key => $value) {
		// 	$persen = 100-$value['persentasi'];
		// 	$persen2 = 100-$persen;
		// 	$kredit_bagihasil = $value['total']*($persen/100);
		// 	$x = str_pad($value['bank'],19," ",STR_PAD_RIGHT)." : ".number_format($value['total'])." - ".$persen2."% = ".number_format($kredit_bagihasil);
		// 	$temp_data['summary_totalgratis'][] = $x;
		// 	// $temp_data['summary_all'] =    		   "TOTAL OMZET        : ".number_format($total_kredit+$cash);	
		// 	$html_noprint .= "<tr><td>".$x."</td></tr>";
		// 	$total_kredit+=$kredit_bagihasil;
		// }

		// $temp_data['summary_totalgratis'] =    "KREDIT             : ".number_format($total_kredit);	

		
		$temp_data['summary_pengeluaran'] =    "TOTAL PENGELUARAN   : (".number_format($totalkeluar).")";	
		if ($totalkeluar >0  ){
			$html_noprint .= "<tr><td>".$temp_data['summary_pengeluaran']."</td></tr>";
			$html_noprint .= '<tr><td style="border:1px dashed white;padding:0"><div style="width:100%;border-bottom: 1px dashed black;"></div></td></tr>';
		}
		$temp_data['summary_coh']         =     "CASH ON HAND       : ".number_format($cash-$totalkeluar+$awal);	
		// $html_noprint .= "<tr><td>".$temp_data['summary_coh']."</td></tr>";
		

		$temp_data['summary_all']         =     "TOTAL OMZET         : ".number_format($total_kredit+$cash);	
		// $html_noprint .= "<tr><td>".$temp_data['summary_all']."</td></tr>";

		$temp_data['detail2'] = $temp;
		
		$temp_data['pembatas'] = $this->spasi($total_margin, "_");
		



		$temp_data['footer'] = $this->set_spacebar($this->slg, $total_margin, "tengah") . "\r\n";
		$temp_data['footer2'] =  $this->set_spacebar("(c) ".$this->comp." - ".date("Y"), $total_margin, "tengah") . "\r\n";
		
		
		if ($noprint==""){
			echo json_encode($temp_data);
			// echo "<pre>";
			// print_r($temp_data);
			// echo "</pre>";
		}else{
			$this->renderPartial('cetakrekap', array('html_noprint'=>$html_noprint) ) ;
			// echo $html_noprint;
		}
	}

	// function actionCetakRekap(){
	// 	$branch_id = Yii::app()->user->branch();
	// 	$date = $_GET['tanggal_rekap'];
		
	// 	if (isset($_REQUEST['inserter'])){
	// 		$username = $_REQUEST['inserter'];
	// 		$username = Users::model()->findByPk($username)->username;
	// 	}else{
	// 		$username = Yii::app()->user->name;
	// 	}
		
		
	// 	$user = Users::model()->find('username=:un',array(':un'=>$username));
	// 	$idk = $user->level;
		

	// if (isset($_REQUEST['uangmasuk'])){//jika closing
	// 	$s = new Setor;
	// 	$s->user_id = $user->id;
	// 	$s->tanggal = $_REQUEST['tanggal_rekap'];
	// 	$s->total =$_REQUEST['uangmasuk'];
	// 	$s->save(); 
	// }
	// 	$this->comp = Branch::model()->findByPk($branch_id)->branch_name;
	// 	$this->adr =  Branch::model()->findByPk($branch_id)->address;
	// 	$this->tlp =  Branch::model()->findByPk($branch_id)->telp;
	// 	$this->slg =  Branch::model()->findByPk($branch_id)->slogan;
		
	// 	// if ($idk==6){
	// 		$filter =  " and sales.inserter = '$user->id' ";
	// 		$filter2 =  " and s.inserter = '$user->id' ";
	// 	// }else{
	// 	// 	$filter =  " ";
	// 	// 	$filter2 =  " ";
	// 	// }
	// 	$total_adt_cost = "
	// 	select sum(adt_cost) adt_cost from sales where 1=1 and status= 1  
	// 	and date(date)='$date' 
	//     $filter  
	// 	";		
	// 	// var_dump($filter);
	// 	$total_adt_cost =  Yii::app()->db->createCommand($total_adt_cost)->queryRow();
	// 	// print_r($total_adt_cost);
	// 	// exit;
	// 	$data1 = Yii::app()->db->createCommand()
	// 	->select("
	// 	SUM( si.item_tax ) as tax, 	
	// 	SUM( si.item_service) as svc, 	
	// 	sum(si.item_price*si.quantity_purchased) as cost, 
	// 	sum(si.item_discount*(si.item_price*si.quantity_purchased)/100)  as tot_disc,
	// 	sum((
	// 		(si.item_price*si.quantity_purchased)+
	// 		(si.item_tax)+
	// 		(si.item_service)-
	// 		(si.item_discount*(si.item_price*si.quantity_purchased)/100))) as stc 
	// 	,s.id
	// 	,s.date
	// 	")
	// 	->from("sales s,sales_items si , items i")
	// 	->where("
	// 	i.id = si.item_id
	// 	and 
	// 	date(s.date)=:date 
	// 	and  s.status=1 
	// 	and s.id = si.sale_id
		
	// 	$filter2 ", array(':date' => $date))
	// 	->queryAll();
		
		
	// 	$sqlGetTotal = "SELECT  
	// 	outlet.kode_outlet ,
	// 	items.category_id ,
	// 	SUM(sales_items.item_price * IF(sales_items.item_price<0,-quantity_purchased,quantity_purchased))   total
	// 	FROM sales_items
	// 	JOIN sales ON sales.id=sales_items.sale_id
	// 	JOIN items ON items.id=sales_items.item_id
	// 	JOIN outlet ON outlet.kode_outlet = items.kode_outlet
	// 	WHERE DATE(DATE) = '$date' AND sales.status = 1
		
	// 	$filter
	// 	GROUP BY items.category_id

		
		
	// 	ORDER BY outlet.kode_outlet ";
	// 	// var_dump($filter);
	// 	// var_dump($sqlGetTotal);

	// 	$arrayGetTotal = array();
	// 	$getTotal = Yii::app()->db->createCommand($sqlGetTotal)->queryAll();
	// 	foreach ($getTotal as $gt) {
	// 		$arrayGetTotal[$gt[category_id]] = $gt[total];
	// 	}
		
	// 	// if ($idk != 2){ //jika bukan admin
	// 		$filterX = " and inserter = $user->id ";
	// 	// }else{
	// 	// 	$filterX = "  ";				
	// 	// }
	// 	$payment = Yii::app()->db->createCommand()
	// 	->select('
	// 	 sum(cash)cash,sum(edc_bca)edc_bca,sum(edc_niaga)edc_niaga,sum(compliment)compliment,sum(dll)dll,sum(voucher)voucher , 
	// 	 SUM(cash+edc_bca+edc_niaga+compliment+dll) total
	// 	')
	// 	->from('sales s,sales_payment ')
	// 	->where("
	// 	date(s.date)=:date 
	// 	and  s.status=1 
	// 	and s.id = sales_payment.id $filterX", array(':date' => $date))
	// 	->queryAll();
	
	// 	$data = Yii::app()->db->createCommand()
	// 	->select('
	// 		m.nama nama_motif,
	// 		c.category nm,
	// 		outlet.kode_outlet kode_outlet,items.item_name item_name,item_price harga,sales_items.item_price * sum(if(sales_items.item_price<0,-quantity_purchased,quantity_purchased)) price,sum(if(sales_items.item_price<0,-quantity_purchased,quantity_purchased)) qp')
	// 	->from('sales_items')
	// 	->join('sales', 'sales.id=sales_items.sale_id')
	// 	->join('items', 'items.id=sales_items.item_id')
	// 	->join('outlet', 'outlet.kode_outlet = items.kode_outlet')
	// 	->join('categories c', 'c.id = items.category_id')
	// 	->leftjoin('motif m', 'm.id = items.motif')
	// 	// ->join('categor', 'outlet.kode_outlet = items.kode_outlet')
	// 	// ->where('date(date=:date)', array(':date'=>date('2013-03-05')))
	// 	->where("date(date) = '".$date."' and sales.status = 1  $filterX")
	// 	->group('item_id,sales_items.item_price')
	// 	->order('items.motif')	
	// 	->queryAll();


	// 	$query_gratis = "
	// 	SELECT
	// 		categories.category nm,
	// 		items.item_name item_name,
	// 		bkd.harga harga,
	// 		bkd.jumlah jumlah 
	// 	FROM
	// 		barangkeluar_detail bkd
	// 	INNER JOIN barangkeluar bk ON bk.id = bkd.head_id
	// 	INNER JOIN items ON items.id = bkd.kode
	// 	INNER JOIN items_satuan iss ON iss.id  = bkd.satuan and items.id = iss.item_id
	// 	INNER JOIN categories ON categories.id = items.category_id
	// 	WHERE bk.user = '$user->username'

	// 		and date(tanggal)= '$date'
	// 		and jenis_keluar='gratis (compliment)'
	// 	GROUP BY
	// 		bkd.kode,bkd.harga
	// 	ORDER BY categories.category asc
	// 	";
	// 	// echo $query_gratis;
	// 	$data_gratis = Yii::app()->db->createCommand($query_gratis)->queryAll();
	// 	// echo "<pre>";
	// 	// print_r($data_gratis);
	// 	// echo "</pre>";

	// 	$query_pengeluaran  = "select * from pengeluaran where 1=1 and date(tanggal)='$date' and user='$user->username'order by tanggal desc";
	// 	$data_pengeluaran = Yii::app()->db->createCommand($query_pengeluaran)->queryAll();
		


		
	// 	$pembatas = 20;
	// 	$model = $_GET['data'];
	// 	$detail = $_GET['data_detail'];
		
 //        $total_margin = 40;
 //        $temp_data = array();
 //        $temp_data['logo'] = $this->comp;
 //        $temp_data['alamat'] = $this->adr;
 //        $temp_data['no_telp'] = "Telp. ".$this->tlp;
 //        $temp_data['trx_tgl'] = date('d  M  Y ',strtotime($date));
 //        $pjg_ket = $total_margin - 13;
		
		
	// 	$temp_data['kasir']=   "Kasir          : " .$username;

 //        $temp_data['tgl_cetak']=   "Tanggal Cetak  : " . date('d-M-Y H:i:s') . "\r";

		
		
	// 	$temp_data['pembatas'] = $this->spasi($total_margin, "-");

	// 	$tmp3 = array();
	// 	$tmp = array();
	// 	$kosong = array();
	// 	$tmpt = array();
	// 	// echo "<pre>";
	// 	// print_r($data1);
	// 	// echo "</pre>";
	// 	// var_dump($payment);
	// 	foreach($payment as $dt1){
	// 		$tmpt['total'] =     "Total Payment :";
	// 		$tmpt['totalvalue'] = "\r".number_format($dt1['total'])."\r\n";
			
	// 		$tmpt['comp'] =     "Compliment    :";
	// 		$tmpt['compvalue'] = "\r".number_format($dt1['compliment'])."\r\n";
			
	// 		$tmpt['netcash'] =  "Net Cash      :";
	// 		$tmpt['netcashvalue'] = "\r".number_format($dt1['cash'])."\r\n";

	// 		$tmpt['bca'] =      "EDC BNI       :";
	// 		$tmpt['bcavalue'] = "\r".number_format($dt1['edc_bca'])."\r\n";

	// 		$tmpt['niaga'] =                  "BNI YAP       :";
	// 		$tmpt['niagavalue'] = "\r".number_format($dt1['edc_niaga'])."\r\n";
			
	// 		$tmpt['voucher'] =  "Voucher        :";
	// 		$voucher = $dt1['voucher'];
	// 		$uangcash = $dt1['cash'];
	// 		$tmpt['vouchervalue'] = "\r(".number_format($voucher).")\r\n";
			

	// 		$tmpt['total_pembayaran_label'] = "Total         :";
	// 		$tmpt['total_pembayaran'] = "\r".number_format($dt1['cash'] + $dt1['edc_bca'] + $dt1['edc_niaga'])."\r\n";


	// 		$tmpt['uangmasuk'] =              "Uang Fisik    :";


	// 		$tmpt['sisa_label'] =             "Sisa          :";
			
	// 		$tmp3 = $tmpt;
	// 		//$username = Yii::app()->user->name;
	// 		//$uss = Users::model()->find('username=:un',array(':un'=>$username));
			
		


	// 		// $tmpt['dll'] =    "Pending       :";
	// 		// $tmpt['dllvalue'] = "\r".number_format($dt1['dll'])."\r\n";
			
	// 	}
		
	// 	// var_dump($tmp3);
	// 	foreach($data1 as $dt1){
	// 		//gross sales
	// 		// $net_sales = $dt1['cost'] - $dt1['tot_disc']-$dt1['tax'];
	// 		//$net_sales = $dt1['cost'];
	// 		$tmp['gross'] ="Total Bersih   :";
	// 		$tmp['grossvalue'] = "\r".number_format($dt1['stc'])."\r\n";
			
	// 		$tmp['net'] =  "Total Kotor    :";
	// 		$tmp['netvalue'] = "\r".number_format($dt1['cost'])."\r\n";

	// 		$tmp['disc'] = "Discount       :";
	// 		$tmp['discvalue'] = "("."\r".number_format($dt1['tot_disc']).")\r\n";

	// 		$tmp['svc'] = "Service        :";
	// 		$tmp['svcvalue'] = "\r".number_format($dt1['svc'])."\r\n";
			
	// 		$tmp['tax'] =      "Tax            :";
	// 		$tmp['taxvalue'] = "\r".number_format($dt1['tax'])."\r\n";

	// 		$tmp['adt_cost'] =    "Biaya Bank     :";
	// 		$tmp['adt_costvalue'] = "\r".number_format($total_adt_cost['adt_cost'])."\r\n";


	// 		$final =  ($dt1['stc']-$voucher)+$total_adt_cost['adt_cost'];
	// 		$tmp['total_final'] = "Total Akhir    :";
	// 		$tmp['total_final_value'] = "\r".number_format($final)."\r\n";	
	// 		$tmp2 = $tmp;
			
	// 		//------------------------------------------------------------
	// 	}	

	// 	$cek = Setor::model()->count(" user_id = '$user->id' and date(tanggal)='$date' ");
	// 	if ($cek==0){	
	// 		$masuk = $_REQUEST['uangmasuk'];
	// 		$tmp3['uangmasukvalue'] = "\r".number_format($_REQUEST['uangmasuk'])."\r\n";
	// 	}else{
	// 		$model = Setor::model()->find(" user_id = '$user->id' and date(tanggal)='$date' ");
	// 		$masuk = $model->total;
	// 		$tmp3['uangmasukvalue'] = "\r".number_format($model->total)."\r\n";
	// 	}
		
	// 	// var_dump($masuk);
	// 	// exit;
	// 	// if ($model->total==""){
	// 		// $s = 0;
	// 	// }else{
	// 		// $s = $model->total;
	// 	// }
		
	// 	$tmp3['sisa'] =  "\r".number_format($masuk-$uangcash)."\r\n";
	// 	// var_dump($tmp3['sisa']);

	// 	$tmpt['pembayaran_penjualan_label'] = "Total Pendapatan Cash       :\n";
	// 	$tmpt['pembayaran_penjualan'] =  number_format($model->total);
	
		
	// 	$temp_data['detail'] = $tmp2;
	// 	$temp_data['detailpay'] = $tmp3;
	// 	$temp_data['hutang'] = $hihi;
		
	// 	// $tmp_hutang['']
		
	// 	$pjg_ket = $total_margin - 20;
				
	// 	$jml_item = 18;
		
	// 	//for($c=0;$c<$jml_item;$c++)
	// 	$temp = array();
	// 	$total_exist = 0;
	// 	$tenan_tmp = "";
	// 	foreach ($data as $rows)
	// 	{
	// 		$tenan = $rows['nm'];

	// 		// $ctgr = "";
	// 		// echo $rows['category']." - ".$ctgr."<BR>";
	// 		$tmp4 = array();




	// 		if($rows['nama_motif']!=$ctgr){
	// 			$ctgr = $rows['nama_motif'];
	// 			// $tenan = $rows['nm'];
	// 			$wCtgr = "KATEGORI  : ".strtoupper($rows['nama_motif'])."\r";
	// 			// if ($rows['kode_outlet']!="27"){ // jika ubukan paket
	// 				$tmp4['dept'] = $wCtgr;
	// 				$tmp4['totalpertenan'] = number_format( $arrayGetTotal[$rows['kode_outlet']] ); 
	// 			// }else{
	// 			// 	$mPaket = Paket::model()->

	// 			// }
				
	// 			$tmp4['pembatas'] = $this->spasi($total_margin, "_");
	// 		}else{
	// 			$tmp4['dept'] = "";
	// 			$tmp4['pembatas'] = "";
	// 			// $tmp4['totalpertenan'] = 'tidak ada  judul';
	// 			// $total_exist = 0;
	// 		}
	// 		$tmp3 = $tmp4;
	// 		// $tmp5 = array();
	// 		// $ttp += ($rows['harga']*$rows['qp']);
	// 		$table = " - ".substr(strtoupper($rows['item_name']) ,0,30);
	// 		$table .= " \n ".strtoupper($rows['harga'])." x ".strtoupper( $rows['qp'])."  = ";
	// 		// $table .= "\t ".strtoupper($rows['qp'])." Item :".$this->set_spacebar(number_format($rows['price']),$pjg_ket, "kanan"). "\n";
			
	// 		// $total_exist += $rows['price'];

	// 		$totalItems += $rows['qp'];
	// 		$totalPrice += $rows['price'];			
	// 		$tmp3['table'] = $table;
	// 		$tmp3['harga'] = number_format($rows['price']);

	// 		$temp[] = $tmp3;
	// 	}

	// 	$temp_data['gratis'] = array();
	// 	// var_dump($data_gratis);
 // 		foreach ($data_gratis as $key => $value) {
	// 		// $ttp += ($value['harga']*$value['qp']);
	// 		$nama = " - ".substr(strtoupper($value['item_name']) ,0,30);
	// 		$totals = $value['harga']*$value['jumlah'];
	// 		$total = "  ".strtoupper($value['harga'])." x ".strtoupper( $value['jumlah'])."  = ". ($totals);
	// 		$array = array("nama"=>$nama,"total"=>$total);
	// 		$temp_data['gratis'][] = $array;
	// 		$totalgratis+=$totals;
	// 		$totalgratis_qty+=$value['jumlah'];

	// 	}
	// 	$temp_data['total_gratis'] = " \n\n\n ITEM($totalgratis_qty) : ".number_format($totalgratis)."\r\n";

		// $temp_data['pengeluaran'] = array();
		// foreach ($data_pengeluaran as $key => $value) {
		// 	$nama = " - ".substr(strtoupper($value['jenis_pengeluaran']) ,0,30);
		// 	$totals = number_format($value['total']);
		// 	$total =  ($totals);
		// 	$array = array("nama"=>$nama,"total"=>$total);
		// 	$html_noprint .= "<tr><td>".$nama."  :  ".$total."</td></tr>";

		// 	$temp_data['pengeluaran'][] = $array;
		// 	$totalkeluar+=$value['total'];
		// }

		
	// 	$temp_data['summary_totalpenjualan'] = "TOTAL PENJUALAN    : ".number_format($final );	
	// 	$temp_data['summary_totalgratis'] =    "TOTAL COMPLIMENT   : ".number_format($totalgratis);	
	// 	$temp_data['summary_pengeluaran'] =    "TOTAL PENGELUARAN  : ".number_format($totalkeluar);	
	// 	$temp_data['summary_all'] =    		   "TOTAL KESELURAHAN  : ".number_format($totalkeluar+$totalgratis+$final);	


	// 	// echo "<pre>";
	// 	// print_r($hihi);
	// 	// echo "</pre>";
		
	// 	$temp_data['detail2'] = $temp;
		
	// 	$temp_data['pembatas'] = $this->spasi($total_margin, "_");
		


	// 	$temp_data['total'] = "\t ".$totalItems." ITEMS\t\t ".number_format($totalPrice)."\r\n";
	// 	$temp_data['footer'] = $this->set_spacebar($this->slg, $total_margin, "tengah") . "\r\n";
	// 	$temp_data['footer2'] =  $this->set_spacebar("(c) ".$this->comp." - ".date("Y"), $total_margin, "tengah") . "\r\n";
		
		
	// 	echo json_encode($temp_data);
	// 	// echo "<pre>";
	// 	// print_r($temp_data);
	// 	// echo "</pre>";
	// }
		
	function actionCetakKeluar($id){
		if (isset($_REQUEST['inserter'])){
			$username = $_REQUEST['inserter'];
			$username = Users::model()->findByPk($username)->username;
		}else{
			$username = Yii::app()->user->name;
		}
		$branch_id = Yii::app()->user->branch();
		$this->comp = Branch::model()->findByPk($branch_id)->branch_name;
		$this->adr =  Branch::model()->findByPk($branch_id)->address;
		$this->tlp =  Branch::model()->findByPk($branch_id)->telp;
	$this->slg =  Branch::model()->findByPk($branch_id)->slogan;
        

		$temp_data['logo'] = $this->comp;
        $temp_data['alamat'] = $this->adr;
        $temp_data['no_telp'] = "Telp. ".$this->tlp;
		
		
		$user = Users::model()->find('username=:un',array(':un'=>$username));
		$query_gratis = "
		SELECT
			bk.user as user,
			b.branch_name as keluar_ke,
			iss.nama_satuan as nama_satuan,
			#categories.category nm,
			m.nama as nm,
			items.item_name item_name,
			bkd.harga harga,
			bkd.jumlah jumlah ,
			bk.tanggal as tgl_keluar,
			bk.jenis_keluar as jenis_keluar
		FROM
			barangkeluar_detail bkd
		INNER JOIN barangkeluar bk ON bk.id = bkd.head_id
		INNER JOIN items ON items.id = bkd.kode
		INNER JOIN items_satuan iss ON iss.id = bkd.satuan 
		LEFT JOIN categories ON categories.id = items.category_id
		LEFT JOIN motif m ON m.id = items.motif
		LEFT JOIN branch as b on b.id = bk.keluar_ke 
		WHERE bk.id = '$id'
		GROUP BY
			bkd.kode,bkd.harga
		ORDER BY m.id asc
		";
		$data_gratis = Yii::app()->db->createCommand($query_gratis)->queryAll();
		
		$temp_data['gratis'] = array();
		// $temp_data['user'] = array();
		// var_dump($data_gratis);
 		$no = 1;
 		foreach ($data_gratis as $key => $value) {
			// $ttp += ($value['harga']*$value['qp']);
			$nama = substr(strtoupper($value['item_name']."(".$value[nama_satuan].")") ,0,30);
			$totals = $value['harga']*$value['jumlah'];
			// $total = "  ".number_format($value['harga'])." x ".strtoupper( $value['jumlah'])."  = ". number_format($totals);
			$total = "Jumlah : ".$value['jumlah'];
			$ctgr = "".$value['nm'];
			$array = array("category"=>$ctgr,"nama"=>$nama,"total"=>$total);
			$temp_data['gratis'][] = $array;
			$temp_data['tgl_keluar'] = $value['tgl_keluar'];
			$temp_data['jenis_keluar'] = $value['jenis_keluar'];
			$temp_data['keluar_ke'] = $value['keluar_ke'];
			$temp_data['user'] = $value['user'];
			// $temp_data['nama_satuan'][] = $value['nama_satuan'];
			$totalgratis+=$totals;
			$totalgratis_qty+=$value['jumlah'];
			$no++;
		}
		$temp_data['total_gratis'] = "ITEM($totalgratis_qty) : ".number_format($totalgratis)."\r\n";

		$temp_data['pembatas'] = "________________________________________";	
		$temp_data['summary_totalpenjualan'] = "TOTAL PENJUALAN    : ".number_format($final );	
		$temp_data['summary_totalgratis'] =    "TOTAL COMPLIMENT   : ".number_format($totalgratis);	
		$temp_data['summary_pengeluaran'] =    "TOTAL PENGELUARAN  : ".number_format($totalkeluar);	
		$temp_data['summary_all'] =    		   "TOTAL KESELURAHAN  : ".number_format($totalkeluar+$totalgratis+$final);	


		echo json_encode($temp_data);
		// echo "<pre>";
		// print_r($temp_data);
		// echo "</pre>";
	}
	function actionCetakMasuk($id){
		if (isset($_REQUEST['inserter'])){
			$username = $_REQUEST['inserter'];
			$username = Users::model()->findByPk($username)->username;
		}else{
			$username = Yii::app()->user->name;
		}
		$branch_id = Yii::app()->user->branch();
		$this->comp = Branch::model()->findByPk($branch_id)->branch_name;
		$this->adr =  Branch::model()->findByPk($branch_id)->address;
		$this->tlp =  Branch::model()->findByPk($branch_id)->telp;
		$this->slg =  Branch::model()->findByPk($branch_id)->slogan;
        

		$temp_data['logo'] = $this->comp;
        $temp_data['alamat'] = $this->adr;
        $temp_data['no_telp'] = "Telp. ".$this->tlp;
		
		
		$user = Users::model()->find('username=:un',array(':un'=>$username));
		$query_gratis = "
		SELECT
			bk.user as user,
			#b.branch_name as keluar_ke,
			iss.nama_satuan as nama_satuan,
			#categories.category nm,
			m.nama as nm,
			items.item_name item_name,
			bkd.harga harga,
			bkd.jumlah jumlah ,
			bk.tanggal as tgl_keluar,
			bk.jenis as jenis_keluar
		FROM
			barangmasuk_detail bkd
		INNER JOIN barangmasuk bk ON bk.id = bkd.head_id
		INNER JOIN items ON items.id = bkd.kode
		INNER JOIN items_satuan iss ON iss.id = bkd.satuan 
		LEFT JOIN categories ON categories.id = items.category_id
		LEFT JOIN motif m ON m.id = items.motif
		#LEFT JOIN branch as b on b.id = bk.keluar_ke 
		WHERE bk.id = '$id'
		GROUP BY
			bkd.kode,bkd.harga
		ORDER BY m.id asc
		";
		$data_gratis = Yii::app()->db->createCommand($query_gratis)->queryAll();
		
		$temp_data['gratis'] = array();
		// var_dump($data_gratis);
 		$no = 1;
 		foreach ($data_gratis as $key => $value) {
			// $ttp += ($value['harga']*$value['qp']);
			$nama = substr(strtoupper($value['item_name']."(".$value[nama_satuan].")") ,0,30);
			$totals = $value['harga']*$value['jumlah'];
			$total = "  ".number_format($value['harga'])." x ".strtoupper( $value['jumlah'])."  = ". number_format($totals);

			$ctgr = "".$value['nm'];
			$array = array("category"=>$ctgr,"nama"=>$nama,"total"=>$total);
			$temp_data['gratis'][] = $array;
			$temp_data['tgl_keluar'] = $value['tgl_keluar'];
			$temp_data['jenis_keluar'] = $value['jenis_keluar'];
			$temp_data['keluar_ke'] = $value['keluar_ke'];
			$temp_data['user'] = $value['user'];

			// $temp_data['nama_satuan'][] = $value['nama_satuan'];
			$totalgratis+=$totals;
			$totalgratis_qty+=$value['jumlah'];
			$no++;
		}
		$temp_data['total_gratis'] = "ITEM($totalgratis_qty) : ".number_format($totalgratis)."\r\n";

		$temp_data['pembatas'] = "________________________________________";	
		$temp_data['summary_totalpenjualan'] = "TOTAL PENJUALAN    : ".number_format($final );	
		$temp_data['summary_totalgratis'] =    "TOTAL COMPLIMENT   : ".number_format($totalgratis);	
		$temp_data['summary_pengeluaran'] =    "TOTAL PENGELUARAN  : ".number_format($totalkeluar);	
		$temp_data['summary_all'] =    		   "TOTAL KESELURAHAN  : ".number_format($totalkeluar+$totalgratis+$final);	


		echo json_encode($temp_data);
		// echo "<pre>";
		// print_r($temp_data);
		// echo "</pre>";
	}
	
		function actionCetakStok($id){
		if (isset($_REQUEST['inserter'])){
			$username = $_REQUEST['inserter'];
			$username = Users::model()->findByPk($username)->username;
		}else{
			$username = Yii::app()->user->name;
		}
		$branch_id = Yii::app()->user->branch();
		$this->comp = Branch::model()->findByPk($branch_id)->branch_name;
		$this->adr =  Branch::model()->findByPk($branch_id)->address;
		$this->tlp =  Branch::model()->findByPk($branch_id)->telp;
		$this->slg =  Branch::model()->findByPk($branch_id)->slogan;
        

		$temp_data['logo'] = $this->comp;
        $temp_data['alamat'] = $this->adr;
        $temp_data['no_telp'] = "Telp. ".$this->tlp;
		
		
		$user = Users::model()->find('username=:un',array(':un'=>$username));
		$query_gratis  = "select 
		iss.nama_satuan as nama_satuan,
		m.nama as nm,
		i.item_name item_name,
		iss.id item_satuan_id,
		i.id id

		 from items i inner join items_satuan iss
		  on iss.item_id = i.id and i.is_stockable = 1
		  inner join categories as c on c.id = i.category_id
		  left join motif m on m.category_id = c.id and m.id = i.motif
		  where 1=1 and i.hapus=0 		 
		  group by iss.id
		  order by c.category, m.id, item_name  asc

		  ";
		// $query_gratis = "
		// SELECT
		// 	iss.nama_satuan as nama_satuan,
		// 	m.nama as nm,
		// 	items.item_name item_name,
		// 	iss.id item_satuan_id,
		// 	items.id id
		// FROM
		// items 
		// INNER JOIN items_satuan iss ON iss.item_id = items.id
		// INNER JOIN categories ON categories.id = items.category_id
		// LEFT JOIN motif m ON m.id = items.motif
		// where 
		// items.is_stockable = 1
		// group by iss.id
		// order by categories.category, m.id  asc

		// ";
		$data_gratis = Yii::app()->db->createCommand($query_gratis)->queryAll();
		// echo "<pre>";
		// print_r($data_gratis);
		// echo "</pre>";

		
		$temp_data['gratis'] = array();
		// var_dump($data_gratis);
 		$no = 1;
 		foreach ($data_gratis as $key => $value) {
			// $ttp += ($value['harga']*$value['qp']);
			$nama = substr(strtoupper($value['item_name']."(".$value[nama_satuan].")") ,0,30);
			$stok = ItemsController::getStok($value['id'],$value['item_satuan_id'],$branch_id );
			$hargaa = 0;
			$totals = $hargaa*$stok;
			$total = " Stok : ".strtoupper( $stok)." ";

			$ctgr = "".$value['nm'];
			$array = array("category"=>$ctgr,"nama"=>$nama,"total"=>$total);
			$temp_data['gratis'][] = $array;
			$temp_data['tgl_keluar'] = date("d M Y ");
			$temp_data['jenis_keluar'] = "Laporan Stok";
			$temp_data['keluar_ke'] = "-";
			$temp_data['user'] =$username ;

			// $temp_data['nama_satuan'][] = $value['nama_satuan'];
			$totalgratis+=$totals;
			$totalgratis_qty+=$stok;
			$no++;
		}
		$temp_data['total_gratis'] = "ITEM($totalgratis_qty) : ".number_format($totalgratis)."\r\n";

		$temp_data['pembatas'] = "________________________________________";	
		$temp_data['summary_totalpenjualan'] = "TOTAL PENJUALAN    : ".number_format($final );	
		$temp_data['summary_totalgratis'] =    "TOTAL COMPLIMENT   : ".number_format($totalgratis);	
		$temp_data['summary_pengeluaran'] =    "TOTAL PENGELUARAN  : ".number_format($totalkeluar);	
		$temp_data['summary_all'] =    		   "TOTAL KESELURAHAN  : ".number_format($totalkeluar+$totalgratis+$final);	


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

 	 public function actionTransaksiHapus(){
    	$this->layout = "backend";
		$this->render('transaksihapus',array(
		));
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


	public function actionAdminJSON() {
		// echo "<pre>";
		// print_r($_REQUEST);
		// exit;



	    $draw                   = $_REQUEST["draw"];//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
	    $orderByColumnIndex     = $_REQUEST['order'][0]['column'];// index of the sorting column (0 index based - i.e. 0 is the first record)
	   	if ($_REQUEST['columns'][$orderByColumnIndex]['data']=="0") {
			$orderBy = "id";
	   	}else{
	    	$orderBy                = $_REQUEST['columns'][$orderByColumnIndex]['data'];//Get name of the sorting column from its index
	   	}
	    $orderType              = $_REQUEST['order'][0]['dir']; // ASC or DESC
	    if (isset($_REQUEST['start'])){	
		    $start                  = $_REQUEST["start"];//Paging first record indicator.
	    }else{
		    $start                  = 0;
	    }

	    if (isset($_REQUEST['length'])){	
	    $length                 = $_REQUEST['length'];//Number of records that the table can display in the current draw
	    }else{
	    	$length = 0;
	    }
	    /* END of POST variables */

		$username = Yii::app()->user->name;
		$user = Users::model()->find('username=:un',array(':un'=>$username));
		$idk = $user->level; 

	

		if ($_REQUEST['month']){
			$day2 = $_REQUEST['day'];
			$month = $_REQUEST['month'];
			$year = $_REQUEST['year'];
		}else{
			$day2 = intval(Date('d'));
			$month = intval(Date('m'));
			$year = intval(Date('Y'));
		}
		
			if($idk != 2) //jika kasir
			$filter = " and inserter = '$user->username'";
			else
			$filter = "";
			// $filter = " ";

			if(isset($_REQUEST['status'])){
			
			if ($_REQUEST['status']!='semua'){		
				$this->status_bayar = "  having sb = '$_REQUEST[status]'";
			}else{
				$this->status_bayar = "";
			}

			}else{
			$this->status_bayar = "";
			}
		
		$table = $this->sqlSales();
		// echo $table;
		// exit;
		$cabang = $_REQUEST['cabang'];
		if (!empty($cabang)){
			$where_branch = " and branch='$cabang'";
		}else{
			if (Yii::app()->user->level() === "1"){
				$bcdefault = Yii::app()->user->branch();
				$where_branch = " and branch='$bcdefault'";
			}else{
				$where_branch = " ";
			}
		}

		// echo $table;
		// exit;

		$sql = "  SELECT * FROM ($table) AS  D 
		where month(D.date)=$month and year(D.date)=$year and day(D.date)=$day2 $where_branch 
			$filter
		group by D.id
		$this->status_bayar

		order by D.date asc
		";


		// echo $sql ;
		// exit;
	    $recordsTotal = count(Yii::app()->db->createCommand($sql)->queryAll());
	      if(!empty($_REQUEST['search']['value'])){

	        for($i=0 ; $i<count($_REQUEST['columns']);$i++){
	        	if ($_REQUEST['columns'][$i]['searchable']=="true"){
					echo "123";

		            $column     =   $_REQUEST['columns'][$i]['name'];//we get the name of each column using its index from POST request
					$where[]    =   "$column like '%".$_REQUEST['columns'][$i]['search']['value']."%'";
					// $where[]    =   "$column like '%".$_REQUEST['search']['value']."%'";
	        	}else{
	        		// echo "masuk";
	        	}
	        }
	        $where = "WHERE ".implode(" OR " , $where);// id like '%searchValue%' or name like '%searchValue%' ....
	        /* End WHERE */

	        // $sql = sprintf("SELECT * FROM %s %s %s", $query , $where, " AND CREATED_BY='".$uid."'");//Search query without limit clause (No pagination)
            $sql = "SELECT * FROM ($sql) as d $where  limit  $start,$length  ";
			// echo '123';
	        $recordsFiltered = count($data);//Count of search result
    	}
    	else {
			// echo '456';
         $sql = "SELECT * FROM ($sql) as d  ORDER BY $orderBy $orderType limit  $start,$length ";
         $data =  Yii::app()->db->createCommand($sql)->queryAll();
         $recordsFiltered = $recordsTotal;
    	}

    	 $response = array(
	         "draw"             => intval($draw),
	         "recordsTotal"     => $recordsTotal,
	         "recordsFiltered"  => $recordsFiltered,
	         "data"             => $data
        );

    	
        echo json_encode($response);
        exit;
      }


	
}
