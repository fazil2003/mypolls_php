<?php
include_once("database.php");
?>

<?php

        $limit=$_POST['limit'];
        $start=$_POST['start'];

        $selected_category=$_GET['selected_category'];

        $user_id=0;
        $find_user_query=mysqli_query($db,"SELECT * FROM users WHERE name='$selected_category'");
        while($find_user_row=mysqli_fetch_array($find_user_query)){
            $user_id=$find_user_row['id'];
        }
		
		
		####GOOGLE####
		$search=$selected_category;
		$searchSplit=explode(' ',$search);
		$searchQueryItems=array();
		foreach($searchSplit as $searchTerm){
			$searchQueryItems[]="(title LIKE '%".$searchTerm."%' OR category LIKE '%".$searchTerm."%')";
		}
		$query1='SELECT * FROM questions'.(!empty($searchQueryItems)?' WHERE '.implode(' AND ',$searchQueryItems):'').' OR user_id='.$user_id.' ORDER BY id DESC LIMIT '.$start.','.$limit.'';
		#$query=mysqli_query($db,"SELECT * FROM questions WHERE title LIKE '%$selected_category%' OR category LIKE '%$selected_category%' OR user_id=$user_id ORDER BY id DESC LIMIT $start,$limit");
		$query=mysqli_query($db,$query1);

        while($row=mysqli_fetch_array($query)){
               
            $user_id=$row['user_id'];
            $query_user=mysqli_query($db,"SELECT * FROM users WHERE id=$user_id");
            while($row_user=mysqli_fetch_array($query_user)){
                $row_user_name=$row_user['name'];
            }
            // COOKIE USER - ID
            $cookie_id=$_COOKIE['user'];
            $query_user=mysqli_query($db,"SELECT * FROM users WHERE name='$cookie_id'");
            while($row_user=mysqli_fetch_array($query_user)){
                $cookie_user_id=$row_user['id'];
            }
            
            //Convert Date to Our Read Format
            $timestamp=strtotime($row['date']);
            $newDate=date('d-F-Y',$timestamp);
            

            if(($_COOKIE['user']==$row_user_name) or ($_COOKIE['user']=="admin")){ 

                echo "<div class='content'>";
           
                echo "<span id='name'>Created by <b>".$row_user_name."</b> on ".$newDate."</span><br><br>";

                echo "<span id='title'>".$row['title']."</span><br>
                <span id='category'>".$row['category']."</span><br>

                <br>";

                $result_option1=mysqli_query($db,"SELECT * FROM answers WHERE answer='".$row['option1']."' AND question_id=".$row['id']."");
                $result_option2=mysqli_query($db,"SELECT * FROM answers WHERE answer='".$row['option2']."' AND question_id=".$row['id']."");
                $result_option3=mysqli_query($db,"SELECT * FROM answers WHERE answer='".$row['option3']."' AND question_id=".$row['id']."");
                $result_option4=mysqli_query($db,"SELECT * FROM answers WHERE answer='".$row['option4']."' AND question_id=".$row['id']."");

                $total_option1=mysqli_num_rows($result_option1);
                $total_option2=mysqli_num_rows($result_option2);
                $total_option3=mysqli_num_rows($result_option3);
                $total_option4=mysqli_num_rows($result_option4);

                ### DISPLAY OTHER OPTIONS ###
                if($row['option1']!=""){
                    echo "<div id='option-user'>".$row['option1']." - <b>".$total_option1."</b></div><br>";
                }
                if($row['option2']!=""){
                    echo "<div id='option-user'>".$row['option2']." - <b>".$total_option2."</b></div><br>";
                }
                if($row['option3']!=""){
                    echo "<div id='option-user'>".$row['option3']." - <b>".$total_option3."</b></div><br>";
                }
                if($row['option4']!=""){
                    echo "<div id='option-user'>".$row['option4']." - <b>".$total_option4."</b></div><br>";
                }
                
                
                echo "<a href='delete_question.php?question_id=".$row['id']."'><button id='delete_button'>Delete</button></a>";
            
                echo "</div>";

            }

            ### ELSE NOT COOKIE USER IS QUESTION ASKER
            else{

                echo "<div class='content'>";
           
                echo "<span id='name'>Created by <b>".$row_user_name."</b> on ".$newDate."</span><br><br>";

                echo "<span id='title'>".$row['title']."</span><br>
                <span id='category'>".$row['category']."</span><br>

                <br>";

                $question_id=$row['id'];
                $result1=mysqli_query($db,"SELECT * FROM answers WHERE user_id='$cookie_user_id' AND question_id='$question_id'");
                if(mysqli_num_rows($result1)>0){
                    while($row1=mysqli_fetch_array($result1)){
                        ### ANSWERED ###
                        if($row['option1']==$row1['answer']){
                            echo "<div id='option-selected'>".$row['option1']."</div><br>";
                        }
                        if($row['option2']==$row1['answer']){
                            echo "<div id='option-selected'>".$row['option2']."</div><br>";
                        }
                        if($row['option3']==$row1['answer']){
                            echo "<div id='option-selected'>".$row['option3']."</div><br>";
                        }
                        if($row['option4']==$row1['answer']){
                            echo "<div id='option-selected'>".$row['option4']."</div><br>";
                        }
                    }
                    ### DISPLAY OTHER OPTIONS ###
                    echo "<p id='option-title'>OPTIONS</p>";
                    if($row['option1']!=""){
                        echo "<div id='option-not-selected'>".$row['option1']."</div><br>";
                    }
                    if($row['option2']!=""){
                        echo "<div id='option-not-selected'>".$row['option2']."</div><br>";
                    }
                    if($row['option3']!=""){
                        echo "<div id='option-not-selected'>".$row['option3']."</div><br>";
                    }
                    if($row['option4']!=""){
                        echo "<div id='option-not-selected'>".$row['option4']."</div><br>";
                    }
                }
                else{
                    ### NOT ANSWERED ###
                    if($row['option1']!=""){
                        echo "<a href='answer.php?id=".$row['id']."&option=".$row['option1']."'><div id='option'>".$row['option1']."</div></a><br>";
                    }
                    if($row['option2']!=""){
                        echo "<a href='answer.php?id=".$row['id']."&option=".$row['option2']."'><div id='option'>".$row['option2']."</div></a><br>";
                    }
                    if($row['option3']!=""){
                        echo "<a href='answer.php?id=".$row['id']."&option=".$row['option3']."'><div id='option'>".$row['option3']."</div></a><br>";
                    }
                    if($row['option4']!=""){
                        echo "<a href='answer.php?id=".$row['id']."&option=".$row['option4']."'><div id='option'>".$row['option4']."</div></a><br>";
                    }
                }
                
                echo "</div>";

            }

            
        }
        ##END OF WHILE
?>