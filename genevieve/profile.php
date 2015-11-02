<?php include_once("header.php"); ?>
		<script type="text/javascript" >
			//authO();
		</script>
		<script type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
		<script type="text/javascript">
			var fb = localStorage.getItem('fb');
			var token = localStorage.getItem('token');

			$.ajax({
		      type: 'get',
		      data: 'access_token='+token,
		      url:'https://graph.facebook.com/me',
		      success: function(response){
		      	var div = document.createElement('div');
		      	div.setAttribute('class','large-3 medium-3 small-12 columns panel white left');
		      	var th = document.createElement('img');
		      	th.setAttribute('src','http://graph.facebook.com/'+fb+'/picture?type=square');
		      	th.setAttribute('class','th');
		      	var p = document.createElement('p');
		      	p.setAttribute('class','right');
		      	p.textContent = "Olá "+response.name;
		      	div.appendChild(th);
		      	div.appendChild(p);
		      	document.getElementById('profile').appendChild(div);
		      },error: function(data){
		      	console.log(data);
		      }
		    });
		</script>

		<?php include_once("menu.php"); ?>
		<div class="row">
			<div id="profile" class="large-12 medium-12 small-12 columns panel white">
				<div class="large-9 medium-9 small-12 columns panel white right">
					<h4 class="left">Meus dados:</h4><br>
					<table class="large-centered">
					<?php
						require_once("ws/lib/nusoap.php");
			   			$wsdl = "http://localhost:3434/genevieve/ws/services.php?wsdl";

			            //create client object
			            $client = new nusoap_client($wsdl, 'wsdl');

			            $dados = $client->call('getData', array('token' => $_GET['user_token']));
			            $history = $client->call('history', array('token' => $_GET['user_token']));
						if($history!=null){
			            	$tamanho = count($history[0]['item']);
			        ?>
			        <caption>Histórico</caption>
			        <tr>
				        <td>
				        	Música
				        </td>
				        <td>
				        	Artista
				        </td>
				        <td>
				        	Data
				        </td>
			    	</tr>
			        <?php
				        	echo "<tr>";
				        	echo "<td>".$history[0]['item'][0]."</td>";
				        	echo "<td>".$history[0]['item'][1]."</td>";
				        	echo "<td>".$history[0]['item'][2]."</td>";
				        	echo "</tr>";
			        	
			        ?>
			        <?php
			            for ($i=3; $i < $tamanho ; $i++) { 
			            	echo "<tr>";
			            	for ($j=0; $j < count($history[0]['item'][$i]); $j++) { 
					        	echo "<td>".$history[0]['item'][$i][$j]."</td>";			         			
			            	}
			            	echo "</tr>";
			            }
			            	
			        ?>
			        </table>
			        <?php
			            echo "<p class='panel callout'>Você passou ".$dados."% do seu tempo aqui escutando sua música favorita.</p>";
			        }
					?>
				</div>
			</div>
		</div>

<?php include_once("footer.php"); ?>