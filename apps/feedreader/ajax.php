<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	session_start();
	require_once("flexfeed.class.php");

	$rss_url = $_GET["url"];
	$_SESSION["url"] = $rss_url;

	$feed = new flexfeed($rss_url);

?>
<div class="header">
	<div class="row">
		<?php if($feed->icon){ ?>
			<div class="small-2 small-offset-8 medium-offset-0 medium-2 columns">
				<img class="logo" src="<?php echo $feed->icon ?>">
			</div>
			<div class="small-12 medium-10 columns">
				<h1><?php echo $feed->title ?></h1>
				<p><?php echo $feed->description ?></p>
				<a href="<?php echo $feed->link ?>" target="_blank"><?php echo $feed->link ?></a>
			</div>
		<?php }else{ ?>
			<div class="small-12 columns">
				<h1><?php echo $feed->title ?></h1>
				<p><?php echo $feed->description ?></p>
				<a href="<?php echo $feed->link ?>" target="_blank"><?php echo $feed->link ?></a>
			</div>
		<?php } ?>
	</div>
</div>
<div class="row">
	<div class="small-12 columns">
		<div class="feed_box">
			<input id="url" type="text" value="<?php echo $rss_url ?>"><div class="btn link">Load</div>
		</div>
	</div>
	<?php
		if($feed->error){
				echo '<div class="small-12 columns"><div class="error feed_box"> <h2> ERROR: </h2><h3>' . $feed->error . '</h3></div></div>';
				die();
			}
		?>
	<?php
		foreach ($feed->posts as $key => $post) {
	?>
			<div class="small-12 columns">
				<div class="feed_box">
					<div class="row">
						<div class="small-12 columns">
							<h2 class="post_title" data-id = "<?php echo $post->id ?>"><?php echo $post->title ?></h2>
							<p class="content" id="<?php echo $post->id ?>"><?php echo $post->content ?></p>
						</div>
					</div>
					<div class="bottom">
						<div class="row">
							<div class="small-12 columns">
								<?php if($post->published){ ?><span class="date"><?php echo date("d. m. Y, H:i:s", strtotime($post->published)) ?></span><?php } ?>
								<a class="link" href="<?php echo $post->link ?>" target="_blank">Continue Reading &raquo;</a>
							</div>
						</div>
					</div>
				</div>
			</div>
	<?php
		}
	?>
</div>
