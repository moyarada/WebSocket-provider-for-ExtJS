// Grid

MR.grid.store = new Ext.data.DirectStore( {
   api : {
      read : MR.direct.DataService.getGridData,
      load : MR.direct.DataService.getGridData
   },
   root : 'data',
   totalProperty : 'totalCount',
   autoDestroy : false,
   sortInfo : {
      field : 'price',
      direction : 'ASC'
   },
   fields : [ {
      name : 'company'
   }, {
      name : 'price',
      type : 'float'
   }, {
      name : 'change',
      type : 'float'
   }, {
      name : 'pctChange',
      type : 'float'
   }, {
      name : 'lastChange',
      type : 'date',
      dateFormat : 'n/j h:ia'
   } ]

});

// example of custom renderer function
MR.grid.change = function(val) {
   if (val > 0) {
      return '<span style="color:green;">' + val + '</span>';
   } else if (val < 0) {
      return '<span style="color:red;">' + val + '</span>';
   }
   return val;
};

// example of custom renderer function
MR.grid.pctChange = function(val) {
   if (val > 0) {
      return '<span style="color:green;">' + val + '%</span>';
   } else if (val < 0) {
      return '<span style="color:red;">' + val + '%</span>';
   }
   return val;
};

MR.grid.panel = new Ext.grid.GridPanel( {
   store : MR.grid.store,
   columns : [ {
      id : 'company',
      header : "Company",
      width : 160,
      sortable : true,
      dataIndex : 'company'
   }, {
      header : "Price",
      width : 75,
      sortable : true,
      renderer : 'usMoney',
      dataIndex : 'price'
   }, {
      header : "Change",
      width : 75,
      sortable : true,
      renderer : MR.grid.change,
      dataIndex : 'change'
   }, {
      header : "% Change",
      width : 75,
      sortable : true,
      renderer : MR.grid.pctChange,
      dataIndex : 'pctChange'
   }, {
      header : "Last Updated",
      width : 85,
      sortable : true,
      renderer : Ext.util.Format.dateRenderer('m/d/Y'),
      dataIndex : 'lastChange'
   } ],
   stripeRows : true,
   autoExpandColumn : 'company',
   height : 320,
   width : 600,
   frame : true,
   title : 'Sliding Pager',

   plugins : new Ext.ux.PanelResizer( {
      minHeight : 100
   }),

   bbar : new Ext.PagingToolbar( {
      pageSize : 10,
      store : MR.grid.store,
      displayInfo : true,
      plugins : new Ext.ux.ProgressBarPager()
   }),
   listeners : {
      'viewready' : function(panel) {
         MR.logger.log("Updating show", panel.store);
         panel.store.load({
            params : {
               start : 0,
               limit : 10
            }
         });
      }
   }
});

MR.grid.onGridHandler = function(item, event) {
   MR.logger.log(item, event);
   MR.addTab('grid', 'Example grid', MR.grid.panel);
};