<div id="desktop">
	<?php
		// apps und die dazugehörigen fenster ausgeben
		$i = 0;
		foreach($apps as $app){
			?>
				<div class="app" id="<?php echo $app->name ?>" style="top: <?php if($i==0){echo "1";}else{echo 8*$i;} ?>rem; left: <?php echo $app->left ?>; top: <?php echo $app->top ?>;">
					<div class="appicon" style="background: url(<?php echo $app->icon ?>)">

					</div>
					<div class="appname">
						<?php echo $app->full_name ?>
					</div>
				</div>

				<div class="window <?php if($app->window_active){echo "active";}else{echo "inactive";} ?>" id="window_<?php echo $app->name ?>" style="left: <?php echo $app->window_left ?>; top: <?php echo $app->window_top ?>; display: <?php echo $app->window_on ?>; width: <?php echo $app->window_width ?>; height: <?php echo $app->window_height ?>;">
					<div class="window_name">
						<?php echo $app->full_name ?>
						<div class="window_close" data-window="<?php echo $app->name ?>">

						</div>
					</div>
					<div class="window_content">
						<iframe src="<?php echo $app->url ?>" frameborder="0">

						</iframe>
					</div>
				</div>
			<?php
			$i++;
		}

	?>

<div id="startmenu">
		<?php
			//startmenüeinträge ausgeben
			foreach($apps as $app){
				?>

				<div class="startmenu_button" data-app="<?php echo $app->name ?>">
					<?php echo $app->full_name ?>
				</div>

				<?php
			}

		?>
		<div id="logout">
			Logout
		</div>
		<div id="reset">
			Reset
		</div>
	</div>

</div>
<div id="leiste">
	<div id="start">
		<?php
			//haut den usernamen in den startbutton
			echo $user->full_name ?>'s WebTop
	</div>
</div>

<script>
	// Funktionen
	$(document).ready(function(){

		//fenster hiden, sollen nur bei bedarf angezeigt werden
		//$(".window").hide();
		//startmenü hiden, soll nur bei bedarf angezeigt werden
		$("#startmenu").hide();

		//fenster draggable machen
		$(".window").draggable({
			distance: 0,
			delay: 10,
			containment: "#desktop",
			start: function() {
				// macht das fenster transparent und hidet den iframe, der sorgt nur beim bugs wenn die maus beim verschieben drüber kommt
				$(".window").css("background","rgba(0,0,0,0.2)");
				$(".window").css("border","2px #fff solid");
				$(".window").children(".window_content").fadeOut(300);
				makeactive($(this).attr("id"));
			},
			stop: function() {
				$(".window").css("background","transparent");
				$(".window").css("border","none");
				$(".window").children(".window_content").fadeIn(300, function() {
					save();
				});
			}
		});

		//macht fenster resizeable
		$(".window").resizable({
			distance: 0,
			delay: 10,
			minHeight: 300,
			minWidth: 400,
			containment: "#desktop",
			start: function() {
				// macht das fenster transparent und hidet den iframe, der sorgt nur beim bugs wenn die maus beim resizen drüber kommt
				$(".window").css("background","rgba(0,0,0,0.2)");
				$(".window").css("border","2px #fff solid");
				$(".window").children(".window_content").fadeOut(300);
				makeactive($(this).attr("id"));
			},
			stop: function() {
				$(".window").css("background","transparent");
				$(".window").css("border","none");
				$(".window").children(".window_content").fadeIn(300, function() {
					save();
				});
			}
		});

		//macht apps draggable
		$(".app").draggable({
			stop: function(){
				save()
			}
		});

		//bindet eine doppelklickfunktion auf die apps, welche das dazugehörige fenster öffnet
		$(".app").dblclick(function(){
			var id = $(this).attr("id");
			$("#window_"+id).fadeIn(300, function() {
				save();
			});
			makeactive("window_"+id);
		})

		// ... selbsterklärend^^
		$(".window_close").click(function(){
			var id = $(this).attr("data-window");
			$("#window_"+id).fadeOut(300, function() {
				save();
			});
		})

		//macht das startmenü wieder weg wenn man auf den desktop klickt
		$("#desktop").click(function(){
			$("#startmenu").fadeOut(300);
		})

		//zeigt das startmenü beim mouseover auf den startbutton an
		$("#start").hover(function(){
			$("#startmenu").fadeIn(300);
		})

		//fenster bei klick hervorheben. geht leider nur bei klick auf die titelleiste, man kann keine klicks auf einen iframe kriegen :( Könnte das natürlich über mauskoordinaten usw machen, aber das ist zu viel des guten
		$(".window").click(function(){
			makeactive($(this).attr("id"));
		})

		//das gleiche wie bei einem mouseover auf den startbutton, falls das mouseover nicht will warum auch immer, ist nur zum backup da
		$(".startmenu_button").click(function(){
			var id = $(this).attr("data-app");
			$("#window_"+id).fadeIn(300, function() {
				save();
			});
			makeactive("window_"+id);
		})

		//logout. ruft eine php funktion am server auf und refresht die seite
		$("#logout").click(function(){
			$.post( "/ajax/logout.php").done(function(antwort){
				$.cookie("webtop-lepojic_klein-loggedin", null);
				$.cookie("webtop-lepojic_klein-loggedin-key", null);
				window.location.href=window.location.href
			})
		})

		$("#reset").click(function(){
			reset()
		})

		//verhindert einen rechte maus klick :P
		$(document).bind("contextmenu",function(e){
	        return false;
	    });

	    makeactive($(".active").attr("id"));

	})

	function makeactive(id){
		$(".window").children(".overlay").remove();
		$(".window").removeClass("active");
		$(".window").addClass("inactive");
		$(".window").append('<div class="overlay"></div>')
	    $("#"+id).removeClass("inactive");
	    $("#"+id).children(".overlay").remove();
	    $("#"+id).addClass("active");
	}

	function reset(){

		$.post( "/ajax/reset.php").done(function(antwort){
			window.location.href=window.location.href
		})

	}

	function save(){

		var apps = new Object;
		$(".app").each(function(){
			var id = $(this).attr("id");
			apps[id] = new Object
			apps[id].left = $(this).css("left");
			apps[id].top = $(this).css("top");
			apps[id].window_left = $("#window_" + id).css("left");
			apps[id].window_top = $("#window_" + id).css("top");
			apps[id].window_on = $("#window_" + id).css("display");
			apps[id].window_width = $("#window_" + id).css("width");
			apps[id].window_height = $("#window_" + id).css("height");
			apps[id].window_active = $("#window_" + id).hasClass("active");
		})

		var sendapps = JSON.stringify(apps);

		$.ajax({
			type: "POST",
			url: "/ajax/save.php",
			data: {apps: sendapps}
		})
	}

</script>
