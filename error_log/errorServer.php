#!/usr/bin/php
<?php

require_once('../rabbit/path.inc');
require_once('../rabbit/rabbitMQLib.inc');

//$client = new rabbitMQClient("../rabbit/dbRabbitMQ.ini","errorLogger");

function logerror($type,$error){
  $file_data = $error . '\n';
  $error_log_name = $type.".txt";
  $file_data .= file_get_contents($error_log_name);
  file_put_contents($error_log_name, $file_data);
  return json_encode(["message" => "Error Logged"]);
}
function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    return "ERROR: unsupported error type";
  }
  switch ($request['type'])
  {
    case "DBerrors":
    case "Frontenderrors":
    case "DMZerrors":
    case "RabbitMQerrors":
        return logerror($request['type'], $request['error']);
      
  }
  return array("returnCode" => '0', 'message'=>"unsupported error type");
}

$server = new rabbitMQServer("errorServerMQ.ini","errorServer");

echo "Error Logger BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "Error Logger END".PHP_EOL;
exit();

?>
