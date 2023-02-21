<?php


class JurnalController extends Controller
{
    public $layout='main2';

    // generate jurnal from expense feature
    public static function createExpenseTransaction($model){
        $user = Users::model()->find('username=:un',array(':un'=>Yii::app()->user->name));
        $jurnal = new AkuntansiJurnal;
        $jurnal->tipejurnal_id = 1;
        $jurnal->periode_id = 1;
        $jurnal->tanggal_posting = date("Y-m-d H:i:s");
        $jurnal->nomor = self::generateKodeJurnal();
        $jurnal->user_id = $user->id;
        $jurnal->saldo = $model->total;
        $jurnal->created_at = date("Y-m-d H:i:s");
        $jurnal->branch_id =  Yii::app()->user->branch();
        if ($jurnal->save()){
            $pembayaran_via =  $model->pembayaran_via == "0" ? "CASH": $model->pembayaran_via;
            $jurnalDetail = new AkuntansiJurnalDetail;
            $jurnalDetail->jurnal_id = $jurnal->id;
            $jurnalDetail->akun_id = $model->akun_id;
            $jurnalDetail->debit = $model->total;
            $jurnalDetail->kredit = 0;
            $jurnalDetail->saldo = $model->total;
            $jurnalDetail->created_at = date("Y-m-d H:i:s");
            $jurnalDetail->save();
            // transaksi jurnal kredit
            $jurnalDetail = new AkuntansiJurnalDetail;
            $jurnalDetail->jurnal_id = $jurnal->id;
            $jurnalDetail->akun_id = Bank::model()->find("nama = '".$pembayaran_via."'")->akun_id;
            $jurnalDetail->debit = 0;
            $jurnalDetail->kredit =  $model->total;
            $jurnalDetail->saldo = $model->total;
            $jurnalDetail->created_at = date("Y-m-d H:i:s");
            $jurnalDetail->save();
        }
        $jurnal->keterangan = "Transaksi Pengeluaran #{$model->id}";
        $jurnal->jml_detail_jurnal =  count(AkuntansiJurnalDetail::model()->findAll(" jurnal_id = '$jurnal->id' "));
        $jurnal->update();

    }

    public static function createBuyTransaction($model){
        $user = Users::model()->find('username=:un',array(':un'=>Yii::app()->user->name));
        $jurnal = new AkuntansiJurnal;
        $jurnal->tipejurnal_id = 1;
        $jurnal->periode_id = 1;
        $jurnal->tanggal_posting = date("Y-m-d H:i:s");
        $jurnal->nomor = self::generateKodeJurnal();
        $jurnal->keterangan = "Transaksi Pembelian #{$model->kode_trx}";
        $jurnal->user_id = $user->id;
        $jurnal->saldo = $model->grand;
        $jurnal->created_at = date("Y-m-d H:i:s");
        $jurnal->branch_id =  Yii::app()->user->branch();
        $jurnal->masuk_id =  $model->id;
        if ($jurnal->save()){
            // if ($model->kembali < 0){ // credit handling
                self::createBuyLoanTransaction($jurnal,$model);
            // }else{
            //     self::createBuyCashTransaction($jurnal,$model);
            // }
        }
        $jurnal->jml_detail_jurnal =  count(AkuntansiJurnalDetail::model()->findAll(" jurnal_id = '$jurnal->id' "));
        $jurnal->update();
    }

    public static function updateBuyLoanTransaction($model){
        $jurnal = AkuntansiJurnal::model()->find("masuk_id = '$model->id'");
        if ($jurnal){
            AkuntansiJurnalDetail::model()->deleteAllByAttributes(array('masuk_id' => $model->id));
            self::createBuyLoanTransaction($jurnal, $model);
        }
    }

    public static function createBuyLoanTransaction($jurnal,$model){
         $pembayaran_via =  $model->metode_pembayaran == "0" ? "CASH": $model->metode_pembayaran;
        // if diskon
        if ($model->diskon > 0){
            $jurnalDetail = new AkuntansiJurnalDetail;
            $jurnalDetail->jurnal_id = $jurnal->id;
            $jurnalDetail->akun_id = 48; //Pembelian diskon di pendapatan
            $jurnalDetail->debit = 0;
            $jurnalDetail->kredit = $model->diskon;
            $jurnalDetail->saldo = $model->diskon;
            $jurnalDetail->created_at = date("Y-m-d H:i:s");
            $jurnalDetail->save();
        }

        // persediaan
        $jurnalDetail = new AkuntansiJurnalDetail;
        $jurnalDetail->jurnal_id = $jurnal->id;
        $jurnalDetail->akun_id = 39; //Persediaan
        $jurnalDetail->debit = $model->grand + $model->diskon;
        $jurnalDetail->kredit = 0;
        $jurnalDetail->saldo = $model->grand + $model->diskon;
        $jurnalDetail->created_at = date("Y-m-d H:i:s");
        $jurnalDetail->save();
        
        if ($model->bayar > 0){ // if user pay
            // cash in transaction
            $jurnalDetail = new AkuntansiJurnalDetail;
            $jurnalDetail->jurnal_id = $jurnal->id;
            $jurnalDetail->akun_id = Bank::model()->find("nama = '".$pembayaran_via."'")->akun_id;
            $jurnalDetail->debit = 0;
            $jurnalDetail->kredit = abs($model->grand);
            $jurnalDetail->saldo = abs($model->grand);
            $jurnalDetail->created_at = date("Y-m-d H:i:s"); 
            $jurnalDetail->save();

            //set loan 
            if ($model->kembali < 0){
                $jurnalDetail = new AkuntansiJurnalDetail;
                $jurnalDetail->jurnal_id = $jurnal->id;
                $jurnalDetail->akun_id = 24; // debit in hutang
                $jurnalDetail->debit = 0;
                $jurnalDetail->kredit =  abs($model->kembali);
                $jurnalDetail->saldo =  abs($model->kembali);
                $jurnalDetail->created_at = date("Y-m-d H:i:s");
                $jurnalDetail->save();
            }

        }else{ // if user not paying anything
            // set full loan
            $jurnalDetail = new AkuntansiJurnalDetail;
            $jurnalDetail->jurnal_id = $jurnal->id;
            $jurnalDetail->akun_id = 24; // debit in hutang
            $jurnalDetail->debit = 0;
            $jurnalDetail->kredit =  $model->grand ;
            $jurnalDetail->saldo = $model->grand ;
            $jurnalDetail->created_at = date("Y-m-d H:i:s");
            $jurnalDetail->save();
        }
    }

   

    public static function createSalesTransaction($sales,$salesPayment){
        // get logged user id
        $user = Users::model()->find('username=:un',array(':un'=>Yii::app()->user->name));
        $jurnal = new AkuntansiJurnal;
        $jurnal->tipejurnal_id = 1;
        $jurnal->periode_id = 1;
        $jurnal->tanggal_posting = date("Y-m-d H:i:s");
        $jurnal->nomor = self::generateKodeJurnal();
        $jurnal->keterangan = "Transaksi Penjualan #{$sales->faktur_id}";
        $jurnal->user_id = $user->id;
        $jurnal->saldo = $sales->sale_total_cost;
        $jurnal->created_at = date("Y-m-d H:i:s");
        $jurnal->branch_id =  Yii::app()->user->branch();
        $jurnal->sales_id =  $sales->id;
        if ($jurnal->save()){
                if ($sales->kembali < 0){ // credit handling
                    self::createPiutangTransaction($jurnal,$sales);
                }else{
                    self::createCashTransaction($jurnal,$sales);
                }

            self::createJurnalPersediaan($jurnal,$sales);// jurnal modal keluar  
             $jurnal->jml_detail_jurnal =  count(AkuntansiJurnalDetail::model()->findAll(" jurnal_id = '$jurnal->id' ")); // 4 redords is created by 1 transaction 
             $jurnal->update();
        }
    }
    
    public static function createCashTransaction($jurnal,$sales){
        $pembayaran_via =  $sales->pembayaran_via == "0" ? "CASH": $sales->pembayaran_via;
        $jurnalDetail = new AkuntansiJurnalDetail;
        $jurnalDetail->jurnal_id = $jurnal->id;
        $jurnalDetail->akun_id = Bank::model()->find("nama = '".$pembayaran_via."'")->akun_id;
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
    }

    public static function createPiutangTransaction($jurnal,$sales){
         // piutang transaction
            $jurnalDetail = new AkuntansiJurnalDetail;
            $jurnalDetail->jurnal_id = $jurnal->id;
            $jurnalDetail->akun_id = 6;
            $jurnalDetail->debit = abs($sales->kembali);
            $jurnalDetail->kredit = 0;
            $jurnalDetail->saldo = abs($sales->kembali);
            $jurnalDetail->created_at = date("Y-m-d H:i:s");
            $jurnalDetail->save();

            // transaksi jurnal kredit 
            $jurnalDetail = new AkuntansiJurnalDetail;
            $jurnalDetail->jurnal_id = $jurnal->id;
            $jurnalDetail->akun_id = 38; //pendapatan piutang
            $jurnalDetail->debit = 0;
            $jurnalDetail->kredit =  abs($sales->kembali);
            $jurnalDetail->saldo = $sales->kembali;
            $jurnalDetail->created_at = date("Y-m-d H:i:s");
            $jurnalDetail->save();
    }

    public static function createJurnalPersediaan($jurnal,$sales){
        $jurnalDetail = new AkuntansiJurnalDetail;
        $jurnalDetail->jurnal_id = $jurnal->id;
        $jurnalDetail->akun_id = 4; //Harga Pokok Penjualan
        $jurnalDetail->kredit = 0;
        $jurnalDetail->debit = $sales->sale_equity;
        $jurnalDetail->saldo = $sales->sale_equity;
        $jurnalDetail->created_at = date("Y-m-d H:i:s");
        $jurnalDetail->save();

        $jurnalDetail = new AkuntansiJurnalDetail;
        $jurnalDetail->jurnal_id = $jurnal->id;
        $jurnalDetail->akun_id = 39; //Persediaan
        $jurnalDetail->debit = 0;
        $jurnalDetail->kredit = $sales->sale_equity;
        $jurnalDetail->saldo = $sales->sale_equity;
        $jurnalDetail->created_at = date("Y-m-d H:i:s");
        $jurnalDetail->save();
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