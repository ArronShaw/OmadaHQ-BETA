<?php 
require_once 'db.php'; // The mysql database connection script
if(isset($_GET['item'])){
	$item = $mysqli->real_escape_string($_GET['item']);
	$status = "0";
	$created = date("Y-m-d", strtotime("now"));

	$query="INSERT INTO personal_task(user_id,item,status,created_at)  VALUES ('$user_id','$item', '$status', '$created')";
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);

	$result = $mysqli->affected_rows;

	echo $json_response = json_encode($result);
	}
?>