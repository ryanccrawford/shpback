<?php 

    function items($action,$data,$db){
        global $response;
        $userid = isset($_SESSION['userid'])?$_SESSION['userid']:false;    
        if(!$userid){
            $response['error'][] = array('message','not logged in');
        }else{
            $listid = $data->listid;
            if($action === 'add_item' && $userid){
                addItem($userid, $listid, $db);
            }
            if($action === 'get_item' && $userid){
                getItem($userid, $listid, $itemid, $db);
            }
            if($action === 'get_items' && $userid){
                getItems($userid, $listid, $db);
            }
            if($action === 'remove_item' && $userid){
                removeItem($userid, $listid, $itemid, $db);
            }
            if($action === 'remove_items' && $userid){
                removeItems($userid, $listid, $db);
            }  
        }
            respond($response);
    }
    // Add new item to list
    function addItem($userid, $listid, $itemname, $database){
        global $response;
       $category = isset($data->walmartcategory)?$data->walmartcategory:null;
       $categoryid = isset($data->walmartcategoryid)?$data->walmartcategoryid:null;
       // item_id, , user_id, name, upc, walmart_id, walmart_category, walmart_category_id, walmart_price
        $sql = 'INSERT INTO '. ITEMS .' (list_id, user_id, name, walmart_category_id, walmart_category) VALUES ($listid, $userid, "'. $itemname .'", $categoryid, "'.$category.'")';
        $database->query($sql);
        $response['item_added']['itemid'] = $database->insert_id;
        $response['item_added']['listid'] = $listid;
    }
    // Get item from list 
    function getItem($userid, $listid, $itemid, $db){
        global $response;
        $sql = 'SELECT * FROM '. ITEMS .' WHERE user_id=$userid AND list_id=$listid AND item_id=$itemid';
        $db->query($sql);
        $result = $db->getResults();
        $response['result'] = $result;
    }
    // Get all items from list
    function getItems($userid, $listid, $db){
        global $response;
        $sql = 'SELECT * FROM '. ITEMS .' WHERE user_id=$userid AND list_id=$listid';
        $db->query($sql);
        $result = $db->getResults();
        $response['result'] = $result;
    }
   // Remove Item from list 
    function removeItem($userid, $listid, $itemid, $db){
        global $response;
        $sql = 'DELETE FROM '. ITEMS .' WHERE user_id=$userid AND list_id=$listid AND item_id=$itemid';
        $db->query($sql);
        $result = $db->getResults();
         if($result){
            $response['result'] = $result;
         }else{
            $response['error'][] = array('message','delete faild');
         }
    }
    // Remove all items from Lists 
    function removeItems($userid, $listid, $db){
       global $response;
        $sql = 'DELETE FROM '. ITEMS .' WHERE user_id=$userid AND list_id=$listid';
        $db->query($sql);
        $result = $db->getResults();
         if($result){
            $response['result'] = $result;
         }else{
            $response['error'][] = array('message','delete faild');
         }
    }