<?php ob_start(); ?>
<!-- New posts -->
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		
		<link rel="stylesheet" href="css/bootstrap/css/bootstrap.min.css">
		
		<link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
		
		<link href="css/roboto.min.css" rel="stylesheet">
        	<link href="css/material.min.css" rel="stylesheet">
        	<link href="css/ripples.min.css" rel="stylesheet">
		
		<script src="https://code.jquery.com/jquery-2.1.4.min.js" crossorigin="anonymous"></script>
		
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha256-Sk3nkD6mLTMOF0EOpNtsIry+s1CsaqQC1rVLTAy+0yc= sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>
		
		<script>
			$(document).ready(function() {
				$('#key').bind('keydown', function(e) {
					if(e.keyCode == 13) {
						e.preventDefault();
					}
				});
			});
		</script>
		
		<title>Create a Post</title>
	</head>
	<body>
		<div class="container">
			<h2>Ticker</h2>
			<?php 
				include('header.php');
			?>
			
			<br><br>
			
			<div class="row">
				<div class="col-xs-12">
					<form id="postForm" action="newpost.php" method="post">
					<div class="form-group" id="feedback">
							<b>New Post:</b>
							<textarea class="form-control" id="newpost" type="text" rows="3" placeholder="What's on your mind?"></textarea>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-xs-6">
					<br>
					<input type="button" class="btn btn-success" name="submit" value="Submit" onclick="
					
					var contents = $('#newpost').val();
					var key = $('#key').val();
					$.get('newpost.php?posttext=' + contents, function() {
						$(location).attr('href', 'index.php');
					});
					">
					</form>
				</div>
			</div>
			
			<?php
				if(getId() != null) {
					if(isset($_GET['posttext'])) {
						$timeStamp = time();
						$timeRef = $timeStamp - 3600;
						$checkRes = $conn->prepare("SELECT * FROM posts WHERE content = :content AND time < :timeRangeCheck");
						$checkRes->bindParam(":content", $_GET['posttext']);
						$checkRes->bindParam(":timeRangeCheck", $timeRef);
						$checkRes->execute();
						if($checkRes->rowCount() == 0) {
							$userId = getId();
							
							$gcres = $conn->prepare("SELECT * FROM geoloc WHERE poster = :uid");
							$gcres->bindParam(":uid", $userId);
							$gcres->execute();

							$grows = $gcres->fetchAll(PDO::FETCH_ASSOC);
							$curLat = $grows[0]['latitude'];
							$curLong = $grows[0]['longitude'];
							
							$insertRes = $conn->prepare("INSERT INTO posts VALUES ('NULL', :postid, :content, :curLat, :curLong, :time, '1')");
							$insertRes->bindParam(":postid", $userId);
							$insertRes->bindParam(":content", $_GET['posttext']);
							$insertRes->bindParam(":curLat", $curLat);
							$insertRes->bindParam(":curLong", $curLong);
							$insertRes->bindParam(":time", $timeStamp);
							$insertRes->execute();
						}
					}
				}
			?>
		</div>
	</body>
</html>