<?php
include_once('database.php');
include_once('header.php');
?>

<?php
$result=mysqli_query($db,"SELECT * FROM answers WHERE id=".$_GET['answer_id']."");
while($row=mysqli_fetch_array($result)){
    $question_id=$row['question_id'];
    mysqli_query($db,"UPDATE questions SET max_answers='no' WHERE id=".$question_id."");
}
mysqli_query($db,"DELETE FROM answers WHERE id=".$_GET['answer_id']."");
echo "<script>goBackFunction(2);</script>";
?>
