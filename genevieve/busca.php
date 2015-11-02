<?php include_once("header.php"); ?>
		<script type="text/javascript" src="js/auth.js">
			authO();
		</script>

		<div id="busca" class="row">
			<form action="lista.php" method="post">
				<div class="small-12 medium-12 large-8 columns large-centered" style="margin-bottom:2%;">
					<img src="img/logo.png">
				</div>
				<div class="small-12 medium-12 large-8 columns large-centered">
					<div class="row collapse">
						<div class="small-10 columns">
							<input type="text" placeholder="Beatles, Nirvana, Red hot..." name="search" required>
						</div>
						<div class="small-2 columns">
							<input type="submit" class="button postfix" value="Go" name="pesquisar">
						</div>
					</div>
				</div>
			</form>
		</div>

<?php include_once("footer.php"); ?>