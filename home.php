<?php
include_once("header.php");
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

<a href='ask.php'><button id='ask_button_top'>Create Poll</button></a>

<a href='profile.php'><button id='profile_button_top'>Profile</button></a>
<a href='logout.php'><button id='logout_button_top'>Logout</button></a>

<center>
    <div class='container' style='text-align:left'>

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
   url:"fetch.php",
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