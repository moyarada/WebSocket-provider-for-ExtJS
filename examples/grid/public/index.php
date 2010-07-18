<?php
defined('APPLICATION_PATH') || define('APPLICATION_PATH', 
    realpath(dirname(__FILE__) . '/../../../application'));
    
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../'),
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

?>

<html>
<head>
    <title>WebSocket ExtJs provider</title>
     
    <link rel="stylesheet" type="text/css" href="../../../lib/extjs/resources/css/ext-all-notheme.css"/> 
    <link rel="stylesheet" type="text/css" href="../../../lib/extjs/resources/css/radatheme.css"/> 
    <script type="text/javascript" src="https://getfirebug.com/firebug-lite.js"></script>   
    <script type="text/javascript" src="../../../lib/extjs/ext-base-debug.js"></script> 
    <script type="text/javascript" src="../../../lib/extjs/ext-all-debug.js"></script> 
    <script type="text/javascript" src="../../../lib/extjs/StatusBar.js"></script>
    <script type="text/javascript" src="../../../lib/extjs/ProgressBarPager.js"></script> 
    <script type="text/javascript" src="../../../lib/extjs/PanelResizer.js"></script> 
    <script type="text/javascript" src="../../../lib/extjs/PagingMemoryProxy.js"></script> 
    <script type="text/javascript" src="js/common/nspaces.js"></script> 
    <script type="text/javascript" src="../../../src/socketprovider.js"></script>
    
    <script type="text/javascript" src="api/api.php"></script>
      
    <script type="text/javascript">
    
    Ext.Direct.addProvider(MR.direct.REMOTING_API);
    
    Ext.Direct.on('message', function(e){
       MR.logger.log('Message received',e);
    });
    
    </script>
    
    
    <script type="text/javascript" src="js/common/mainview.js"></script>
    <script type="text/javascript" src="js/grid.js"></script>
    <!--  
    <script type="text/javascript" src="/js/orders/module.js"></script>
    <script type="text/javascript" src="/js/wsdl/wsdl.js"></script>
      -->
    
     
</head>
<body>

</body>

</html>