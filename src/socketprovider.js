/**
 * Instance of the provider injected into WebSocket object
 */
WebSocket.prototype.provider = null;
/**
 * Callback called onmessage event
 */
WebSocket.prototype.mycallback = null;

Ext.direct.SocketProvider = Ext.extend(Ext.direct.RemotingProvider, {
   wsocket: null, 
   connected: false,
   callbacks: [],
   
   parseResponse: function(xhr){
      //console.log(xhr);
      if(!Ext.isEmpty(xhr.data)){
          if(typeof xhr.data == 'object'){
              return xhr.data;
          }
          return Ext.decode(xhr.data);
      }
      return null;
   },
   
   connect : function() {
      if (this.url) {
         if (!this.connected) {
            this.connectSocket();
         }
         this.initAPI();
      } else if (!this.url) {
         throw 'Error initializing SocketProvider, no url configured.';
      }
   },
   
   connectSocket: function() {
      try {
         this.wsocket = new WebSocket(this.url);
      } catch(ex) {
         console.log(ex);
      }

      this.wsocket.onopen = this.onSockOpen;
      this.wsocket.onmessage = this.onSockMessage;
      this.wsocket.onclose = this.onSockClose;
      this.wsocket.onerror = this.onSockError;
      this.wsocket.provider = this;
   },
   
   onSockOpen: function(evt) { 
      this.connected = true;
      this.provider.fireEvent('connect', this.provider);
   },
   
   onSockMessage: function(response) {
      try {
         var opt = {};
         var status = true;
         if (this.mycallback != null) {
            var callback = this.mycallback.createDelegate(this.provider, [opt, status, response]);
            callback(); 
         } else {
            this.provider.fireEvent('data', this.provider, response);
         }
      } catch(ex) {
         console.log(ex);
      }
   },
   
   onSockClose: function(obj) {
      this.connected = false;
      this.provider.fireEvent('disconnect', this.provider);
   },
   
   onSockError: function(evt) { 
      alert("Error "+evt);
      this.provider.fireEvent('exception', this.provider);
   },
   

   disconnect : function() {
      if (this.connected) {
         this.wsocket.close();
      }
   },
   
   doSend : function(data){
      var o = {
          url: this.url,
          callback: this.onData,
          scope: this,
          ts: data,
          timeout: this.timeout
      }, callData;

      if(Ext.isArray(data)){
          callData = [];
          for(var i = 0, len = data.length; i < len; i++){
              callData.push(this.getCallData(data[i]));
          }
      } else{
          callData = this.getCallData(data);
      }

      if(this.enableUrlEncode){
          var params = {};
          params[Ext.isString(this.enableUrlEncode) ? this.enableUrlEncode : 'data'] = Ext.encode(callData);
          o.params = params;
      }else{
          o.jsonData = callData;
      }
      
      this.wsRequest(o);
      
  },
  
  wsRequest : function(o) {
     var data = {'data': o.jsonData};
     if (this.wsocket.readyState == 1) {
        console.log(o);
        this.wsocket.mycallback = o.callback;
        this.wsocket.send(Ext.encode(data));
     } else {
        throw 'Socket is not ready. Current state is '+this.wsocket.readyState;
     }
     
  },
  
  processForm: function(t){
     var o = {
           url: this.url,
           params: t.params,
           callback: this.onData,
           scope: this,
           form: t.form,
           isUpload: t.isUpload,
           ts: t
       };
     console.log('Submitting form');
    
     var form = Ext.getDom(o.form);
     var serForm = Ext.lib.Ajax.serializeForm(form);
     var fields = serForm.split("&");
     var formFields = {};
     
     //@todo review this code
     for(var key = 0, l = fields.length; key < l; key++) {
        var field = fields[key];
        var fld    = field.split("=");
        var fName  = fld[0];
        var fValue = fld[1];
        formFields[fName] = fValue;
     }
     formData = Ext.apply(o.params, formFields);
     reqData = {
           "action" : o.ts.action,
           "method" : o.ts.method,
           "tid" : o.ts.tid,
           "type": o.params.extType,
           "data": formData
        
     };
     console.log(reqData, o);
     this.wsocket.mycallback = o.callback;
     this.wsocket.send(Ext.encode(reqData));
  }

});

Ext.Direct.PROVIDERS['socket'] = Ext.direct.SocketProvider;