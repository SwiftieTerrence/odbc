<?php
include 'config.php';
$name = $_REQUEST["name"];
$password = $_REQUEST["password"];
$re_password = $_REQUEST["confirm_pwd"];
$class = $_REQUEST["class"];
$student_number = $_REQUEST["std_number"];
$sex = $_REQUEST["sex"];
$avatar = "";
$hobby = "";

if(isset($_REQUEST['internet'])){
    $checkbox = $_REQUEST['internet'];
    if(count($checkbox)==0)
        $hobby = "";
    else
    $hobby = implode(",", $checkbox);

}

$grade = $_REQUEST["grades"];
if(isset($_REQUEST["remark"]))
    $remark = $_REQUEST["remark"];
else
    $remark = "";

# handle password
if($password != $re_password){
    echo "<script>alert(\"两次输入的密码不一致\");location.href=\"add_stu.html\"</script>";
    return;
}

if($_FILES['file']['name']!=null){
    var_dump($_FILES['file']);
    # 文件处理发生错误
    $avatar = handleFile($student_number);
    if(!$avatar){
        echo "<script>location.href=\"student_index.php?id=$student_number\"</script>";
        return;
    }
}
$update = "UPDATE student SET password='$password', name='$name', class='$class', hobby='$hobby', remark='$remark' WHERE student_number='$student_number';";
echo $update;
echo $name;
$result = $db->exec($update);
if(!$result){
    echo "<script>alert(\"数据库错误\");location.href=\"student_index.php?id=$student_number\"</script>";
}else{
    echo "<script>alert(\"成功\");location.href=\"student_index.php?id=$student_number\"</script>";
}

function handleFile($student_number){
    # handle the file
    echo $_FILES["file"]["type"];
    if (($_FILES["file"]["type"] == "image/jpeg")
        && ($_FILES["file"]["size"] < 1024000))
    {
        if ($_FILES["file"]["error"] > 0)
        {
            echo "Error: " . $_FILES["file"]["error"] . "<br />";
            echo "<script>alert(\"file error\")</script>";
            return false;
        }
        else
        {
            echo "Upload: " . $_FILES["file"]["name"] . "<br />";
            echo "Type: " . $_FILES["file"]["type"] . "<br />";
            echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
            echo "Stored in: " . $_FILES["file"]["tmp_name"];

            # store the image
            move_uploaded_file($_FILES["file"]["tmp_name"],
                "upload/" . $student_number.".jpg");
            echo "Stored in: " . "upload/" . $student_number.".jpg";
            $avatar = "upload/" . $student_number.".jpg";
            return $avatar;

        }

    }
    else
    {
        echo "<script>alert(\"无效文件\")</script>";
        return false;
    }
}
?>