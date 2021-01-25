<?php
include_once("header.php");
include_once("database.php");
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

<a href='ask.php'><button id='ask_button_top'>Create Poll</button></a>

<button onclick='history.go(-1);' id='profile_button_top'>Back</button>
<a href='logout.php'><button id='logout_button_top'>Logout</button></a>

<script>
    function showPassword(){
        document.getElementById('password').style.display='inline-block';
        document.getElementById('show_password').style.display='none';
        document.getElementById('hide_password').style.display='inline-block';
    }
    function hidePassword(){
        document.getElementById('password').style.display='none';
        document.getElementById('show_password').style.display='inline-block';
        document.getElementById('hide_password').style.display='none';
    }
</script>


<center>
    <div class='container' style='text-align:left'>
        <div class='profile'>
            <?php
            $query_for_user=mysqli_query($db,"SELECT * FROM users WHERE id='".$_COOKIE['id']."'");
            while($row_for_user=mysqli_fetch_array($query_for_user)){
                echo "<p id='heading_field'>Profile</p>";
                echo "Name : <span id='name_field'>".$row_for_user['name']."</span><br><br>";
                echo "Email : <span id='email_field'>".$row_for_user['email']."</span><br><br>";
                echo "Password: <span id='password'>".$row_for_user['password']."</span> ";
                echo "<button onclick='showPassword();' id='show_password'>Show Password</button>";
                echo "<button onclick='hidePassword();' id='hide_password'>Hide Password</button>";
            }
            ?>
        </div>

        <div class='profile' id='buttonForm'>
            <center>
                <a href='ask.php'><button class='ask_button_bottom'>Create Poll</button></a>
                <button onclick='showSearchBar()' class='ask_button_bottom' id='searchBtn'>Search</button>
            </center>
        </div>

        <div class='profile' id='searchForm'>
            <form method='get' action='topic.php'>
                <input id='search_text_field' type='text' name='category' placeholder='Search' required />
                <input id='search_button' type='submit' value='SEARCH' />
            </form>
        </div>

        <div class='container_inner'>
            <!--content display here-->
        </div>
    </div>
    <div class='message'></div>
</center>

<a href='ask.php'>
    <div id='ask_question_pencil'>
        <img src='/mypolls/images/edit.png' />
    </div>
</a>

<script>
  
$(document).ready(function(){
 
 var limit = 15;
 var start = 0;
 var action = 'inactive';
 function load_country_data(limit, start)
 {
  $.ajax({
   url:"fetch_profile.php",
   method:"POST",
   data:{limit:limit, start:start},
   cache:false,
   success:function(data)
   {
    $('.container_inner').append(data);
    if(data == '')
    {
     $('.message').html("<button type='button' class='btn btn-info'>No Data Found</button>");
     action = 'active';
    }
    else
    {
	 
     $('.message').html("");
	  action = "inactive";
    }
   }
  });
 }

 if(action == 'inactive')
 {
  action = 'active';
  load_country_data(limit, start);
 }
 $(window).scroll(function(){
  if($(window).scrollTop() + $(window).height() > $(".container").height() && action == 'inactive')
  {
   action = 'active';
   start = start + limit;
   setTimeout(function(){
    load_country_data(limit, start);
   }, 1000);
  }
 });
 
});
</script>