<?php

include("classes/autoload.php");


$login = new Login();
$user_data = $login->check_login($_SESSION['mybook_userid']);

if (isset($_GET['find'])) {
    $find = addslashes($_GET['find']);
    $sql = "select * from users where first_name like '%$find%' || last_name like '%$find%' limit 30 ";
    $DB = new Database();
    $results = $DB->read($sql);
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>People who like | Profile</title>
</head>
<style type="text/css">
    #blue_bar {
        height: 50px;
        background-color: #405d9b;
        color: #d4dfeb;
    }

    #search_box {
        width: 400px;
        height: 20px;
        border-radius: 5px;
        border: none;
        padding: 4px;
        font-size: 14px;
        background-image: url("./socialimages/search.png");
        background-repeat: no-repeat;
        background-position: right;
    }

    #profile_pic {
        width: 150px;
        border-radius: 50%;
        border: solid 2px white;
    }

    #menu_buttons {
        width: 100px;
        display: inline-block;
        margin: 2px;
    }

    #friends_img {
        width: 75px;
        float: left;
        margin: 8px;
    }

    #friends_bar {
        min-height: 400px;
        margin-top: 20px;
        padding: 8px;
        text-align: center;
        font-size: 20px;
        color: #405d9b;
    }

    #friends {
        clear: both;
        font-size: 12px;
        font-weight: bold;
        color: #405d9b;
    }

    textarea {
        width: 100%;
        border: none;
        font-family: tahoma;
        font-size: 14px;
        height: 60px;
    }

    #post_button {
        float: right;
        background-color: #405d9b;
        border: none;
        color: white;
        padding: 4px;
        font-size: 14px;
        border-radius: 2px;
        width: 50px;
    }

    #post_bar {
        margin-top: 20px;
        background-color: white;
        padding: 10px;
    }

    #post {
        padding: 4px;
        font-size: 13px;
        display: flex;
        margin-top: 20px;
    }
</style>

<body style="font-family: tahoma; background-color:#d0d8e4;">

    <?php include("header.php"); ?>
    <!-- cover area -->
    <div style="width: 800px; margin:auto; min-height: 400px;">

        <!--below cover area-->
        <div style="display: flex;">

            <!--post area-->
            <div style="min-height: 400px; flex: 2.5; padding: 20px; padding-right: 0px;">

                <div style="border:solid thin #aaa; padding: 10px; background-color: white;">

                    <?php
                    $User = new User();
                    $image_class = new Image();
                    if (is_array($results)) {
                        foreach ($results as $row) {
                            $FRIEND_ROW = $User->get_user($row['userid']);
                            include("user.php");
                        }
                    } else {
                        echo "no results  were found";
                    }
                    ?>
                    <br style="clear: both;">
                </div>
            </div>
        </div>
    </div>
</body>

</html>