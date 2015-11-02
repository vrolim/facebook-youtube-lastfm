		<div id="fb-root"></div>
		<script type="text/javascript">
			function profile(){
				window.location.assign('profile.php?user_token='+localStorage.getItem('fb'));
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
		  function sair(){
		  FB.logout(function(response) {
		   console.log('aqui');
		  });}
		  };

		  // Load the SDK asynchronously
		  (function(d, s, id) {
		    var js, fjs = d.getElementsByTagName(s)[0];
		    if (d.getElementById(id)) return;
		    js = d.createElement(s); js.id = id;
		    js.src = "//connect.facebook.net/en_US/sdk.js";
		    fjs.parentNode.insertBefore(js, fjs);
		  }(document, 'script', 'facebook-jssdk'));
		</script>
		<div class="row">
			<div class="panel white">
				<a href="http://localhost:3434/genevieve"><img src="img/logo.png" width="300px" height="250px"></a>
				<ul class="inline-list right">
					<li><a class="button round tiny" onclick="profile();">Perfil</a></li>
					<li><a id="logout" class="button round tiny" onclick="sair();">Sair</a></li>
				</ul>
				<form action="lista.php" method="post" class="small-12 medium-6 large-6 columns right">
					<div class="row collapse">
						<div class="small-10 columns">
							<input type="text" placeholder="Beatles, Nirvana, Red hot..." name="search" required>
						</div>
						<div class="small-2 columns">
							<input type="submit" class="button postfix" value="Go" name="pesquisar">
						</div>
					</div>
				</form>
			</div>
		</div>