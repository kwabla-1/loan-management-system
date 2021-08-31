<?php 
	include "./config/config.php";
	// session_destroy();

	if (!isset($_SESSION['attemptNumber'])) {
		$_SESSION['attemptNumber'] = 3;
	}

	// check if can login again
	if(isset($_SESSION['attempt_again'])){
		$now = time();
		if($now >= $_SESSION['attempt_again']){
			header('Location: index.php');
			unset($_SESSION['attempt']);
			unset($_SESSION['attempt_again']);
			unset($_SESSION['attemptNumber']);
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/styles.css">
    <title>Login</title>
</head>
<body>
    <style>
        .main{
			width: 100vw;
			height: 100vh;
			display: grid;
			grid-template-columns: [spring-start] 1fr [main-start] minmax(min-content, 2fr) [main-end]  1fr [spring-end];
			align-items: center;
			justify-items: center;
			background-image: linear-gradient(135deg, #8c1105ff, #25221eff);
			color: black;
		}

		.login{
			background-color: white;
			grid-column: main-start / main-end;
			border-radius: 5px;
			padding: 5rem;
			box-shadow:20px 20px 50px rgba(0, 0, 0, .9);
		}

		.login h3{
			text-align: center;
		}

		.login form h4{
			font-size: 2rem;
			font-weight: 500;
			text-transform: uppercase;
			text-align: center;
			margin: 0;
			/* padding: 1rem; */
		}

		.login form{text-align: center;}

		.username,
		.password{
			margin: 1.5rem;
			text-align: center;
			color: black;
			font-weight: 400;
		}

		.username input,
		.password input{
			border: none;
			background-color: transparent;
			border-radius: 100px;
			border: 1px solid #8e8e8e;;
			padding: 1.5rem 5rem;
			color: black;
		}

		.username small{
			font-size: 1.4rem;
			color: red;
			font-weight: 300;
		}

		.password small{
			font-size: 1.4rem;
			color: red;
			font-weight: 300;
		}


		.username.success input {
		border-color: green;
		}

		.username.error input {
		border-color: red;
		}

		.password.success input {
		border-color: green;
		}

		.password.error input {
		border-color: red;
		}

		.username.error small {
		visibility: visible;
		}

		.password.error small {
		visibility: visible;
		}


		.username input::placeholder,
		.password input::placeholder{
			color: black;
		}

		input:focus{
			outline: none;
		}

		.btn-login{
			border: black;
			background: linear-gradient(90deg, #8c1105ff, #25221eff);
			color: white;
			font-size: 1.4rem;
			text-transform: uppercase;
			border-radius: 100px;
			padding: 1.5rem 7rem;
			transition: all .2s;
		}

		.btn-login.locked{
			background: gray !important;
			color: #6d6d6d;
		}

		.btn-login:hover.locked{
			cursor: progress;
			color: #6d6d6d;
		}

		.btn-login:hover{
			cursor: pointer;
			background-color: crimson;
			color: white;
		}

		/*STYLES FOR ERROR INDICATION*/
		.Error_message{
			visibility: hidden;
		}

		.res_error.error{
			visibility: visible;
			display: block;
		}

		.res_error{
			color: crimson;
			text-transform: uppercase;
			font-size: 1.4rem;
			font-weight: 300;
			visibility: hidden;
			display: none;
		}

</style>

    <div class="main">
		<div class="login">

			<form action="handlers/formHandler.php" method="POST" id="login_form">

				<!-- <h3 id="resError" class="res_error">Unkown User</h3> -->
				<h4>Login</h4>

				<div class="username">
					<input type="text" id="admin_name" name="username"  placeholder="Username"><br>
					<small id="Error_message"></small>
				</div>

				<div class="password">
					<input type="password" id="admin_password" name="password"  placeholder="password"><br>
					<small id="Error_message"></small>
				</div>
				<?php 
					if ($_SESSION['attemptNumber'] == 0) {
						echo '
							<input type="submit" disabled name="login" class="btn-login locked" value="Login">
						';
					}else{
						echo '
							<input type="submit"  name="login" class="btn-login" value="Login">
						';
					}
				?>
				
				
			</form>
            <h3>You only have <?php echo $_SESSION['attemptNumber']; ?> attempt to login, <br/><?php if ($_SESSION['attemptNumber'] == 0) {
				echo "Button diabled for all failed attempt TRY AGAIN IN THE NEXT 30 MINUTE";
			}else{
				echo "after all failed attempt the butto will be disabled for 30 minute";
			} ?></h3>
		</div>
        
	</div>
</body>
</html>