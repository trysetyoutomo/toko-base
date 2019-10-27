<?php 
$usaha = SiteController::getConfig("jenis_usaha");
$tampil_pajak = SiteController::getConfig("tampil_pajak");
$tampil_service = SiteController::getConfig("tampil_service");
if ($usaha=="Toko"){
	$bool_tipe_harga_hidden = 'false';
    $bool_tipe_satuan_hidden = 'false';
    $bool_input_qty = 'false';
}else{
    $bool_tipe_harga_hidden = 'true';
    $bool_tipe_satuan_hidden = 'true';
	$bool_input_qty = 'true';
}
?>
<script type="text/javascript">
Ext.define('SalesItems',{
    extend:'Ext.data.Model',
    autoLoad:true,
    fields:[
    {
        name:'kode',
        type:'string'
    },
    {
        name:'id',
        type:'integer'
    },
    {
        name:'sale_id',
        type:'integer'
    },
    {
        name:'lokasi',
        type:'integer'
    },
    {
        name:'item_id',
        type:'string'
    },
    {
        name:'item_name',
        type:'string'
    },
    {
        name:'item_satuan',
        type:'string'
    },
    {
        name:'item_price_tipe',
        type:'string'
    },
     {
        name:'item_satuan_id',
        type:'string'
    },
    {
        name:'quantity_purchased',
        type:'integer'
    },
    {
        name:'item_tax',
        type:'integer'
    },
       {
        name:'item_service',
        type:'integer'
    },
    {
        name:'item_price',
        type:'integer'
    },
    {
        name:'item_discount',
        type:'integer'
    },
    {
        name:'item_total_cost',
        type:'integer'
    },
    {
        name:'ukuran',
        type:'integer'
    },
    {
        name:'ketebalan',
        type:'integer'
    },
    {
        name:'panjang',
        type:'integer'
    },
     {
        name:'permintaan',
        type:'string'
    },
    {
        name:'is_paket',
        type:'string'
    }
    
    ]
});

var liveSearchPanel_SalesItems; 
var empGroupStore_SalesItems; 
var id_inc=0;
var Ext;
var jumlah_baris;
var meja_cetak = 0;
var meja_tipe_cetak;
var item_master_id = 0;



	// var init_satuan = [['1','kode'],['2','mantap']];
	// var init_satuan = [['1','kode'],['2','mantap']];
	var init_satuan = [];
	var init_tipe = [];


	var proxy = new Ext.data.proxy.Ajax({
        // id : "prox"
        type:'ajax',
        url:'index.php?r=ItemsSatuan/GetSatuanByItemID',
        //  extraParams: {
        //     id: item_master_id
        // },
        reader:{
            type:'json',
            root:'data',
            totalProperty:'totalCount'
        }
	});
	var proxy_tipe = new Ext.data.proxy.Ajax({
        // id : "prox"
        type:'ajax',
        url:'index.php?r=ItemsSatuan/GetTipePriceSatuan',
        //  extraParams: {
        //     id: item_master_id
        // },
        reader:{
            type:'json',
            root:'data',
            totalProperty:'totalCount'
        }
	});

    var storeTipe = new Ext.data.SimpleStore({
        id : "proxy-tipe",
        fields: ['id', 'price_type'],
        data : init_tipe,
		 proxy: proxy_tipe,
         listeners: {
         	beforequrey:function(){
         		
         	},
	        load: function(store, records, successful, eOpts) {
	            if (successful) {
	            	
	            }
	        }
   		 },
   		  autoLoad:{
            start: 1, 
            limit: 50
        }
    });

      var sizeDropdownStore = new Ext.data.SimpleStore({
        id : "proxy-satuan",
        fields: ['satuan_id', 'nama_satuan'],
        data : init_satuan,
		 proxy: proxy,
         listeners: {
         	beforequrey:function(){
         		
         	},
	        load: function(store, records, successful, eOpts) {
	            if (successful) {
	            	// alert('123');
	            }
	        }
   		 },
   		  autoLoad:{
            start: 1, 
            limit: 50
        }
    });
      
    
    var formCombo = new Ext.form.ComboBox({
                typeAhead: true,
                // triggerAction: 'all',
                mode: 'local',
                emptyText : "Pilih",
                editable :false,
                // listClass: 'x-combo-list-big',
                store: sizeDropdownStore,
                valueField: 'nama_satuan',
                displayField: 'nama_satuan',
                listeners:
                {
                	beforequrey:function(){
                		alert("123");

                	},
					load:function() {
						alert("123");

						
					},
					select:function(e){

						var grid = liveSearchPanel_SalesItems;
						var selectedRecord = grid.getSelectionModel().getSelection()[0];
						var row = grid.store.indexOf(selectedRecord);
						// console.log(this);
						
						var models = grid.getStore().getRange();
						var x = formComboTipeHarga.getStore();
			            x.proxy.extraParams.id= this.value;
			            // x.proxy.extraParams.id= models[row].get("item_satuan_id");
			            x.load();

						// alert("123");
					}
				}
	});
	 var formComboTipeHarga = new Ext.form.ComboBox({
                typeAhead: true,
                // triggerAction: 'all',
                mode: 'local',
                emptyText : "Pilih",
                editable :false,
                // listClass: 'x-combo-list-big',
                store: storeTipe,
                valueField: 'price_type',
                displayField: 'price_type',
                listeners:
                {
                	beforequrey:function(){

                	},
					load:function() {

						
					}
				}
	});

//Ext.Loader.setConfig({enabled: true});

// Ext.require([
//     'Ext.grid.*',
//     'Ext.data.*',
//     'Ext.util.*',
//     'Ext.state.*',
//     'Ext.toolbar.Paging',
//     'Ext.ux.PreviewPlugin',
//     'Ext.ModelManager',
//     'Ext.tip.QuickTipManager'
// ]);
function cetakbillterakhir(){
	var c = confirm("Yakin  ? ");
	if (!c){
		return false;
		// exit;
	}
	  $.ajax({
        url : 'index.php?r=sales/CetakBillTerakhir',
        type : 'GET',
		beforeSend : function(){
			$(".full").fadeIn();
		},
        success : function(data)
        {


			$(".full").fadeOut();
            var sales = jQuery.parseJSON(data);
            if (sales.sale_id!="")
            {
				print_bayar(sales);      
            }else{

            }

        },
        error : function(data)
        {
            alert("Tidak bsia mencetak, Terjadi Kesalahan : "+JSON.stringify(data));
        }
    });
}

function kalkulasiRow(models,row){
	// alert(persen);
	// alert(persen_svc);
	var angka_diskon = models[row].get("item_discount");

	var subtotal = Math.round(models[row].get("item_price")*models[row].get("quantity_purchased") );
	var total_diskon = Math.round((angka_diskon/100)*subtotal );
	// subtotal = subtotal-total_diskon;

	var tt_pajak = Math.round(persen*subtotal);
	var tt_svc = Math.round(persen_svc*subtotal);

	var item_total_cost = tt_pajak+tt_svc+subtotal-total_diskon; 

	models[row].set("item_tax",tt_pajak);
// 
	models[row].set("item_service",tt_svc);

	models[row].set("item_total_cost",item_total_cost);
	// var d = (models[row].get("item_discount")/100) * (models[row].get("item_price")*models[row].get("quantity_purchased"));
	// models[row].set("item_total_cost",models[row].get("item_service")+models[row].get("item_tax")+(models[row].get("item_price")*models[row].get("quantity_purchased")) - d);

						


					
	// models[row].set("item_tax",(models[row].get("item_price")*persen)*models[row].get("quantity_purchased"));
	// models[row].set("item_service",(models[row].get("item_price")*persen_svc)*models[row].get("	quantity_purchased"));
						
	// var d = models[row].get("item_discount") * (models[row].get("item_price")*models[row].get("quantity_purchased"));


	kalkulasi1();

}

function void_bayar(status, table,sale_id)
{
    //    alert(table);
    var subtotal = $('#sum_sub_total').html();
    var discount =$('#sum_sale_discount').html();
    var tax =$('#sum_sale_tax').html();
    var service=$('#sum_sale_service').html();
    var total_cost=$('#sum_sale_total').html();
    var payment=$('#pembayaran').val();
    var paidwith=$('#payment').val();
    
	// var bayar=$('#bayar').val();
	if ($('#bayar').val()==""){	
	    var bayar = $('#bayar').val();
	}else{
	    var bayar = $('#total_hutang').val();
	}



    var cash=$('#cash').val();
    var edcbca=$('#edcbca').val();
    var edcniaga=$('#edcniaga').val();
    var voucher = $('#voucher').val();
    var compliment=$('#compliment').val();
    var dll=$('#dll').val();
	
	
	payment = {
		cash : cash,
		edcbca : edcbca,
		edcniaga : edcniaga,
		voucher : voucher,
		compliment : compliment,
		dll : dll
	}
	
	data = {
        sale_id : sale_id,
        subtotal : subtotal,
        discount : discount,
        tax : tax,
        service : service,
        total_cost : total_cost,
        payment : payment,
        paidwith : paidwith,
        status : status,
        table : table
    };
    var data_detail = [];
    var inc = 0;
    liveSearchPanel_SalesItems.store.each(function (rec) { 
        //        var temp = new Array(10,10);
        //        temp['item_price'].push(rec.get('item_total_cost'));
        //        data_detail[0]['quantity_purchased']=rec.get('quantity_purchased');
        data_detail[inc] = {
            "item_id":rec.get('item_id'),
            "quantity_purchased":rec.get('quantity_purchased'),
            "item_tax":rec.get('item_tax'),
            "item_discount":rec.get('item_discount'),
            "item_price":rec.get('item_price'),
            "item_total_cost":rec.get('item_total_cost')
        };
        inc=inc+1;
    });
       console.log(payment);

    $.ajax({
        url : 'index.php?r=sales/void',
        data : {
            data:data,
            data_detail:data_detail,
			data_payment : payment
        },
        success : function(data)
        {
            //var obj = jQuery.parseJSON(data);
             
            if (data=="success")
            {
                $("#dialog_bayar").dialog("close");
                liveSearchPanel_SalesItems.store.removeAll();
                $('#sum_sub_total').html(0);
                $('#sum_sale_discount').html(0);
                $('#sum_sale_tax').html(0);
                $('#sum_sale_service').html(0);
                $('#sum_sale_total').html(0);
                $('#pembayaran').val(0);
                $('#payment').val(0);
                $("#e1").select2("close");
                $('#dialog_meja').load('index.php?r=site/table');
                $("#dialog_bayar").dialog("close");
                $("#dialog_bayar2").dialog("close");
      
               
                show_meja('Meja');
            }
        },
        error : function(data)
        {
            //alert(data);
            $('#dialog_meja').load('index.php?r=site/table');
        }
    });
}

function useDefaultPrinter() {
    var applet = document.jzebra;
    if (applet != null) {
        // Searches for default printer
        <?php 
        $printer_setting = Parameter::model()->findByPk(1)->printer_utama;
        ?>
        // var s_printer = '<?php //echo $printer_setting ?>';
        // if (s_printer!='')
	        // applet.findPrinter(s_printer);
	        applet.findPrinter();
	    // else
	    // 	alert("Tidak bisa print, Printer belum di pilih !!");
    }
         
    monitorFinding();
}
function monitorFinding() {
    var applet = document.jzebra;
    if (applet != null) {
        if (!applet.isDoneFinding()) {
            window.setTimeout('monitorFinding()', 100);
        } else {
            var printer = applet.getPrinter();
            // alert(printer == null ? "Printer not found" );
            // alert(printer == null ? "Printer not found" : "Printer \"" + printer + "\" found");
            // alert(printer == null ? "Printer not found":none);
        }
    } else {
        alert("Applet not loaded!");
    }
}
function chr(i){	
	return String.fromCharCode(i);
}

function jarak(){
		useDefaultPrinter();
		var applet = document.jzebra;
		if (applet != null) {
				applet.append("\n");
				applet.append("\x1Bm");
				applet.print();
		}
}

function print_bayar(data) {
try{	
	useDefaultPrinter();
	var applet = document.jzebra;
	applet.init();
	if (applet != null && data!= null) {


		// Send characters/raw commands to applet using "append"
		// Hint:  Carriage Return = \r, New Line = \n, Escape Double Quotes= \"
		applet.append(chr(27)+chr(69)+"\r");//perintah untuk bold
		// applet.append(chr(27)+"\x61"+"\x31"); //perintah untuk center
		applet.append(chr(27) + "\x61" + "\x31"); // center justify
		applet.append(chr(27) + chr(33) + chr(128));//underliner
		
		applet.append(data.logo+"\r\n\n");
		applet.append(chr(27) + chr(64));//cancel character sets			
		applet.append(chr(27) + "\x61" + "\x31"); // center justify
		// applet.append(data.per+"\r\n");
		applet.append(data.alamat+"\r\n");
		applet.append(data.no_telp+"\r\n");
		applet.append(data.trx_tgl+"\r\n");
		applet.append(data.no_nota+"\r\n");
		applet.append(chr(27) + chr(64));//cancel character sets
		applet.append("\n");

		//applet.print();
		// alert(data.mejavalue);
		//if(data.mejavalue!=null){
		//applet.append(data.no_meja+data.mejavalue);
		//applet.append("\n");
		//}

		applet.append(data.kasir);
		applet.append("\n");
		if(data.namapelanggan!=null){
			applet.append(data.namapelanggan);
			applet.append("\n");
		}
		// applet.append("\n");


		applet.append("\n");
		applet.append(data.pembatas);
		applet.append("\n");
		// alert(JSON.stringify(data.detail));
		// var sales = jQuery.parseJSON(data);
		$.each(data.detail, function(i,cetak) {
			applet.append(cetak.quantity);
			applet.append("\n");
			applet.append(cetak.nama_item);
			applet.append("\n");
		});
		applet.append(data.pembatas);
		applet.append("\n");
		applet.append(data.subtotal);
		applet.append(data.discount);
		<?php 
        if ($tampil_pajak=="1"){
        ?>
        applet.append(data.ppn);
        <?php
        } 
        ?>

        <?php 
        if ($tampil_service=="1"){
        ?>
        applet.append(data.service);
        <?php
        } 
        ?>

        // applet.append(data.service);
        

		applet.append(data.pembatas);
		applet.append("\n");
		applet.append(data.total);
		if (data.cd==1){
			applet.append(data.voucher);
		}
		applet.append(data.bayar);
		applet.append(data.kembali);
		applet.append(data.line_bawah);
		applet.append(data.pcm);
		applet.append(data.slogan);


		//alert('berhasil');
		// applet.append("A590,1570,2,3,1,1,N,\"Testing the print() function\"\n");
		// applet.append("27,112,0,55,27\"Testing the print() function\"\n");

		// applet.append("Ramdani memang kasep \n");

		// applet.append(data.cinta);
		applet.append("\n");
		applet.append("\n");
		applet.append("\n");
		applet.append("\n");
		applet.append("\n");
		applet.append("\n");
		applet.append("\n");
		applet.append("\x1Bm");
		// var multiple = applet.getAllowMultipleInstances();
		// applet.allowMultipleInstances(true);	
		// applet.setEndOfDocument("P1,1\r\n");
		// applet.setEndOfDocument("^XZ");
		// applet.setDocumentsPerSpool("2");
		// // applet.setDocumentsPerSpool(3);
		// applet.clear();	
		// applet.clear();				
		// applet.print();		
		// Send characters/raw commands to printer
		if (data.cd!=0){
			var isDrawer  = '<?php echo Parameter::model()->findByPk(1)->drawer; ?>'
			if (isDrawer==1){		
				applet.append(chr(27)+"\x70"+"\x30"+chr(25)+chr(25)+"\r");
			}
		// bayar_lagi(data);
		}
		applet.print();
		/*
		(function myLoop(i){
			setTimeout(function(){
				// alert("hellow");
				if (--i) myLoop(i);
			//	call myLoop again if i>0
			},1000)
		})(10);
		*/
	

		//	applet.clearException();

	}	

	}
	catch(err) {
		alert(err);
		// alert("silahkan refresh halaman ini");
	}
}

function print_items(lokasi,lokindex) {
	// alert(lokasi);
	try { 
	var applet = document.jzebra;
	applet.findPrinter(lokasi);

	if (lokasi == "POS-80C"){
		lokasi = "BAR";
	}else{
		lokasi = "KITCHEN";
	}
 	var data_detail = [];
    var inc = 0;
	var namapel = $('#namapel').val();
	//alert(namapel);
	$(".baris").each(function() {
			var idb = $(this).find('.pk').html();
			var nama = $(this).find('.nama_menu').html();
			var lokasi = $(this).find('.pk').attr("lokasi");
			var jml = $(this).find('.jumlah').find('.input-jumlah').val();
			var permintaan = $(this).find('.permintaan').find('.area-permintaan').val();
			var belum_print = $(this).find('.pk[cetak=0]').length;
			if (belum_print!=0){
				if (lokasi==lokindex){
				     data_detail.push({
				        "item_id":idb,
				        "item_name":nama,
						"namapel":namapel,
				        "quantity_purchased":jml,
				        "permintaan":permintaan,
				        "lokasi":lokasi
				    });
				 }
			 }
	
	    inc=inc+1;

	});
				if (data_detail.length>0){
				applet.append(chr(27) + chr(33) + chr(128));//underliner
				applet.append("\n PERMINTAAN KEPADA : "+lokasi+"\r\n");
				applet.append("\n MEJA : "+meja_cetak+"\r\n");
				applet.append(chr(27) + chr(64));//cancel character sets			
				applet.append(chr(27) + chr(64));//cancel character sets
				applet.append("\n");
				applet.append("  Tanggal : ");
				applet.append("<?php echo date('d M Y') ?>");
				applet.append("\n");
				applet.append("  Jam     : ");
				applet.append("<?php echo date('H:i:s') ?>");
				applet.append("\n");
				applet.append("  Waiter  : ");
				applet.append("<?php echo Yii::app()->user->name; ?>");
				applet.append("\n");
				applet.append("  Nama    : "+namapel+"\n");
				
			
				applet.append("  Tipe    : "+meja_tipe_cetak+"\n");
				applet.append("\n");
				
				
				applet.append("\n");
				applet.append("--------------------------------------");
				applet.append("\n");
				applet.append("\n");
				//applet.append("<table width = '500'>");
				//applet.append("<tr>");
				//applet.append("<th>Nama</th>");
				//applet.append("<th>Jumlah</th>");
				//applet.append("</tr>");
				//applet.append("</table>");
	        	$.each(data_detail,function(i,cetak) {
				
					if (cetak.permintaan==''){
						applet.append("  "+cetak.item_name+" x "+cetak.quantity_purchased);
						applet.append("\n");
					}else{
	        		// if (cetak.lokasi==lokindex){	
						applet.append("  "+cetak.item_name+" x "+cetak.quantity_purchased+" / '" +cetak.permintaan + "' ");
						applet.append("\n");
	        		// }
					// applet.append(cetak.nama_item);
					// applet.append("\n");
					// alert('repeat');
					}
				});
				
				 applet.append("\n");
				 applet.append("\n");
				 applet.append("\n");
				 applet.append("\n");
				 applet.append("\n");
				 applet.append("\n");
				 applet.append("\n");
				 applet.append("\x1Bm"); 
				// applet.append("______________________")
				applet.print();
				}
				return true;
			}
		catch(err) {
	        alert(err);
	        alert("tidak bisa print , halaman ini akan reload, dan silahkan ulangi kembali penginputan menu ! ");
	        window.location.reload();
	        exit;
	        return false;
	    }
}


function opencash(){
useDefaultPrinter();
 var applet = document.jzebra;
  applet.append(chr(27)+"\x70"+"\x30"+chr(25)+chr(25)+"\r");
  applet.print();
}

function print_keluar(data) {

	useDefaultPrinter();
	var applet = document.jzebra;

	applet.append(chr(27)+chr(69)+"\r");//perintah untuk bold
	// applet.append(chr(27)+"\x61"+"\x31"); //perintah untuk center
	applet.append(chr(27) + "\x61" + "\x31"); // center justify
	applet.append(chr(27) + chr(33) + chr(128));//underliner
	
	applet.append(data.logo+"\r\n\n");
	applet.append(chr(27) + chr(64));//cancel character sets			
	applet.append(chr(27) + "\x61" + "\x31"); // center justify
	// applet.append(data.per+"\r\n");
	applet.append(data.alamat+"\r\n");
	applet.append(data.no_telp+"\r\n");
	applet.append(data.tgl_keluar+"\r\n");
	applet.append("Petugas :"+data.user+"\r\n");
	if (data.jenis_keluar=="pengalihan")
		applet.append(data.jenis_keluar+" ("+data.keluar_ke+") "+"\r\n");
	else
		applet.append(data.jenis_keluar+"\r\n");
	applet.append(chr(27) + chr(64));//cancel character sets
	applet.append("\n");

	// applet.append(chr(27) + chr(33) + chr(32));//double weidth
	// applet.append(chr(27) + "\x61" + "\x31"); // center justify
	// applet.append("BARANG KELUAR \r\n"); // center justify
	// applet.append(chr(27) + chr(64));//cancel character sets	
	applet.append(data.pembatas);
	// alert(JSON.stringify(data.gratis))
	// var curr = data.gratis[0].category;
	// applet.append("\n\n "+curr+" \n");
	// applet.append(data.pembatas);
	// alert(curr);
	var no = 1;
	var c;
	var baris;
	$.each(data.gratis, function(i,cetak){
	// applet.append("\n");
	
	if (c!=cetak.category){
		no= 1;
		c = cetak.category;
		baris = c;
		// baris = "\n\n "+c+"\n"+data.pembatas;
		applet.append(chr(27) + chr(33) + chr(32));//double weidth
		// applet.append(chr(27) + "\x61" + "\x31"); // center justify
		applet.append("\n\n"+baris+" \r\n"); // center justify
		applet.append(data.pembatas);
		applet.append(chr(27) + chr(64));//cancel character sets	
		// applet.append(baris);
		applet.append("\n");
		// no = 1;
	}else{
	
		applet.append("\n");
		// applet.append("\n\n "+c+" \n");
		// applet.append(data.pembatas);
	}

		// applet.append("\n");
		applet.append(no+". "+cetak.nama+"\n"+cetak.total);

		// applet.append(chr(27) + chr(64));//cancel character sets		
		no++;
	});
	applet.append("\n");
	applet.append("\n");
	applet.append("\n");
	applet.append(data.total_gratis);

	applet.append("\n");
	applet.append("\n");
	applet.append("\n");
	applet.append("\n");
	applet.append("\n");
	applet.append("\n");
	applet.append("\n");
	applet.append("\x1Bm"); 
	// alert(data);
            
        // Send characters/raw commands to printer
      
		applet.print();



}
function print_masuk(data) {

	useDefaultPrinter();
	var applet = document.jzebra;

	applet.append(chr(27)+chr(69)+"\r");//perintah untuk bold
	// applet.append(chr(27)+"\x61"+"\x31"); //perintah untuk center
	applet.append(chr(27) + "\x61" + "\x31"); // center justify
	applet.append(chr(27) + chr(33) + chr(128));//underliner
	
	applet.append(data.logo+"\r\n\n");
	applet.append(chr(27) + chr(64));//cancel character sets			
	applet.append(chr(27) + "\x61" + "\x31"); // center justify
	// applet.append(data.per+"\r\n");
	applet.append(data.alamat+"\r\n");
	applet.append(data.no_telp+"\r\n");
	applet.append(data.tgl_keluar+"\r\n");
	if (data.jenis_keluar=="pengalihan")
		applet.append(data.jenis_keluar+" ("+data.keluar_ke+") "+"\r\n");
	else
		applet.append(data.jenis_keluar+"\r\n");
	applet.append(chr(27) + chr(64));//cancel character sets
	applet.append("\n");

	// applet.append(chr(27) + chr(33) + chr(32));//double weidth
	// applet.append(chr(27) + "\x61" + "\x31"); // center justify
	// applet.append("BARANG KELUAR \r\n"); // center justify
	// applet.append(chr(27) + chr(64));//cancel character sets	
	applet.append(data.pembatas);
	// alert(JSON.stringify(data.gratis))
	// var curr = data.gratis[0].category;
	// applet.append("\n\n "+curr+" \n");
	// applet.append(data.pembatas);
	// alert(curr);
	var no = 1;
	var c;
	var baris;
	$.each(data.gratis, function(i,cetak){
	// applet.append("\n");
	
	if (c!=cetak.category){
		no= 1;
		c = cetak.category;
		baris = c;
		// baris = "\n\n "+c+"\n"+data.pembatas;
		applet.append(chr(27) + chr(33) + chr(32));//double weidth
		// applet.append(chr(27) + "\x61" + "\x31"); // center justify
		applet.append("\n\n"+baris+" \r\n"); // center justify
		applet.append(data.pembatas);
		applet.append(chr(27) + chr(64));//cancel character sets	
		// applet.append(baris);
		applet.append("\n");
		// no = 1;
	}else{
	
		applet.append("\n");
		// applet.append("\n\n "+c+" \n");
		// applet.append(data.pembatas);
	}

		// applet.append("\n");
		applet.append(no+". "+cetak.nama+"\n"+cetak.total);

		// applet.append(chr(27) + chr(64));//cancel character sets		
		no++;
	});
	applet.append("\n");
	applet.append("\n");
	applet.append("\n");
	applet.append(data.total_gratis);

	applet.append("\n");
	applet.append("\n");
	applet.append("\n");
	applet.append("\n");
	applet.append("\n");
	applet.append("\n");
	applet.append("\n");
	applet.append("\x1Bm"); 
	// alert(data);
            
        // Send characters/raw commands to printer
      
		applet.print();



}

// function print_rekap(data,pkdetail) {
//     useDefaultPrinter();
//     console.log(data);
//     // var applet = document.jzebra;
	
//     var applet = document.jzebra;
//     if (applet != null) {
//         // Send characters/raw commands to applet using "append"
//         // all gede selain posiflex
// 		//applet.append(chr(27) + "\x61" + "\x31"); // center justify
// 		//applet.append(chr(27) + chr(33) + chr(128));//underliner
		
// 		applet.append(chr(27) + chr(33) + chr(32));//double weidth
// 		applet.append(chr(27) + "\x61" + "\x31"); // center justify
		
// 		applet.append(data.logo+"\r\n");
// 		applet.append(chr(27) + chr(64));//cancel character sets			
//         applet.append(chr(27) + "\x61" + "\x31"); // center justify
// 		applet.append(data.alamat+"\r\n");
//         applet.append(data.no_telp+"\r\n");
//         applet.append(data.trx_tgl+"\r\n");
// 		applet.append(chr(27) + chr(64));//cancel character sets
//         applet.append("\n");
//         // applet.append(data.no_meja);
//         // applet.append("\n");
		
//         applet.append(data.kasir);
//         applet.append("\n");
//         applet.append(data.tgl_cetak);
//         applet.append("\n");
//         applet.append(data.pembatas);
//         applet.append("\n");
		
// 		applet.append(chr(27) + chr(33) + chr(32));//double weidth
//         applet.append(chr(27) + "\x61" + "\x31"); // center justify
//         applet.append("PENJUALAN\r\n"); // center justify
// 		applet.append(chr(27) + chr(64));//cancel character sets			
// 		applet.append(data.pembatas);
//         applet.append("\n");
		
	
// 		if(data.detail.net!=null)
// 		applet.append(data.detail.net);

// 		if(data.detail.netvalue!=null)
// 		applet.append(data.detail.netvalue);

// 		if(data.detail.disc!=null)
// 		applet.append(data.detail.disc);

// 		if(data.detail.discvalue!=null)
// 		applet.append(data.detail.discvalue);
		
// 		if(data.detail.svc!=null)
// 		applet.append(data.detail.svc);

// 		if(data.detail.svcvalue!=null)
// 		applet.append(data.detail.svcvalue);
	
// 		if(data.detail.tax!=null)
// 		applet.append(data.detail.tax);
	
// 		if(data.detail.taxvalue!=null)
// 		applet.append(data.detail.taxvalue);

// 		if(data.detail.gross!=null)
// 		applet.append(data.detail.gross);

// 		if(data.detail.grossvalue!=null)
// 		applet.append(data.detail.grossvalue);

// 		if(data.detail.adt_cost!=null)
// 		applet.append(data.detail.adt_cost);

// 		if(data.detail.adt_costvalue!=null)
// 		applet.append(data.detail.adt_costvalue);

// 		if(data.detail.voucher!=null)
// 		applet.append(data.detailpay.voucher);

// 		if(data.detail.vouchervalue!=null)
// 		applet.append(data.detailpay.vouchervalue);


// 		if(data.detail.total_final!=null)
// 		applet.append(data.detail.total_final);

// 		if(data.detail.total_final_value!=null)
// 		applet.append(data.detail.total_final_value);
//         // applet.append("\n");
//         // applet.append("\n");
//         // applet.append("\n");
//         // applet.append("\n");
//         // applet.append("\n");
//         // applet.append("\n");
//         // applet.append("\n");
//         // applet.append("\n");

// 		applet.append(data.pembatas);
// 		applet.append("\n");
		
// 		applet.append(chr(27) + chr(33) + chr(32));//double weidth
//         applet.append(chr(27) + "\x61" + "\x31"); // center justify
//         applet.append("CARA PEMBAYARAN  \r\n"); // center justify
// 		applet.append(chr(27) + chr(64));//cancel character sets			
// 		// applet.append(chr(27) + chr(64));//cancel character sets			
// 		applet.append(data.pembatas);
//         applet.append("\n");
		
// 		// applet.append(data.detailpay.total);
// 		// applet.append(data.detailpay.totalvalue);
// 		// applet.append(data.detailpay.comp);
// 		// applet.append(data.detailpay.compvalue);
// 		applet.append(data.detailpay.netcash);
// 		applet.append(data.detailpay.netcashvalue);
// 		applet.append(data.detailpay.bca);
// 		applet.append(data.detailpay.bcavalue);
// 		applet.append(data.detailpay.niaga);
// 		applet.append(data.detailpay.niagavalue);
//         applet.append(data.pembatas);
//         applet.append("\n");
// 		applet.append(data.detailpay.total_pembayaran_label);
// 		applet.append(data.detailpay.total_pembayaran);


// 		applet.append("\n");
// 		applet.append(chr(27) + chr(33) + chr(32));//double weidth
//         applet.append(chr(27) + "\x61" + "\x31"); // center justify
// 		// alert(data.h)
//         if (data.hutang != null){
// 			applet.append("PEMBAYARAN PIUTANG  \r\n"); // center justify
// 			applet.append(chr(27) + chr(64));//cancel character sets			
// 			applet.append(data.pembatas);
// 			applet.append("\n");
// 			//isi untuk hutang
// 			var totalhutang;
// 			$.each(data.hutang, function(i,cetak){
// 				applet.append(cetak.faktur+ ", ");
// 				applet.append(cetak.bayar+ " \n" );
// 				 totalhutang = parseFloat(totalhutang) +  parseFloat(cetak.bayar);
// 			});
// 				applet.append(" \n");
// 				// applet.append("Total  : "+totalhutang);
			
// 			//akhir untuk hutang
// 			applet.append("\n");
// 			applet.append(data.pembatas);
// 			applet.append("\n");
// 		}
		
// 		applet.append(chr(27) + chr(33) + chr(32));//double weidth
//         applet.append(chr(27) + "\x61" + "\x31"); // center justify
//         applet.append("RINCIAN PENJUALAN\r\n"); // center justify
// 		applet.append(chr(27) + chr(64));//cancel character sets			
// 		// applet.append(data.pembatas);
//         // applet.append("\n");
// 		$.each(data.detail2, function(i,cetak){
// 			applet.append(cetak.pembatas);
// 			// if (cetak.dept!="") {
// 			if (cetak.dept!="") {
// 				// applet.append("\n");
// 				// applet.append(cetak.dept);
// 			    // applet.append(chr(27) + chr(97) + chr(2));//right alignment 
// 					applet.append("\n");
// 			       // applet.append(cetak.dept+" "+ cetak.totalpertenan);//right alignment 
// 			       applet.append(cetak.dept);//right alignment 
// 				// applet.append();
// 			       // applet.append(chr(27) + chr(97) + chr(2));//right alignment 
// 		        // applet.append(chr(27) + chr(69) + " TOTAL     : \t 5000\r\n" + chr(27) + chr(70)); 
// 				// applet.append(chr(27) + chr(64));//cancel character sets	

// 				// applet.append("\n");
// 				// applet.append(cetak.totalpertenan);
// 				applet.append("\n");
// 			//	applet.append(cetak.pembatas);
// 				// applet.append("\n");
// 			}
// 			// else{
// 			// 	applet.append("\n");
// 			// }
// 			// if (pkdetail==true){
// 				    // applet.append(chr(27) + chr(97) + chr(2));//right alignment 
// 				applet.append(chr(27) + chr(69) +cetak.table+" "+cetak.harga+"\r\n");
// 				// applet.append(chr(27) + chr(69) +cetak.harga+"\r\n");
// 				// applet.append(chr(27) + chr(69) +cetak.table+cetak.harga+"\r\n"+chr(27) + chr(97)+chr(2));
// 			// }
// 			applet.append(chr(27) + chr(64));//cancel character sets		
// 			// applet.append("\n");
// 		});
// 		applet.append("\n");
//         applet.append(data.pembatas);
			
//         applet.append(data.total);
//         applet.append("\n");
//         applet.append("\n");




// 		// applet.append(chr(27) + chr(33) + chr(32));//double weidth
//   //       applet.append(chr(27) + "\x61" + "\x31"); // center justify
//   //       applet.append("COMPLIMENT (GRATIS)\r\n"); // center justify
// 		// applet.append(chr(27) + chr(64));//cancel character sets	
// 		// applet.append(data.pembatas);
// 		// // alert(JSON.stringify(data.gratis))
// 		// $.each(data.gratis, function(i,cetak){
// 		// 	// applet.append("\n");
// 		// 	applet.append("\n");
// 		// 	applet.append(cetak.nama+" \n "+cetak.total);
// 		// 	// applet.append(chr(27) + chr(64));//cancel character sets		
// 		// });
//   //       applet.append("\n");
// 		// applet.append(data.total_gratis);
		
//         // applet.append("\n");
//         // applet.append("\n");


//         applet.append(chr(27) + chr(33) + chr(32));//double weidth
//         applet.append(chr(27) + "\x61" + "\x31"); // center justify
//         applet.append("PENGELUARAN \r\n"); // center justify
// 		applet.append(chr(27) + chr(64));//cancel character sets	
// 		applet.append(data.pembatas);
// 		$.each(data.pengeluaran, function(i,cetak){
// 			// applet.append("\n");
// 			applet.append("\n");
// 			applet.append(cetak.nama+"  : "+cetak.total+"\r");
// 			// applet.append(chr(27) + chr(64));//cancel character sets		
// 		});
//         applet.append("\n");
//     	applet.append(data.total_pengeluaran);
//         applet.append("\n");
//         applet.append("\n");
//           applet.append(chr(27) + chr(33) + chr(32));//double weidth
//         applet.append(chr(27) + "\x61" + "\x31"); // center justify
//         applet.append("RINGKASAN \r\n"); // center justify
// 		applet.append(chr(27) + chr(64));//cancel character sets	
// 		applet.append(data.pembatas);
// 		applet.append("\n");
// 		applet.append(data.summary_totalpenjualan);
// 		applet.append("\n");
// 		applet.append(data.summary_totalgratis);
// 		applet.append("\n");
// 		applet.append(data.summary_pengeluaran);
// 		applet.append("\n");
// 		applet.append(data.pembatas);
// 		applet.append("\n");
// 		applet.append(data.summary_all);

// 		applet.append("\n");
// 		applet.append("\n");
// 		applet.append("\n");


//         applet.append(data.footer);
//         // applet.append("\n");
//         applet.append(data.footer2);
//         applet.append("\n");
//         applet.append("\n");
//         applet.append("\n");
//         applet.append("\n");
//         applet.append("\n");
//         applet.append("\n");
//         applet.append("\n");
//         applet.append("\n");
//         applet.append("\x1Bm"); 
//        // alert(data);
            
//         // Send characters/raw commands to printer
      
// 		applet.print();
// 		// window.location.href + "/../sample.pdf";
//     }
// }



function print_rekap(data,pkdetail) {
    useDefaultPrinter();
    // console.log(data);
    // alert("ok");
    // var applet = document.jzebra;
    
    var applet = document.jzebra;
    // alert(JSON.stringify(applet));
    if (applet != null) {
        // Send characters/raw commands to applet using "append"
        // all gede selain posiflex
        //applet.append(chr(27) + "\x61" + "\x31"); // center justify
        //applet.append(chr(27) + chr(33) + chr(128));//underliner
        
        applet.append(chr(27) + chr(33) + chr(32));//double weidth
        applet.append(chr(27) + "\x61" + "\x31"); // center justify
        
        applet.append(data.logo+"\r\n");
        applet.append(chr(27) + chr(64));//cancel character sets            
        applet.append(chr(27) + "\x61" + "\x31"); // center justify
        applet.append(data.alamat+"\r\n");
        applet.append(data.no_telp+"\r\n");
        applet.append(data.trx_tgl+"\r\n");
        applet.append(chr(27) + chr(64));//cancel character sets
        applet.append("\n");
        // applet.append(data.no_meja);
        // applet.append("\n");
        
        applet.append(data.kasir);
        applet.append("\n");
        applet.append(data.tgl_cetak);
        applet.append("\n");
        applet.append(data.pembatas);
        applet.append("\n");
        
        applet.append(chr(27) + chr(33) + chr(32));//double weidth
        applet.append(chr(27) + "\x61" + "\x31"); // center justify
        applet.append("PENJUALAN\r\n"); // center justify
        applet.append(chr(27) + chr(64));//cancel character sets            
        applet.append(data.pembatas);
        applet.append("\n");
        
    
        if(data.detail.net!=null)
        applet.append(data.detail.net);

        if(data.detail.netvalue!=null)
        applet.append(data.detail.netvalue);

        if(data.detail.disc!=null)
        applet.append(data.detail.disc);

        if(data.detail.discvalue!=null)
        applet.append(data.detail.discvalue);
        
        if(data.detail.svc!=null)
        applet.append(data.detail.svc);

        if(data.detail.svcvalue!=null)
        applet.append(data.detail.svcvalue);
    
        if(data.detail.tax!=null)
        applet.append(data.detail.tax);
    
        if(data.detail.taxvalue!=null)
        applet.append(data.detail.taxvalue);

        if(data.detail.gross!=null)
        applet.append(data.detail.gross);

        if(data.detail.grossvalue!=null)
        applet.append(data.detail.grossvalue);

        if(data.detail.adt_cost!=null)
        applet.append(data.detail.adt_cost);

        if(data.detail.adt_costvalue!=null)
        applet.append(data.detail.adt_costvalue);

        if(data.detail.voucher!=null)
        applet.append(data.detailpay.voucher);

        if(data.detail.vouchervalue!=null)
        applet.append(data.detailpay.vouchervalue);


        if(data.detail.total_final!=null)
        applet.append(data.detail.total_final);

        if(data.detail.total_final_value!=null)
        applet.append(data.detail.total_final_value);
        // applet.append("\n");
        // applet.append("\n");
        // applet.append("\n");
        // applet.append("\n");
        // applet.append("\n");
        // applet.append("\n");
        // applet.append("\n");
        // applet.append("\n");

        applet.append(data.pembatas);
        applet.append("\n");
        
        applet.append(chr(27) + chr(33) + chr(32));//double weidth
        applet.append(chr(27) + "\x61" + "\x31"); // center justify
        applet.append("METODE PEMBAYARAN  \r\n"); // center justify
        applet.append(chr(27) + chr(64));//cancel character sets            
        // applet.append(chr(27) + chr(64));//cancel character sets         
        applet.append(data.pembatas);
        applet.append("\n");
        
        // applet.append(data.detailpay.total);
        // applet.append(data.detailpay.totalvalue);
        // applet.append(data.detailpay.comp);
        // applet.append(data.detailpay.compvalue);
        applet.append(data.detailpay.netcash);
        applet.append(data.detailpay.netcashvalue);
        applet.append(data.detailpay.bca);
        applet.append(data.detailpay.bcavalue);
        applet.append(data.detailpay.niaga);
        applet.append(data.detailpay.niagavalue);
        applet.append(data.pembatas);
        applet.append("\n");
        applet.append(data.detailpay.total_pembayaran_label);
        applet.append(data.detailpay.total_pembayaran);


        applet.append("\n");
        applet.append(chr(27) + chr(33) + chr(32));//double weidth
        applet.append(chr(27) + "\x61" + "\x31"); // center justify
        // alert(data.h)
        if (data.hutang != null){
            applet.append("PEMBAYARAN PIUTANG  \r\n"); // center justify
            applet.append(chr(27) + chr(64));//cancel character sets            
            applet.append(data.pembatas);
            applet.append("\n");
            //isi untuk hutang
            var totalhutang;
            $.each(data.hutang, function(i,cetak){
                applet.append(cetak.faktur+ ", ");
                applet.append(cetak.bayar+ " \n" );
                 totalhutang = parseFloat(totalhutang) +  parseFloat(cetak.bayar);
            });
                applet.append(" \n");
                // applet.append("Total  : "+totalhutang);
            
            //akhir untuk hutang
            applet.append("\n");
            applet.append(data.pembatas);
            applet.append("\n");
        }
        
        applet.append(chr(27) + chr(33) + chr(32));//double weidth
        applet.append(chr(27) + "\x61" + "\x31"); // center justify
        applet.append("RINCIAN PENJUALAN\r\n"); // center justify
        applet.append(chr(27) + chr(64));//cancel character sets            
        // applet.append(data.pembatas);
        // applet.append("\n");
        
        $.each(data.detail2, function(i,cetak){
            applet.append(cetak.pembatas);
            // if (cetak.dept!="") {
            if (cetak.dept!="") {
                // applet.append("\n");
                // applet.append(cetak.dept);
                // applet.append(chr(27) + chr(97) + chr(2));//right alignment 
                    applet.append("\n");
                   applet.append(cetak.dept+" "+ cetak.totalpertenan);//right alignment 
                   // applet.append(cetak.dept+" - ("+cetak.totalperkategori+")");//right alignment 
                // applet.append();
                   // applet.append(chr(27) + chr(97) + chr(2));//right alignment 
                // applet.append(chr(27) + chr(69) + " TOTAL     : \t 5000\r\n" + chr(27) + chr(70)); 
                // applet.append(chr(27) + chr(64));//cancel character sets 

                // applet.append("\n");
                // applet.append(cetak.totalpertenan);
                applet.append("\n");
            //  applet.append(cetak.pembatas);
                // applet.append("\n");
            }
            // else{
            //  applet.append("\n");
            // }
            // if (pkdetail==true){
                    // applet.append(chr(27) + chr(97) + chr(2));//right alignment 
                applet.append(chr(27) + chr(69) +cetak.table+" "+cetak.harga+"\r\n");
                // applet.append(chr(27) + chr(69) +cetak.harga+"\r\n");
                // applet.append(chr(27) + chr(69) +cetak.table+cetak.harga+"\r\n"+chr(27) + chr(97)+chr(2));
            // }
            applet.append(chr(27) + chr(64));//cancel character sets        
            // applet.append("\n");
        });


        applet.append("\n");
        applet.append(data.pembatas);
            
        applet.append(data.total);
        applet.append("\n");
        applet.append("\n");

        // alert("12345");



        // applet.append(chr(27) + chr(33) + chr(32));//double weidth
  //       applet.append(chr(27) + "\x61" + "\x31"); // center justify
  //       applet.append("COMPLIMENT (GRATIS)\r\n"); // center justify
        // applet.append(chr(27) + chr(64));//cancel character sets 
        // applet.append(data.pembatas);
        // // alert(JSON.stringify(data.gratis))
        // $.each(data.gratis, function(i,cetak){
        //  // applet.append("\n");
        //  applet.append("\n");
        //  applet.append(cetak.nama+" \n "+cetak.total);
        //  // applet.append(chr(27) + chr(64));//cancel character sets     
        // });
  //       applet.append("\n");
        // applet.append(data.total_gratis);
        
        // applet.append("\n");
        // applet.append("\n");


        applet.append(chr(27) + chr(33) + chr(32));//double weidth
        applet.append(chr(27) + "\x61" + "\x31"); // center justify
        applet.append("RINCIAN PURCHASED \r\n"); // center justify
        applet.append(chr(27) + chr(64));//cancel character sets    
        applet.append(data.pembatas);
        $.each(data.pengeluaran, function(i,cetak){
            // applet.append("\n");
            applet.append("\n");
            applet.append(cetak.nama+"  : "+cetak.total+"\r");
            // applet.append(chr(27) + chr(64));//cancel character sets     
        });
        applet.append("\n");
        applet.append(data.total_pengeluaran);
        applet.append("\n");
        applet.append("\n");
          applet.append(chr(27) + chr(33) + chr(32));//double weidth
        applet.append(chr(27) + "\x61" + "\x31"); // center justify
        applet.append("RINGKASAN \r\n"); // center justify
        applet.append(chr(27) + chr(64));//cancel character sets    
        applet.append(data.pembatas);
        applet.append("\n");
        applet.append(data.summary_all);
        applet.append("\n");
        // applet.append(data.pembatas);
        // applet.append("\n");
        // console.log(data);
        // alert(JSON.st);
        // alert(data.summary_totalgratis);
        // alert("ok");
        // alert(JSON.stringify(data));
      

        applet.append(data.summary_pengeluaran);

        applet.append("\n");
        applet.append(data.pembatas);
        applet.append("\n");
        applet.append(data.summary_totalpenjualan);
        applet.append("\n");
        // applet.append("\n");
        $.each(data.summary_totalgratis, function(i,cetak){
            // alert(cetak);
            applet.append(cetak);
            applet.append("\n");
        });

        // applet.append("\n");
        applet.append(data.pembatas);
        applet.append("\n");
        applet.append(data.summary_coh);
        applet.append("\n");
        applet.append(data.pembatas);

        applet.append("\n");
        applet.append("\n");
        applet.append("\n");


        applet.append(data.footer);
        // applet.append("\n");
        applet.append(data.footer2);
        applet.append("\n");
        applet.append("\n");
        applet.append("\n");
        applet.append("\n");
        applet.append("\n");
        applet.append("\n");
        applet.append("\n");
        applet.append("\n");
        applet.append("\x1Bm"); 
       // alert(data);
            
        // Send characters/raw commands to printer
      
        applet.print();
        // window.location.href + "/../sample.pdf";
    }
}

//---------------------------
// function print_rekap(data) {
// // alert("123");
//     useDefaultPrinter();
//     // var applet = document.jzebra;
	
//     var applet = document.jzebra;
//     if (applet != null) {
//         // Send characters/raw commands to applet using "append"
//         applet.append(chr(27) + "\x61" + "\x31"); // center justify
// 		applet.append(chr(27) + chr(33) + chr(128));//underliner
// 		applet.append(data.logo+"\r\n");
// 		applet.append(chr(27) + chr(64));//cancel character sets			
//         applet.append(chr(27) + "\x61" + "\x31"); // center justify
// 		applet.append(data.alamat+"\r\n");
//         applet.append(data.no_telp+"\r\n");
//         applet.append(data.trx_tgl+"\r\n");
// 		applet.append(chr(27) + chr(64));//cancel character sets
//         applet.append("\n");
//         // applet.append(data.no_meja);
//         // applet.append("\n");
		
//         applet.append(data.kasir);
//         applet.append("\n");
//         applet.append(data.tgl_cetak);
//         applet.append("\n");
//         applet.append(data.pembatas);
//         applet.append("\n");
		
// 		applet.append(chr(27) + chr(33) + chr(32));//double weidth
//         applet.append(chr(27) + "\x61" + "\x31"); // center justify
//         applet.append("PENJUALAN\r\n"); // center justify
// 		applet.append(chr(27) + chr(64));//cancel character sets			
// 		applet.append(data.pembatas);
//         applet.append("\n");
		
// 		applet.append(data.detail.gross);
// 		applet.append(data.detail.grossvalue);
// 		applet.append(data.detail.disc);
// 		applet.append(data.detail.discvalue);
// 		applet.append(data.detail.svc);
// 		applet.append(data.detail.svcvalue);
// 		applet.append(data.detail.tax);
// 		applet.append(data.detail.taxvalue);
// 		applet.append(data.detail.net);
// 		applet.append(data.detail.netvalue);
// 		applet.append(data.detailpay.niaga);
// 		applet.append(data.detailpay.niagavalue);

//         // applet.append("\n");
//         // applet.append("\n");
//         // applet.append("\n");
//         // applet.append("\n");
//         // applet.append("\n");
//         // applet.append("\n");
//         // applet.append("\n");
//         // applet.append("\n");

// 		applet.append(data.pembatas);
// 		applet.append("\n");
		
// 		applet.append(chr(27) + chr(33) + chr(32));//double weidth
//         applet.append(chr(27) + "\x61" + "\x31"); // center justify
//         applet.append("CARA PEMBAYARAN  \r\n"); // center justify
// 		applet.append(chr(27) + chr(64));//cancel character sets			
// 		applet.append(data.pembatas);
//         applet.append("\n");
		
// 		applet.append(data.detailpay.total);
// 		applet.append(data.detailpay.totalvalue);
// 		applet.append(data.detailpay.comp);
// 		applet.append(data.detailpay.compvalue);
// 		applet.append(data.detailpay.netcash);
// 		applet.append(data.detailpay.netcashvalue);
// 		applet.append(data.detailpay.bca);
// 		applet.append(data.detailpay.bcavalue);
// 		applet.append(data.detailpay.mandiri);
// 		applet.append(data.detailpay.mandirivalue);
// 		applet.append(data.detailpay.c_bca);
// 		applet.append(data.detailpay.c_bcavalue);
// 		applet.append(data.detailpay.c_mandiri);
// 		applet.append(data.detailpay.c_mandirivalue);
		
// 		//ceta
// 		// applet.append("123");
		
		
// 		applet.append(data.detailpay.dll);
// 		applet.append(data.detailpay.dllvalue);
		
// 		//kr` 
//         // applet.append("\n");
//         // applet.append("\n");
//         // applet.append("\n");
//         // applet.append("\n");
//         // applet.append("\n");
//         // applet.append("\n");
//         // applet.append("\n");
		
// 		applet.append(data.pembatas);
// 		applet.append("\n");
// 			applet.append(chr(27) + chr(33) + chr(32));//double weidth
//         applet.append(chr(27) + "\x61" + "\x31"); // center justify
// 		// alert(data.h)
//   //       if (data.hutang != null){
// 		// 	applet.append("PEMBAYARAN PIUTANG  \r\n"); // center justify
// 		// 	applet.append(chr(27) + chr(64));//cancel character sets			
// 		// 	applet.append(data.pembatas);
// 		// 	applet.append("\n");
// 		// 	//isi untuk hutang
// 		// 	var totalhutang;
// 		// 	$.each(data.hutang, function(i,cetak){
// 		// 		applet.append(cetak.faktur+ ", ");
// 		// 		applet.append(cetak.bayar+ " \n" );
// 		// 		 totalhutang = parseFloat(totalhutang) +  parseFloat(cetak.bayar);
// 		// 	});
// 		// 		applet.append(" \n");
// 		// 		// applet.append("Total  : "+totalhutang);
			
// 		// 	//akhir untuk hutang
// 		// 	applet.append("\n");
// 		// 	applet.append(data.pembatas);
// 		// 	applet.append("\n");
// 		// }
		
// 		applet.append(chr(27) + chr(33) + chr(32));//double weidth
//         applet.append(chr(27) + "\x61" + "\x31"); // center justify
//         applet.append("RINCIAN PENJUALAN\r\n"); // center justify
// 		applet.append(chr(27) + chr(64));//cancel character sets			
// 		// applet.append(data.pembatas);
//         // applet.append("\n");
// 		$.each(data.detail2, function(i,cetak){
// 			applet.append(cetak.pembatas);
// 			applet.append("\n");
// 			if (cetak.dept!="") {
// 			applet.append(cetak.dept);
// 			applet.append("\n");
// 			applet.append(cetak.pembatas);
// 				 applet.append("\n");
// 			}
// 			// else
// 			// {
// 			// applet.append(cetak.pembatas);
// 			// applet.append("\n");
// 			// }
// 			// applet.append(chr(27) + chr(69) + " TOTAL     : \t 5000\r\n" + chr(27) + chr(70)); 
// 			// applet.append(cetak.table+"\n");
// 			// applet.append(chr(27) + chr(97) + chr(2));//right alignment 
// 			applet.append(chr(27) + chr(69) +cetak.table+cetak.harga+"\r\n"+chr(27) + chr(97)+chr(2));
// 			applet.append(chr(27) + chr(64));//cancel character sets		
// 			// applet.append("\n");
// 		});
		
// 		// jQuery.each(data.detail, function(){
// 			// jQuery.each(this, function () {
//                 // // applet.append(this.gross);
// 				// // applet.append("\n");
//             // });
// 		// });
		
//         // $.each(data.detail2, function() {
// 			// $.each(this, function(i,cetak){
// 				// // applet.append(cetak.dept);
// 				// // applet.append("\n");
// 				// // applet.append(cetak.pembatas);
// 				// // applet.append("\n");
// 				// // applet.append(cetak.table);
// 				// // applet.append("\n");
// 			// });
//         // });
// 		applet.append("\n");
//         applet.append(data.pembatas);
			
//         applet.append(data.total);
//         applet.append("\n");
//         applet.append(data.footer);
//         // applet.append("\n");
//         applet.append(data.footer2);
//         applet.append("\n");
//         applet.append("\n");
//         applet.append("\n");
//         applet.append("\n");
//         applet.append("\n");
//         applet.append("\n");
//         applet.append("\n");
//         applet.append("\n");
//         applet.append("\x1Bm"); 
//        // alert(data);
            
//         // Send characters/raw commands to printer
      
// 		applet.print();
// 		// window.location.href + "/../sample.pdf";
//     }
// }
//---------------------------
var persen = parseFloat($("#parameter-pajak").val())/100;
var persen_svc = parseFloat($("#parameter-service").val())/100;


function Bersihkan(id){

}
function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}
function add_item(id)
{	
    // alert("ok")
    // alert(id);

	var isbarcode = true;
	// alert(id);
	if (id=="" || id==null || id=="0"){
		id = $("#e1").val();
		isbarcode = false;
	}
    // alert("12345");
	var name =  $("#namapel option:selected").val();
	$.ajax({
        url : 'index.php?r=items/check',
        // data : 'id='+$("#e1").val(),
         data : 'id='+id+"&name="+name,
        success : function(data)
        {
    		$("#input_items").val("");
    		if (isbarcode==false){
               $("#e1").select2("open");

    		}else{
	    		$("#input_items").focus();
    		}
							
            var obj = jQuery.parseJSON(data);
            // alert(obj.discount_customer);
            // alert(obj.kode);
        	if(obj.kode==null){
    		   $().toastmessage('showToast', {
                   text : "Data tidak ditemukan",
                   sticky : false,
                   type     : 'warning'
               });
               
               $("#input_items").val("");
               $("#wrapper-item-search").show();
               $("#full-screen").show();
               $("#e1").focus();
               $("#e1").select2("open");
               exit;
        	}else{
                <?php 
                if ($bool_input_qty=='true'){
                ?>
                var c = 0;
				while (c==0 || !isNumeric(c)){       
                    c = prompt("Masukan Jumlah ","1");
                }
				if (c=="0"){
					c = 1;
				}   
                    <?php }else{ ?>
				var c = 1;
                <?php 
                }
                ?>
				$("#qty").val(c);
        	}


            // jika produk pulsa maka 
            if (obj.is_pulsa=="1"){
                // alert("123");
                var no_hp= 0;
                while (no_hp==0 || !isNumeric(no_hp)){       
                    no_hp = prompt("Masukan Nomor Handphone ","08");
                }
            }

            // end produk pulsa

            // alert("123")
            // alert($("#qty").val());
            if ($("#qty").val()==0 || $("#qty").val()=="" ){
        		$().toastmessage('showToast', {
					text : "Tidak Boleh 0",
					sticky : false,
					type     : 'error'
				});
				$("#input_items").val("");
				$("#input_items").focus();

        		exit;
        	}

            if ($("#costumer_type").val()==1) {
            	obj.total_cost  = obj.total_cost;
            }else if ($("#costumer_type").val()==2) {
	        	obj.total_cost  = obj.price_distributor;
            }else if ($("#costumer_type").val()==3) {
            	obj.total_cost  = obj.price_reseller;
            }



            var total = obj.total_cost * $("#qty").val();
            // alert(obj.discount);
            if (obj.discount==0){
	            var dc =  $("#discount").val();
	            if (dc==0){
	            	dc = obj.discount_customer;
	            }
            }
	        else{
	        	var message = "Item "+obj.item_name+" mendapatkan discount "+obj.discount+" %";
	    	 	$().toastmessage('showToast', {
					text : message,
					sticky : false,
					type     : 'success'
				});
	            var dc =  obj.discount;
	        }


            var discount = total * dc / 100;
    		// }else{
	     //        var discount = total * obj.discount  / 100;    			
    		// }            
            // var tax = $("#qty").val()*obj.tax_percent;
            
            // alert(persen_svc);
            var tax = $("#qty").val()*obj.total_cost*persen;
            var service = $("#qty").val()*obj.total_cost*persen_svc;


			// var taxdisc = tax*10/100 ;
			// var totaltax = tax - taxdisc;
            // var total_cost = total-discount+totaltax;
            var total_cost = total-discount+tax;
            id_inc = id_inc + 1;    

            var isExist = false;
			
			var grid = liveSearchPanel_SalesItems;
				// alert(obj.item_satuan_id);
			liveSearchPanel_SalesItems.store.each(function (rec) {
				// if (rec.get('item_name')==obj.item_name && rec.get('item_satuan')==obj.nama_satuan ){
				if (rec.get('item_name')==obj.item_name && rec.get('item_satuan_id')==obj.item_satuan_id ){
						var row = grid.store.indexOf(rec);
						var models = grid.getStore().getRange();
						models[row].set("quantity_purchased",(models[row].get("quantity_purchased")+parseInt($("#qty").val())));
						kalkulasiRow(models,row);
						// models[row].set("item_tax",(models[row].get("item_price")*persen)*models[row].get("quantity_purchased"));
						// models[row].set("item_service",(models[row].get("item_price")*persen_svc)*models[row].get("quantity_purchased"));
						// models[row].set("item_total_cost",models[row].get("item_tax")+models[row].get("item_service")+(models[row].get("item_price")*models[row].get("quantity_purchased")));kalkulasi1();
						
						 $("#input_items").val("");
						 // $("#input_items").focus();
						isExist = true;
				}
				// alert(models);

			});
			if (isExist==true){
				return false;
			}
			// alert(liveSearchPanel_SalesItems.getStore().getRange());
			// alert(obj.nama_satuan);
            var r = Ext.create('SalesItems', {
                id : id_inc,
                item_id:  obj.id,
                quantity_purchased: $("#qty").val(),
                // item_tax: obj.tax_percent*$("#qty").val(),
                item_service: service,
                item_tax: tax,
                service_value: obj.service_angka,
                pajak_value:obj.pajak_angka,
                lokasi: obj.lokasi,
                item_name: obj.item_name,
                item_price_tipe: obj.price_type,
                item_satuan: obj.nama_satuan,
                item_satuan_id: obj.nama_satuan_id,
                item_price:obj.total_cost,
                item_discount: dc,
                item_total_cost:total_cost,
                ukuran:obj.ukuran,
                panjang:obj.panjang,
                ketebalan:obj.ketebalan,
                is_paket:obj.is_paket,
                permintaan:no_hp,
                kode:obj.kode
            
            });
            


            liveSearchPanel_SalesItems.store.insert(0, r);

            // new by try 25 juni 2018
            // item_master_id = obj.id;
            // alert(obj.id);
            

            // var s = formCombo.getStore();
            // s.proxy.extraParams.id= obj.id;
            // s.load();


  //           s.load({
		//     params:{
		//         'foo1': bar1,
		//         'foo2': bar2
		//     } 
		// });
			// var s = formCombo.getProxy().extraParams = {
			// 	foo: 'bar'
			// };
   //          s.read();
			// s.load();
            // s.extraParams = {id: obj.id};
    		// console.log(s);



            var sum = 0;
            discount = 0;
            tax = 0;
            var subtotal = 0;
			kalkulasi1();
			$("#wrapper-item-search").hide();
			$("#full-screen").hide();
			$("#e1").select2("close");
			$("#input_items").focus();
			// proxy.read();
			// sizeDropdownStore.fireEvent("load",sizeDropdownStore);

          
                
        },
        error : function(data)
        {
        //alert(data);
                
        }
    });
}



function update_bill()
{

}

function load_bill(meja,data)
{
    //    alert("meja");
    liveSearchPanel_SalesItems.store.removeAll();
    // alert(data);
    $.getJSON('index.php?r=sales/load&id='+data, function(data) {
    		$('#tanggal_jt').val(data.sales.tanggal_jt);
    		// $('#total_hutang').val(data.sales.bayar);
    		$('#total_hutang').val(0);
	    	$('#sum_sale_bayar').html(data.sales.bayar);
    		$('#tanggal').val(data.date);
	    	$('#umum-value').val(data.sales.nama).trigger("change");

	    // console.log(data.si);
        $.each(data.si, function(key, val) {
	    	$('#namapel').val(val.nama).trigger("change");
	    	$('#umum-value').val(val.nama);
        });

        $.each(data.si, function(key, val) {

        // alert(val.nama);
            // alert(val.nama);
            // $('#namapel').select2('data', {id: val.nama, a_key: val.nama});
	    	// total_hutang
            // alert(val.item_discount);


            var r = Ext.create('SalesItems', {
                item_id:  val.item_id,
                quantity_purchased:val.quantity_purchased,
                item_tax: val.item_tax,
                item_service: val.item_service,
                item_name: val.item_name,
                item_price:val.item_price,
                item_discount: val.item_discount,
                lokasi: val.lokasi,
                permintaan: val.permintaan,
                keterangan: val.keterangan,
                item_total_cost:val.item_total_cost,
                kode:val.kode,
                panjang:val.panjang,
                ukuran:val.ukuran,
                ketebalan:val.ketebalan,
                is_paket:val.is_paket,
                item_satuan : val.nama_satuan,
                item_satuan_id : val.item_satuan_id,
                item_price_tipe : val.item_price_tipe
            });
            liveSearchPanel_SalesItems.store.insert(0, r);
            kalkulasi1();

        });
        // alert('masuj');
    });
          
//    $.ajax({
//        url : '/postech?r=sales/load',
//        data : 'id='+data,
//        success : function(data)
//        {
//            var obj = jQuery.parseJSON(data);
//            //            alert(obj.si.item_id);
//            var data = obj.si;
//            $.each(data, function(i, item) {
//                alert(item.item_price);
//            });​
//        //            $.each(data, function(i, item) {
//        //              //  alert(data[i].item_price);
//        //            });​
//        //            var r = Ext.create('SalesItems', {
//        //                item_id:  obj.si.item_id,
//        //                quantity_purchased:obj.si.quantity_purchased,
//        //                item_tax: obj.si.item_tax,
//        //                item_name: obj.si.item_name,
//        //                item_price:obj.si.item_price,
//        //                item_discount: obj.si.item_discount,
//        //                item_total_cost:obj.si.item_total_cost
//        //            });
//        //            liveSearchPanel_SalesItems.store.insert(0, r);
//        }
//    });
}

// function cetakReport(){
	// // alert('cek');
	// $.ajax({
        // url : '/postech/index.php?r=sales/cetakReport',
        // // data : {
            // // submit:true,
            // // // data_detail:data_detail
        // // },
        // success : function(result)
        // {
			// // alert('cek');
            // var sales = jQuery.parseJSON(result);
            // if (sales.sale_id!="")
            // {
				// print_bayar(sales);
            // }
        // },
        // error : function(result)
        // {
            // alert('error');
            // // $('#dialog_meja').load('index.php?r=site/table');
        // }
    // });
// }

function bayar(status,table,sale_id)
{
    //alert(sale_id); 
    var subtotal = $('#sum_sub_total').html();
    var discount =$('#sum_sale_discount').html();
    var tax =$('#sum_sale_tax').html();
    var service=$('#sum_sale_service').html();
    var total_cost=parseInt($('#sum_sub_total').html())-parseInt($('#sum_sale_discount').html())+   parseInt($('#sum_sale_tax').html())+parseInt($('#sum_sale_service').html());
    // var total_cost=$('#sum_sale_total').html();
    var payment=$('#pembayaran').val();
    var tanggal=$('#tanggal').val();
    var tanggal_jt=$('#tanggal_jt').val();
    // alert(tanggal_jt);
    // var paidwith=$('#payment').val();
    var paidwith=1;
    var custype=$('#custype').val();
	var id_voucher = $('#id_v').val();
    // var bayar=$('#bayar').val();
    // alert($('#bayar').val());
    // if ($('#bayar').val()=="0"){	
    	// alert($('#text1').val());
    if ($('#text1').val()=="0" || $('#text1').val()=="" || $('#text1').val()=="0.00" ){	
	    var bayar = $('#total_hutang').val();
	}else{
	    var bayar = $('#bayar').val();
	}

    //tamu
    if ($('#namapel').val()=="umum")
   	 	var tamu =$('#umum-value').val();
	else
    	var tamu =$('#namapel').val();

    var via;
    if ($('#bank-kredit').val()=="0"){
        via = $('#bank-debit').val();
    }else{
        via = $('#bank-kredit').val();        
    }
   
		
    var belum_bayar = $("#sum_sale_bayar").html();
    var kembali = $("#kembali").attr("asli");
	var nama_kasir = $("#data-user").html();

    var cash=$('#cash').val();
    var edcbca=$('#edcbca').val();
    var edcniaga=$('#edcniaga').val();
    var creditbca=$('#creditbca').val();
    var creditmandiri=$('#creditmandiri').val();
    var voucher=$('#sum_sale_voucher').html();
    // var voucher=$('#voucher').val();
    var compliment=$('#compliment').val();
    var dll=$('#dll').val();
	
	var data2 ;
	
	payment = {
		cash : cash,
		edcbca : edcbca,
		edcniaga : edcniaga,
		creditbca : creditbca,
		creditmandiri : creditmandiri,
		voucher : voucher,
		compliment : compliment,
		dll : dll
	}
	// console.log(payment);
	
	
	data = {
        id_voucher : id_voucher,
        sale_id : sale_id,
        subtotal : subtotal,
        discount : discount,
        tax : tax,
        service : service,
        total_cost : total_cost,
        payment : payment,
        paidwith : paidwith,
        status : status,
        table : table,
        custype : custype,
        tanggal : tanggal,
        tanggal_jt : tanggal_jt,
        namapelanggan : tamu,
        bayar: bayar,
        belum_bayar : belum_bayar,
        bayar_via : via,
        nama_kasir : nama_kasir,
		kembali: kembali
    };

	var data_detail = [];
    var inc = 0;
    liveSearchPanel_SalesItems.store.each(function (rec) { 
        //        var temp = new Array(10,10);
        //        temp['item_price'].push(rec.get('item_total_cost'));
        //        data_detail[0]['quantity_purchased']=rec.get('quantity_purchased');
        data_detail[inc] = {
            "item_name":rec.get('item_name'),
            "item_id":rec.get('item_id'),
            "item_satuan_id":rec.get('item_satuan_id'),
            "item_satuan":rec.get('item_satuan'),
            "quantity_purchased":rec.get('quantity_purchased'),
            "item_tax":rec.get('item_tax'),
            "item_service":rec.get('item_service'),
            "item_discount":rec.get('item_discount'),
            "item_price":rec.get('item_price'),
            "item_total_cost":rec.get('item_total_cost'),
            "permintaan":rec.get('permintaan'),
            "keterangan":rec.get('keterangan'),
            "is_paket":rec.get('is_paket')
        };
        inc=inc+1;
    });
    // alert(JSON.stringify(data_detail));
    //console.log(data_detail);
	//alert();
	// var id = 
	var idx = "";
    $.ajax({
        url : 'index.php?r=sales/bayar',
		type : 'POST',
        data : {
            data:data,
            data_detail:data_detail,
			data_payment : payment
        },
        success : function(data)
        {
        	// alert(data);
            $("#bank-kredit").val(0);
            $("#bank-debit").val(0);
            var sales = jQuery.parseJSON(data);
            data2 = sales;
            idx  = sales.id;

            if (sales.status==0){
            }


            if (sales.sale_id!="" || sales.ishutang==1)
            {

               
                liveSearchPanel_SalesItems.store.removeAll();

                $(".langsung").trigger("click");
                $('#sum_sub_total').html(0);
                $('#sum_sale_discount').html(0);
                $('#sum_sale_service').html(0);
                $('#sum_sale_tax').html(0);
                $('#sum_sale_total').html(0);
                $('#sum_sale_bayar').html(0);
                $('#sum_sale_total2').html(0);
                $('#pembayaran').val(0);
                $('#payment').val(0);
                $('#namapel').val("");
                $('#vouchernominal').val("");
                $('#sum_sale_voucher').html(0);
                $('#text1').val(0);
                $('#qty').val(1);
                $("#e1").select2("close");
                  $("#input_items").val("");
                $("#input_items").focus();
                $("#umum-value").val("");
                $('#dialog_meja').load('index.php?r=site/table');
                $('select option[value="1"]').attr("selected",true);
                $('#namapel').trigger("change");
                $("#dialog_bayar").dialog("close");
                $("#tanggal_jt").val("");
               
                show_meja('Bayar Nanti');
                $("#head-meja").html("");
			    $("#head-meja-nilai").html("");

                if (sales.status == 1)
                {
                   	var jenis_cetak = '<?php echo SiteController::getConfig("ukuran_kertas"); ?>';

		            if (jenis_cetak=="24cmx14cm" || jenis_cetak=="12cmx14cm"){

						var c = confirm("Cetak Bukti ?? ");
						if (c){	
                            $.ajax({
                                url : '<?php echo Yii::app()->createUrl("Sales/cetakfaktur") ?>',
                                data : {
                                    id : idx
                                },
                                success:function(data){
                                $('.body-bukti').html(data);
                                $(".btn-modal-preview").trigger("click");

                                }
                            });
						// window.open("<?php echo Yii::app()->createUrl("Sales/cetakfaktur") ?>&id="+idx);
						}
		            // }else if (jenis_cetak=="80mm" || jenis_cetak=="58mm"){
              //           var c = confirm("Cetak Bukti ?? ");
              //           if (c){ 
              //               $.ajax({
              //                   url : '<?php echo Yii::app()->createUrl("Sales/cetakfaktur_mini") ?>',
              //                   data : {
              //                       id : idx
              //                   },
              //                   success:function(data){
              //                   $('.body-bukti').html(data);
              //                   $(".btn-modal-preview").trigger("click");

              //                   }
              //               });
              //           // window.open("<?php echo Yii::app()->createUrl("Sales/cetakfaktur") ?>&id="+idx);
              //           }
                    }else{
						var i =1;
						var ulang  =  1;<?php //echo Parameter::model()->findByPk(1)->qty_cb; ?>
						function myLoop(){
							setTimeout(function(){
								//alert("Hello");
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
                // alert("masuj");
            }
			
			
        },
        error : function(data)
        {
            alert(JSON.stringify(data));
            $('#dialog_meja').load('index.php?r=site/table');
        }
    });
    return idx;
		// var applet2 = document.jzebra;
		// applet2.findPrinter(printers[1]);
		// print_items(data2,applet2,2);

	//
	}
var commands = Array("hahahaha","jhihihihihi");
var counter = 0;
function doPrint(){
	document.jzebra.findPrinter(printers[counter])
}
function doneFinding(){
	var e = document.jzebra.getException();
	if (e != null){
		document.jzebra.print(commands[counter]);
	}else{
		alert("error");
	}
}
function donePrinting(){
	counter++;
	if (counter<printers.length){
		doPrint()
	}else{
		alert('completed');
	}
}
function editdiskongrid(ediskon){
	//ambil data dari grid
	var data_detail = [];
    var inc = 0;
    liveSearchPanel_SalesItems.store.each(function (rec) { 
        data_detail[inc] = {
            "item_id":rec.get('item_id'),
            "quantity_purchased":rec.get('quantity_purchased'),
            "item_tax":rec.get('item_tax'),
            "item_service":rec.get('item_service'),
            "item_name":rec.get('item_name'),
            "item_discount":rec.get('item_discount'),
            "item_price":rec.get('item_price'),
            "item_total_cost":rec.get('item_total_cost'),
            "is_paket":rec.get('is_paket'),
            "item_satuan_id":rec.get('item_satuan_id'),
            "item_satuan":rec.get('item_satuan'),
            "item_price_tipe":rec.get('item_price_tipe'),
            "kode":rec.get('kode')
        };

        inc=inc+1;
		console.log(data_detail);
		});
		//remove isi grid
		liveSearchPanel_SalesItems.store.removeAll();

		// var ediskon = $('#discount').val();
		
		for (i = 0; i < data_detail.length; i++) {
			// alert(data_detail[i].name);
			var hargatotal = data_detail[i].quantity_purchased * data_detail[i].item_price;
			var potongan = (hargatotal*ediskon)/100;
			var itcost = hargatotal-potongan+data_detail[i].item_tax+data_detail[i].item_service;
			
			var r = Ext.create('SalesItems', {
				//POS V2
                item_satuan_id:  data_detail[i].item_satuan_id,
                item_satuan:  data_detail[i].item_satuan,
                item_price_tipe:  data_detail[i].item_price_tipe,

                item_id:  data_detail[i].item_id,
                quantity_purchased:data_detail[i].quantity_purchased,
                item_tax: data_detail[i].item_tax,
                item_service: data_detail[i].item_service,
                item_name: data_detail[i].item_name,
                item_price:data_detail[i].item_price,
                // item_discount: val.item_discount,
                item_discount: ediskon,
                item_total_cost: itcost,
                is_paket: data_detail[i].is_paket,
                item_satuan_id: data_detail[i].item_satuan_id,
                kode: data_detail[i].kode
			});
			// alert(i);
			liveSearchPanel_SalesItems.store.insert(0, r);
            kalkulasi1();
		}
}

function cetakbill()
{
    //    alert(sale_id);
    var number_meja= $("#tombol_meja").attr('value');
    number_meja =  number_meja.replace(/[^0-9]+/g, '');
    var table = number_meja;
    var nama = $('#namapel').html();
    var subtotal = $('#sum_sub_total').html();
    var discount =$('#sum_sale_discount').html();
    var tax =$('#sum_sale_tax').html();
    var service=$('#sum_sale_service').html();
    var total_cost=$('#sum_sale_total').html();
    var payment=$('#pembayaran').val();
    var paidwith=$('#payment').val();
	var namapelanggan=$('#namapel').val();
    data = {
        sale_id : 0,
        subtotal : subtotal,
        discount : discount,
        tax : tax,
        service : service,
        total_cost : total_cost,
        payment : payment,
        paidwith : paidwith,
        status : 0,
        table : table,
        namapelanggan : namapelanggan
    };
    var data_detail = [];
    var inc = 0;
    liveSearchPanel_SalesItems.store.each(function (rec) { 
        //        var temp = new Array(10,10);
        //        temp['item_price'].push(rec.get('item_total_cost'));
        //        data_detail[0]['quantity_purchased']=rec.get('quantity_purchased');
         data_detail[inc] = {
            "item_id":rec.get('item_id'),
            "item_satuan_id":rec.get('item_satuan_id'),
            "quantity_purchased":rec.get('quantity_purchased'),
            "item_tax":rec.get('item_tax'),
            "item_service":rec.get('item_service'),
            "item_discount":rec.get('item_discount'),
            "item_price":rec.get('item_price'),
            "item_total_cost":rec.get('item_total_cost'),
            "permintaan":rec.get('permintaan'),
            "is_paket":rec.get('is_paket')
        };
        inc=inc+1;
        inc=inc+1;
    });
    //    console.log(data_detail);

    $.ajax({
        url : 'index.php?r=sales/hanyacetak',
        type : 'GET',
		data : {
            data:data,
            data_detail:data_detail
        },
        success : function(data)
        {
        	// alert(JSON.stringify(data));
            var sales = jQuery.parseJSON(data);
            if (sales.sale_id!="")
            {
                print_bayar(sales);
                // $.each(sales.detail, function(i,dani) {
                // // alert(dani.quantity + " " + dani.nama_item);
                // // var total_cetak = dani.logo + dani.alamat; 
                // });


                //$("#dialog_bayar").dialog("close");
             
            }
			
			
        },
        error : function(data)
        {
            alert(data);
            $('#dialog_meja').load('index.php?r=site/table');
        }
    });
}

// function cetakdapur()
// {
//    print_items("DAPUR",2);
//    alert('PERMINTAAN TELAH TERKIRIM KE DAPUR')	
// }
// function cetakbar()
// {
//    print_items("BAR",1);	
//    alert('PERMINTAAN TELAH TERKIRIM KE BAR')	
// }
function cetakbardapur(meja,meja_tipe){
	// alert(meja);
	// alert(meja_tipe);
	meja_cetak = meja;
	meja_tipe_cetak = meja_tipe;
	if (confirm('Aplikasi akan mengirimkan permintaan ke bar dan dapur ?')){
		
		// alert(meja_tipe_cetak);
		// alert(meja_tipe);
		
		var bar = false;
		var dapur = false;
		var jsonObj = [];

		$(".baris").each(function() {
			var idb = $(this).find('.pk').html();
			var nama = $(this).find('.nama_menu').html();
			var lokasi = $(this).find('.pk').attr("lokasi");
			var jml = $(this).find('.jumlah').find('.input-jumlah').val();
			var permintaan = $(this).find('.permintaan').find('.area-permintaan').val();
			var belum_print = $(this).find('.pk[cetak=0]').length;

			item = {}
			item["idb"] = idb;
			item["nama"] = nama;
			item["jml"] = jml;
			item["permintaan"] = permintaan;
			item["lokasi"] = lokasi;
			// jsonObj.push(item);

			if (belum_print!=0){
				if (lokasi=="1"){
		     		bar = true;
		     	}
		     	if (lokasi=="2"){
		     		dapur = true;
		     	}
	     	}

		});
	    if (dapur==true){		
			 if (print_items("POS-80C dapur",2)){
				 alert('PERMINTAAN TELAH TERKIRIM KE DAPUR');				// alert('dapur true');
				 // return true;
			 }
		}

	    if (bar==true){		
			if (print_items("POS-80C",1)){
			    alert('PERMINTAAN TELAH TERKIRIM KE BAR');
			    // return true;
			}
	    }

	    return true;
    }else{
    	return false;
    }
	    


	// alert('lewat');
	 // alert('PERMINTAAN TELAH TERKIRIM KE DAPUR & BAR')	
}

/*
function cetakbill()
{
    //    alert(sale_id);
    var number_meja= $("#tombol_meja").attr('value');
    number_meja =  number_meja.replace(/[^0-9]+/g, '');
    var table = number_meja;
    
    var subtotal = $('#sum_sub_total').html();
    var discount =$('#sum_sale_discount').html();
    var tax =$('#sum_sale_tax').html();
    var service=0;
    var total_cost=$('#sum_sale_total').html();
    var payment=$('#pembayaran').val();
    var paidwith=$('#payment').val();
    data = {
        sale_id : 0,
        subtotal : subtotal,
        discount : discount,
        tax : tax,
        service : service,
        total_cost : total_cost,
        payment : payment,
        paidwith : paidwith,
        status : 0,
        table : table
    };
    var data_detail = [];
    var inc = 0;
    liveSearchPanel_SalesItems.store.each(function (rec) { 
        //        var temp = new Array(10,10);
        //        temp['item_price'].push(rec.get('item_total_cost'));
        //        data_detail[0]['quantity_purchased']=rec.get('quantity_purchased');
        data_detail[inc] = {
            "item_id":rec.get('item_id'),
            "quantity_purchased":rec.get('quantity_purchased'),
            "item_tax":rec.get('item_tax'),
            "item_discount":rec.get('item_discount'),
            "item_price":rec.get('item_price'),
            "item_total_cost":rec.get('item_total_cost')
        };
        inc=inc+1;
    });
    //    console.log(data_detail);

    $.ajax({
        url : '/postech/index.php?r=sales/hanyacetak',
        data : {
            data:data,
            data_detail:data_detail
        },
        success : function(data)
        {
            var sales = jQuery.parseJSON(data);
            if (sales.sale_id!="")
            {
                print_bayar(sales);
                // $.each(sales.detail, function(i,dani) {
                // // alert(dani.quantity + " " + dani.nama_item);
                // // var total_cetak = dani.logo + dani.alamat; 
                // });


                //$("#dialog_bayar").dialog("close");
             
            }
			
			
        },
        error : function(data)
        {
            //alert(data);
            alert("silahkan isikan menu terlebih dahulu");
            $('#dialog_meja').load('index.php?r=site/table');
        }
    });
}
*/
Ext.onReady(function() {
    var id = 0
    // $('#add_item').click(function () {
        
        
        
        
    
    //     });
       
    
    
    // empGroupStore_SalesItems = Ext.create('Ext.data.Store', {
    //     model:'SalesItems',
    //     pageSize:20,
    //     proxy:{
    //         type:'ajax',
    //         url:'index.php?r=salesitemshb/list',
    //         reader:{
    //             type:'json',
    //             root:'data',
    //             totalProperty:'totalCount'
    //         }
    //     },
    //     autoLoad:{
    //         start: 0, 
    //         limit: 25
    //     }
    // });

					// 	// alert()
					// 	// alert("123");
					// 	// addComboboxItemsWFS();
					// 	// this.store.clearData();
					// 	// this.store.loadData(my_data);
					// },
     //            	select: function(e){
     //  //           		
     //            	},
     //                focus: function (sm) {
     //                	// alert()
     //                	// var a = sm.getSelected().get('satuan_id');
     //                	// alert(a);
     //                	// alert()
     //                	// console.log(sm);
     //                }
                    	
                // }
              

    sizeDropdownStore.on('load', function(store, records, options)
	{
		// var record = new store.recordType({
		//     satuan_id: '1',
		//     nama_satuan: 'Kotak'
		// });
		// formCombo.add(record);
	 //    sizeDropdownStore.add(['2','box']);
	 //    sizeDropdownStore.add(['3','Gelas']);
	// 	// alert("123");
	// 	console.log(store);
	// 	console.log(records);
	// 	console.log(options);
		

	// 	// alert("loaded");
		// sizeDropdownStore.insert(0, 
		// 	new Ext.data.Record(
		// 		{'satuan_id':'4', 'nama_satuan':'box'}
		// 	)
		// ); 
	// 	// formCombo.setValue(0);

	}, this, {single: false});

    var satuan_before = "";
	var price_type_before = "";
					
    liveSearchPanel_SalesItems=Ext.create('Ext.grid.Panel',{
        id : 'ext_sales_items',
        // searchUrl:'index.php?r=salesitems/list',
        title: 'Daftar Item',
        listeners: {
            // keyup: {
            //     element: 'el',
            //     fn: function (eventObject, htmlElement, object, options) {
                 
            //         //                    alert(eventObject.keyCode);]\
                    
            //         if (eventObject.keyCode===46)
            //         {
            //             var pGrid = Ext.ComponentMgr.get('ext_sales_items');
            //             //                        alert(pGrid.id);
            //             id = pGrid.selModel.getCurrentPosition().row;
            //             record=pGrid.getStore().getAt(id);
            //             pGrid.store.remove(record);
            //             pGrid.getView().focus(); 
            //             pGrid.getSelectionModel().select(0);
            //             kalkulasi();
            //         }
            //     }
            // }
        },
        //    store: empGroupStore_SalesItems,
        plugins: [
        Ext.create('Ext.grid.plugin.RowEditing', {
            clicksToEdit: 1,
            listeners: {
                'beforeedit': function(editor,e){
                	   	var grid = liveSearchPanel_SalesItems;
					var selectedRecord = grid.getSelectionModel().getSelection()[0];
					var row = grid.store.indexOf(selectedRecord);
					
					var models = grid.getStore().getRange();
				
            		var s = formCombo.getStore();
		            s.proxy.extraParams.id= models[row].get("item_id");
		            s.load();

            		var x = formComboTipeHarga.getStore();
		            // x.proxy.extraParams.id= models[row].get("item_satuan_id");
		            x.proxy.extraParams.id= models[row].get("item_satuan");
		            x.proxy.extraParams.id_item= models[row].get("item_id");
		            // x.proxy.extraParams.id_item= models[row].get("item_id");
		            x.load();

					satuan_before = models[row].get("item_satuan");
					price_type_before = models[row].get("item_price_tipe");

                },
                'edit': function(editor,e){
                	// alert(satuan_before);
                	// alert(price_type_before);

					var grid = liveSearchPanel_SalesItems;
					var selectedRecord = grid.getSelectionModel().getSelection()[0];
					var row = grid.store.indexOf(selectedRecord);						
					var models = grid.getStore().getRange();
					var harga_before = models[row].get("item_price");
					// alert("before "+satuan_before);
					// alert("before "+price_type_before);
					// alert("after "+models[row].get("item_satuan"));
					// alert("after "+models[row].get("item_price_tipe"));

					
					if (satuan_before!=models[row].get("item_satuan") || price_type_before!=models[row].get("item_price_tipe") ){

            		 $.ajax({
			            // type: 'POST',
			            url: '<?php echo Yii::app()->createAbsoluteUrl("Items/GetHargaJualBySatuan"); ?>',
			           	data : "id="+models[row].get("item_id")+"&satuan_name="+models[row].get("item_satuan")+"&price_type="+models[row].get("item_price_tipe"),
			            success:function(data){
			            	// alert(data);
			            	var json = JSON.parse(data);
			            	// models
	            			models[row].set("item_satuan_id",json.id);
	            			models[row].set("item_price",json.harga_jual);
	            			kalkulasiRow(models,row);
	            			return;
        		  

			           

			            	// e.find("tr").find(".harga").val(json.harga_beli);
			            },
			            error:function(data){
			                // alert(data);
			                // alert(JSON.stringify(data));
			            },
			  
			            dataType:'html'
			        });
        		 }
        		  	kalkulasiRow(models,row);


                }
            }
        })
        ], 
       
        columns: [		
         {
            text:'kode',
            flex:1,
            sortable:true,
            dataIndex:'kode',
            // editor   : {
            //     xtype:'textfield',
            //     allowBlank:false
            // },
            hidden : false
        },
        {
            text:'id',
            flex:1,
            sortable:true,
            dataIndex:'id',
            editor   : {
                xtype:'textfield',
                allowBlank:false
            },
            hidden : true
        },
        {
            text:'sale_id',
            flex:1,
            sortable:true,
            dataIndex:'sale_id',
            editor   : {
                xtype:'textfield',
                allowBlank:false
            },
            hidden:true
        },
         {
            text:'lokasi',
            flex:1,
            sortable:true,
            dataIndex:'lokasi',
            editor   : {
                xtype:'textfield',
                allowBlank:false
            },
            hidden:true
        },
        {
            text:'ID Item',
            flex:1,
            sortable:true,
            dataIndex:'item_id',
            // editor   : {
            //     xtype:'textfield',
            //     allowBlank:false
            // },
            hidden:true
        },
        {
            text:'Nama Item',
            flex:1,
            minWidth: 160,
            sortable:true,
            dataIndex:'item_name'
            // ,
            // editor   : {
            //     xtype:'textfield',
            //     allowBlank:false
            // }
        },
        {
            text:'Tipe Harga',
            flex:1,
            hidden:<?php echo $bool_tipe_harga_hidden ?>,
            sortable:true,
            dataIndex:'item_price_tipe',
            editor: formComboTipeHarga
            // editor   : {
            //     xtype:'textfield',
            //     allowBlank:false
            // }
        },
        {
            text:'Satuan',
            flex:1,
            // hidden:false,
            hidden:<?php echo $bool_tipe_satuan_hidden ?>,
            
            sortable:true,
            dataIndex:'item_satuan',
            editor: formCombo
            // editor   : {
            //     xtype:'textfield',
            //     allowBlank:false
            // }
        },
        {
            text:'SID',
            flex:1,
            hidden:true,
            sortable:true,
            dataIndex:'item_satuan_id',
         },

        {
            text:'Jumlah',
            flex:1,
            sortable:true,

            dataIndex:'quantity_purchased',
            editor   : {
                xtype:'textfield',
                allowBlank:false
            }
        },
     
        {
            text:'Harga',
            flex:1,
            sortable:true,
            dataIndex:'item_price',
            editor   : {
                xtype:'textfield',
                allowBlank:false
            }
        },
         {
            text:'Ukuran',
            flex:1,
            sortable:true,
            hidden:true,
            dataIndex:'ukuran'
            // editor   : {
            //     xtype:'textfield',
            //     allowBlank:false
            // }
        },
         {
            text:'Panjang',
            flex:1,
            sortable:true,
			hidden:true,
            dataIndex:'panjang'
            // editor   : {
            //     xtype:'textfield',
            //     allowBlank:false
            // }
        },
         {
            text:'Ketebalan',
            flex:1,
            sortable:true,
            hidden:true,
            dataIndex:'ketebalan'

            // editor   : {
            //     xtype:'textfield',
            //     allowBlank:false
            // }
        },


        {
            text:'Diskon(%)',
            flex:1,
            sortable:true,
            dataIndex:'item_discount'
            ,editor   : {
                xtype:'textfield',
                allowBlank:false
            }
        },
        {
            text:'Pajak',
            flex:1,
            hidden:true,
            sortable:true,
            dataIndex:'item_tax',
            // editor   : {
                // xtype:'textfield',
                // allowBlank:false
            // }
        },
          {
            text:'service',
            flex:1,
            hidden:true,
            sortable:true,
            dataIndex:'item_service',
            // hidden : true
            // editor   : {
                // xtype:'textfield',
                // allowBlank:false
            // }
        },
         {
            text:'is_paket',
            flex:1,
            sortable:true,
            dataIndex:'is_paket',
            hidden : true
            // editor   : {
                // xtype:'textfield',
                // allowBlank:false
            // }
        },
        {
            text:'Total',
            flex:1,
            sortable:true,
            dataIndex:'item_total_cost',
            // editor   : {
                // xtype:'textfield',
                // allowBlank:false
            // }
        },
         {
            text:'pajak_value',
            flex:15,
            sortable:false,
            dataIndex:'pajak_value',
             hidden:true
            // editor   : {
                // xtype:'textfield',
                // allowBlank:false
            // }
        },
        {
            text:'service_value',
            flex:15,
            sortable:false,
            dataIndex:'service_value',
             hidden:true
            // editor   : {
                // xtype:'textfield',
                // allowBlank:false
            // }
        },
         {
            text:'keterangan',
            flex:1,
            sortable:true,
            dataIndex:'permintaan',
             // hidden : false,
            editor   : {
                xtype:'textarea',
                allowBlank:true
            }
        },
		
        {
            text:'Action',
            flex:1,
            minWidth: 50,
            hidden:false,
            xtype:'actioncolumn',
            items:[
            {
                icon:'icon/delete.gif',
                handler:function(grid,rowIndex,colIndex,item,e){
                    id=grid.getStore().getAt(rowIndex,colIndex);
                    grid.store.remove(id);
                    kalkulasi1();
                    $("#input_items").focus();
                }
            }
            ]
        }
        ],
        height: 400,
//        width :900,
        viewConfig: {
            stripeRows: true
        },
        renderTo : 'sales_items'

    }); 

	
	
    function kalkulasi()
    {
        //alert('kalkulasi');
        var sum = 0;
        var discount = 0;
        var tax = 0;
        var subtotal = 0;
        //alert('test');
        liveSearchPanel_SalesItems.store.each(function (rec) { 
            subtotal += rec.get('item_price')*rec.get('quantity_purchased'); 
            sum += rec.get('item_total_cost'); 
            discount += rec.get('item_discount') * (rec.get('item_price')*rec.get('quantity_purchased')) /100 ; 
            // tax += rec.get('item_tax') *  rec.get('quantity_purchased'); 
            tax += rec.get('item_tax');
        });

		// tax = (subtotal-discount)/10;
		
        $('#sum_sub_total').html(subtotal);
        $('#sum_sale_discount').html(Math.round(discount));
        $('#sum_sale_tax').html(Math.round(tax));
        service  = 	var_service * (subtotal-discount)/ 100;
        //service  = 	0;
            
        $('#sum_sale_service').html(Math.round(service));
        $('#sum_sale_total').html(Math.round(subtotal-discount+service+tax));
        $('#total_bayar').html(Math.round(subtotal-discount+tax+service));
    }

});

</script>