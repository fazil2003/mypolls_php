<?php
include_once('database.php');
include_once('header.php');
?>

<?php
mysqli_query($db,"DELETE FROM answers WHERE question_id=".$_GET['question_id']."");
mysqli_query($db,"DELETE FROM questions WHERE id=".$_GET['question_id']."");
echo "<script>goBackFunction(1);</script>";
?>
