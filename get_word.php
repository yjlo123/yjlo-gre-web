<?php
	require 'connect.php';

	$index = rand(1, 7513);
	//$index = rand(1, 10);
	//$index = 2414;

	$from_fav = $_POST['fav'];
	$current_word = $_POST['current_word'];

	if ($from_fav == "true"){
		$count_result = $conn->query("SELECT COUNT(*) AS count FROM favorite");
		$num_of_fav = $count_result->fetch_assoc()["count"];
		if ($count_result->num_rows > 0){
			$random_fav_query = "SELECT D.* FROM data D, (SELECT * FROM favorite ORDER BY RAND() LIMIT 1) F WHERE D.word=F.word";
			$result = $conn->query($random_fav_query);
			// make sure return a new word
			while ($num_of_fav > 1 && $result->fetch_assoc()["word"] == $current_word){
				$result = $conn->query($random_fav_query);
			}
			$result->data_seek(0);
		}
	} else {
		$result = $conn->query("SELECT * FROM data WHERE id=$index");
	}

	if ($result->num_rows > 0) {

		$row = $result->fetch_assoc();
		$word = $row["word"];
		$pronunciation = $row["pronunciation"];
		$meaning = $row["meaning"];
		$sentence = $row["sentence"];
		
		echo "<p class='word'>".$word."</p>";
		echo "<p class='pronunciation'>".$pronunciation."</p>";

		echo "<div class='meaning_list'>";
		$meaningArray = json_decode($meaning);
		foreach ($meaningArray as $this_meaning){
			echo "<p class='meaning'><span class='pos'>".$this_meaning->{'pos'}."</span>".$this_meaning->{'def'}."</p>";
		}
		echo "</div>";

		echo "<p class='sentence'>";
		$sentenceArray = json_decode($sentence);
		foreach ($sentenceArray as $this_sentence){
			$en = $this_sentence->{'en'};
			$cn = $this_sentence->{'cn'};
			$word_position = stripos($en, $word);
			$original_word = substr($en, $word_position, strlen($word));
			$en = str_ireplace($word, "<strong>".$original_word."</strong>", $en);
			
			echo "<p class='sentence'>".$en."<br/>".$cn."</p>";
		}
		echo "</p>";
		
		$result = $conn->query("SELECT * FROM favorite WHERE word='".$word."'");
		if ($result->num_rows > 0) {
			echo "@";
		}else{
			echo "#";
		}

	} else {
		echo "<p class='word'>No record.</p>";
		$conn->close();
		die();
	}
	$conn->close();

?>