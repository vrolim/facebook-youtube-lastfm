<?php include_once("header.php"); ?>
		<div id="fb-root"></div>
		<script type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
		<script>
		  function statusChangeCallback(response) {

		    if (response.status === 'connected') {
		    	localStorage.setItem('token',response.authResponse.accessToken);
		    	start();
		    } else if (response.status === 'not_authorized') {
		      // The person is logged into Facebook, but not your app.
		      document.getElementById('status').innerHTML = 'Please log ' +
		        'into this app.';
		    } else {
		      // The person is not logged into Facebook, so we're not sure if
		      // they are logged into this app or not.
		      document.getElementById('status').innerHTML = 'Please log ' +
		        'into Facebook.';
		    }
		  }

		  function checkLoginState() {
		    FB.getLoginStatus(function(response) {
		      statusChangeCallback(response);
		    });
		  }

		  window.fbAsyncInit = function() {
		  FB.init({
		    appId      : '<your key here>',
		    cookie     : true,  // enable cookies to allow the server to access 
		                        // the session
		    xfbml      : true,  // parse social plugins on this page
		    version    : 'v2.3' // use version 2.2
		  });

		  FB.getLoginStatus(function(response) {
		    statusChangeCallback(response);
		  });
		  FB.login(function(response) {
		   // handle the response
		  }, {scope: 'email,public_profile'});
		  };

		  // Load the SDK asynchronously
		  (function(d, s, id) {
		    var js, fjs = d.getElementsByTagName(s)[0];
		    if (d.getElementById(id)) return;
		    js = d.createElement(s); js.id = id;
		    js.src = "//connect.facebook.net/en_US/sdk.js";
		    fjs.parentNode.insertBefore(js, fjs);
		  }(document, 'script', 'facebook-jssdk'));

		  function start() {
		    console.log('Welcome!  Fetching your information.... ');
		    FB.api('/me', function(response) {
		    	var fb_id = response.id;
		    	localStorage.setItem('fb',fb_id);
		    	email = response.email;
				$.ajax({
			      type: 'post',
			      data: 'email='+email+'&facebook_id='+fb_id,
			      url:'cadastro.php',
			      success: function(data){
			      	if(data==='exists'){
			      		console.log(data);
			      		window.location.assign("busca.php");
			      	}
			      	if(data==='ok'){
			      		console.log(data);
				  		window.location.assign('profile.php?user_token='+localStorage.getItem('fb'));
			      	}
			      	if(data==='error'){
			      		console.log(data);
			      	}

			      },error: function(data){
			      	console.log(data);
			      }
			    });
		    });
		  }
		</script>

		<div class="row">
			<div class="panel white">
				<img src="img/logo.png" width="300px" height="250px">
			</div>
			<div class="large-6 medium-6 small-12 columns" style="border-right:1px solid #EEEEEE;">
				<h1>Cadastro</h1>
				<form action="" method="">
					<label>Email</label>
					<input type="text" name="usuario" required>
					<label>Senha</label>
					<input type="password" name="senha" required>
					<label>Confirmação de senha</label>
					<input type="password" name="confirma-senha" required>
					<input type="submit" value="Cadastrar" class="button success right">
				</form>
			</div>
			<div class="large-6 medium-6 small-12 columns" >
				<h1>Login</h1>
				<form action="busca.php" method="post">
					<label>Email</label>
					<input type="text" name="usuario">
					<label>Senha</label>
					<input type="password" name="senha">
					<input type="submit" value="Entrar" class="button success right">
				</form>
			</div>
			<hr>
			<div class="panel white">
				<h4>Ou entre usando o Facebook</h4>
				<div class="fb-login-button" data-max-rows="1" data-size="xlarge" data-show-faces="false" data-auto-logout-link="false" onclick='facebookLogin()'>Login with Facebook</div>
			</div>
		</div>
<?php include_once("footer.php"); ?>