
<style>
    :root {
        --gray-light: #8598AE;
        --primary : #2E4057;
    }

    body {
        background-color: #F7F7F8;
        font-family: 'Work Sans', sans-serif;
    }

    .navpos{
        background-color: var(--primary)!important;
    }

    .btn-primary{
        background-color: var(--primary)!important;
        border-color:var(--primary)!important;
    }
    .btn-outline-primary{
        color: var(--primary)!important;
        border-color:var(--primary)!important;
    }
    
    .bg-primary{
        background-color: var(--primary)!important;
    }


    #menu-category {
        padding-left: 0px;
    }

    #menu-category li.active {
        background-color: var(--bs-primary);
        color: white
    }

    #menu-category li:first-child {
        margin-left: 0rem;
    }

    #menu-category li {
        display: inline-block;
        margin-left: 1rem;
        padding: 3px 15px 3px 15px;
        color: gray;
        border-radius: 0.5rem;
        border: 1px solid gray;
        text-align: center;
        cursor: pointer;
    }

    .item-menu {
        min-height: 100px;
        padding: 0.5rem 0.5rem 1rem 0.5rem;
        border-radius: 0.5rem;
    }

    .item-menu img {
        border-radius: 1rem;
        object-fit: cover;
        width: 100%;
        max-height: 150px;
        min-height: 150px;
    }

    .item-name {
        font-size: 1.2rem;
        font-weight: 500;
    }

    .item-price {
        font-size: 1.1rem;
        font-weight: regular;
    }

    .btn-selected {
        border-radius: 1rem;
    }

    .summary-item-qty {
        color: var(--gray-light)
    }

    .summary-item-image {
        border-radius: 20%;
        min-height: 70px;
        object-fit: cover;
        margin-left: 0.5rem;
    }

    .brand-img {
        max-width: 75px;
    }
    .btn-money-5000{
        background-image: url("img/uang/5000.jpg");
        background-size: cover;
        min-height: 50px;
        
    }

    .btn-money-2000{
        background-image: url("img/uang/2000.jpg");
        background-size: cover;
        min-height: 50px;
        
    }


    .btn-money-1000{
        background-image: url("img/uang/1000.jpg");
        background-size: cover;
        min-height: 50px;
        
    }

    .btn-money-10000{
        background-image: url("img/uang/10000.jpg");
        background-size: cover;
        min-height: 50px;
        
    }

    .btn-money-20000{
        background-image: url("img/uang/20000.jpg");
        background-size: cover;
        min-height: 50px;
        
    }

    .btn-money-50000{
        background-image: url("img/uang/50000.jpg");
        background-size: cover;
        min-height: 50px;
        
    }

    .btn-money-75000{
        background-image: url("img/uang/75000.jpg");
        background-size: cover;
        min-height: 50px;
        
    }

    .btn-money-100000{
        background-image: url("img/uang/100000.jpg");
        background-size: cover;
        min-height: 50px;
        
    }
</style>
<!-- Button trigger modal -->




<!-- Your content goes here -->
<div class=" navpos align-items-center position-fixed w-100" style="height: 70px;top:0px;left:0px;z-index: 99">
    <div class="row">
        <div class="col-2 py-3 text-center">
            <div class="bg-white" style="    border-radius: 0px 0px 20px 20px;position: relative;top: -1rem;left: 1rem;">
                <img class="brand-img img ml-3" style="width:80px;height:auto;" src="logo/35_POS_LOGO.png" alt="">
            </div>
        </div>
        <div class="col-10 text-end text-white" style="display: flex;
            flex-wrap: nowrap;
            flex-direction: row-reverse;
            align-items: center;">
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

<div class="container-fluid" id="app">
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
                <input type="text" class="form-control" id="expected-cash" v-model="closing.openAmount" @change="closingkalkulasi" disabled>
                </div>
            </div>
            <div class="row mb-3">
                <label for="expected-cash" class="col-sm-4 col-form-label">Pengeluaran</label>
                <div class="col-sm-8">
                <input type="text" class="form-control" id="expected-cash" v-model="closing.expense" @change="closingkalkulasi" disabled>
                </div>
            </div>
            <div class="row mb-3">
                <label for="expected-cash" class="col-sm-4 col-form-label">Total Transaksi Cash</label>
                <div class="col-sm-8">
                <input type="text" class="form-control" id="expected-cash" v-model="closing.expectedCash" @change="closingkalkulasi" disabled>
                </div>
            </div>
            
            <div class="row mb-3">
                <label for="withdrawal-bank" class="col-sm-4 col-form-label">Total Transaksi Bank</label>
                <div class="col-sm-8">
                <input type="text" class="form-control" id="withdrawal-bank" v-model="closing.withdrawBank" @change="closingkalkulasi" disabled>
                </div>
            </div>
            <hr>
            <div class="row mb-3">
                <label for="counted-cash" class="col-sm-4 col-form-label">Cash Terhitung</label>
                <div class="col-sm-8">
                <input type="text" class="form-control" id="counted-cash" v-model="closing.countedCash" @input="closingkalkulasi">
                </div>
            </div>
            <div class="row mb-3">
                <label for="remaining-cash" class="col-sm-4 col-form-label">Kurang/Lebih Cash</label>
                <div class="col-sm-8">
                <input type="text" class="form-control" id="remaining-cash" v-model="closing.remainingCash" @change="closingkalkulasi" disabled>
                </div>
            </div>
            <div class="row mb-3">
                <label for="comment" class="col-sm-4 col-form-label">Comment</label>
                <div class="col-sm-8">
                <textarea placeholder="Catatan khusus Closing" class="form-control" id="comment" rows="3" v-model="closing.comment"></textarea>
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
                    <input :maxlength="discount_type === 'percent' ? 2 : 10" v-model="discount_value" ref="discount_value" type="text" class="form-control" id="discount_value" placeholder="Enter your text" />
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
                <textarea maxlength="50" v-model="comment_item" ref="comment_item" type="text" class="form-control" id="comment_item" placeholder="Enter your text"></textarea>
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
                    <h4 class="modal-title" id="modalMeja">Total Bayar ({{formatMoney(grandtotal)}})</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="min-height: 400px;">
                    <div class="row">
                        <div class="col-3 mt-1">
                            <div class="fs-4 text-center">
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
                                <div class="col">
                                    <button class="btn-money-1000 btn btn-success w-100" idr-value="1000" @click="setInputCash"></button>
                                </div>
                                <div class="col">
                                    <button class="btn-money-2000 btn btn-success w-100" idr-value="2000" @click="setInputCash"></button>
                                </div>
                                <div class="col">
                                    <button class="btn-money-5000 btn btn-success w-100" idr-value="5000" @click="setInputCash"></button>
                                </div>
                                <div class="col">
                                    <button class="btn-money-10000 btn btn-success w-100" idr-value="10000" @click="setInputCash"></button>
                                </div>
                                <div class="col">
                                    <button class="btn-money-20000 btn btn-success w-100" idr-value="20000" @click="setInputCash"></button>
                                </div>
                                <div class="col">
                                    <button class="btn-money-50000 btn btn-success w-100" idr-value="50000" @click="setInputCash"></button>
                                </div>
                                <div class="col">
                                    <button class="btn-money-75000 btn btn-success w-100" idr-value="75000" @click="setInputCash"></button>
                                </div>
                                <div class="col">
                                    <button class="btn-money-100000 btn btn-success w-100" idr-value="100000" @click="setInputCash"></button>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="input-group input-group-lg mb-3">
                                        <div class="input-group-prepend input-group-lg">
                                            <span class="input-group-text">IDR </span>
                                        </div>
                                        <input :disabled="payment_cash === false" @change="kalkulasi" @change="this.select()" v-model="input_cash" placeholder="Masukan nilai Rupiah" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row pt-4">
                        <div class="col-3 mt-1">
                            <div class="fs-4 text-center">
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
                            <div class="row mb-4">
                                <div class="col">
                                    <button @click="setCashlessMethod" class="btn btn-light w-100" payment-minimal="50000" bank_name="BCA">BCA</button>
                                </div>
                                <div class="col">
                                    <button @click="setCashlessMethod" class="btn btn-light w-100" payment-minimal="50000" bank_name="BRI">BRI</button>
                                </div>
                                <div class="col">
                                    <button @click="setCashlessMethod" class="btn btn-light w-100" payment-minimal="50000" bank_name="BNI">BNI</button>
                                </div>
                                <div class="col">
                                    <button @click="setCashlessMethod" class="btn btn-light w-100" payment-minimal="1" bank_name="QRIS">QRIS</button>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-group input-group-lg mb-3">
                                    <div class="input-group-prepend input-group-lg">
                                        <span class="input-group-text">IDR </span>
                                    </div>
                                    <input placeholder="Masukan nilai Rupiah" type="text" class="form-control" :disabled="disabledPaymentBank || payment_bank === false" v-model="input_bank" />
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-group input-group-lg mb-3">
                                    <div class="input-group-prepend input-group-lg">
                                        <span class="input-group-text">0.0 </span>
                                    </div>
                                    <input :disabled="disabledPaymentBank || payment_bank === false" placeholder="Masukan Nomor Kartu" type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr  v-if="payment_cash && !payment_bank">
                    <div class="row pt-4" v-if="payment_cash && !payment_bank">
                        <div class="col-3 mt-1">
                            <div class="fs-4 text-center">
                                <div class="row">
                                    <div class="col-12">
                                        Uang Kembali
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-9 mt-1">
                            <div class="col-12">
                                <div class="input-group input-group-lg mb-3">
                                    <div class="input-group-prepend input-group-lg">
                                        <span class="input-group-text">IDR </span>
                                    </div>
                                    <input  readonly="true" v-model="change" placeholder="Change" type="text" :class="{'form-control':true,'text-danger':change === 'Uang cash kurang!'}" aria-label="Amount (to the nearest dollar)">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-4 d-none">
                        <div class="col-3 mt-1">
                            <div class="fs-4 text-center">
                                <img src="https://media.istockphoto.com/id/1191080960/photo/traditional-turkish-breakfast-and-people-taking-various-food-wide-composition.jpg?s=612x612&w=0&k=20&c=PP5ejMisEwzcLWrNmJ8iPPm_u-4P6rOWHEDpBPL2n7Q=" width="60" />
                            </div>
                        </div>
                        <div class="col-9 mt-1">
                            <button class="btn btn-danger btn-lg "><i class="fa fa-qrcode"></i> Tampilkan QR Code</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">Batal</button>
                    <button :disabled="leftAmount > 0" @click="bayar(1)" type="button" class="btn btn-success btn-lg" data-bs-dismiss="modal">Bayar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <img src="..." class="rounded me-2" alt="...">
                <strong class="me-auto">Bootstrap</strong>
                <small>11 mins ago</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Hello, world! This is a toast message.
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
                <div class="modal-body" style="min-height: 400px;">
                    <div class="row g-2">
                        <div class="col-3" v-for="item in table" :key="item.id">
                            <button :disabled="keranjang.length <= 0 && item.active === false" @click="dineIn(item)" :class="{ 'btn-lg':true,'btn':true,'btn-outline-primary':item.active === true,'w-100':true}"><i class="fa fa-chair"></i> Meja {{item.no_meja}} </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="z-index: 1;margin-top: 5rem;">
        <div class="col-8 ">
            <h5 class="mb-4 mt-4">Menu Items <span style="color:var(--gray-light)">({{items.length}})</span></h5>
            <div class="row mt-3 mb-2">
                <div class="col-12">
                    <div class="input-group mb-3">
                        <input v-model="search_keyword" @input="searchItem" type="text" class="form-control" placeholder="Cari Items" aria-label="Recipient's username" aria-describedby="basic-addon2" />
                        <div class="input-group-append">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="">
                <div style="width:100000px;white-space: nowrap;overflow-x: auto;">
                    <ul id="menu-category">
                        <li @click="filterCategory('all')" :class="{'active':activeCategory === 'all'}">Semua Kategori</li>
                        <li @click="filterCategory(item.id)" :class="{'active':item.id === activeCategory}" v-for="item in categories" :key="item.id">{{item.category.toLowerCase()}}</li>
                    </ul>
                </div>
            </div>
            <div class="row gy-3 gx-2"> 
                <div class="col-1 col-lg-4 col-md-4 col-sm-3" v-for="item in items" :key="item.id">
                    <div :class="{ 'item-menu':true,'card':true,'border-primary': !isInKeranjang(item.id), 'border-3': !isInKeranjang(item.id) }" style="min-height:315px;">
                        <div class="row" @click="add(item.id)">
                            <img src="https://food.fnr.sndimg.com/content/dam/images/food/fullset/2018/10/4/1/FN_chain-restaurant-entrees_Applebees_Bourbon-Street-Chicken-Shrimp_s6x4.jpg.rend.hgtvcom.616.411.suffix/1538685780055.jpeg" alt="item menu" />
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
                                    <button @click="add(item.id)" class="btn btn-outline-primary w-100 rounded">Tambah Item <i class=" fa fa-plus"></i></button>
                                </div>
                                <div v-else>

                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button @click="minQTY(item.id)" class="btn btn-primary" type="button"><i class="fa fa-minus"></i></button>
                                        </span>
                                        <input v-model="keranjang.filter((obj) => obj.id == item.id)[0].qty" type="text" class="form-control text-center" readonly />
                                        <span class="input-group-btn">
                                            <button @click="addQTY(item.id)" class="btn btn-primary" type="button"><i class="fa fa-plus"></i></button>
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div class="col-4">
            <div class="card mt-4 position-fixed " style="width: -webkit-fill-available;">
                <div class="card-header p-3"> <span class="h5">Ringkasan Pesanan <span style="color: var(--gray-light);">{{activeTable}}</span>
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        <button @click="updateTableItems" :class="{ 'ml-1':true, 'btn':true, 'btn-success' : true, 'btn-sm':true, 'd-none':activeTableNumber == ''  }"><i class="fa fa-check"></i></button>
                </div>
                <div class="card-body">
                    <div v-if="keranjang.length > 0"><span class="fw-medium">Total Items</span> <span style="color:var(--gray-light)">({{ keranjang.length}})</div>
                    <div id="chart-items" id="summary-item" style="height:400px;overflow-y:auto;overflow-x:hidden">
                        <div v-if="keranjang.length <=0" class="pt-2 pb-2 mt-2 w-100">
                            <div class="row justify-content-center align-items-center">
                                <div class="col-12 text-center" style="color: var(--gray-light)">
                                    <i class="fa fa-cart-plus "></i> Keranjang kosong
                                </div>
                            </div>
                        </div>
                        <div v-if="keranjang.length > 0" class="card pt-2 pb-2 mt-2" v-for="item in keranjang" :key="item.id">
                            <div class="row justify-content-center align-items-center">
                                <div class="col-3">
                                    <img class="w-100 p-1 summary-item-image" src="https://media.istockphoto.com/id/1191080960/photo/traditional-turkish-breakfast-and-people-taking-various-food-wide-composition.jpg?s=612x612&w=0&k=20&c=PP5ejMisEwzcLWrNmJ8iPPm_u-4P6rOWHEDpBPL2n7Q=" alt="item menu" />
                                </div>
                                <div class="col-5 ">
                                    <div class="summary-item-name fs-9">{{item.nama}}</div>
                                    <div class="summary-item-qty">({{formatMoney(item.harga_jual)}}) x {{item.qty}}</div>
                                </div>
                                <div class=" col-2 summary-item-deletion">
                                    <div @click="removeItem(item.id)" class="card" style="width: 30px;height: 30px;justify-content: center;align-items: center;border-radius: 50%;">
                                        <i class="fa fa-trash " style="color: #deb7b7;"></i>
                                    </div>
                                </div>
                                <div class=" col-2 summary-item-note">
                                    <div @click="addNote(item.id)" class="card" style="width: 30px;height: 30px;justify-content: center;align-items: center;border-radius: 50%;">
                                        <i class="fa fa-note-sticky " style="color: rgb(226 228 92);"></i>
                                    </div>
                                </div>
                            </div>
                            <div v-if="item.comment != '' ">
                                <div class="ml-2 ps-3">
                                    <p style="font-style:italic;color:brown" class="py-0">{{item.comment}}</p>
                                </div>
                            </div>
                        </div> <!-- end card -->
                    </div>

                    <div class="card mt-4 ">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">Subtotal</div>
                                <div class="col-6 text-end">{{formatMoney(subtotal)}}</div>
                            </div>
                            <div class="row">
                                <div class="col-6 text-danger">Diskon 
                                <div @click="showDiscountPopup()" class="card d-inline-block border-danger" style="border-radius: 50%;">
                                    <div style="display: flex;align-items: center;justify-content: center;width: 30px;height: 30px;">    
                                        <i class="fa fa-edit text-danger "></i>
                                    </div>
                                </div>
                            
                            </div>
                                <div class="col-6 text-end">{{formatMoney(discount)}}</div>
                            </div>
                            <div class="row">
                                <div class="col-6">Pajak ({{percent_tax}}%)</div>
                                <div class="col-6 text-end">{{formatMoney(tax)}}</div>
                            </div>
                            <div class="row">
                                <div class="col-6">Service ({{percent_service}}%)</div>
                                <div class="col-6 text-end">{{formatMoney(service)}}</div>
                            </div>

                            <div class="row justify-content-center align-items-center">
                                <hr>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6">Pembulatan</div>
                                <div class="col-6 text-end">{{formatMoney(rounded)}}</div>
                            </div>
                            <div class="row">
                                <div class="col-6 h5">Total</div>
                                <div class="col-6 text-end h5">{{formatMoney(grandtotal)}}</div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-3">
                                    <button :disabled="activeTableNumber != ''" type="button" class="btn btn-primary w-100 btn-lg" data-bs-toggle="modal" data-bs-target="#modalTable">
                                        <i class="fa fa-chair"></i>
                                    </button>
                                </div>
                                <div class="col-3">
                                    <button @click="cleanUpOrder" type="button" class="btn btn-primary w-100 btn-lg">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                </div>
                                
                                <div class="col-6">
                                    <button :disabled="keranjang.length <= 0" type="button" class="btn btn-success w-100 btn-lg" data-bs-toggle="modal" data-bs-target="#modal-payment">Lanjut Bayar</button>
                                    
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <button @click="clickClosing" type="button" class="btn btn-primary w-100 btn-lg">
                                        <i class="fa fa-refresh"></i> Closing
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
    const app = Vue.createApp({
        methods: {
            clickTutupRegister(){
                let vm = this;
                let tanggal = '<?php echo date("Y-m-d"); ?>';
                $.ajax({
                url:'<?=$this->createUrl('sales/cetakrekap')?>',
                data:'tanggal_rekap='+tanggal+"&uangmasuk="+vm.closing.countedCash,
                beforeSend : function(){
                    vm.disabledTutupRegister = true;
                },  
                success: function(data){
                    var json = jQuery.parseJSON(data);
                    var jenis_printer = '<?php echo SiteController::getConfig("jenis_printer"); ?>';
                    if (jenis_printer === "Mini Printer" )
                      print_rekap(json,false);               
                    vm.disabledTutupRegister = false;     
                    setTimeout(function() {
                        alert("Data berhasil disimpan!, sistem akan logout secara otomatis");
                        window.location.assign("<?php echo $this->createUrl('site/logout'); ?>");
                    }, 100);
                },
                error: function(data){
                    alert('error');
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
                this.closing.remainingCash = parseInt(this.closing.countedCash) - ( parseInt(this.closing.expectedCash) + parseInt(this.closing.openAmount));
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
                if (this.leftAmount >= 0 && this.payment_bank){
                    this.input_bank = this.leftAmount;
                }else{
                    this.input_bank = "";
                }
                this.kalkulasi()
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


                this.kalkulasi()
            },
            kalkulasi() {
                const vm = this;


                if (this.payment_bank === false && this.payment_cash === true ){
                    if (this.leftAmount === this.grandtotal){
                    }else if (this.leftAmount > 0){ // jika masih ada sisa
                        this.disabledPaymentBank = false;
                        if (this.payment_bank)   
                            this.input_bank = this.leftAmount;
                        else
                            this.input_bank = "";
                    }
                    else{
                        // this.payment_bank = false;
                        // this.disabledPaymentBank = true;
                    }
                }


                vm.leftAmount = vm.grandtotal - ( (isNaN(vm.input_cash) ? 0 : vm.input_cash) + (isNaN(vm.input_bank) ? 0 : vm.input_bank)) ;
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
                if (vm.payment_cash && !vm.payment_bank)
                    vm.change = vm.input_cash > vm.grandtotal ?  vm.formatMoney(vm.input_cash - vm.grandtotal) : "Uang cash kurang!";
                


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
                        obj.nama.toLowerCase().includes(event.target.value)
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
                let vm = this;
                let sales = {
                    sale_id: null,
                    subtotal: vm.subtotal,
                    discount: vm.discount,
                    tax: vm.tax,
                    service: vm.service,
                    total_cost: vm.grandtotal,
                    payment: null,
                    paidwith_id: vm.payment_bank_method === "" ? "CASH" : vm.payment_bank_method,
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
                        cash: total_cash,
                        edcbca: vm.input_bank,
                        edcniaga: 0,
                        voucher: vm.discount,
                        compliment: 0,
                        dll: 0
                    }
                    let sales_items = [];
                    vm.keranjang.forEach(function(rec) {
                        sales_items.push({
                            "item_id": rec.id,
                            "item_name": rec.nama,
                            "quantity_purchased": rec.qty,
                            "item_tax": 0,
                            "item_discount": 0,
                            "item_price": rec.harga_jual,
                            "item_total_cost": rec.harga_jual * rec.qty,
                            "permintaan": rec.comment
                        });
                    });

                    if (sales_items.length < 0) {
                        alert("gagal!");
                    }

                    $.ajax({
                        url: 'index.php?r=sales/bayar',
                        type: 'POST',
                        data: {
                            data: sales,
                            data_detail: sales_items,
                            data_payment: sales_payment
                        },
                        success: function(data) {
                            var sales = JSON.parse(data);
                            vm.refreshTable();
                            vm.cleanUpOrder()
                    
                            vm.modalTable.hide();
                            if (sales.status == 1)
                                {
                                    var jenis_cetak = '<?php echo SiteController::getConfig("ukuran_kertas"); ?>';
                                    var jenis_printer = '<?php echo SiteController::getConfig("jenis_printer"); ?>';

                                    if (jenis_cetak=="24cmx14cm" || jenis_cetak=="12cmx14cm"){

                                        var c = confirm("Cetak Bukti ?? ");
                                        if (c){	
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
                                        // window.open("<?php echo Yii::app()->createUrl("Sales/cetakfaktur") ?>&id="+idx);
                                        }
                                    }else if ( (jenis_cetak=="80mm" || jenis_cetak=="58mm") && jenis_printer === "Epson LX" ){
                                        var c = confirm("Cetak Bukti ?? ");
                                        if (c){ 
                                            window.open("<?php echo Yii::app()->createUrl("Sales/cetakfaktur_mini") ?>&id="+sales.sale_id);
                                        }
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
                            alert(data);
                        }
                    });
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
                this.payment_cash = true;
                this.payment_bank = false;
                this.search_keyword = "";
                this.discount_type = "percent";
                this.discount_value = 0;
                this.payment_bank_method = "";

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
                if (this.payment_cash)
                    this.input_cash = event.currentTarget.getAttribute("idr-value");
                this.kalkulasi();
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

        },
        data() {
            return {
                subtotal: 0,
                discount: 0,
                discount_value: 0,
                discount_type: "percent", //percent or amount
                percent_tax: <?php echo Parameter::model()->find(" store_id = '".Yii::app()->user->store_id()."' ")->pajak ?>,
                percent_service: <?php echo Parameter::model()->find(" store_id = '".Yii::app()->user->store_id()."' ")->service ?>,
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
                payment_cash: true,
                payment_bank: false,
                payment_bank_method: "",
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
            this.refreshTable();
        },
        watch: {
            "grandtotal": function() {
                this.input_cash = this.estimate(this.grandtotal);
            },
            "input_cash": function() {
                this.kalkulasi();
            },
            "input_bank": function() {
                this.kalkulasi();
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