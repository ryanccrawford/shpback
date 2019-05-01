<?php 

    function lists($action,$data,$db){
        global $response;
        $userid = isset($_SESSION['userid'])?$_SESSION['userid']:false;    
        if(!$userid){
            $response['error'][] = array('message','not logged in');
        }else{
            if($action === 'add_list' && $userid){
                $name = isset($data->listname) ? $data->listname : null;
                addUserList($userid,$listname,$listname,$db);
            }
            if($action === 'get_list' && $userid){
                $listid = $data->listid;
                getUserList($userid,$listid,$db);
            }
            if($action === 'get_lists' && $userid){
                getUserLists($userid,$db);
            }
            if($action === 'update_list_name' && $userid){
                $new_name = $data->listname;
                $listid = $data->listid;
                updateUserListName($userid,$listid,$new_name,$db);
            }
            if($action === 'remove_list' && $userid){
                $listid = $data->listid;
                removeUserList($userid, $listid, $db);
                
            }
            if($action === 'remove_lists' && $userid){
                removeUsersLists($userid, $db);
                
            }  
        }
            respond($response);
    }
    // Add new list to database
    function addUserList($userid, $listname, $database){
        global $response;
        $sql = 'INSERT INTO '. LISTS .' (user_id, name, created_on) VALUES ($userid, "'. $listname .'", CURDATE())';
        $database->query($sql);
        $response['listid'] = $database->insert_id;
    }
    // Get List from database kinda point less to have this
    function getUserList($userid, $listid, $database){
        global $response;
        $sql = 'SELECT * FROM '. LISTS .' WHERE user_id=$userid AND list_id=$listid';
        $database->query($sql);
        $result = $database->getResults();
        $response['result'] = $result;
    }
    // Get all users list
    function getUserLists($userid,$database){
        global $response;
        $sql = 'SELECT * FROM '. LISTS .' WHERE user_id=$userid';
        $database->query($sql);
        $result = $database->getResults();
        $response['result'] = $result;
    }
    // Update List from database
    function updateUserListName($userid, $listid, $listname, $database){
        global $response;
        $sql = 'UPDATE '. LISTS .' SET name="'.$listname.'" WHERE user_id=$userid AND list_id=$listid';
        $database->query($sql);
        $result = $database->getResults();
        $response['result'] = $result;

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