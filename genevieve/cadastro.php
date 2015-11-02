<?php
	include_once('connections.php');

	if(isset($_POST['email'])){
		$email = $_POST['email'];
		$senha = '123456';
		$facebook_id = $_POST['facebook_id'];
   		$result = mysqli_query($conn, "SELECT * from usuarios WHERE login='$email' and facebook_id='$facebook_id'");

   		if ($result->num_rows === 0) {
			$sql = "INSERT INTO usuarios (login, senha, facebook_id) 
			VALUES ('$email', '123456', '$facebook_id')";
			mysqli_query($conn, $sql);

			mysqli_close($conn);

			echo "ok";
		}else{
			echo "exists";
		}
	}else{
		echo "error";
	}


?>