<?php

function pagination_link()
{
    $page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $page_number = ($page_number < 1) ? 1 : $page_number;
    
    $arr['next_page'] = "";
    $arr['prev_page'] = ""; 
    //get current url
    $url ="http://" . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'];
    $url .= "?";
    
    $next_page_link = $url;
    $prev_page_link = $url;
    $page_found = false;

    $num = 0;
    foreach ($_GET as $key => $value)
    {   
        $num++;
        if($num == 1)
        {
            if($key == "page"){
                $next_page_link .= $key ."=" . ($page_number + 1);
                $prev_page_link .= $key ."=" . ($page_number - 1);
                $page_found = true;
            }
            else
            {
                $next_page_link .= $key ."=" . $value;
                $prev_page_link .= $key ."=" . $value;
            }
        }
        else
        {   
            if($key == "page")
            {
                $next_page_link .= "&" . $key ."=" . ($page_number + 1);
                $prev_page_link .= "&" . $key ."=" . ($page_number - 1);
                $page_found = true;
            }
            else
            {
                $next_page_link .= "&" . $key ."=" . $value;
                $prev_page_link .= "&" . $key ."=" . $value;
            }
        }    
    }
    
    $arr['next_page'] = $next_page_link;
    $arr['prev_page'] = $prev_page_link;
    if(!$page_found)
    {
        $arr['next_page'] = $next_page_link . "&page=2";
        $arr['prev_page'] = $prev_page_link . "&page=1";
    }
    return $arr;
}

function i_own_content($row)
{   
    $myid = $_SESSION['mybook_userid'];
    //profiles
    if(isset($row['gender']) && $myid == $row['userid'])
    {
        return true;
    }
    //comments and posts
    if(isset($row['postid']))
    {   
        if($myid == $row['userid'])
        {
            return true;
        }
        else
        {   
            $Post = new Post();
            $one_post = $Post->get_one_post($row['parent']);

            if($myid == $one_post['userid'])
            {
                return true;
            }
        }
    }

    return false;
}

function add_notification($userid,$activity,$row)
{
    $row = (object)$row;
    $userid = esc($userid);
    $activity = esc($activity);
    $content_owner = $row->userid;
    $date = date("Y-m-d H:i:s");
    $contentid =0; 
    $content_type = "";

    if(isset($row->postid))
    {
        $contentid = $row->postid;
        $content_type = "post";

        if($row->parent > 0)
        {
            $content_type = "comment";
        }
    }   
    
    if(isset($row->gender))
    {
        $content_type = "profile";
        $contentid = $row->userid;
    }
    
    $query = "insert into notifications (userid,activity,content_owner,date,contentid,content_type) values ('$userid','$activity','$content_owner','$date','$contentid','$content_type')";
    $DB = new Database();
    $DB->save($query);
}

function content_i_follow($userid,$row)
{   
    $row = (object)$row;
    $userid = esc($userid);
    $date = date("Y-m-d H:i:s");
    $contentid =0; 
    $content_type = "";

    if(isset($row->postid))
    {
        $contentid = $row->postid;
        $content_type = "post";

        if($row->parent > 0)
        {
            $content_type = "comment";
        }
    }    
    
    if(isset($row->gender))
    {
        $content_type = "profile";
    }
    
    $query = "insert into content_i_follow (userid,date,contentid,content_type) values ('$userid','$date','$contentid','$content_type')";
    $DB = new Database();
    $DB->save($query);
}

function esc($value)
{
    return addslashes($value);
}

function notification_seen($id)
{
    $notification_id = addslashes($id);
    $userid = $_SESSION['mybook_userid'];
    $DB = new Database();
    
    
    $query = "select * from notification_seen where userid = '$userid' && notification_id = '$notification_id' limit 1"; 
    $check = $DB->read($query);
    
    if(!is_array($check)){
        $query = "insert into notification_seen (userid,notification_id) values ('$userid','$notification_id')";
        $DB->save($query);
    }
    
}

function check_notifications()
{
    $number = 0;

    $userid = $_SESSION['mybook_userid'];
    $DB = new Database();
    
    $query = "select * from notifications where userid !='$userid' && content_owner = '$userid' limit 30";
    $data = $DB->read($query);

    if (is_array($data)) {
        
        foreach($data as $row){
            $query = "select * from notification_seen where userid = '$userid' && notification_id = '$row[id]' limit 1"; 
            $check = $DB->read($query);
    
            if(!is_array($check)){
                $number++;
            }
        }
    }
    return $number;
}