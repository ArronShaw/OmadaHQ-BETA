<?php 
require_once 'connect.php'; // The mysql database connection script
if(isset($_GET['itemID'])){
	$status = $mysqli->real_escape_string($_GET['status']);
	$itemID = $mysqli->real_escape_string($_GET['itemID']);

	$query="UPDATE personal_task set status='$status' where id='$itemID'";
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);

	$result = $mysqli->affected_rows;

	$json_response = json_encode($result);
}
?>
