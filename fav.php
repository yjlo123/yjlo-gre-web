<?php
	require 'connect.php';

	$word = $_POST['word'];

	function echo_fav_count($conn){
		$result = $conn->query("SELECT COUNT(*) AS count FROM favorite");
		if($result->num_rows > 0){
			$total = $result->fetch_assoc()["count"];
			echo $total;
		}
	}

	if(!empty($word) && $word == "#"){
		echo_fav_count($conn);
		die();
	}

	$result = $conn->query("SELECT * FROM favorite WHERE word='".$word."'");

	if ($result->num_rows == 0) {
		$query_insert = "INSERT INTO favorite (word) VALUES ('".$word."')";
		
		if($conn->query($query_insert)){
			echo_fav_count($conn);
		}else{
			echo "error";
		}
		
	} else if ($result->num_rows > 0){
		
		if($conn->query("DELETE FROM favorite WHERE word='".$word."'")){
			echo_fav_count($conn);
			
		}else{
			echo "error";
		}
		
	}else{
		echo "error";
	}
	
	$conn->close();

?>