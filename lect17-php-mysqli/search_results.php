<?php
// Because the form was submitted via the GET method, all the information that the user typed in from the form is stored in the $_GET superglobal variable
var_dump($_GET);

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

// Set a characterset for special characters
$mysqli->set_charset("utf8");


// ---- STEP 2: Generate and submit SQL query
$sql = "SELECT tracks.name AS track, 
genres.name AS genre, 
media_types.name AS media
FROM tracks
JOIN genres
	ON tracks.genre_id = genres.genre_id
JOIN media_types
	ON tracks.media_type_id = media_types.media_type_id
WHERE 1=1";

// If user entered in a track name in the search form, append an AND statement in the WHERE clause
if( isset($_GET["track_name"]) && !empty($_GET["track_name"])) {
	// This means user entered a track name in the previous form
	$sql = $sql . " AND tracks.name LIKE '%" . $_GET["track_name"] . "%'";
}

// If user selected a genre in the search form
if( isset($_GET["genre"]) && !empty($_GET["genre"])) {
	$sql = $sql . " AND tracks.genre_id = " . $_GET["genre"];
}

// If user selected a media type in the search form
if( isset($_GET["media_type"]) && !empty($_GET["media_type"])) {
	$sql = $sql . " AND tracks.media_type_id = " .  $_GET["media_type"];
}

// Don't forget the semicolon for the end of the SQL statement!
$sql = $sql . ";";

// Pro tip: print out the entire SQL statement to double check your SQL statement looks correct
echo "<hr>" . $sql;

// Submit the query!
$results = $mysqli->query($sql);
if( !$results ) {
	echo $mysqli->error;
	exit();
}

$mysqli->close();

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Song Search Results</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<h1 class="col-12 mt-4">Song Search Results</h1>
		</div> <!-- .row -->
	</div> <!-- .container-fluid -->
	<div class="container-fluid">
		<div class="row mb-4 mt-4">
			<div class="col-12">
				<a href="search_form.php" role="button" class="btn btn-primary">Back to Form</a>
			</div> <!-- .col -->
		</div> <!-- .row -->
		<div class="row">
			<div class="col-12">

				Showing 2 result(s).

			</div> <!-- .col -->
			<div class="col-12">
				<table class="table table-hover table-responsive mt-4">
					<thead>
						<tr>
							<th>Track</th>
							<th>Genre</th>
							<th>Media Type</th>
						</tr>
					</thead>
<tbody>

	<!-- <tr>
		<td>For Those About To Rock (We Salute You)</td>
		<td>Rock</td>
		<td>MPEG audio file</td>
	</tr>

	<tr>
		<td>Put The Finger On You</td>
		<td>Rock</td>
		<td>MPEG audio file</td>
	</tr> -->

	<!-- Create a <tr> tag for every search result -->

	<?php while( $row = $results->fetch_assoc()): ?>
		<tr>
			<td><?php echo $row["track"]; ?></td>
			<td><?php echo $row["genre"]; ?></td>
			<td><?php echo $row["media"]; ?></td>
		</tr>

	<?php endwhile;?>

</tbody>
				</table>
			</div> <!-- .col -->
		</div> <!-- .row -->
		<div class="row mt-4 mb-4">
			<div class="col-12">
				<a href="search_form.php" role="button" class="btn btn-primary">Back to Form</a>
			</div> <!-- .col -->
		</div> <!-- .row -->
	</div> <!-- .container-fluid -->
</body>
</html>