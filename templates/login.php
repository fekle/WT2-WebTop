<div class="row">
	<div class="small-12 columns">
		<div class="box">
			<form>
				<h1>WebTop</h1>
				<h2>Uselessness in perfection.</h2>
				<hr>
				<div id="login">
					<p>Username</p>
						<input type="text" name="user" id="user">
					<p>Password</p>
						<input type="password" name="pwd" id="pwd">

					<div id="error">

					</div>

					<div class="button" id="submit">
						Login
					</div>
					<div class="mini_button" id="goto_register">
						No Account? Register &raquo;
					</div>
				</div>
				<div id="register">
					<div class="mini_button" id="goto_register_fh">
						Student at FH Technikum Wien? Register via LDAP &raquo;
					</div>
					<p>Username</p>
						<input type="text" name="user_register" id="user_register">
					<p>Passwort</p>
						<input type="password" name="pwd_register" id="pwd_register">
					<p>Retype Password</p>
						<input type="password" name="pwd_register2" id="pwd_register2">
					<p>Name</p>
						<input type="text" name="name_register" id="name_register">


					<div class="button" id="submit_register">
						Register
					</div>
					<div class="mini_button" id="goto_login">
						Already have an Account? Login &raquo;
					</div>
				</div>
				<div id="register_fh">
					<p>Username</p>
						<input type="text" name="user_register_fh" id="user_register_fh">
					<p>Password</p>
						<input type="password" name="pwd_register_fh" id="pwd_register_fh">

					<div class="button" id="submit_register_fh">
						Register via LDAP
					</div>
					<div class="mini_button" id="goto_login_fh">
						Already have an Account? Login &raquo;
					</div>
				</div>

				<p class="copyright">
					&copy; 2014 Aleksandar Lepojic, Felix Klein
				</p>
			</form>
		</div>
	</div>
</div>
<script>
	var login = true;
	//klick auf login button
	$("#submit").click(function(){
		send();
	})

	$("#submit_register").click(function(){
		register(false);
	})

	$("#submit_register_fh").click(function(){
		register(true);
	})

	$("#goto_register").click(function(){
		login = false;
		$("#login").slideToggle(300, function(){
			$("#register").slideToggle(300);
		});
	})

	$("#goto_login").click(function(){
		login = true;
		$("#register").slideToggle(300, function(){
			$("#login").slideToggle(300);
		});
	})

	$("#goto_login_fh").click(function(){
		login = false;
		$("#register_fh").slideToggle(300, function(){
			$("#login").slideToggle(300);
		});
	})

	$("#goto_register_fh").click(function(){
		login = false;
		$("#register").slideToggle(300, function(){
			$("#register_fh").slideToggle(300);
		});
	})

	//enter taste
	$(document).keypress(function(event){
		//schaun welche taste
		var keycode = (event.keyCode ? event.keyCode : event.which);
		//wenn taste = 13 (ENTER), dann send()
		if(keycode == '13' && login){
			send();
		}

	})

	function register(fh){
		var data = new Object();
		var error = false;

		if(fh){
			if($("#user_register_fh").val() && $("#pwd_register_fh").val()){
				data.fh = true;
				data.user = $("#user_register_fh").val();
				data.pwd = $("#pwd_register_fh").val();
				data.cryptpwd = CryptoJS.SHA512($("#pwd_register_fh").val()).toString();
			}else{
				error = true;
			}

		}else{
			if($("#user_register").val() && $("#pwd_register").val() && $("#name_register").val() && $("#pwd_register2").val()){
				data.fh = false;
				data.user = $("#user_register").val();
				data.pwd = CryptoJS.SHA512($("#pwd_register").val()).toString();
				data.pwd2 = CryptoJS.SHA512($("#pwd_register2").val()).toString();
				data.name = $("#name_register").val();
			}else{
				error = true;
			}
		}

		if(!error){
			$.ajax({
				type: "POST",
				url: "/ajax/register.php",
				data: {data: JSON.stringify(data)}
			}).done(function(antwort){
				var answer = JSON.parse(antwort);
				if(answer.success){
					alert("Thanks for registering, " + answer.name + "! You may now login.");
					window.location.href=window.location.href;
				}else{
					alert(answer.error);
				}
			})
		}else{
			alert("Please enter all Fields!");
		}
	}

	function send(){
		//button text ändern
		$("#submit").addClass("wait")
		$("#submit").html("Bitte Warten...")

		//error wegmachen
		$("#error").html("")

		var data = new Object();

		data.user = $("#user").val();

		// passwort wird direkt am client verschlüsselt, um ein abfangen trotz fehlendem SSL/TLS Zertifikat zu verhindern :)
		var pwd = CryptoJS.SHA512($("#pwd").val()).toString();
		data.pwd = pwd;

		//an server schicken und fragen ob user und pw passt
		$.ajax({
			type: "POST",
			url: "/ajax/login.php",
			data: {data: JSON.stringify(data)}
		}).done(function(antwort){
			if(antwort=="true"){
				//fenster reloaden, der server schickt dann den webtop
				$.cookie("webtop-lepojic_klein-loggedin", data.user, { expires: 30 });
				$.cookie("webtop-lepojic_klein-loggedin-key", data.pwd, { expires: 30 });
				window.location.href=window.location.href
			}else{
				// fehlermeldung anzeigen und passwort sowie button zrucksetzen
				$("#pwd").val("")
				$("#error").html("Username oder Passwort falsch.")
				$("#submit").removeClass("wait")
				$("#submit").html("Login")
			}
		})
	}
</script>
