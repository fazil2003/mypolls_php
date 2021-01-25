<?php
include_once("database.php");
include_once("header.php");
$maximum_quota=5;
?>


<?php
if(isset($_POST['submit'])){
    $user=$_COOKIE['user'];
    $title=$_POST['question'];
	$category=$_POST['category'];
	
	$option1=$_POST['option1'];
	$option2=$_POST['option2'];
	$option3=$_POST['option3'];
	$option4=$_POST['option4'];
		
	$user_id=0;
    $find_user_query=mysqli_query($db,"SELECT * FROM users WHERE name='$title'");
    while($find_user_row=mysqli_fetch_array($find_user_query)){
        $user_id=$find_user_row['id'];
    }
	
	####GOOGLE####
	$search=$title;
	$searchSplit=explode(' ',$search);
	$searchQueryItems=array();
	foreach($searchSplit as $searchTerm){
		$searchQueryItems[]="(title LIKE '%".$searchTerm."%' OR category LIKE '%".$searchTerm."%')";
	}
	$query1='SELECT * FROM questions'.(!empty($searchQueryItems)?' WHERE '.implode(' AND ',$searchQueryItems):'').' OR user_id='.$user_id.'';
	$query_s=mysqli_query($db,$query1);
	
	if(mysqli_num_rows($query_s)>0){
		header("Location:topic.php?category=".$title."");
	}
	
	else{
		$query=mysqli_query($db,"SELECT * FROM users WHERE name='$user'");
		while($row=mysqli_fetch_array($query)){
			$user_id=$row['id'];
		}

		mysqli_query($db,"INSERT INTO questions (title,category,option1,option2,option3,option4,user_id) VALUES ('$title','$category','$option1','$option2','$option3','$option4',$user_id)");
		echo "<script>goBackFunction(2);</script>";
	}
	
}
?>
<?php
	$user=$_COOKIE['user'];
	$query=mysqli_query($db,"SELECT * FROM users WHERE name='$user'");
	while($row=mysqli_fetch_array($query)){
		$user_id=$row['id'];
	}
	$result=mysqli_query($db,"SELECT * FROM questions WHERE user_id='$user_id'");
	$total_questions=mysqli_num_rows($result);
	if($total_questions>($maximum_quota-1)){
		$remaining_polls=0;
		$message="No Remaining Poll.";
	}
	else{
		$remaining_polls=$maximum_quota-$total_questions;
		$message="Remaining Polls : ".$remaining_polls;
	}
?>

<button onclick='history.go(-1);' id='profile_button_top'>Back</button>
<a href='logout.php'><button id='logout_button_top'>Logout</button></a>

<center>
    <div class='container'>
	<?php
	if($remaining_polls==0){
		echo "<p id='warning-message'>".$message."</p>";
		echo "<p id='warning-message'>Sorry, we allow each user to run ".$maximum_quota." polls at the time.";
		echo " Delete the old polls to create a new poll.</p>";
		echo "<a href='profile.php'><button id='delete_button'>Delete Polls</button></a>";
	}
	else{
		?>

		<form action='ask.php' method='post'>

		<?php echo "<p id='warning-message'>".$message."</p>"; ?>

			<textarea id='ask_question_box' name='question' placeholder='Type your Question here...' required></textarea>
			<br>
			<input id='option-box' type='text' placeholder='Option 1 (Required)' name='option1' required />
			<br>
			<input id='option-box' type='text' placeholder='Option 2 (Required)' name='option2' required />
			<br>
			<input id='option-box' type='text' placeholder='Option 3 (Optional)' name='option3' />
			<br>
			<input id='option-box' type='text' placeholder='Option 4 (Optional)' name='option4' />


            <br><br>
			
			<?php
			$array=array('General','Animation','Architecture','Art','Automobile','Biology','Business','Chemistry','Computer-Hardware','Computer-Programming','Computer-Software','Economics','Electrical','Electronics','Environment','Fashion','Foods-and-Cooking','Gaming','Geography','Hacking','Health','History','Language','Love','Mathematics','Mechanical','Memes','Movie','Physics','Politics','Psychology','Religion','Society','Sports','Technology','Quote');
	 		echo "<select name='category' id='select_box' required>";
			echo "<option value=''>Select Category</option>";
			for($i=0;$i<count($array);$i++){
				echo"<option value='".$array[$i]."'>".$array[$i]."</option>";
			}
			echo"</select>";
			?>

            <br><br>

            <input id='submit_btn' name='submit' type='submit' value='Ask Question' />
        </form>

		<?php
	}
	?>
        
    </div>
</center>