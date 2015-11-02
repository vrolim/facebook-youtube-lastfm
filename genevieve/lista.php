<?php include_once("header.php"); ?>
		<script type="text/javascript" src="js/lastfm.api.md5.js"></script>
		<script type="text/javascript" src="js/lastfm.api.js"></script>
		<script type="text/javascript" src="js/lastfm.api.cache.js"></script>
		<script type="text/javascript" src="js/auth.js">
			authO();
		</script>

		<?php include_once("menu.php"); ?>
		<?php 

			if( isset($_POST['pesquisar']) ){
				$search = $_POST['search'];
				$tag = "lista";
			}

			if( isset($_GET['tag']) ){
				$search = $_GET['tag'];
				$tag = "tag";
			}

			if( isset($_GET['name']) ){
				$search = $_GET['name'];
				$tag = "detalhe";
			}
		?>
		<script type="text/javascript">
			var formSearch = "<?php Print($search);?>";
			var tag = "<?php Print($tag);?>";
			var cache = new LastFMCache();

			var lastfm = new LastFM({
			apiKey : '<your key here>',
			apiSecret : '<your key here>',
			cache : cache
			});
			if(tag==="tag"){
				lastfm.tag.getTopArtists({tag: formSearch}, {success: function(data){
				for( i in data.topartists.artist){
					var imagem = data.topartists.artist[i].image[3];
					for( j in imagem){
						var img = imagem[j];
						if(img===""){
							img = "http://placehold.it/70x70&amp;text=thumb";
						}
						break;
					}
					var div = document.createElement('div'),
					th = document.createElement('img'),
					seeMore = document.createElement('img'),
					a = document.createElement('a');
					a.setAttribute('href','lista.php'+'?name='+data.topartists.artist[i].name);
					div.setAttribute('class', 'panel white');
					div.setAttribute('id', 'itemLista');
					th.setAttribute('src',img);
					th.setAttribute('class', "th thumb left");
					seeMore.setAttribute('src',"img/see-more.png");
					seeMore.setAttribute('class', "right");
					seeMore.setAttribute('id','seeMore');  
					var titulo = document.createElement('h6');
					titulo.setAttribute('class','large-10 medium-8 small-8 columns left');
					titulo.textContent = data.topartists.artist[i].name;
					a.appendChild(seeMore);
					div.appendChild(th);
					div.appendChild(titulo);
					div.appendChild(a);

					document.getElementById("lista").appendChild(div);
				}
			    }, error: function(code, message){
			   		 /* Show error message. */
			    	console.log(message);
			 	}});	
			}
			if(tag==="lista"){
				lastfm.artist.search({artist: formSearch}, {success: function(data){
				for( i in data.results.artistmatches.artist){
					
					var imagem = data.results.artistmatches.artist[i].image[3];
					for( j in imagem){
						var img = imagem[j];
						if(img===""){
							img = "http://placehold.it/70x70&amp;text=thumb";
						}
						break;
					}
					var div = document.createElement('div'),
					th = document.createElement('img'),
					seeMore = document.createElement('img'),
					a = document.createElement('a');
					a.setAttribute('href','lista.php'+'?name='+data.results.artistmatches.artist[i].name);
					div.setAttribute('class', 'panel white');
					div.setAttribute('id', 'itemLista');
					th.setAttribute('src',img);
					th.setAttribute('class', "th thumb left");
					seeMore.setAttribute('src',"img/see-more.png");
					seeMore.setAttribute('class', "right");
					seeMore.setAttribute('id','seeMore');  
					var titulo = document.createElement('h6');
					titulo.setAttribute('class','large-10 medium-8 small-8 columns left');
					titulo.textContent = data.results.artistmatches.artist[i].name;
					a.appendChild(seeMore);
					div.appendChild(th);
					div.appendChild(titulo);
					div.appendChild(a);

					document.getElementById("lista").appendChild(div);
				}
			    }, error: function(code, message){
			   		 /* Show error message. */
			    	console.log(message);
			 	}});
			}
			if(tag==="detalhe"){
				lastfm.track.search({track: formSearch}, {success: function(data){
				/* Use Data */
					lastfm.artist.getInfo({artist:formSearch}, {success: function(data){
						var imagens = data.artist.image[3];
						for( i in imagens){
							var imgBio = imagens[i];
							if(imgBio===""){
								imgBio = "http://placehold.it/150x150&amp;text=thumb"
							}
							break;
						}
						var bio = data.artist.bio.content;
						var p = document.createElement('p');
						var foto = document.createElement('img');
						foto.setAttribute('src', imgBio);
						foto.setAttribute('class','large-3 medium-4 small-12 columns left th photo');
						p.innerHTML = bio;
						p.setAttribute('class','large-9 medium-8 small-12 columns panel white');
						document.getElementById("info").appendChild(foto);
						document.getElementById("info").appendChild(p);
					}, error: function(code,message){
						alert(message);
					}});
					for( i in data.results.trackmatches.track){
						var imagem = data.results.trackmatches.track[i].image[3];
						for( j in imagem){
							var img = imagem[j];
							if(img===""){
								img = "http://placehold.it/70x70&amp;text=thumb"
							}
							break;
						}
						var div = document.createElement('div'),
						th = document.createElement('img'),
						seeMore = document.createElement('img'),
						a = document.createElement('a');
						a.setAttribute('href','visualizacao.php'+'?artist='+formSearch+'&song_name='+data.results.trackmatches.track[i].name+'&user_token='+localStorage.getItem('fb'));
						div.setAttribute('class', 'panel white');
						div.setAttribute('id', 'itemLista');
						th.setAttribute('src',img);
						th.setAttribute('class', "th thumb left");
						seeMore.setAttribute('src',"img/play.png");
						seeMore.setAttribute('class', "right");
						seeMore.setAttribute('id','play');  
						var titulo = document.createElement('h6');
						titulo.setAttribute('class','large-10 medium-8 small-8 columns left');
						titulo.textContent = data.results.trackmatches.track[i].name;
						a.appendChild(seeMore);
						div.appendChild(th);
						div.appendChild(titulo);
						div.appendChild(a);

						document.getElementById("lista").appendChild(div);
					}
				    }, error: function(code, message){
				    /* Show error message. */
				    	alert(message);
				 }});
			}
		</script>
       	<div class="row" role="content">
			<div id="info">

			</div>
		</div>
		<div class="row">
		 	<div class="large-4  medium-4 small-12 columns left">
				<div class="panel white">
					<?php include_once('catalogo.php');?>
				</div>
			</div> 
	        <div class="large-8 medium-8 small-12 columns right">
				<div class="row" id="lista">

				</div>     
	      	</div>
     	</div>
<?php include_once("footer.php"); ?>