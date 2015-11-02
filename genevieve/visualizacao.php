<?php include_once("header.php"); ?>
		<script type="text/javascript" src="js/lastfm.api.md5.js"></script>
		<script type="text/javascript" src="js/lastfm.api.js"></script>
		<script type="text/javascript" src="js/lastfm.api.cache.js"></script>
		<script type="text/javascript" src="js/auth.js">
			authO();
		</script>
		<script type="text/javascript" src="js/jquery-2.1.3.min.js"></script>

		<?php
			if( isset($_GET['artist']) ){
				include_once('connections.php');

				$song_name = $_GET['song_name'];
				$artist = $_GET['artist'];
				$user_token = $_GET['user_token'];
				$data_listen = date('Y-m-d',time());
				$sql = "INSERT INTO historico (song_name, artist, data_listen, user_id) 
				VALUES ('$song_name', '$artist', '$data_listen', '$user_token')";
				mysqli_query($conn, $sql);

				mysqli_close($conn);
			}

			require_once("ws/lib/nusoap.php");
   			$wsdl = "http://localhost:3434/genevieve/ws/services.php?wsdl";

            //create client object
            $client = new nusoap_client($wsdl, 'wsdl');

            $favorite = $client->call('getFavorite', array('token' => $_GET['user_token']));
            $favoriteSong = $favorite[0]['item'][0];
            $favoriteArtist = $favorite[0]['item'][1];
		?>
		<script type="text/javascript">
			var qsong = "<?php Print($song_name);?>";
			var qartist = "<?php Print($artist);?>";
			$.ajax({
		      type: 'get',
		      data: 'part=id&q='+qartist+' '+qsong+'&key=<your key here>',
		      url:'https://www.googleapis.com/youtube/v3/search',
		      success: function(data){
		        var videoId = data.items[0].id.videoId;  
		        var iframe = document.createElement('iframe');
		        iframe.setAttribute('src','//www.youtube.com/embed/'+videoId);
		        iframe.setAttribute('width','420');
		        iframe.setAttribute('height','300');
		        iframe.setAttribute('frameborder','0');
		        document.getElementById('video').appendChild(iframe);
		      }
		    });
		</script>
		<?php include_once("menu.php"); ?>

		<div class="row">
			<div class="large-3 medium-3 small-12 columns left ">
				<div class="panel white">
					<?php include_once('catalogo.php');?>
				</div>
			</div> 
			<div class="large-9 medium-9 small-12 columns">
			    <div id="video" class="flex-video">
		            
			    </div>
			    <div class="panel white" id="titulo">
			    	<?php
			    	echo '<b>'.$_GET['artist'].'</b>'."  -  ".$_GET['song_name'];
			    	?>
			    </div>
			</div> 
			<div class="large-8 medium-8 small-12 columns left">
				<h3>Recomendado para você:</h3>
				<div id="recomendacao">

				</div>
			</div>
		</div>
		<script type="text/javascript">
			var songSearch = "<?php Print($favoriteSong); ?>";
			var artistSearch = "<?php Print($favoriteArtist); ?>";
			var cache = new LastFMCache();

			var lastfm = new LastFM({
			apiKey : '<your key here>',
			apiSecret : '<your key here>',
			cache : cache
			});
			lastfm.track.getSimilar({artist: artistSearch,track: songSearch}, {success: function(data){
			/* Use Data */
				for(i = 0; i < 28; i++ ){
					var song = data.similartracks.track[i].name;
					var artist = data.similartracks.track[i].artist.name;
					var imagem = data.similartracks.track[i].image[3];
					console.log(imagem);
					for( j in imagem){
						var img = imagem[j];
						if(img===""){
							img = "http://placehold.it/70x70&amp;text=thumb"
						}
						break;
					}
					var a = document.createElement('a');
					var div = document.createElement('div');
					var div2 = document.createElement('div');
					var p = document.createElement('p');
					var th = document.createElement('img');
					a.setAttribute('href','visualizacao.php'+'?artist='+artist+'&song_name='+song+'&user_token='+localStorage.getItem('fb'));
					a.setAttribute('class','hover');
					div.setAttribute('class','large-6 medium-6 small-12 columns');
					th.setAttribute('src', img);
					th.setAttribute('class','left th-rec');
					div2.setAttribute('class','panel div-rec');

					p.innerHTML = '<b>'+artist+'</b><br>'+song;
					p.setAttribute('class','p-rec')
					div2.appendChild(th); 
					div2.appendChild(p);
					div.appendChild(div2);
					a.appendChild(div);
					document.getElementById("recomendacao").appendChild(a);
				}
			    }, error: function(code, message){
			    /* Show error message. */
			    	console.log(message);
			 }});
		</script>
<?php include_once("footer.php"); ?>