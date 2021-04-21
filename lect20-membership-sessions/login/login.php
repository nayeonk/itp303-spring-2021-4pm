<?php
//session_start(); //allows us to access session variables on this page

require '../config/config.php';

// If user is NOT logged in, do the usual things like checking for user input etc etc
if( !isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {	
	// Will go into this if statement ONLY if username and password inputs were submitted via the POST method (aka a user clicked on the login button to submit the loign form.)
	if ( isset($_POST['username']) && isset($_POST['password']) ) {

		if ( empty($_POST['username']) || empty($_POST['password']) ) {

			$error = "Please enter username and password.";

		}
		else {

			// Check if user input matches a username/password combo in the database
			$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

			if($mysqli->connect_errno) {
				echo $mysqli->connect_error;
				exit();
			}

			// hash the user's input for password (and compare this with the hashed password stored in the database)
			$passwordInput = hash("sha256", $_POST['password']);

			$sql = "SELECT * FROM users
						WHERE username = '" . $_POST['username'] . "' AND password = '" . $passwordInput . "';";

			echo "<hr>" . $sql . "<hr>";
			
			$results = $mysqli->query($sql);

			if(!$results) {
				echo $mysqli->error;
				exit();
			}

			// If there is a match, we will get a result back. num_rows returns the number of results from the above sql query
			if($results->num_rows > 0) {
				//login is succesful!
				// store this user's username in a session
				$_SESSION["username"] = $_POST["username"];
				$_SESSION["logged_in"] = true;

				// Redirect user to the home page using relative path
				header("Location: ../song-db/index.php");

			}
			else {
				$error = "Invalid username or password.";
			}
		} 
	}
}
else {
	// user is logged in, kick them out of this page. Redirect user to home page
	header("Location: ../song-db/index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login | Song Database</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
	<?php include '../song-db/nav.php'; ?>
	<div class="container">
		<div class="row">
			<h1 class="col-12 mt-4 mb-4">Login</h1>
		</div> <!-- .row -->
	</div> <!-- .container -->

	<div class="container">
		<!-- Submit login form to itself -->
		<form action="login.php" method="POST">

			<div class="row mb-3">
				<div class="font-italic text-danger col-sm-9 ml-sm-auto">
					<!-- Show errors here. -->
					<?php
						if ( isset($error) && !empty($error) ) {
							echo $error;
						}
					?>
				</div>
			</div> <!-- .row -->
			

			<div class="form-group row">
				<label for="username-id" class="col-sm-3 col-form-label text-sm-right">Username:</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" id="username-id" name="username">
				</div>
			</div> <!-- .form-group -->

			<div class="form-group row">
				<label for="password-id" class="col-sm-3 col-form-label text-sm-right">Password:</label>
				<div class="col-sm-9">
					<input type="password" class="form-control" id="password-id" name="password">
				</div>
			</div> <!-- .form-group -->

			<div class="form-group row">
				<div class="col-sm-3"></div>
				<div class="col-sm-9 mt-2">
					<button type="submit" class="btn btn-primary">Login</button>
					<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" role="button" class="btn btn-light">Cancel</a>
				</div>
			</div> <!-- .form-group -->
		</form>

		<div class="row">
			<div class="col-sm-9 ml-sm-auto">
				<a href="register_form.php">Create an account</a>
			</div>
		</div> <!-- .row -->

	</div> <!-- .container -->
</body>
</html>