<?php 

    function lists($action,$data,$db){
        global $response;
        $listid = $data->listid;
        $userid =  $data->userid;
        $userid = intval($userid);
        if(!$userid){
            $response['auth'] = array('message','user not loggedin');
            respond($response);
        }
            if($action === 'add_list'){
                $name = $data->listname;
                addlist($userid,$name,$db);
            }
            if($action === 'get_list'){
                getUserList($userid,$listid,$db);
            }
            if($action === 'get_lists'){
                getUserLists($userid,$db);
            }
            if($action === 'update_list_name' && $userid){
                $new_name = $data->listname;
                updateUserListName($userid,$listid,$new_name,$db);
            }
            if($action === 'remove_list'){
                removeUserList($userid, $listid, $db);
            }
            if($action === 'remove_lists'){
                removeUsersLists($userid, $db);
            }  
            if($action === 'getAllListsForUser'){
                getAllListsForUser($userid, $db);
            }  
         respond($response);
        }
           
    
    // Add new list to database
    function addlist($userid, $listname, $database){
        global $response;
        $sql = 'INSERT INTO '. LISTS .' (list_id, user_id, name, created_on) VALUES (NULL, $userid, "'. $listname .'", CURDATE())';
        $database->query($sql);
        $result = $database->getResults();

        $response['listid'] = $database->getInsertedId();
        respond($response);
    }
    // Get List from database kinda point less to have this
    function getUserList($userid, $listid, $database){
        global $response;
        $sql = 'SELECT * FROM '. LISTS .' WHERE user_id=$userid AND list_id=$listid';
        $database->query($sql);
        $result = $database->getResults();
        $response['list'] = $result;
        respond($response);
    }
    // Get all users list
    function getUserLists($userid,$database){
        global $response;
        $sql = 'SELECT * FROM '. LISTS .' WHERE user_id=$userid';
        $database->query($sql);
        $result = $database->getResults();
        $response['lists'] = $result;
        respond($response);
    }
    // Update List from database
    function updateUserListName($userid, $listid, $listname, $database){
        global $response;
        $sql = 'UPDATE '. LISTS .' SET name="'.$listname.'" WHERE user_id=$userid AND list_id=$listid';
        $database->query($sql);
        $result = $database->getResults();
        $response['result'] = $result;
        respond($response);

    }
    // Remove List from database
    function removeUserList($userid, $listid, $database){
        global $response;
        $sql1 = 'DELETE FROM '. LISTS .' WHERE user_id=$user_id AND list_id=$listid';
        $database->query($sql1);
        $result1 = $database->getResults();
        if($result1){
            $sql2 = 'DELETE FROM '. ITEMS .' WHERE user_id=$user_id AND list_id=$listid';
            $database->query($sql2);
            $result2 = $database->getResults();
            if($result2){
                $response['result'] = array($result2,$result2);
            }
        }else{
            $response['error'][] = array('message','delete faild');
        }
        respond($response);
    }
    function getAllListsForUser($userid, $db){
        global $response;
      
        $sql = 'SELECT * FROM users_lists inner JOIN list_items on users_lists.user_id=list_items.user_id WHERE users_lists.user_id=$userid';
        $db->query($sql);
        $data_lists = $db->getResults();
        if(count($data_lists) > 0){
            $response['lists'] = count($data_lists);
            respond($response);
        }
        $response['list'] = array('message','No List');
        respond($response);
    }
    // Remove all users Lists and items from database
    function removeUsersLists($userid, $database){
        global $response;
        $sql1 = 'DELETE FROM '. LISTS .' WHERE user_id=$user_id';
        $database->query($sql1);
        $result1 = $database->getResults();
        if($result1){
            $sql2 = 'DELETE FROM '. ITEMS .' WHERE user_id=$user_id';
            $database->query($sql2);
            $result2 = $database->getResults();
            if($result2){
                $response['result'] = array($result2,$result2);
            }
        }else{
            $response['error'][] = array('message','delete faild');
        }
    }