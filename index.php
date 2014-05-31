<?php
	$settings = array(
		"title" => "Title of your Wall",
		"heading" => "Title Heading",
		"subtitle" => "Subtitle of your wall.",
		"css" => array("styles.css"),
		"token" => "your_application_token", // Ziggeo Application Token
		"private_key" => "your_private_key", // Ziggeo Application Private Key
		"sdkpath" => dirname(__FILE__) . '/serversdk/Ziggeo.php', // path to Ziggeo php sdk
		"name_google" => FALSE,
		"open" => TRUE // People can record themselves
	);
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title><?= $settings["title"] ?></title>
		<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
		<?php foreach ($settings["css"] as $css) { ?>
			<link rel="stylesheet" href="<?= $css ?>" />
		<?php } ?>
		<link rel="stylesheet" href="//assets.ziggeo.com/css/ziggeo-betajs-player.min.css" />
		<script src="//assets.ziggeo.com/js/ziggeo-jquery-json2-betajs-player.min.js"></script>
		<script>ZiggeoApi.token = "<?= $settings["token"] ?>";</script>		
		<?php require_once($settings["sdkpath"]);
		      $ziggeo = new Ziggeo($settings["token"], $settings["private_key"]); ?>
	</head>
	<body>
		<div class="header">
			<h1><?= $settings["heading"] ?></h1>
			<p>
				<?= $settings["subtitle"] ?>
				<br />
				<span class="powered">
					Proudly powered By the <a href="http://api.ziggeo.com">Ziggeo API</a>
				</span>
			</p>
		</div>
		<div class="wrapper">
			<?php if ($settings["open"]) { ?>
				<div class="container" id="add_video_container">
					<a href="#" id="add_video" class="recorder"><span class="pseudo-btn">Add Your Video</span></a>
				</div>
				<div class="container" id="video_name_container" style="display:none">
					<div class="form-container">
						<div class="overlay">
							<div class="form-group">
								<input type="name" class="form-control" id="fullname" placeholder="Full Name">
							</div>
							<div class="center"><a href="#" id="use_name" class="btn btn-default btn-xs btn-primary">Submit</a></div>
							<p class="footnote"><a href="#" id="skip_name">Skip &raquo;</a></p>
	  					</div>
					</div>
				</div>
				<div class="container" id="video_recorder_container" style="display:none">
				</div>
			<? } ?>
			<?php foreach ($ziggeo->videos()->index() as $video) { ?>
				<div class="container">
					<ziggeo ziggeo-width=288
					        ziggeo-height=216
					        ziggeo-limit=60
					        ziggeo-video="<?= $video->token ?>">
					</ziggeo>
					<?php if (is_array($video->tags) && count($video->tags) > 0) { ?>
						<?php $name = str_replace("_", " ", $video->tags[0]); ?>
						<?php if ($settings["name_google"]) { ?>
						    <p><a href="http://google.com#q=<?= urlencode($name) ?>" target="_blank"><?= $name ?> &raquo;</a></p>
						<?php } else { ?>
						    <p><?= $name ?></p>
						<?php } ?> 
					<? } ?>
				</div>
			<? } ?>
		</div>
		<?php if ($settings["open"]) { ?>
			<script>
				$("#add_video").on("click", function () {
					$("#add_video_container").css("display", "none");
					$("#video_name_container").css("display", "");
				});
				$("#skip_name").on("click", function () {
					$("#video_name_container").css("display", "none");
					$("#video_recorder_container").css("display", "");
					$("#video_recorder_container").html("<ziggeo ziggeo-width=288 ziggeo-height=216></ziggeo>");
				});
				$("#use_name").on("click", function () {
					$("#video_name_container").css("display", "none");
					$("#video_recorder_container").css("display", "");
					var name = $("#fullname").val().replace(" ", "_");
					$("#video_recorder_container").html("<ziggeo ziggeo-width=288 ziggeo-height=216 ziggeo-tags='" + name + "'></ziggeo>");
				});
			</script>
		<?php } ?>	
	</body>
</html>