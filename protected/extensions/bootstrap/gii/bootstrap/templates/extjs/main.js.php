<?php echo "var liveSearchPanel_".$this->modelClass."; \n"; ?>
<?php echo "var empGroupStore_".$this->modelClass."; \n"; ?>
//Ext.Loader.setConfig({enabled: true});
<?php
echo "
Ext.require([
    'Ext.grid.*',
    'Ext.data.*',
    'Ext.util.*',
    'Ext.state.*',
    'Ext.toolbar.Paging',
    'Ext.ux.PreviewPlugin',
    'Ext.ModelManager',
    'Ext.tip.QuickTipManager'
    ]);
";
echo "

empGroupStore_".$this->modelClass." = Ext.create('Ext.data.Store', {
        model:'".$this->modelClass."',
        pageSize:20,
        proxy:{
            type:'ajax',
            url:'index.php?r=".strtolower($this->modelClass)."/list',
            reader:{
                type:'json',
                root:'data',
                totalProperty:'totalCount'
            }
        },
        autoLoad:{
            start: 0, 
            limit: 25
        }
    });
	
liveSearchPanel_".$this->modelClass."=Ext.create('Ext.grid.Panel',{
        searchUrl:'index.php?r=".strtolower($this->modelClass)."/list',
        title: '".$this->modelClass."',
        store: empGroupStore_".$this->modelClass.",
        plugins: [
        Ext.create('Ext.grid.plugin.RowEditing', {
            clicksToEdit: 2,
            listeners: {
                edit: function(editor,e){
                    Ext.Ajax.request({
                        url: 'index.php?r=".strtolower($this->modelClass)."/update&id=' + e.record.get('id'),
                        params: e.record.data,
                        success: function(){ 
                            e.record.commit();
                            empGroupStore_".$this->modelClass.".load();
                        }
                    })
                }
            }
        })
        ], 
        tbar: [
        { 
            xtype: 'button', 
            text: 'Add',
            icon : 'icon/Add.gif',
            handler : function()
            {
                form_add_window_".$this->modelClass.".show();
            }
        }
        ],
        dockedItems: [{
            xtype: 'pagingtoolbar',
            store: empGroupStore_".$this->modelClass.",   // same store GridPanel is using
            dock: 'bottom',
            displayInfo: true,
            items : [
            {
                xtype : 'textfield',
                fieldLabel : 'Search',
                columnWidth : 200,
                itemId : 'searchId_".$this->modelClass."',
                id : 'searchId_".$this->modelClass."',
                size : 200,
                width : 200,
                emptyText: 'Enter what you search...',
                listeners:  {
                    specialkey: function (f,e) {    
                        if (e.getKey() == e.ENTER) {
                            var search = $(\"#searchId_".$this->modelClass."-inputEl\").val();
                            
                            empGroupStore_".$this->modelClass.".load({
                                params: {
                                    query: search
                                },
                                scope: this
                            });
                        }
                    }
                }
            }
            ]
        }],
        columns: [";
		?>
		<?php foreach($this->tableSchema->columns as $column): 
			echo 
			"{
				text:'".$column->name."',
				flex:1,
				sortable:false,
				dataIndex:'".$column->name."',
				editor   : {
					xtype:'textfield',
					allowBlank:false
				}
			},\n";
			endforeach; ?>
		<?php
		echo "
        {
            text:'Action',
            flex:1,
            xtype:'actioncolumn',
            items:[
            {
                icon:'icon/delete.gif',
                handler:function(grid,rowIndex,colIndex,item,e){
                    Ext.MessageBox.show({
                        title:'".$this->modelClass."',
                        msg:'Do you want to delete this row?',
                        buttons:Ext.Msg.YESNO,
                        fn:function(btn){
                            id=grid.getStore().getAt(rowIndex,colIndex).data.id;
                            if(btn=='yes'){
                                $.ajax({
                                    url:'index.php?r=".strtolower($this->modelClass)."/delete&ajax=1&id='+id,
                                    type:'post',
                                    timeOut:3000,
                                    success:function(){
                                        
                                        grid.getStore().load();
                                    }
                                });
                            }
                        }
                    })
                }
            }
            ]
        }
        ],
        height: 400,
        viewConfig: {
            stripeRows: true
        }

    });";


?> 
