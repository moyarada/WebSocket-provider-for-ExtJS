// Namespaces
"use strict";
Ext.namespace('MR');
Ext.namespace('MR.grid');
/* Console Logger object */
MR.logger = {
   checkFBLoger : function() {
      if (typeof (console) == 'object')
         return true;
      return false;
   },
   log : function() {
      if (this.checkFBLoger()) {
         console.log(arguments);
      }
   }
};

Ext.BLANK_IMAGE_URL = '../../../lib/extjs/resources/images/default/s.gif';
