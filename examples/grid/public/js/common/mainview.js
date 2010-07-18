/*
 MR.modules = Array();

 MR.registerModule = function (module) {
 if (MR.modules.indexOf(module.name)) {
 MR.modules.push(module.name);
 MR.logger.log('Register module '+module.name+" :");
 }
 };



 // Check if module registered
 MR.isModule = function(module) {
 if (MR.modules.indexOf(module.name)) {
 throw new Exception('Module is not registered');
 return false;
 } 
 return true;
 };

 // Order handlers
 MR.viewOrderAction = new Ext.Action({
 id: 'viewOrderAction',
 text: 'View',
 handler: function(item, event, other){
 if(MR.isModule(MR.orders)) {
 MR.orders.viewOrders();
 } 
 },
 iconCls: 'blist'
 });

 MR.addOrderAction = new Ext.Action({
 id: 'addOrderAction',
 text: 'Add',
 handler: function(){
 if(MR.isModule(MR.orders)) {
 MR.orders.addOrder();
 }
 },
 iconCls: 'blist'
 });

 MR.syncOrdersAction = new Ext.Action({
 id: 'syncOrdersAction',
 text: 'Sync',
 handler: function(){
 if(MR.isModule(MR.orders)) {
 MR.orders.syncOrders();
 }
 },
 iconCls: 'blist'
 });



 //Customers handlers
 MR.viewCustomerAction = new Ext.Action({
 id: 'viewCustomerAction',
 text: 'View',
 handler: function(item, event, other){
 MR.logger.log(item, item.getId(),'Click','You clicked on "View".');

 },
 iconCls: 'blist'
 });

 MR.addCustomerAction = new Ext.Action({
 id: 'addCustomerAction',
 text: 'Add',
 handler: function(){
 MR.logger.log('Click','You clicked on "Add".');
 },
 iconCls: 'blist'
 });

 //Items handlers
 MR.viewItemAction = new Ext.Action({
 id: 'viewItemAction',
 text: 'View',
 handler: function(item, event, other){
 MR.logger.log(item, item.getId(),'Click','You clicked on "View".');

 },
 iconCls: 'blist'
 });

 MR.addItemAction = new Ext.Action({
 id: 'addItemAction',
 text: 'Add',
 handler: function(){
 MR.logger.log('Click','You clicked on "Add".');
 },
 iconCls: 'blist'
 });
 */
if (Ext.Direct) {
   MR.provider = Ext.Direct.providers['ext-gen3'];
   MR.logger.log(MR.provider);
}

MR.wsConnectAction = new Ext.Action( {
   id : 'connectAction',
   text : 'Connect',
   handler : function(item, event) {
      MR.onConnectHandler(item, event);
   },
   iconCls : 'blist'
});

MR.wsDisconnectAction = new Ext.Action( {
   id : 'disconnectAction',
   text : 'Disconnect',
   handler : function(item, event) {
      MR.onDisconnectHandler(item, event);
   },
   iconCls : 'blist'
});

MR.onConnectHandler = function(item, event) {
   try {
      MR.provider.connect();
      MR.logger.log('Connect to WebSocket');
   } catch (ex) {
      MR.logger.log(ex);
   }
};
MR.onDisconnectHandler = function(item, event) {
   try {
      MR.provider.disconnect();
      MR.logger.log("Disconnect from WebSocket");
   } catch (ex) {
      MR.logger.log(ex);
   }

};
/*
 * MR.wsdlListAction = new Ext.Action({ id: 'wsdlListAction', text: 'List of
 * webservices', handler: function (item, event) { MR.wsdl.onListHandler(item,
 * event); }, iconCls: 'blist' });
 */
MR.toptbar = [ {
   itemId : 'wsMenu',
   text : 'WebSocket',
   iconCls : 'add16',
   menu : [ MR.wsConnectAction, MR.wsDisconnectAction ]

} /*
    * ,{ itemId: 'customerMenu', text: 'Customers', iconCls: 'add16', menu:
    * [MR.viewCustomerAction, MR.addCustomerAction] },{ itemId: 'itemsMenu',
    * text: 'Items', iconCls: 'add16', menu: [MR.viewItemAction,
    * MR.addItemAction] }
    */
];

// Contact tab panel
MR.contentTabs = new Ext.TabPanel( {
   region : 'center',
   id : 'm',
   deferredRender : true,
   activeTab : 0,
   autoDestroy : false,
   items : [ {
      id : 'dashboard-tab',
      title : 'Dashboard',
      autoScroll : true,
      bbar : new Ext.ux.StatusBar( {
         id : 'm-statusbar',
         defaultText : 'WebSocket Disconnected'
      })

   } ],
   listeners : {
      'tabchange' : function(tabPanel, tab) {
         // Ext.History.add(tabPanel.id + tokenDelimiter + tab.id);
}
}

});

MR.addTab = function(id, title, content) {
   MR.contentTabs.add( {
      id : id,
      title : title,
      autoDestroy : false,
      items : content,
      closable : true,
      enableTabScroll : true,
      autoScroll : true,
      layout : 'fit'
   });
   MR.contentTabs.activate(id);
};

MR.sb = Ext.getCmp('m-statusbar');

Ext.onReady(function() {

   MR.view = new Ext.Viewport( {
      layout : 'border',
      items : [ {
         id : 'topmenu',
         region : 'north',
         baseCls : 'x-plain',
         split : true,
         height : 30,
         minHeight : 40,
         maxHeight : 85,
         layout : 'fit',
         margins : '5 5 0 5',
         tbar : MR.toptbar
      }, MR.contentTabs ]

   });
   
   

   MR.provider.on('connect', function(provider) {
      MR.logger.log("Connected");
      MR.sb.setStatus( {
         text : 'WebSocket Connected'
      });
   });

   MR.provider.on('disconnect', function(provider) {
      MR.logger.log("Disconnected");
      MR.sb.setStatus( {
         text : 'WebSocket Disconnected'
      });
   });
   
   
   MR.addTab('grid', 'Example grid', MR.grid.panel);
   

});