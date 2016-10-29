<!DOCTYPE html>

<html>
<head>
	<title>YJLO GRE</title>
	<meta charset="UTF-8">
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="shortcut icon" type="image/png" href="https://yjlo.xyz/favicon.gif"/>
	<link rel="stylesheet" href="css/main.css"/>
	<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
</head>
<body class="noselect">
	<div class="big_wrapper">
		<div><div class="top_bar">
			<div class="tool list ios" style="float:left;">
				<img src="img/list.png" class="tool_img"/>
			</div>
			<div class="count fav_count ios">
				123
			</div>

			<div class="tool fav ios">
				<img src="img/fav.png" class="tool_img"/>
			</div>
			<div class="tool mode">
				<img src="img/mode_day.png" class="tool_img"/>
			</div>
			<div class="google_play">
				<a href="https://play.google.com/store/apps/details?id=com.yjlo.yjlogre.app" target="_blank"><img height=100% src="img/google_play.png"/></a>
			</div>
		</div></div>
		<div class="container">
			<div id="result">Loading...</div>
		</div>
		<div class="background"></div>
		<div>
			<div class="next_btn">Next</div>
		</div>
	</div>

</div>
</body>

<script>
	var from_favlist = false;

	function next(){
		$(".next_btn").empty().append( "Loading..." );

		$.ajax({
			type: "POST",
			url: "get_word.php",
			data: {"fav": from_favlist?"true":"false",
					"current_word": $(".word").text()}
		}).done(function( msg ) {
			$( "#result" ).empty().append( msg.slice(0,-1) ); // remove last char
			$(".next_btn").empty().append( "Next" );
			$('html,body').scrollTop(0);

			var fav_char = msg.slice(-1);
			if(fav_char === '@'){ // @ faved, # not faved
				$('.fav img').attr('src', 'img/faved.png');
			}else{
				$('.fav img').attr('src', 'img/fav.png');
			}
		});
	}

	function fav(){
		$.ajax({
			type: "POST",
			url: "fav.php",
			data: {word: $(".word").text()}
		}).done(function( msg ) {
			if(msg !== "error"){
				// update fav count
				$(".fav_count").text(msg);
			}
		});
	}

	function update_fav_count(){
		$.ajax({
			type: "POST",
			url: "fav.php",
			data: {word: "#"}
		}).done(function( msg ) {
			$(".fav_count").text(msg);
		});
	}

	$( document ).ready(function() {
		$(".fav_count").hide();
		next();
		update_fav_count();
	});


	/* Click events */
	$(".next_btn").click(function() {next()});

	$('.mode').click(function() {
		$('img', this).attr('src', function(i, oldSrc) {
			return oldSrc == 'img/mode_day.png' ? 'img/mode_night.png' : 'img/mode_day.png';
		});
		$('.container').toggleClass("night_mode");
		$('.background').toggleClass("night_mode");
		return false;
	});

	$('.fav').click(function() {
		fav();
		$('img', this).attr('src', function(i, oldSrc) {
			return oldSrc == 'img/fav.png' ? 'img/faved.png' : 'img/fav.png';
		});
		return false;
	});

	$('.list').click(function() {
		$('img', this).attr('src', function(i, oldSrc) {
			return oldSrc == 'img/list.png' ? 'img/list_fav.png' : 'img/list.png';
		});
		from_favlist = !from_favlist;
		$( ".fav_count" ).toggle();
		if (from_favlist){
			update_fav_count();
		}
		return false;
	});

	/* platform detection */
	var standalone = window.navigator.standalone,
		userAgent = window.navigator.userAgent.toLowerCase(),
		safari = /safari/.test( userAgent ),
		ios = /iphone|ipod|ipad/.test( userAgent );
		ipad = /ipad/.test( userAgent );

	if( ios ) {
		if ( !standalone && safari ) {
			// browser
		} else if ( standalone && !safari ) {
			// standalone
		} else if ( !standalone && !safari ) {
			// UIWebView
			add_css("uiwebview.css");
		}
		if ( ipad ) {
			add_css("ipad.css");
		}
	} else {
		//not iOS
	}

	function add_css(filename){
		var script = document.createElement('link');
		script.type = 'text/css';
		script.rel = 'stylesheet';
		script.href = 'https://yjlo.xyz/gre/css/'+filename;
		document.getElementsByTagName('body')[0].appendChild(script);
	}

</script>
</html>