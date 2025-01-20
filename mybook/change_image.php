<?php 

if(isset($_GET['change']) && ($_GET['change'] == "profile" || $_GET['change'] == "cover"))
{
if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != "") {

    if ($_FILES['file']['type'] == "image/jpeg") {

        $allowed_size = (1024 * 1024) * 7;
        if ($_FILES['file']['size'] < $allowed_size) {
            $folder = "uploads/" . $user_data['userid'] . "/";
            //create folder
            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }
            $image = new Image();

            $filename = $folder . $image->generate_filename(15) . ".jpg";
            move_uploaded_file($_FILES['file']['tmp_name'], $filename);

            $change = "profile";

            if (isset($_GET['change'])) {
                $change = $_GET['change'];
            }

            if ($change == "cover") {

                if (file_exists($user_data['cover_image'])) {
                   // unlink($user_data['cover_image']);
                }
                $image->resize_image($filename, $filename, 1500, 1500);
            } else {

                if (file_exists($user_data['profile_image'])) {
                   // unlink($user_data['profile_image']);
                }
                $image->resize_image($filename, $filename, 1500, 1500);
            }

            if (file_exists($filename)) {

                $userid = $user_data['userid'];


                if ($change == "cover") {
                    $query = "update users set cover_image = '$filename' where userid = '$userid' limit 1";
                    $_POST['is_cover_image'] = 0;
                } else {
                    $query = "update users set profile_image = '$filename' where userid = '$userid' limit 1";
                    $_POST['is_profile_image'] = 0;
                }

                $DB = new Database();
                $DB->save($query);

                //create a post
                $post = new Post();

                $post->create_post($userid, $_POST, $filename);

                header(("Location: profile.php"));
                die;
            }
        } else {
            echo "<div style = 'text-align:center;font-size:12px;color:white;background-color:grey;'>";
            echo "The following errors occured:<br><br>";
            echo "please image of size 3mb or lower are allowed!";
            echo "</div>";
        }
    } else {
        echo "<div style = 'text-align:center;font-size:12px;color:white;background-color:grey;'>";
        echo "The following errors occured:<br><br>";
        echo "please only jpeg!";
        echo "</div>";
    }

    $filename = "uploads/" . $_FILES['file']['name'];
    move_uploaded_file($_FILES['file']['tmp_name'], $filename);

    if (file_exists($filename)) {

        $userid = $user_data['userid'];
        $query = "update users set profile_image = '$filename' where userid = '$userid' limit 1";
        $DB = new Database();
        $DB->save($query);

        header(("Location: profile.php"));
        die;
    }
} else {
    echo "<div style = 'text-align:center;font-size:12px;color:white;background-color:grey;'>";
    echo "The following errors occured:<br><br>";
    echo "please add a valid image!";
    echo "</div>";
}
}