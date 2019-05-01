<?php
session_start();
require_once('includes.php');
//Check for api key
if(get_param('apiKey') !== API_KEY){
    
    $error = array('error' => 'Invalid or missing Api Key');
    respond($error);
}
$response = array();
global $response;
$datab = new database();

$api = get_param('api');
$action = get_param('action');
$data = get_param('data', true);
If(!strlen($action)){
    $response['error'] = array('message' => 'missing action');
    respond($response);
}

doaction($action,$api,$datab,$data);


function doaction($_action,$_api,$_db,$_data=null){
global $response;
    switch ($_api) {
        case 'users':
        users($_action,$_data,$_db);
        break;
        case 'lists':
        lists($_action,$_data,$_db);                                
        break;
        case 'listsItems':
        listsItems($_action,$_data,$_db);
        break;
        case 'items':
        items($_action,$_data,$_db);
        break;
        default:
            $response['error'] = array('message'=>'api value missing');
            respond($response);
        break;
    }
}

