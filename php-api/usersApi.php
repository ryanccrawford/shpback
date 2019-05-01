<?php
    function users($action,$data,$db){
        global $response;
        if($action === 'insert'){
            $email = $data->email;
            $password = $data->password;
            $zip = $data->zip;
            addUser($email,$password,$zip,$db);
        }
        if($action === 'update_password'){
            $email = $data->email;
            $new_password = $data->newpassword;
            $old_password = $data->password;
            updateUserPassword($email,$old_password,$new_password,$db);
        }
        if($action === 'auth_user'){
            $email = $data->email;
            $password = $data->password;
            authenticate($email,$password,$db);
        }
        if($action === 'update_email'){
            $oldEmail = $data->email;
            $newEmail = $data->newemail;
            $password = $data->password;
            updateUserEmail($oldEmail,$newEmail,$password,$db);
        }
        if($action === 'update_zip'){
            $email = $data->email;
            $password = $data->password;
            $zip = $data->zip;
            updateUserZip($email,$password,$zip,$db);
        }
       
    }
    // Add a new user to the database, sends the client a JSON message with results.
    function addUser($email, $password, $zip, $database){
        global $response;
        if(strlen($zip) != 5 || strlen($email) < 5 || strlen($password) < 4){
            if(strlen($email) < 5){
                $response['error'][] = array('message'=>'bad email address'); 
            }
            if(strlen($zip) != 5){
                 $response['error'][] = array('message'=>'zip code invalid');
            }
            if(strlen($password) < 4){
                 $response['error'][] = array('message'=>'password too short');
            }
            respond($response);
        }
        
        if(!checkUserExisit($email, $database)){
            $encrypt_password = md5($password);
            $sql = 'INSERT INTO '. USERS .' (email, password, created_on, zip) VALUES ("' . $email . '", "' . $encrypt_password . '", CURDATE(),"' . $zip . '")';
            $database->query($sql);
            $response['userid'] = $database->getInsertedId();
            respond($response);
        }
        $response['error'][] = array('message'=>'user exist');
        respond($response);
    }
    function updateUserZip($email, $password, $zip, $database){
        global $response;
        if(strlen($zip) != 5 || strlen($email) < 5 || strlen($password) < 4){
            if(strlen($email) < 5){
                $response['error'][] = array('message'=>'bad email address'); 
            }
            if(strlen($zip) != 5){
                 $response['error'][] = array('message'=>'zip code invalid');
            }
            if(strlen($password) < 4){
                 $response['error'][] = array('message'=>'password too short');
            }
            respond($response);
        }
        $encrypt_password = md5($password);
        $sql = "UPDATE ". USERS ." SET zip=$zip WHERE email=$email AND password=$encrypt_password";
        $database->query($sql);
        $response['users'] = array('code'=>200,'zip'=>$zip);
        respond($response);
    }
    function authenticate($email, $password, $database){
        global $response;
        $sql = 'SELECT user_id, email FROM ' . USERS . ' WHERE email="'.$email.'" AND password="'.md5($password).'"';
        $database->query($sql);
        $result = $database->getResults();
        if($result == 0){
           $response['error'] = array('message','faild');
        }else{
            $_SESSION["userid"] = $result[0]['user_id'];
            $_SESSION["email"] = $email;
            
            $response['email'] = $email;
            $response['userid'] = $result[0]['user_id'];
        }
       respond($response);

    }
    function updateUserEmail($old_email,$new_email,$password,$database){
        global $response;
        if(authenticate($old_email, $password, $database)){
            $sql = 'UPDATE users SET email="$new_email" WHERE email="$old_email" AND `password`="$password"';
            $database->query($sql);
            $result = $database->getResults();
            if($result == 0){
                $response['error'][] = array('message','Could Not Update Email');
            }else{
               $response['success'] = $result;
            }
        }else{
            $response['error'][] = array('code',401);
        }
        respond($response);
    }
    function updateUserPassword($email,$old_password,$new_password,$database){
        global $response;
        if(authenticate($email, $old_password, $database)){
            $old = md5($old_password);
            $new = md5($new_password);
            $sql = 'UPDATE users SET `password`="$new" WHERE email="$email" AND `password`="$old"';
            $database->query($sql);
            $result = $database->getResults();
            if($result == 0){
                $response['error'][] = array('message','Could Not Update Password');
            }else{
               $response['success'] = $result;
            }
        }else{
            $response['error'][] = array('code',401);
        }
        respond($response);

    }
    function checkUserExisit($email, $database){
        $sql = 'SELECT email FROM ' . USERS . ' WHERE email="'.$email.'" ';
        $database->query($sql);
        $result = $database->getResults();
        if($result == 0){
            return false;
        }
        return true;
    }
    function resetPassword($email,$database){
        //TODO: create php script for password reset 
        // need to verify an encryption string saved to datbase then sent in email to verify the email account
        // right now this just sends the email 
        $link = 'https://fe41a14.online-server.cloud/emailrecovery.php?email=$email&v=$pword';
        $html_message = createEmail($link);
        sendEmail($email, 'Password Recovery', $html_message);

    }
    function sendEmail($email, $subject, $html_message){
      
        $mail_headers = "MIME-Version: 1.0" . "\r\n";
        $mail_headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $mail_headers .= 'From: <admin@fe41a14.online-server.cloud>' . "\r\n";
        mail($email,$subject,$html_message,$mail_headers);

    }
    function createEmail($link){
        return '
            <html>  
                <head>
                <title>Password Recovery</title>
                </head>
                <body>
                    <h1>Recover your password</h1>
                    <p>Click the following link to reset your password</p>
                    <p><a href="$link">RESET PASSWORD</a></p>
                </body>
            </html>';
    }