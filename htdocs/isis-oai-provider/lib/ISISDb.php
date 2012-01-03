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

  function getidentifiers($param){
    return $this->wxis_document_post( $this->wxis_url("getidentifiers.xis",$param) );
  }

  
  function index($param){
    return wxis_index($param);
  }
  
  function wxis_document_get($url){
    
    return file_get_contents($url);

  }

  function wxis_url ( $IsisScript, $param ) {
    $request = "http://" . $this->wxis_host . $this->wxis_action . "?" . "IsisScript=" . $IsisScript;
    if ($this->dbname != ''){
      $request.= "&database=" . $this->dbpath . "/" . $this->dbname;
    }  

    foreach ($param as $key => $value){
        $request .= "&" . $key . "=" . $value;
    }

    return $request;
  
 }

  function wxis_document_post( $url, $content = "" ){ 
    $content = str_replace("\\\"","\"",$content);
    $content = str_replace("\n","",$content);
    $content = str_replace("\r","",$content);
    $content = str_replace("\\\\","\\",$content);

    // Strip URL  
    $url_parts = parse_url($url);
    $host = $url_parts["host"];
    $port = ($url_parts["port"]) ? $url_parts["port"] : 80;
    $path = $url_parts["path"];
    $query = $url_parts["query"];
    if ( $content != "" )
    {
      $query .= "&content=" . urlencode($content);
    }
    $timeout = 10;
    $contentLength = strlen($query);
    
    // Generate the request header 
      $ReqHeader =  
        "POST $path HTTP/1.0\n". 
        "Host: $host\n". 
        "User-Agent: PostIt\n". 
        "Content-Type: application/x-www-form-urlencoded\n". 
        "Content-Length: $contentLength\n\n". 
        "$query\n"; 
    
    // Open the connection to the host 
    $fp = fsockopen($host, $port, $errno, $errstr, $timeout);
    
    fputs( $fp, $ReqHeader ); 
    if ($fp) {
      while (!feof($fp)){
        $result .= fgets($fp, 4096);
      }
    }
    
    $response = strstr($result,"\n\r");
    $response = trim($response);

    return $response; 
  }

}

?>