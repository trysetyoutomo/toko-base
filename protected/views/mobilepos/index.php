<?php 
// var_dump($cekClosed);
// var_dump($setor);
// exit;
		if ($cekKasir){ // cek apakah transaksi kasir ini sudah di tutup
			?>
				<script type="text/javascript">
				Swal.fire("Informasi","Tranksaksi kasir <?php echo $username ?> pada tanggal <?php echo date("d M Y", strtotime($cekKasir->tanggal)) ?> telah ditutup pada <?php echo date("d M Y H:i", strtotime($cekKasir->updated_at)) ?> , kasir hanya bisa melakukan register 1 kali dalam sehari", 'info')
                .then((result) => {
                    window.location.href = '<?php echo Yii::app()->createUrl('site/admin') ?>'
                });
				</script>
			<?php 
		}

		if (!$cekClosed && $cekSales <= 0){ // cek apakah kasir belum closing dan cek apkah transaksi d bawah 0 
			if ($setor){ //cek apakah sudah register
			}else{ // cek user belum melakukan register, user diarahkan ke setor
				if ($level != "1"){
					?>
					<script type="text/javascript">
                        Swal.fire("Hanya pengguna dengan level kasir yang dapat mengakses halaman ini")
                        .then((result) => {
                            window.location.href = '<?php echo Yii::app()->createUrl('site/admin') ?>'
                        });
					</script>
					<?php 
				}

                ?>
                <script type="text/javascript">
                window.location.href = '<?php echo Yii::app()->createUrl('mobilepos/inputsaldo') ?>';
                </script>
                <?php 

			}
		}else{
            $criteria = new CDbCriteria;
            $criteria->select = 't.* ';
            $criteria->join = ' INNER JOIN `sales_items` AS `si` ON si.sale_id = t.id INNER JOIN `items` AS `i` ON i.id = si.item_id';
            $criteria->addCondition("t.inserter = '$user_id' and  date(t.date) = '".$cekClosed->tanggal."' and t.status = 1 and store_id = '".Yii::app()->user->store_id()."' ");
			$cekSales  = Sales::model()->findAll($criteria);
			if (count($cekSales) > 0){
			?>
                <script type="text/javascript">
                Swal.fire('Informasi', "Tranksaksi kasir <?php echo $username ?> pada tanggal  <?php echo date("d M Y", strtotime($cekClosed->tanggal)) ?>  belum ditutup, silahkan hubungi admin ", 'info')
                .then((result) => {
                    window.location.href = '<?php echo Yii::app()->createUrl('site/admin') ?>'
                });
                </script>
            <?php 
			}else{  // jika tidak ada transaksi sales, dan belum d close maka close otomatis dengan reason tidak ada trasnsaksi
				$setor = Setor::model()->find(" user_id = '$user_id' and  date(tanggal) = '".$cekClosed->tanggal."' and store_id = '".Yii::app()->user->store_id()."' ");
				if ($setor){
					$setor->is_closed = 1;
					$setor->closed_reason = "Otomatis tutup, karena tidak ada transaksi";
					if ($setor->save())
						$this->redirect(array('mobilepos'));
				}
    }
    }


?>

<!-- Your content goes here -->
<div class=" navpos align-items-center position-fixed w-100" style="top:0px;left:0px;z-index: 99;">
    <div class="row"  style="height: 40px;">
        <div class="col-2 py-3 text-center">
            <div class="bg-white" style="    border-radius: 0px 0px 20px 20px;position: relative;top: -1rem;left: 1rem; width:100px">
                <img class="brand-img img ml-3" style="width:80px;height:auto;" src="logo/35_POS_LOGO.png" alt="">
            </div>
        </div>
        <div class="col-10 text-end text-white" style="display: flex;
            flex-wrap: nowrap;
            flex-direction: row-reverse;
            align-items: flex-start;
            height:0px
            ">
        <div class="btn-group">
            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php echo Yii::app()->user->name ?>
            </button>
            <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?php echo $this->createUrl('sales/index');?>">Backend</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="<?php echo $this->createUrl('site/logout'); ?>">Logout</a></li>
            </ul>
        </div>
        </div>
    </div>
</div>

<div class="container-fluid" id="app" v-cloak style="overflow-x:hidden">
<div v-if="isLoading">Loading .. </div>

  <button @click.prevent="clickkeranjang()"   href="#" class="btn btn-primary rounded-circle floating-button summary-button d-block d-md-none d-sm-none d-lg-none">
    
  <i v-if="ringkasanPesananMobile === false" class="fas fa-shopping-cart"></i>
  <i v-if="ringkasanPesananMobile === true" class="fas fa-times"></i>

    <span v-if="keranjang.length > 0 && ringkasanPesananMobile === false" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
        {{  keranjang.length  }}
        <span class="visually-hidden">unread messages</span>
      </span>
</button>

    <!-- Modal Close Register-->
    <div class="modal fade" id="modalCloseRegister" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Close Register</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form>
            <div class="row mb-3">
                <label for="expected-cash" class="col-sm-4 col-form-label">Saldo Awal</label>
                <div class="col-sm-8">
                <!-- <input type="text" class="form-control form-control-sm" id="expected-cash" v-model="closing.openAmount" @change="closingkalkulasi" disabled> -->
                <label>{{closing.openAmount.toLocaleString('id-ID', { style: 'currency',currency: 'IDR'})}}</label>

                </div>
            </div>
            <div class="row mb-3">
                <label for="expected-cash" class="col-sm-4 col-form-label">Pengeluaran</label>
                <div class="col-sm-8">
                <!-- <input type="text" class="form-control form-control-sm" id="expected-cash" v-model="closing.expense" @change="closingkalkulasi" disabled> -->
                <label>{{closing.expense.toLocaleString('id-ID', { style: 'currency',currency: 'IDR'})}}</label>

                </div>
            </div>
            <div class="row mb-3">
                <label for="expected-cash" class="col-sm-4 col-form-label">Total Cash harus ada </label>
                <div class="col-sm-8">
                <label style="border-bottom:2px dashed gray">{{closing.expectedCash.toLocaleString('id-ID', { style: 'currency',currency: 'IDR'})}}</label>
                <!-- <input type="text" class="form-control form-control-sm" id="expected-cash" v-model="closing.expectedCash" @change="closingkalkulasi" disabled> -->
                </div>
            </div>
            
            <div class="row mb-3">
                <label for="withdrawal-bank" class="col-sm-4 col-form-label">Total Transaksi Cashless</label>
                <div class="col-sm-8">
                <label>{{closing.withdrawBank.toLocaleString('id-ID', { style: 'currency',currency: 'IDR'})}}</label>
                </div>
            </div>
            <hr>
            <div class="row mb-3">
                <label for="counted-cash" class="col-sm-4 col-form-label">Cash Terhitung + Saldo Awal</label>
                <div class="col-sm-8">
                <input type="text" class="form-control form-control-sm" id="counted-cash" v-model="closing.countedCash" @input="closingkalkulasi">
                </div>
            </div>
            <div class="row mb-3">
                <label for="remaining-cash" class="col-sm-4 col-form-label">Kurang/Lebih Cash</label>
                <div class="col-sm-8">
                <!-- <input type="text" class="form-control form-control-sm" id="remaining-cash" v-model="closing.remainingCash" @change="closingkalkulasi" disabled> -->
                <label>{{closing.remainingCash.toLocaleString('id-ID', { style: 'currency',currency: 'IDR'})}}</label>
                </div>
            </div>
            <div class="row mb-3">
                <label for="comment" class="col-sm-4 col-form-label">Comment</label>
                <div class="col-sm-8">
                    <textarea placeholder="Catatan khusus Closing" class="form-control form-control-sm tabsize" id="comment" rows="3" v-model="closing.comment"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button @disabled="disabledTutupRegister" @click="clickTutupRegister" type="button" class="btn btn-primary">Tutup Register</button>
            </div>
            </form>
        </div>
        </div>
    </div>
    </div>

    <!-- Info Modal -->
    <div class="modal fade" id="modalInfo" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="infoModalLabel">Information</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <p>{{this.modalInfomessage}}</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>
    <!-- Modal Note-->
    <div class="modal fade" id="modalDiscount" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Diskon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Input field -->
                <div class="mb-3">
                    <label class="form-label me-2">Tipe Diskon</label>
                    <div class="form-check form-check-inline">
                        <input @change="onChangeDiscountType" v-model="discount_type" class="form-check-input" type="radio" name="currency" id="percentRadio" value="percent">
                        <label class="form-check-label" for="percentRadio">%</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input @change="onChangeDiscountType" v-model="discount_type" class="form-check-input" type="radio" name="currency" id="amount" value="amount">
                        <label class="form-check-label" for="amount">IDR</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="comment_item" class="form-label">Nilai Diskon</label>
                    <input :maxlength="discount_type === 'percent' ? 2 : 10" v-model="discount_value" ref="discount_value" type="text" class="form-control form-control-sm" id="discount_value" placeholder="Enter your text" />
                </div>
            </div>
            <div class="modal-footer">
                <!-- Your custom button -->
                <button type="button" class="btn btn-primary"  @click="saveDiscount">Simpan & Tutup</button>
            </div>
            </div>
        </div>
    </div>
 
    <!-- Modal Note-->
    <div class="modal fade" id="modalNote" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Catatan Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Input field -->
                <div class="mb-3">
                <label for="comment_item" class="form-label">Catatan Pesanan</label>
                <textarea maxlength="50" v-model="comment_item" ref="comment_item" type="text" class="form-control form-control-sm" id="comment_item" placeholder="Enter your text"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <!-- Your custom button -->
                <button type="button" class="btn btn-primary"  @click="saveNote()">Simpan Catatan</button>
            </div>
            </div>
        </div>
    </div>

    <!-- Modal payment -->
    <div class="modal fade" id="modal-payment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalMeja">Total Bayar ({{formatMoney(grandtotal)}})</h5>
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                    <div>
                        <button :disabled="loadingBayar" type="button" class="btn btn-secondary me-1 btn-sm " data-bs-dismiss="modal">Batal</button>
                        <button :disabled="leftAmount > 0 || (payment_cash === false && payment_bank === false) || loadingBayar" @click="bayar(1)" type="button" class="btn btn-success btn-sm" ><i v-if="loadingBayar" class="fa fa-spinner fa-spin"></i> Bayar</button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-3 mt-1">
                            <div class=" text-center">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input   @change="onpaymentcashChange" class="form-check-input" type="checkbox" id="payment_cash" v-model="payment_cash" checked>
                                            <label class="form-check-label" for="payment_cash">Cash</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-9 mt-1">
                            <div class="row">
                                <div class="col col-3">
                                    <button :disabled="payment_cash === false" class="btn-money-1000 btn btn-success w-100" idr-value="1000" @click="setInputCash"></button>
                                </div>
                                <div class="col col-3">
                                    <button :disabled="payment_cash === false" class="btn-money-2000 btn btn-success w-100" idr-value="2000" @click="setInputCash"></button>
                                </div>
                                <div class="col col-3">
                                    <button :disabled="payment_cash === false"  class="btn-money-5000 btn btn-success w-100" idr-value="5000" @click="setInputCash"></button>
                                </div>
                                <div class="col col-3">
                                    <button :disabled="payment_cash === false"  class="btn-money-10000 btn btn-success w-100" idr-value="10000" @click="setInputCash"></button>
                                </div>
                                <div class="col col-3">
                                    <button :disabled="payment_cash === false"  class="btn-money-20000 btn btn-success w-100" idr-value="20000" @click="setInputCash"></button>
                                </div>
                                <div class="col col-3">
                                    <button :disabled="payment_cash === false"  class="btn-money-50000 btn btn-success w-100" idr-value="50000" @click="setInputCash"></button>
                                </div>
                                <div class="col col-3">
                                    <button :disabled="payment_cash === false"  class="btn-money-75000 btn btn-success w-100" idr-value="75000" @click="setInputCash"></button>
                                </div>
                                <div class="col col-3">
                                    <button :disabled="payment_cash === false"  class="btn-money-100000 btn btn-success w-100" idr-value="100000" @click="setInputCash"></button>
                                </div>
                                <div class="col col-3 mt-1">
                                    <button :disabled="payment_cash === false"  class="btn btn-success w-100" idr-value="pass" @click="setInputCash"><i class="fa fa-check-circle"></i> Uang Pas</button>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-12">
                                    <div class="input-group mb-1">
                                        <div class="input-group-prepend ">
                                            <span class="input-group-text">Bayar CASH (Rp) </span>
                                        </div>
                                        <input :disabled="payment_cash === false" v-model="input_cash" @input="kalkulasi()"  placeholder="Masukan nilai Rupiah" type="text" class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-1">
                        <div class="col-3 mt-1">
                            <div class=" text-center">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input :disabled="disabledPaymentBank" @change="onpaymentBankChange" class="form-check-input" type="checkbox" id="payment_bank" v-model="payment_bank" />
                                            <label class="form-check-label" for="payment_bank">Cashless</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-9 mt-1">
                        <div class="row mb-1">
                        <div class="col col-3 d-block">
                            <button :disabled="payment_bank === false" @click="setCashlessMethod" 
                                :class="{'btn':true, 'btn-light':true, 'w-100':true, 'btn-payment':true, 'btn-payment-mandiri':true,'btn-payment-active':payment_bank_method === 'MANDIRI' && payment_bank}"  
                                payment-minimal="50000" 
                                bank_name="MANDIRI"></button>
                        </div>
                        <div class="col col-3 d-block">
                            <button :disabled="payment_bank === false" @click="setCashlessMethod" 
                                :class="{'btn':true, 'btn-light':true, 'w-100':true, 'btn-payment':true, 'btn-payment-bca':true,'btn-payment-active':payment_bank_method === 'BCA' && payment_bank }"  
                                payment-minimal="50000" 
                                bank_name="BCA"></button>
                        </div>
                        <div class="col col-3">
                            <button :disabled="payment_bank === false" @click="setCashlessMethod" 
                                :class="{'btn':true, 'btn-light':true, 'w-100':true, 'btn-payment':true, 'btn-payment-bri':true,'btn-payment-active':payment_bank_method === 'BRI' && payment_bank}"  
                                payment-minimal="50000" 
                                bank_name="BRI"></button>
                        </div>
                        <div class="col col-3">
                            <button :disabled="payment_bank === false" @click="setCashlessMethod" 
                                :class="{'btn':true, 'btn-light':true, 'w-100':true, 'btn-payment':true, 'btn-payment-bni':true,'btn-payment-active':payment_bank_method === 'BNI' && payment_bank}"  
                                payment-minimal="50000" 
                                bank_name="BNI"></button>
                        </div>
                        <div class="col col-3">
                            <button :disabled="payment_bank === false" @click="setCashlessMethod" 
                                :class="{'btn':true, 'btn-light':true, 'w-100':true, 'btn-payment':true, 'btn-payment-qris':true,'btn-payment-active':payment_bank_method === 'QRIS' && payment_bank}"  
                                payment-minimal="1" 
                                bank_name="QRIS"></button>
                        </div>
                        <div class="col col-3">
                            <button :disabled="payment_bank === false" @click="setCashlessMethod" 
                                :class="{'btn':true, 'btn-light':true, 'w-100':true, 'btn-payment':true, 'btn-payment-ovo':true,'btn-payment-active':payment_bank_method === 'OVO' && payment_bank}" 
                                payment-minimal="1" 
                                bank_name="OVO"></button>
                        </div>
                        <div class="col col-3">
                            <button :disabled="payment_bank === false" @click="setCashlessMethod" 
                                :class="{'btn':true, 'btn-light':true, 'w-100':true, 'btn-payment':true, 'btn-payment-gopay':true,'btn-payment-active':payment_bank_method === 'GOPAY' && payment_bank}" 
                                payment-minimal="1" 
                                bank_name="GOPAY"></button>
                        </div>
                        <div class="col col-3">
                            <button :disabled="payment_bank === false" @click="setCashlessMethod" 
                                :class="{'btn':true, 'btn-light':true, 'w-100':true, 'btn-payment':true, 'btn-payment-shopeepay':true,'btn-payment-active':payment_bank_method === 'SHOPEEPAY' && payment_bank}" 
                                payment-minimal="1" 
                                bank_name="SHOPEEPAY"></button>
                        </div>
                        <div class="col col-3 d-none">
                            <button :disabled="payment_bank === false" @click="setCashlessMethod" 
                                :class="{'btn':true, 'btn-light':true, 'w-100':true, 'btn-payment':true, 'btn-payment-dana':true,'btn-payment-active':payment_bank_method === 'DANA' && payment_bank}" 
                                payment-minimal="1" 
                                bank_name="DANA"></button>
                        </div>
                    </div>


                            <!-- <div class="col-12">
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend ">
                                        <span class="input-group-text">`IDR` </span>Masukan nilai Rupiah"
                                    </div>
                                    <input placeholder="Masukan nilai Rupiah" type="text" class="form-control form-control-sm" :readonly="disabledPaymentBank || payment_bank === false" v-model="input_bank" />
                                </div>
                            </div> -->


                            <div class="col-12">
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp </span>
                                    </div>
                                    <!-- :readonly="disabledPaymentBank || payment_bank === false" -->
                                    <input placeholder="Masukan nilai Rupiah" type="text" class="form-control form-control-sm" id="rupiahInput"  v-model="input_bank" readonly>
                                </div>
                            </div>



                            <div class="col-12">
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend ">
                                        <span class="input-group-text">0.0 </span>
                                    </div>
                                    <input :disabled="disabledPaymentBank || payment_bank === false" placeholder="Masukan Nomor Kartu" type="text" class="form-control form-control-sm" aria-label="Amount (to the nearest dollar)">
                                </div>
                            </div>
                            <div class="col-12 d-none">
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend ">
                                        <span class="input-group-text">0.0 </span>
                                    </div>
                                    <input :disabled="disabledPaymentBank || payment_bank === false" placeholder="Masukan Kode Approval" type="text" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr  v-if="payment_cash && !payment_bank">
                    <div class="row pt-1" v-if="payment_cash && !payment_bank">
                        <div class="col-3 mt-1">
                            <div class=" text-center">
                                <div class="row">
                                    <div class="col-12 tabsize">
                                        Uang Kembali/ Change
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-9 mt-1">
                            <div class="col-12">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend ">
                                        <span class="input-group-text">Rp </span>
                                    </div>
                                    <input   readonly="true" v-model="change" placeholder="Change" type="text" :class="{'form-control form-control-sm':true,'text-danger':change === 'Uang cash kurang!'}" aria-label="Amount (to the nearest dollar)">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-4 d-none">
                        <div class="col-3 mt-1">
                            <div class=" text-center">
                                <img src="https://media.istockphoto.com/id/1191080960/photo/traditional-turkish-breakfast-and-people-taking-various-food-wide-composition.jpg?s=612x612&w=0&k=20&c=PP5ejMisEwzcLWrNmJ8iPPm_u-4P6rOWHEDpBPL2n7Q=" width="60" />
                            </div>
                        </div>
                        <div class="col-9 mt-1">
                            <button class="btn btn-danger  "><i class="fa fa-qrcode"></i> Tampilkan QR Code</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalTable" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalMeja">Meja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-3" v-for="item in table" :key="item.id">
                            <button :disabled="keranjang.length <= 0 && item.active === false" @click="dineIn(item)" :class="{ '':true,'btn':true,'btn-outline-primary':item.active === true,'w-100':true}"><i class="fa fa-chair"></i> Meja {{item.no_meja}} </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="z-index: 1;margin-top: 2.2rem;overflow:hidden!important">
        <div class="col-12 col-md-8 col-sm-7 ">
            <h5 class="mb-2 mt-4">Menu <span style="color:var(--gray-light)">({{items.length}})</span></h5>
            <div class="row mt-3 ">
                <div class="col-12">
                    <div class="input-group mb-3">
                        <input v-model="search_keyword" @input="searchItem" type="text" class="form-control form-control-sm" placeholder="Cari Items" aria-label="Recipient's username" aria-describedby="basic-addon2" />
                        <div class="input-group-append">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="">
                <div style="width:100000px;white-space: nowrap;overflow-x: auto!important;">
                    <ul id="menu-category">
                        <li @click="filterCategory('all')" :class="{'active':activeCategory === 'all'}">Semua Kategori</li>
                        <li @click="filterCategory(item.id)" :class="{'active':item.id === activeCategory}" v-for="item in categories" :key="item.id">{{item.category.toLowerCase()}}</li>
                    </ul>
                </div>
            </div>
            <div class="row gy-3 gx-2"> 
                <div :class="{'col-6':true, 'col-lg-4':true, 'col-md-4':true, 'col-sm-6':true,'d-none':ringkasanPesananMobile === true }" v-for="item in items" :key="item.id">
                    <div :class="{ 'item-menu':true,'card':true,'border-primary': !isInKeranjang(item.id), 'border-3': !isInKeranjang(item.id) }" style="min-height:210px;">
                        <div class="row" @click="add(item.id)">
                            <img v-if="item.image != '' && item.image.toLowerCase() !== 'images/items/item.gif'" :src="'img/produk/' + item.image" alt="image not found" class="avatar-img rounded-circle" />
                            <img v-if="item.image.toLowerCase() == 'images/items/item.gif'" src="https://nayemdevs.com/wp-content/uploads/2020/03/default-product-image.png" alt="image not found" class="avatar-img rounded-circle" />
                        </div>
                        <div class="row mt-2 mb-2" @click="add(item.id)">
                            <div class="item-name">
                                {{item.nama}}
                            </div>
                            <div class="item-price " @click="add(item.id)">
                                {{ formatMoney(item.harga_jual)}}
                            </div>
                        </div>
                        <div class="row">
                            <div class=" col-12">
                                <div v-if=(isInKeranjang(item.id))>
                                    <button @click="add(item.id)" class="btn btn-outline-primary w-100 rounded btn-sm">Tambah Item <i class=" fa fa-plus"></i></button>
                                </div>
                                <div v-else>

                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button @click="minQTY(item.id)" class="btn btn-primary btn-sm" type="button"><i class="fa fa-minus"></i></button>
                                        </span>
                                        <input v-model="keranjang.filter((obj) => obj.id == item.id)[0].qty" type="text" class="form-control form-control-sm text-center" readonly />
                                        <span class="input-group-btn">
                                            <button @click="addQTY(item.id)" class="btn btn-primary btn-sm" type="button"><i class="fa fa-plus"></i></button>
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div :class="{'d-sm-block':true,'d-none':ringkasanPesananMobile === false,'col-6':ringkasanPesananMobile === false, 'col-md-4':true, 'col-sm-5':true, 'ringkasan-mobile':ringkasanPesananMobile,  'col-12':ringkasanPesananMobile}"  style="overflow-y:auto">
            <div class="card mt-2 position  w-100" style="width: -webkit-fill-available;" >
                <div class="card-header p-2 mb-1"> <span class="">Ringkasan Pesanan 
                     <span v-if="activeTable!==''" style="color: var(--gray-light);">{{activeTable}}</span>
                     <button @click="updateTableItems" :class="{ 'ml-1':true, 'btn':true, 'btn-success' : true, 'btn-sm':true, 'd-none':activeTableNumber == ''  }"><i class="fa fa-check"></i></button>
                </div>
                <div class="card-body pt-0">
                    <div v-if="keranjang.length > 0"> </div>
                    <div id="chart-items" class="full-height"  style="overflow-y:auto;overflow-x:hidden" >
                        <div v-if="keranjang.length <=0" class="pt-2 pb-2 mt-2 w-100">
                            <div class="row justify-content-center align-items-center">
                                <div class="col-12 text-center" style="color: var(--gray-light)">
                                    <i class="fa fa-cart-plus "></i> Keranjang kosong
                                </div>
                            </div>
                        </div>
                        <div v-if="keranjang.length > 0" class="card pt-1 mt-1 p-1 pb-0 p-md-0" v-for="item in keranjang" :key="item.id">
                            <div class="row justify-content-center align-items-center mt-1">
                                <div class="col-5 col-md-6 ">
                                    <div class="summary-item-name" style="font-size:11px">{{item.nama}}</div>
                                    <div class="summary-item-qty" style="font-size:11px">({{formatMoney(item.harga_jual)}}) x {{item.qty}}</div>
                                </div>
                                <div class=" col-3 col-md-2 summary-item-deletion mt-1 mt-md-0">
                                    <div @click="removeItem(item.id)" class="card" style="width: 30px;height: 30px;justify-content: center;align-items: center;border-radius: 50%;">
                                        <i class="fa fa-trash " style="color: #deb7b7;"></i>
                                    </div>
                                </div>
                                <div class=" col-3 col-md-3 summary-item-note mt mt-md-0">
                                    <div @click="addNote(item.id)" class="card" style="width: 30px;height: 30px;justify-content: center;align-items: center;border-radius: 50%;">
                                        <i class="fa fa-note-sticky " style="color: rgb(226 228 92);"></i>
                                    </div>
                                </div>
                            </div>
                            <div v-if="item.comment != '' " class="ml-2 ps-3">
                                <p style="font-style:italic;color:brown" class="py-0">{{item.comment}}</p>
                            </div>
                        </div> <!-- end card -->
                    </div>

                    <div class="card mt-2" style="bottom:0px">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-6 tabsize"  >Subtotal</div>
                                <div class="col-12 col-md-6 text-end tabsize">{{formatMoney(subtotal)}}</div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 text-danger tabsize">Diskon 
                                <div @click="showDiscountPopup()" class="card d-inline-block border-danger" style="border-radius: 50%;">
                                    <div style="display: flex;align-items: center;justify-content: center;width: 20px;height: 20px;">    
                                        <i class="fa fa-edit text-danger "></i>
                                    </div>
                                </div>
                            
                            </div>
                                <div class="col-12 col-md-6 text-end tabsize">{{formatMoney(discount)}}</div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 tabsize">Pajak ({{percent_tax}}%)</div>
                                <div class="col-12 col-md-6 text-end tabsize">{{formatMoney(tax)}}</div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 tabsize" >Service ({{percent_service}}%)</div>
                                <div class="col-12 col-md-6 text-end tabsize">{{formatMoney(service)}}</div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-6 tabsize">Pembulatan</div>
                                <div class="col-12 col-md-6 tabsize text-end">{{formatMoney(rounded)}}</div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 h5 tabsize ">Total</div>
                                <div class="col-12 col-md-6 text-end h5 tabsize">{{formatMoney(grandtotal)}}</div>
                            </div>
                            <div class="row mt-1">
                                <div class="col mt-1 mt-md-1 ">
                                    <button :disabled="activeTableNumber != ''" type="button" class="btn btn-primary w-100 btn-sm" data-bs-toggle="modal" data-bs-target="#modalTable">
                                        Meja
                                    </button>
                                </div>
                                <div class="col mt-1 mt-md-1">
                                    <button @click="cleanUpOrder" type="button" class="btn btn-primary w-100 btn-sm">
                                        Refresh
                                    </button>
                                </div>
                                <div class="col mt-1 mt-md-1">
                                    <button :disabled="keranjang.length <= 0" type="button" class="btn btn-success w-100 btn-sm" data-bs-toggle="modal" data-bs-target="#modal-payment"> Bayar</button>
                                </div>
                                <div class="col mt-1 mt-md-1">
                                <button @click="clickClosing" type="button" class="btn btn-danger w-100 btn-sm">
                                        <!-- <i class="fa fa-times"></i> -->
                                        Closing
                                    </button>                                    
                                </div>
                               
                            </div>
                         

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <script>
    const {createApp, ref} = Vue;
    
    function formatMoney(amount) {
    // Memisahkan bagian desimal
    var parts = amount.toString().split(".");
    var integerPart = parts[0];
    var decimalPart = parts.length > 1 ? parts[1] : "";

    // Menambahkan koma setiap 3 digit dari belakang, kecuali digit terakhir
    var formattedIntegerPart = "";
    for (var i = integerPart.length - 1, j = 1; i >= 0; i--, j++) {
        formattedIntegerPart = integerPart.charAt(i) + formattedIntegerPart;
        if (j % 3 === 0 && i !== 0) {
            formattedIntegerPart = "," + formattedIntegerPart;
        }
    }

    // Menggabungkan bagian integer dan decimal
    var formattedAmount = formattedIntegerPart;
    if (decimalPart !== "") {
        formattedAmount += "." + decimalPart;
    }

    return formattedAmount;
}


    const app = Vue.createApp({
        methods: {
            closeRingkasanPesanan(){
                this.ringkasanPesananMobile = false
            },
            clickkeranjang(){
                this.ringkasanPesananMobile = !this.ringkasanPesananMobile
            },
            clickTutupRegister(){
                let vm = this;
                let tanggal = '<?php echo date("Y-m-d"); ?>';
                $.ajax({
                url:'<?=$this->createUrl('sales/cetakrekap')?>',
                data:'tanggal_rekap='+tanggal+"&uangmasuk="+vm.closing.countedCash+"&comment="+vm.closing.comment,
                beforeSend : function(){
                    vm.disabledTutupRegister = true;
                },  
                success: function(data){
                    var json = jQuery.parseJSON(data);
                    var jenis_printer = '<?php echo SiteController::getConfig("jenis_printer"); ?>';
                    vm.disabledTutupRegister = false;     
                    setTimeout(function() {

                        Swal.fire({
                            title: 'Confirmation',
                            text: 'Data berhasil disimpan!, Apakah anda ingin mencetak Rekap Transaksi Penjualan anda ?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // User clicked "Yes"
                                // Swal.fire('Confirmed!', 'You clicked Yes.', 'success');
                                window.location.assign("<?php echo $this->createUrl('sales/cetakrekap',["noprint"=>true,"tanggal_rekap"=>date("Y-m-d")]); ?>");

                            } else {
                                // User clicked "No"
                                Swal.fire('Logout', 'Sistem akan melakukan logout secara otomatis.', 'success');
                                setTimeout(() => {
                                    window.location.assign("<?php echo $this->createUrl('site/logout'); ?>");
                                }, 3000);

                            }
                        });
                        
                    }, 100);
                },
                error: function(error){
                    let errorResponse = JSON.parse(error.responseText);
                    Swal.fire('Error', errorResponse.message, 'error');
                }
            });
            },
            convertToZero(value) {
                // Check if the value is NaN, null, or an empty string
                if (isNaN(value) || value === null || value === "") {
                // Convert to 0
                return 0;
                } else {
                // Return the original value if it's a valid number or other non-empty value
                return parseInt(value);
                }
            },
            clickClosing(){
                this.getClosingInformation();
            },
            getClosingInformation(){
                const vm = this;
                <?php 
                $username = Yii::app()->user->name;
                $user = Users::model()->find('username=:un',array(':un'=>$username));
                ?>
                var tanggal = '<?php echo date("Y-m-d"); ?>';
                var user_id = "<?php echo $user->id ?>";

                $.ajax({
                    url : "<?php echo Yii::app()->createUrl('sales/GetOmsetByUser'); ?>",
                    data : "date="+tanggal+"&user_id="+user_id,
                    success:function(data){
                        var js = JSON.parse(data);
                        let omset = js.cash;
                        vm.closing.openAmount = vm.convertToZero(js.total_awal);
                        vm.closing.withdrawBank = vm.convertToZero(js.cashless);
                        vm.closing.expectedCash =  vm.convertToZero(js.total_awal) +  vm.convertToZero(js.cash)-vm.convertToZero(js.potongan)-vm.convertToZero(js.pengeluaran);
                        vm.modalCloseRegister.show();
                    }
                });

            },
            closingkalkulasi(){
                this.closing.remainingCash = parseInt(this.closing.countedCash) - ( parseInt(this.closing.expectedCash));
            },
            setCashlessMethod(){
                if (this.payment_bank)
                    this.payment_bank_method = event.currentTarget.getAttribute("bank_name");
                this.kalkulasi();
            },
            updateTableItems() {
                let param = {
                    "active": false,
                    "no_meja": this.activeTableNumber
                }
                this.dineIn(param);
            },
            onpaymentBankChange(){
                const vm = this;
                this.kalkulasi();
            },
            onpaymentcashChange(){
                if (this.payment_cash && this.payment_bank === false)
                    this.input_cash = this.estimate(this.grandtotal);
                else
                    this.input_cash = "";

                if (this.payment_bank && this.payment_cash === false)
                    this.input_bank = this.grandtotal;
                else
                    this.input_bank = "";

                this.kalkulasi();
            },
            ensureNumber(value) {
                if (value === undefined || Number.isNaN(value) || isNaN(value) || value.length == 0  ) {
                    return 0;
                }

                return parseInt(value);
            },
            kalkulasiSisa(){
                const vm =  this;
                vm.leftAmount = this.ensureNumber(vm.grandtotal) -  (this.ensureNumber(vm.input_cash) + this.ensureNumber(vm.input_bank)) ;

                // console.log("grandtotal", typeof this.ensureNumber(vm.grandtotal));
                // console.log("cash", typeof this.ensureNumber(vm.input_cash));
                // console.log("bank", typeof this.ensureNumber(vm.input_bank));

                // console.log("grandtotal",  this.ensureNumber(vm.grandtotal));
                // console.log("cash",  this.ensureNumber(vm.input_cash));
                // console.log("bank",  this.ensureNumber(vm.input_bank));
                
            },
            // isPaymentFilled(){
            //     const vm = this;
            //     let sisa = 0;
                
            //     console.log("this.payment_cash " + this.payment_cash)
            //     console.log("this.payment_bank " + this.payment_bank)
                
            //     // console.log("t " + this.payment_bank)
            //     console.log("grandtotal", typeof this.ensureNumber(vm.grandtotal));
            //     console.log("cash", typeof this.ensureNumber(vm.input_cash));
            //     console.log("bank", typeof this.ensureNumber(vm.input_bank));

            //     if (this.payment_cash && this.payment_bank){
            //         sisa = this.ensureNumber(vm.grandtotal) -  (this.ensureNumber(vm.input_cash) + this.ensureNumber(vm.input_bank)) ;
            //         console.log ('dua');
            //     }else if (this.payment_bank){
            //         sisa = this.ensureNumber(vm.grandtotal) - this.ensureNumber(vm.input_bank) ;
            //         console.log ('tiga');
            //     }
            //     else if (this.payment_cash){
            //         sisa = this.ensureNumber(vm.grandtotal) -  this.ensureNumber(vm.input_cash) ;
            //         console.log ('satu');
            //     }
            //     console.log("grandtotal " +this.ensureNumber(vm.grandtotal));
            //     console.log("input_cash " + this.ensureNumber(vm.input_cash));
            //     console.log("input_bank " + this.ensureNumber(vm.input_bank));
            //     console.log("sisa" + sisa);
            //     console.log("hasil boolean" + sisa <= 0);
            //     return sisa <= 0;   
            // },
            kalkulasi() {
                const vm = this;
                try{                
                vm.subtotal = 0;
                this.keranjang.forEach((obj) => {
                    vm.subtotal += parseInt(obj.harga_jual) * parseInt(obj.qty);
                });
                vm.discount = vm.discount_type === "percent" ?  vm.subtotal * this.discount_value / 100 : vm.discount_value;
                vm.subtotalAfterDiscount = vm.subtotal - vm.discount;
                vm.tax = vm.subtotalAfterDiscount * this.percent_tax / 100;
                vm.service = vm.subtotalAfterDiscount * this.percent_service / 100;
                vm.grandtotal = (vm.subtotalAfterDiscount + vm.tax + vm.service);
                vm.rounded = this.roundTotalToNearestMultiple(vm.grandtotal, 100) - vm.grandtotal;
                vm.grandtotal = this.roundTotalToNearestMultiple(vm.grandtotal, 100);
                this.kalkulasiSisa();

                if (this.payment_bank === false && this.payment_cash === true ){ // jika bayar cash only
                    vm.input_bank = "";
                    vm.change = vm.input_cash >= vm.grandtotal ?  vm.formatMoney(vm.input_cash - vm.grandtotal) : "Uang cash kurang!";
                     if (this.leftAmount > 0){ // jika masih ada yang harus d bayar
                        this.disabledPaymentBank = false; // aktifkan checkbox bank
                    }
                    if (this.leftAmount > 0 && this.payment_cash && this.payment_bank === false){  // jika payment cash aktif, dan masih ada sisa yang harus dibayar
                        this.disabledPaymentBank = false;
                    }
                    this.kalkulasiSisa();
                    // }
                }else  if (this.payment_bank && this.payment_cash === false){ // jika bayar bank only
                    vm.input_cash = "";
                    this.input_bank = this.grandtotal;
                    this.kalkulasiSisa();
                }else  if (this.payment_bank && this.payment_cash){  // jika bank dan cash
                    this.input_bank = "";
                    this.kalkulasiSisa();
                    if (this.leftAmount > 0){  // jika payment cash aktif, dan masih ada sisa yang harus dibayar
                        this.input_bank = this.leftAmount;
                    }else{
                        this.input_bank = "";
                        this.payment_bank = false;
                    }
                    if (this.input_cash === ""){
                        this.payment_cash = false;
                    }

                }else{
                    this.input_bank = "";
                    this.input_cash = "";
                }
                
                }catch(error){
                    console.log("kalkulasi function : " + error);
                }

            },
            roundTotalToNearestMultiple(total, multiple) {
                // Ensure multiple is a positive integer greater than 0
                multiple = Math.abs(Math.floor(multiple)) || 1;

                // Round the total to the nearest multiple
                const roundedTotal = Math.round(total / multiple) * multiple;

                return roundedTotal;
            },
            searchItem(event) {
                if (event.target.value.length > 0) {
                    this.items = this.items.filter(obj =>
                        obj.nama.toLowerCase().includes(event.target.value.toLowerCase())
                    );
                } else {
                    this.items = this.originalItems;
                }
                this.kalkulasi();
            },
            removeItem(id) {
                this.keranjang = this.keranjang.filter(obj => obj["id"] !== id);
                this.kalkulasi();
            },
            saveNote() {
                let isExist = this.keranjang.find(obj => obj.id === this.keranjangItemActive);
                if (isExist) {
                    isExist.comment = this.comment_item;
                    this.modalNote.hide();
                }
            },
            saveDiscount() {
                this.modalDiscount.hide();
                this.kalkulasi();
            },
            addNote(id) {
                this.keranjangItemActive = id;
                let isExist = this.keranjang.find(obj => obj.id === id);
                if (isExist) {
                    this.comment_item = isExist.comment;
                    this.modalNote.show();
                    this.$refs.comment_item.focus();
                }
            },
            add(id,qty = 1) {
                const vm  = this;
                try{
                let isExist = this.keranjang.find(obj => obj.id === id);
                if (isExist === undefined) {
                    let findItem = this.originalItems.filter(function(i) {
                        return (i.id === id);
                    });
                    findItem[0].qty = qty;
                    this.keranjang.push(findItem[0]);
                } else {
                    isExist.qty += 1;
                }
                this.kalkulasi();
                }catch(error){
                    Swal.fire('Error', 'Gagal load produk', 'error');
                    vm.cleanUpOrder();
                }
            },
            formatMoney(value) {
                // Convert the numeric value to Rupiah format
                const formattedValue = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                }).format(value);

                return formattedValue;
            },
            isInKeranjang(id) {
                let isExist = this.keranjang.find(obj => obj.id === id);
                return isExist == undefined
            },
            addQTY(id) {
                this.keranjang.filter((obj) => obj.id == id)[0].qty += 1;
                this.kalkulasi()
            },
            minQTY(id) {
                this.keranjang.filter((obj) => obj.id == id)[0].qty -= 1;
                if (this.keranjang.filter((obj) => obj.id == id)[0].qty <= 0) {
                    this.removeItem(id)
                }
                this.kalkulasi()
            },
            async dineIn(item) {
                const vm = this;
                if (item.active) {
                    if (vm.keranjang.length > 0 && vm.activeTableNumber === ""){
                        let confirmBuka = confirm("Anda memiliki keranjang aktif, dengan membuka meja ini data keranjang akan hilang, apakah anda yakin untuk membukanya ? ");
                        if (!confirmBuka)
                            return false;
                    } 
                    const apiUrl = `index.php?r=mobilepos/Gettablebynumber&id=${item.id}`;
                    try {
                        const response = await fetch(apiUrl, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }

                        const data = await response.json();
                        vm.keranjang = [];
                        if (data.detail.length > 0) {
                            data.detail.map((obj) => {
                                vm.add(obj.item_id, parseInt(obj.quantity_purchased));
                            })
                            vm.activeTableNumber = data.main.table;
                            vm.activeTable = "(Meja " + data.main.table + ")";
                            vm.modalTable.hide();
                        }
                        // Handle the data returned from the server
                    } catch (error) {
                        // Handle errors during the fetch
                        console.error('Fetch error:', error);
                    }

                } else { // if inactive
                    this.bayar(0, item.no_meja);
                }
            },
            bayar(status, no_meja){
                try{
                    
                const vm = this;
                vm.loadingBayar = true;
                let sales = {
                    pembulatan: vm.rounded,
                    sale_id: null,
                    subtotal: vm.subtotal,
                    discount: vm.discount,
                    tax: vm.tax,
                    service: vm.service,
                    total_cost: vm.grandtotal,
                    payment: null,
                    bayar_via: vm.payment_bank ? vm.payment_bank_method === "" ? "CASH" : vm.payment_bank_method : "CASH", 
                    status: status,
                    table: no_meja === undefined ? this.activeTableNumber : no_meja,
                    custype: null,
                    bayar: vm.input_cash
                };
                
                    let total_cash = 0;
                    if (vm.payment_cash && vm.payment_bank === false){
                        total_cash = vm.grandtotal;
                    }else{
                        total_cash = vm.input_cash; 
                    }



                    let sales_payment = {
                        cash: vm.payment_cash ? total_cash : 0,
                        edcbca: vm.payment_bank ? vm.input_bank : 0,
                        edcniaga: 0,
                        voucher: vm.discount,
                        compliment: 0,
                        dll: 0
                    }

                    let sales_items = [];
                    vm.keranjang.forEach(function(rec) {
                        const item_total = rec.harga_jual * rec.qty;
                        const item_pajak = item_total * rec.qty;
                        sales_items.push({
                            "item_id": rec.id,
                            "item_satuan_id": rec.nama_satuan_id,
                            "item_name": rec.nama,
                            "quantity_purchased": rec.qty,
                            "item_tax": item_total * (vm.percent_tax/100 ),
                            "item_service": item_total * (vm.percent_service/100 ),
                            "item_discount": 0,
                            "item_price": rec.harga_jual,
                            "item_total_cost": item_total,
                            "permintaan": rec.comment
                        });
                    });

                    if (sales_items.length < 0) {
                        alert("gagal!");
                        return;
                    }

                    $.ajax({
                        url: 'index.php?r=sales/bayar',
                        type: 'POST',
                        data: {
                            data: sales,
                            data_detail: sales_items,
                            data_payment: sales_payment
                        },
                        beforeSend:function(){
                            vm.loadingBayar = true;
                        },  
                        success: function(data) {
                            var sales = JSON.parse(data);
                            vm.refreshTable();
                            vm.cleanUpOrder()
                            if (sales.status == 1)
                            {
                                    vm.modalTable.hide();
                                    vm.modalPayment.hide();
                                    vm.loadingBayar = false;
                                    var jenis_cetak = '<?php echo SiteController::getConfig("ukuran_kertas"); ?>';
                                    var jenis_printer = '<?php echo SiteController::getConfig("jenis_printer"); ?>';

                                    if (jenis_cetak=="24cmx14cm" || jenis_cetak=="12cmx14cm"){

                                            Swal.fire({
                                                title: 'Confirmation',
                                                text: 'Cetak Receipt ?',
                                                icon: 'question',
                                                showCancelButton: true,
                                                confirmButtonColor: '#3085d6',
                                                cancelButtonColor: '#d33',
                                                confirmButtonText: 'Yes',
                                                cancelButtonText: 'No'
                                            }).then((result) => {
                                                $.ajax({
                                                    url : '<?php echo Yii::app()->createUrl("Sales/cetakfaktur") ?>',
                                                    data : {
                                                        id : sales.sale_id
                                                    },
                                                    success:function(data){
                                                    $('.body-bukti').html(data);
                                                    $(".btn-modal-preview").trigger("click");
                                                    }
                                                });
                                          });
                                    }else if ( (jenis_cetak=="80mm" || jenis_cetak=="58mm") && jenis_printer === "Epson LX" ){
                                            Swal.fire({
                                                title: 'Confirmation',
                                                text: 'Cetak Receipt ?',
                                                icon: 'question',
                                                showCancelButton: true,
                                                confirmButtonColor: '#3085d6',
                                                cancelButtonColor: '#d33',
                                                confirmButtonText: 'Yes',
                                                cancelButtonText: 'No'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.open("<?php echo Yii::app()->createUrl("Sales/cetakfaktur_mini") ?>&id="+sales.sale_id);
                                            }});
                                    }else{
                                        var i =1;
                                        var ulang  =  1;
                                        function myLoop(){
                                            setTimeout(function(){
                                                print_bayar(sales);
                                                i++;
                                                
                                                if (i<=ulang){
                                                    myLoop();
                                                    
                                                }
                                            },1000)
                                        }
                                        myLoop();
                                    }
                                    // alert("Tekan OK untuk mendapatkan rekap ke 2.");
                                    // if (confirm("Cetak receipt ke - 2 ? ")){	
                                    // }
                                    // $("#vouchernominal").val("");
                                }

                            if (sales.error){
                                alert(sales.error);
                            }
                        },
                        error: function(data) {
                            vm.disabledBayarBtn = false;
                            alert(data);
                        }
                    });
                }catch(error){
                    console.log(error);
                }
            },
            async getActiveTable() {
                // Construct the URL with the tableNumber parameter
                const apiUrl = `<?php echo $this->createUrl('mobilepos/table');?>`;

                try {
                    // Make a GET request using the fetch API with the Content-Type header set to application/json
                    const response = await fetch(apiUrl, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            // You can add other headers if needed
                            // 'Authorization': 'Bearer YOUR_ACCESS_TOKEN'
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }

                    // Parse the JSON response
                    const data = await response.json();

                    // Handle the data returned from the server
                    return data;
                } catch (error) {
                    // Handle errors during the fetch
                    console.error('Fetch error:', error);
                }

            },
            async refreshTable() {
                let activeTable = await this.getActiveTable();
                activeTable = activeTable.map(obj => obj.table);
                this.table = this.table.map((obj) => {
                    if (activeTable.includes(obj.no_meja))
                        return {
                            ...obj,
                            active: true
                        };
                    else
                        return {
                            ...obj,
                            active: false
                        };
                });
            },
            cleanUpOrder() {
                this.subtotal = 0;
                this.discount = 0;
                this.tax = 0;
                this.service = 0;
                this.rounded = 0;
                this.grandtotal = 0;
                this.keranjang = [];
                this.activeTableNumber = "";
                this.activeTable = "";
                this.payment_cash = false;
                this.payment_bank = false;
                this.input_bank = "";
                this.input_cash = ""
                this.search_keyword = "";
                this.discount_type = "percent";
                this.discount_value = 0;
                this.payment_bank_method = "MANDIRI";
                this.ringkasanPesananMobile = false;

            },
            estimate(num) {
                if (num < 10000) {
                    num = 10000;
                    return num;
                } else if (num <= 20000) {
                    return 20000;
                } else if (num <= 20000) {
                    return 20000;
                } else if (num <= 50000) {
                    return 50000;
                } else if (num <= 100000) {
                    return 100000;
                } else if (num <= 150000) {
                    return 150000;
                } else if (num <= 200000) {
                    return 200000;
                } else if (num <= 250000) {
                    return 250000;
                } else if (num <= 300000) {
                    return 300000;
                } else {
                    return num;
                }

            },
            setInputCash(event) {
                if (this.payment_cash){
                    let idrValue = event.currentTarget.getAttribute("idr-value");
                    if (idrValue === "pass")
                        this.input_cash =  this.grandtotal;
                    else
                        this.input_cash =  idrValue;
                    this.kalkulasi();
                }
            },
            filterCategory(id) {
                this.activeCategory = id;
                if (id === "all"){
                    this.items = this.originalItems;
                }else{
                    this.items = this.originalItems.filter(obj =>
                        obj.category_id == id
                    );
                }
                this.kalkulasi();
            },
            showDiscountPopup(){
                this.modalDiscount.show();
            }
        },
        computed() {
            <?php 
            $store = Stores::model()->findByPk(Yii::app()->user->store_id());
            $percentTax = $store->percent_tax;
            $percentService = $store->percent_service;
            ?>
        },
        data() {
            return {
                loadingBayar : false,
                search_keyword : "",  
                ringkasanPesananMobile : false,
                subtotal: 0,
                discount: 0,
                discount_value: 0,
                discount_type: "percent", //percent or amount
                percent_tax: <?php echo (!isset($percentTax) || $percentTax === '') ? 0 : trim($percentTax); ?>,
                percent_service: <?php echo (!isset($percentService) || $percentService === '') ? 0 : trim($percentService); ?>,
                tax: 0,
                service: 0,
                rounded: 0,
                grandtotal: 0,
                keranjang: [],
                originalItems: <?=($items == "" ? "[]" : $items); ?>,
                items: <?=($items == "" ? "[]" : $items); ?>,
                table:  <?=($table == "" ? "[]" : $table); ?>,
                categories: <?=($categories == "" ? "[]" : $categories); ?>,
                activeTableNumber: "",
                activeTable: "",
                activeCategory: "all",
                payment_cash: false,
                payment_bank: false,
                payment_bank_method: "MANDIRI",
                input_bank: 0,
                input_cash: 0,
                change: 0,
                leftAmount: 0,
                disabledPaymentBank : false,
                disabledTutupRegister : false,
                keranjangItemActive : "",
                comment_item : "",
                modalTable: null,
                modalInfo : null,
                modalInfoMessage : null,
                modalCloseRegister : null,
                modalNote : null,
                closing : {
                    expectedCash : 0,
                    countedCash : 0,
                    withdrawBank : 0,
                    remainingCash : 0,
                    comment : "",
                    expense : 0,
                    openAmount : 0
                }
            }
        },
        mounted() {
            this.modalTable = new bootstrap.Modal(document.getElementById('modalTable'));
            this.modalNote = new bootstrap.Modal(document.getElementById('modalNote'));
            this.modalDiscount = new bootstrap.Modal(document.getElementById('modalDiscount'));
            this.modalInfo = new bootstrap.Modal(document.getElementById('modalInfo'));
            this.modalCloseRegister = new bootstrap.Modal(document.getElementById('modalCloseRegister'));
            this.modalPayment = new bootstrap.Modal(document.getElementById('modal-payment'));
            this.refreshTable();
            setTimeout(() => {
                this.isLoading = false;
            }, 2000); // Adjust the delay as needed
        },
        watch: {
            "grandtotal": function() {
                this.kalkulasiSisa();
            },
            "input_cash": function() {
                this.kalkulasiSisa();
            },
            "input_bank": function() {
                this.kalkulasiSisa();
            },
            "payment_cash": function() {
                this.kalkulasiSisa();
            },
            "payment_bank": function() {
                this.kalkulasiSisa();
            },
            "payment_bank_method": function() {
                this.kalkulasiSisa();
            },
      
            // "items": function() {
            //     this.items =  this.items.slice().sort((a, b) => a.qty - b.qty);
            // }
        },
        setup() {
            const message = ref('Hello vue!')
            return {
                message,
            }
        }
    });

    app.mount('#app');
</script>

<script>
// Function to scroll back to the top of the page
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Show/hide the button based on scroll position
window.onscroll = function () {
    var btn = document.getElementById('backToTopBtn');
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        btn.style.display = 'block';
    } else {
        btn.style.display = 'none';
    }
};
</script>