<?php

class ISISDb{

  var $dbname;
  var $wxis_host;
  var $wxis_action;

  function ISISDb($dbname) {
    global $CONFIG, $DATABASES;
    
    $this->wxis_host = $_SERVER['SERVER_NAME'];
    $this->dbname = $dbname;
    $this->dbpath = $DATABASES[$dbname]['path'];
    $this->wxis_action = $CONFIG['ENVIRONMENT']['CGI-BIN_DIRECTORY'] . $CONFIG['ENVIRONMENT']['DIRECTORY'] . 'wxis.exe/'  . $CONFIG['ENVIRONMENT']['DIRECTORY'] . 'wxis/';

  }
  

  function get_list($param){
    return wxis_list($param);
  }
  
  function search($param){
    return $this->wxis_document_get( $this->wxis_url("search.xis"), $param );
  }
  
  function index($param){
    return wxis_index($param);
  }
  
  function wxis_document_get($url){
    
    return file_get_contents($url);

  }

  function wxis_url ( $IsisScript, $param ) {
    $request = "http://" . $this->wxis_host . $this->wxis_action . "?" . "IsisScript=" . $IsisScript;
    $request.= "&database=" . $this->dbpath . "/" . $this->dbname;

    foreach ($param as $key => $value){
        $request .= "&" . $key . "=" . $value;
    }

    return $request;
  
 }

}

?>