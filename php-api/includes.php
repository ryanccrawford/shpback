<?php
define('USERS','users');
define('LISTS','users_lists');
define('ITEMS', 'list_items');
define('API_KEY', 'q98ejf-fqwefj-8wefqw8w');
function respond($data){
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($data);
    die;
}
require_once('database.php');
require_once('paramaters.php');
require_once('usersApi.php');
require_once('listsItemsApi.php');
require_once('listsApi.php');



