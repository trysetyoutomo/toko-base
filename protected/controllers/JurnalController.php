<?php


class JurnalController extends Controller
{

    public $layout='main2';


 
    // public getSaldo(){
    //     $query = "";
	// 	$data_pengeluaran = Yii::app()->db->createCommand($query_pengeluaran)->queryRow();		
    // }    


    // generate jurnal from sales & sales payment table 
    public static function createTransaction($sales,$salesPayment){
        // get logged user id
        $user = Users::model()->find('username=:un',array(':un'=>Yii::app()->user->name));
        $jurnal = new AkuntansiJurnal;
        $jurnal->tipejurnal_id = 1;
        $jurnal->periode_id = 1;
        $jurnal->tanggal_posting = date("Y-m-d H:i:s");
        $jurnal->nomor = self::generateKodeJurnal();
        $jurnal->keterangan = "";
        $jurnal->user_id = $user->id;
        $jurnal->saldo = $sales->sale_total_cost;
        $jurnal->created_at = date("Y-m-d H:i:s");
        if ($jurnal->save()){
            if ($sales->pembayaran_via == "0"){  // payment via cash
                // transaksi jurnal debit
                $jurnalDetail = new AkuntansiJurnalDetail;
                $jurnalDetail->jurnal_id = $jurnal->id;
                $jurnalDetail->akun_id = 1; //cash
                $jurnalDetail->debit = $sales->sale_total_cost;
                $jurnalDetail->kredit = 0;
                $jurnalDetail->saldo = $sales->sale_total_cost;
                $jurnalDetail->created_at = date("Y-m-d H:i:s");
                $jurnalDetail->save();
                 // transaksi jurnal kredit
                $jurnalDetail = new AkuntansiJurnalDetail;
                $jurnalDetail->jurnal_id = $jurnal->id;
                $jurnalDetail->akun_id = 38; //pendapatan penjualan
                $jurnalDetail->debit = 0;
                $jurnalDetail->kredit =  $sales->sale_total_cost;
                $jurnalDetail->saldo = $sales->sale_total_cost;
                $jurnalDetail->created_at = date("Y-m-d H:i:s");
                $jurnalDetail->save();
            }else{ // if non cash
                // transaksi jurnal debit
                $jurnalDetail = new AkuntansiJurnalDetail;
                $jurnalDetail->jurnal_id = $jurnal->id;
                $jurnalDetail->akun_id = 1; //cash
                $jurnalDetail->debit = $sales->sale_total_cost;
                $jurnalDetail->kredit = 0;
                $jurnalDetail->saldo = $sales->sale_total_cost;
                $jurnalDetail->created_at = date("Y-m-d H:i:s");
                $jurnalDetail->save();
                 // transaksi jurnal kredit
                $jurnalDetail = new AkuntansiJurnalDetail;
                $jurnalDetail->jurnal_id = $jurnal->id;
                $jurnalDetail->akun_id = 38; //pendapatan penjualan
                $jurnalDetail->debit = 0;
                $jurnalDetail->kredit = $sales->sale_total_cost;
                $jurnalDetail->saldo = $sales->sale_total_cost;
                $jurnalDetail->created_at = date("Y-m-d H:i:s");
                $jurnalDetail->save();
            }
        }
    }

      public static function generateKodeJurnal() {
       	$store_id = Yii::app()->user->store_id();
   		$store_id2 = str_pad($store_id,3,"0",STR_PAD_LEFT);
	    $kode = $store_id2.'35J';
        $query = "SELECT
				IFNULL(
					CONCAT(
						'$kode',
						LPAD(
							MAX(SUBSTR(nomor, 7, 10)) + 1,
							10,
							'0'
						)
					),
					'{$kode}0000000001'
				) AS urutan
			FROM
				akuntansi_jurnal
				inner join 
				branch b on b.id = akuntansi_jurnal.branch_id
				where store_id = '$store_id'
                 ";
                //  echo $query;
                //  exit;
        $model = Yii::app()->db->createCommand($query)->queryRow();

        return $model['urutan'];
    }

    public function actionIndex(){
		if (isset($_GET['Sales']['date']) and isset($_GET['Sales']['tgl']) ) {
			$tgl = $_GET['Sales']['date'];
			$tgl2 = $_GET['Sales']['tgl'];
		}
		else{
			$tgl = date('Y-m-d',strtotime('-7 days')); 
			//$tgl =  strtotime("+7 day", $tgl);
			$tgl2 = date('Y-m-d'); 
		}
		$this->render('laporan_jurnal',
			array(
				'tgl'=>$tgl,
				'tgl2'=>$tgl2,
			)
		);
	}

}