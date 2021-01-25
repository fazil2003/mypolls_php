<?php
include_once("database.php");
include_once("header.php");
?>

<?php
$question_id=$_GET['id'];
$option=$_GET['option'];
$user=$_COOKIE['user'];

$query=mysqli_query($db,"SELECT * FROM users WHERE name='$user'");
while($row=mysqli_fetch_array($query)){
    $user_id=$row['id'];
}
mysqli_query($db,"INSERT INTO answers (answer,user_id,question_id) VALUES ('$option','$user_id',$question_id)");

echo "<script>goBackFunction(1);</script>";
?>
