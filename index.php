<?php
	session_start();
	if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
		// header('Location: index.php');
	} else {
		header('Location: registration.php');
	}
	require_once('conf/db_conf.php');
	
	$note_id = isset($_GET['note_id'])?$_GET['note_id']:0;
	
	$stmt = "SELECT * FROM tbl_notes WHERE status = 1";
	$result = $dbh->query($stmt);
	$arr_notes = array();
	foreach ($result AS $key=>$value) {
		array_push($arr_notes, $value);
	}
	
	// Edit data query
	$arr_edit_data = array();
	if($note_id){
		$stmt = "SELECT * FROM tbl_notes WHERE status = 1 AND Id=".$note_id;
		$stmt = $dbh->prepare($stmt);
		$stmt->execute();
		$arr_edit_data = $stmt->fetch();
		
	}
	$title = '';
	$desc = '';
	if(!empty($arr_edit_data)){
		$title = $arr_edit_data['title'];
		$desc = $arr_edit_data['description'];
		
	}

?>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Sticky Notes Board</title>
		<link rel="stylesheet" href="app.css" type="text/css">
		<link  href="http://fonts.googleapis.com/css? family=Reenie+Beanie:regular" rel="stylesheet" type="text/css">
		
	</head>
	<body>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
			<h3 class="headings">Hi, <span id="greet_span"></span></h3>
		<h1 class="headings">Welcome to Sticky Notes Board</h1>
		<hr />
		<div>
		<a href="session_destroy.php" id="logout">Logout</a>
			<ul>
			<li>
  	<div class="stick-bar"></div>
    <a href="#">
		<input type="hidden" class="note_edit_id input-title" value="<?php echo $note_id; ?>">	
		<input id="txt" type="textbox" class="input-desc" placeholder="Enter title here" value="<?php echo $title; ?>" /> 
    
      <textarea id="txtarea" rows="4" cols="20"><?php echo $desc; ?></textarea>
      
      <button id="btn" class="submit-button cust-btn">Submit</button>


	  
    </a>

  </li>
<?php foreach($arr_notes AS $key=>$value) { ?>
  <li>
	<div class="stick-bar"></div>
     <a href="#" class="note_div">
		<input type="hidden" class="note_id" value="<?php echo $value['Id']; ?>">
        <label for="favorite-animal"><b><?php echo $value['title']; ?></b></label>
		<hr />
		<p><?php echo $value['description']; ?></p>
		<div class="div_action">
			<button class="btn_delete btn_action cust-btn">delete</button>
			<button class="btn_edit btn_action cust-btn">edit</button>
		</div>
      </a>
  </li>
<?php } ?>
</ul>
			
		</div>
		<br /><br /><br /><br /><br /><br /><br /><br />
		<hr />
		<p style="text-align: right;">Copyright; 2018-19, <b>Sunil Kulkarni</b></p>
		
<script>
	$(document).ready(function(){
		
		$(document).on('click','#btn',function(){
			var textbox_val = $('#txt').val();
			var textarea_val = $('#txtarea').val();
			var note_edit_id = $('.note_edit_id').val();
			$.ajax({
				method: "POST",
				url: "ajax/save_note.php",
				cache: false,
				data: {text_val: textbox_val,desc: textarea_val,edit_id: note_edit_id},
				success: function(ret_data){
					window.location.href='index.php';
				}
			});
			
		});
		
		$(document).on('click','.btn_edit',function(){
			var note_id = $(this).parents('.note_div').find('.note_id').val();
			window.location.href='index.php?note_id='+note_id;
			
		});
		
		$(document).on('click','.btn_delete',function(){
			var note_id = $(this).parents('.note_div').find('.note_id').val();
			var $this = $(this);
			$.get('ajax/delete_note.php',{'note_id':note_id},function(ret_data){
				$this.parents('.note_div').parent().remove();
			});
			
		});
	
		var greet = fnGreet();
		setTimeout(function () {
			$('#greet_span').html('Good Morning');
		});
		setTimeout(function () {
			$('#greet_span').html('Good Afternoon');
		}, 200);
		setTimeout(function () {
			$('#greet_span').html('Good Night');
		}, 300);
		setTimeout(function () {
			$('#greet_span').html(greet);
		}, 400);
		
	});

	
      function fnGreet () {
        var myDate = new Date();
        var hrs = myDate.getHours();

        var greet = 'Great Day! isn\'t it?';

        if (hrs < 12)
            greet = 'Good Morning';
        else if (hrs >= 12 && hrs <= 17)
            greet = 'Good Afternoon';
        else if (hrs >= 17 && hrs <= 24)
            greet = 'Good Evening';
          return greet;
      }   
	
	</script>	
</body>
</html>
