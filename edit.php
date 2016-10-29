<?php
	require 'connect.php';

	$id = $_GET['id'];
?>
<!doctype html>
<html>
<head>
	<title>YJLO GRE Edit</title>
</head>

<body>
<div class="container">

<header>
<?php
	if(isset($_POST['sentence']) && isset($_POST['index'])){
		$sen = $_POST['sentence'];
		$index = $_POST['index'];
		$sen = str_replace('\n', '', $sen);
		$sen = str_replace('\r', '', $sen);
		$sen = str_replace("\r\n", '', $sen);
		//echo $sen;
		$sen = mysqli_real_escape_string($conn, $sen);
		//echo $sen;

		$query = "UPDATE data SET sentence='$sen' WHERE id=$index";

		if (mysqli_query($conn, $query)) {
			echo "Updated successfully.";
		} else {
			echo "Error updating record: " . $conn->error;
		}
	}
?>
<div class="control">
	<a href=<?php echo "edit.php?id=".($id>1?$id-1:0);?>><<</a>
	<a href=<?php echo "edit.php?id=".($id<7513?$id+1:7513);?>>>></a>
</div>
</header>

<nav>
<?php

	if(isset($_GET['word'])){
		$word = $_GET['word'];
		$result = $conn->query("SELECT * FROM data WHERE word='$word'");
	}else{
		$result = $conn->query("SELECT * FROM data WHERE id=$id");
	}

	if ($result->num_rows > 0) {

		$row = $result->fetch_assoc();
		$word = $row["word"];
		$index = $row["id"];
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
	} else {
		echo "<p class='word'>No record.</p>";
		$conn->close();
	}
	$conn->close();

?>
</nav>
<article>
	<div>
		<a href=<?php echo "edit.php?id=".$index;?>><?php echo $index;?></a>
	</div>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<textarea class="edit_area" name="sentence"><?php
			$sentence = str_replace("[", "[\n", $sentence);
			$sentence = str_replace("]", "\n]", $sentence);
			echo str_replace("},", "},\n", $sentence);
		?></textarea>
		<input type="hidden" name="index" value="<?php echo $index; ?>">
		<input type="submit" name="submit" value="Save"><br>
	</form>
</article>

</div>


<style type="text/css">
.control{
	float: right;
}
.edit_area{
	font-size: 14px;
	width: 100%;
	height: 40em;
}

div.container {
    width: 100%;
    border: 1px solid gray;
    overflow: hidden;
}

header, footer {
    padding: 6px;
    clear: left;
    overflow: hidden;
}

nav {
	border: 1px solid gray;
    float: left;
    width: 40%;
    margin: 0;
    padding: 1em;
}

article {
    margin-left: 170px;
    border: 1px solid gray;
    padding: 1em;
    overflow: hidden;
}
</style>

</body>

</html>