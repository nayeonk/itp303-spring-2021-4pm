<?php

// ---- STEP 1: Establish a DB connection
$host = "303.itpwebdev.com";
$username = "nayeon_db_user";
$password = "uscItp2021!";
$db = "nayeon_song_db";

// To establish a connection with DB, we will create a new instance of the mysqli class.
// The moment we create an instance of the mysqli class, it will also attempt to connect to the database using the credentials that we pass in
$mysqli = new mysqli($host, $username, $password, $db);

// Pre-emptively check for errors with the connection
// connect_errno returns an error code if there is an error. if there is no error, it will return false
if( $mysqli->connect_errno) {
	// Display the full error message
	echo $mysqli->connect_error;
	// Terminates the program. No subsequent code runs.
	exit();
}

// If we get to this point, it means there were no connection errors

// ---- STEP 2: Generate and submit SQL query
$sql = "SELECT * FROM genres;";
$sql_media = "SELECT * FROM media_types;";

// Pro tip: print out sql queries to double check your sql query looks good
echo "<hr>" . $sql . "<hr>";

// Submit the query using the query() method
// query() method returns an object with results information
$results = $mysqli->query($sql);
var_dump($results);

// Check for any SQL/result errors when we get the results back. If there is any error, query() will return FALSE
if(!$results ) {
	// print out the error message
	echo $mysqli->error;
	exit();
}

$results_media = $mysqli->query($sql_media);
if(!$results_media ) {
	// print out the error message
	echo $mysqli->error;
	exit();
}


// ---- STEP 3: Display Results
echo "<hr>";
echo "Number of results: $results->num_rows";
echo "<hr>";

// To get the actual results, we will use fetch_assoc()
// fetch_assoc() - fetches one result row as an associative array
// var_dump($results->fetch_assoc());

// If we want to see ALL the results, run a while loop
// fetch_assoc() returns FALSE when it reaches the end of the results
// while( $row = $results->fetch_assoc() ) {
// 	var_dump($row);
// 	echo $row["name"];
// 	echo "<hr>";
// }
// When all the results are fetched, calling fetch_assoc() again will return NULL
// var_dump($results->fetch_assoc());


// ---- STEP 4: Close the DB connection
$mysqli->close();

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Song Search Form</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<style>
		.form-check-label {
			padding-top: calc(.5rem - 1px * 2);
			padding-bottom: calc(.5rem - 1px * 2);
			margin-bottom: 0;
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<h1 class="col-12 mt-4 mb-4">Song Search Form</h1>
		</div> <!-- .row -->
	</div> <!-- .container -->
	<div class="container">

		<form action="search_results.php" method="GET">

			<div class="form-group row">
				<label for="name-id" class="col-sm-3 col-form-label text-sm-right">Track Name:</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" id="name-id" name="track_name">
				</div>
			</div> <!-- .form-group -->
			<div class="form-group row">
				<label for="genre-id" class="col-sm-3 col-form-label text-sm-right">Genre:</label>
				<div class="col-sm-9">
<select name="genre" id="genre-id" class="form-control">
	<option value="" selected>-- All --</option>

	<!-- <option value='1'>Rock</option>
	<option value='2'>Jazz</option>
	<option value='3'>Metal</option>
	<option value='4'>Alternative & Punk</option>
	<option value='5'>Rock And Roll</option> -->

	<?php
		// This works but it gets messy mixing php and html
		// while( $row = $results->fetch_assoc()) {
		// 	echo "<option value='" . $row["genre_id"] ."'>" . $row["name"] . "</option>";
		// }

	?>

	<!-- Alternative syntax -->
<?php while( $row = $results->fetch_assoc() ): ?>

	<option value="<?php echo $row['genre_id']; ?>">
		<?php echo $row["name"]; ?>
	</option>

<?php endwhile; ?>


</select>
				</div>
			</div> <!-- .form-group -->
			<div class="form-group row">
				<label for="media-type-id" class="col-sm-3 col-form-label text-sm-right">Media Type:</label>
				<div class="col-sm-9">
<select name="media_type" id="media-type-id" class="form-control">
	<option value="" selected>-- All --</option>

	<!-- <option value='1'>MPEG audio file</option>
	<option value='2'>Protected AAC audio file</option> -->

	<?php while( $row = $results_media->fetch_assoc() ): ?>

		<option value="<?php echo $row['media_type_id']; ?>">
			<?php echo $row["name"]; ?>
		</option>

	<?php endwhile; ?>


</select>
				</div>
			</div> <!-- .form-group -->
			<div class="form-group row">
				<div class="col-sm-3"></div>
				<div class="col-sm-9 mt-2">
					<button type="submit" class="btn btn-primary">Search</button>
					<button type="reset" class="btn btn-light">Reset</button>
				</div>
			</div> <!-- .form-group -->
		</form>
	</div> <!-- .container -->
</body>
</html>