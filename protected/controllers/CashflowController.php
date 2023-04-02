<?php


class CashflowController extends Controller
{
    public $layout='main2';

    public function actionSetortunai(){
        $transaction = Yii::app()->db->beginTransaction();
        $username = Yii::app()->user->name;
        $user = Users::model()->find('username=:un',array(':un'=>$username));
        $now = date("Y-m-d");
        if ($user->level == "1"):
            $cekKasir = Setor::model()->find(" is_closed = 1 and user_id = '$user->id' and  date(tanggal) = '$now'   ");
            if ($cekKasir){
            ?>
                <script type="text/javascript">
                alert("Tranksaksi kasir <?php echo $username ?> pada tanggal <?php echo date("d M Y", strtotime($cekKasir->tanggal)) ?> telah ditutup pada <?php echo date("d M Y H:i", strtotime($cekKasir->updated_at)) ?> , kasir hanya bisa melakukan register 1 kali dalam sehari",);
                window.location.href = '<?php echo Yii::app()->createUrl('site/admin') ?>'
                </script>
            <?php 
            }
        endif;

        if (isset($_REQUEST['head'])):
            $nilai = $_REQUEST['jsonObj'];
            $head = $_REQUEST['head'];	

            $modelh = new stdClass;
            $modelh->tanggal = $_REQUEST['head']['tanggal']. " ".date("H:i:s");
            $modelh->user = Yii::app()->user->name;
            $modelh->branch_id = Yii::app()->user->branch();
            $modelh->jenis_pengeluaran = $_REQUEST['head']['jeniskeluar'];
            $modelh->keterangan = $_REQUEST['head']['keterangan'];
            $modelh->total = $_REQUEST['head']['total'];
            $modelh->biaya = $_REQUEST['head']['biaya'];
            $modelh->pembayaran_via = $_REQUEST['head']['pembayaran_via'];
            JurnalController::createSetorTunai($modelh); // journal posting
            $transaction->commit();	
        endif;
        $this->render('setortunai');
    }

    public function actionTariktunai(){
        $transaction = Yii::app()->db->beginTransaction();
        $username = Yii::app()->user->name;
        $user = Users::model()->find('username=:un',array(':un'=>$username));
        $now = date("Y-m-d");
        if ($user->level == "1"):
            $cekKasir = Setor::model()->find(" is_closed = 1 and user_id = '$user->id' and  date(tanggal) = '$now'   ");
            if ($cekKasir){
            ?>
                <script type="text/javascript">
                alert("Tranksaksi kasir <?php echo $username ?> pada tanggal <?php echo date("d M Y", strtotime($cekKasir->tanggal)) ?> telah ditutup pada <?php echo date("d M Y H:i", strtotime($cekKasir->updated_at)) ?> , kasir hanya bisa melakukan register 1 kali dalam sehari",);
                window.location.href = '<?php echo Yii::app()->createUrl('site/admin') ?>'
                </script>
            <?php 
            }
        endif;

        if (isset($_REQUEST['head'])):
            $nilai = $_REQUEST['jsonObj'];
            $head = $_REQUEST['head'];	

            $modelh = new stdClass;
            $modelh->tanggal = $_REQUEST['head']['tanggal']. " ".date("H:i:s");
            $modelh->user = Yii::app()->user->name;
            $modelh->branch_id = Yii::app()->user->branch();
            $modelh->jenis_pengeluaran = $_REQUEST['head']['jeniskeluar'];
            $modelh->keterangan = $_REQUEST['head']['keterangan'];
            $modelh->total = $_REQUEST['head']['total'];
            $modelh->biaya = $_REQUEST['head']['biaya'];
            $modelh->pembayaran_via = $_REQUEST['head']['pembayaran_via'];
            JurnalController::createTarikTunai($modelh); // journal posting
            $transaction->commit();	
        endif;
        $this->render('tariktunai');
    }

    public function actionTransfer(){
        $transaction = Yii::app()->db->beginTransaction();
        $username = Yii::app()->user->name;
        $user = Users::model()->find('username=:un',array(':un'=>$username));
        $now = date("Y-m-d");
        if ($user->level == "1"):
            $cekKasir = Setor::model()->find(" is_closed = 1 and user_id = '$user->id' and  date(tanggal) = '$now'   ");
            if ($cekKasir){
            ?>
                <script type="text/javascript">
                alert("Tranksaksi kasir <?php echo $username ?> pada tanggal <?php echo date("d M Y", strtotime($cekKasir->tanggal)) ?> telah ditutup pada <?php echo date("d M Y H:i", strtotime($cekKasir->updated_at)) ?> , kasir hanya bisa melakukan register 1 kali dalam sehari",);
                window.location.href = '<?php echo Yii::app()->createUrl('site/admin') ?>'
                </script>
            <?php 
            }
        endif;

        if (isset($_REQUEST['head'])):
            $nilai = $_REQUEST['jsonObj'];
            $head = $_REQUEST['head'];	

            $modelh = new stdClass;
            $modelh->tanggal = $_REQUEST['head']['tanggal']. " ".date("H:i:s");
            $modelh->user = Yii::app()->user->name;
            $modelh->branch_id = Yii::app()->user->branch();
            $modelh->jenis_pengeluaran = $_REQUEST['head']['jeniskeluar'];
            $modelh->keterangan = $_REQUEST['head']['keterangan'];
            $modelh->total = $_REQUEST['head']['total'];
            $modelh->biaya = $_REQUEST['head']['biaya'];
            $modelh->sumber = $_REQUEST['head']['sumber'];
            $modelh->tujuan = $_REQUEST['head']['tujuan'];
            JurnalController::createTransfer($modelh); // journal posting
            $transaction->commit();	
        endif;
        $this->render('transfer');
    }
    
}