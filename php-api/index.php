<?php

$wrongPlace = array(
    'error' => 'cannot access this server directly. API access only'
);
header("Content-Type: application/json; charset=UTF-8");
echo json_encode($wrongPlace);
die;



switch ($api) {
    case 'users':
    if(strlen($action)){
        if($action == 'update'){

        }
        if($action == 'add'){
            
        }
        if($action == 'authenticate'){
            
        }
        if($action == 'get'){
            
        }
        if($action == 'recover'){
            
        }
    }
    break;
    case 'lists':
    if(strlen($action)){
        if($action == 'update'){

        }
        if($action == 'add'){
            
        }
        if($action == 'get'){
            
        }
        if($action == 'remove'){
            
        }
    }
    break;
    case 'items':
    if(strlen($action)){
        if($action == 'update'){

        }
        if($action == 'add'){
            
        }
        if($action == 'get'){
            
        }
        if($action == 'remove'){
            
        }
    }
    break;
    case 'stores':
    if(strlen($action)){

    }
    break;
    default:
        $response['error'] = 'api value missing';
        respond($response);
    break;
}
