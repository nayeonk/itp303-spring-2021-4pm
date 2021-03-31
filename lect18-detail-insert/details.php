<?php

var_dump($_GET);

if(!isset($_GET["track_id"]) || empty($_GET["track_id"])) {
	// A track id is not given, show error message. Don't do anything else.
	$error = "Invalid track";
}
else {
	// A track id is given so continue to connect to the DB.
	$host = "303.itpwebdev.com";
	$user = "nayeon_db_user";
	$password = "uscItp2021!";
	$db = "nayeon_song_db";

	// DB Connection
	$mysqli = new mysqli($host, $user, $password, $db);

	if ( $mysqli->connect_errno ) {
		echo $mysqli->connect_error;
		exit();
	}

	$mysqli->set_charset('utf8');

	// Generate the SQL statmenet to get information about this ONE track
	$sql = "SELECT tracks.name AS track, artists.name AS artist,
composer, albums.title AS album, genres.name AS genre,
milliseconds, bytes, unit_price
FROM tracks
LEFT JOIN albums
	ON tracks.album_id = albums.album_id
LEFT JOIN artists
	ON albums.artist_id = artists.artist_id
LEFT JOIN genres
	ON tracks.genre_id = genres.genre_id
WHERE tracks.track_id = " . $_GET["track_id"] . ";";

	// Double check SQL statement
	echo "<hr>" . $sql . "<hr>";

	// Submit the query!
	$results = $mysqli->query($sql);
	if(!$results) {
		echo $mysqli->error;
		exit();
	}

	// In this case, we only get ONE result so no need to run a while loop
	// the result gets saved as an associate array into the $row variable
	$row = $results->fetch_assoc();

	var_dump($row);

	$mysqli->close();
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Song Details | Song Database</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="index.php">Home</a></li>
		<li class="breadcrumb-item"><a href="search_form.php">Search</a></li>
		<li class="breadcrumb-item"><a href="search_results.php">Results</a></li>
		<li class="breadcrumb-item active">Details</li>
	</ol>
	<div class="container">
		<div class="row">
			<h1 class="col-12 mt-4">Song Details</h1>
		</div> <!-- .row -->
	</div> <!-- .container -->
	<div class="container">
		<div class="row mt-4">
			<div class="col-12">

<?php if( isset($error) && !empty($error) ): ?>

	<div class="text-danger">
		<?php echo $error; ?>
	</div>

<?php else: ?>
				
				<table class="table table-responsive">
					<tr>
						<th class="text-right">Track Name:</th>
						<td>
							<?php echo $row["track"];?>
								
						</td>
					</tr>
					<tr>
						<th class="text-right">Artist Name:</th>
						<td>
							<?php echo $row["artist"];?>
						</td>
					</tr>
					<tr>
						<th class="text-right">Composer:</th>
						<td><?php echo $row["composer"];?></td>
					</tr>
					<tr>
						<th class="text-right">Album:</th>
						<td><?php echo $row["album"];?></td>
					</tr>
					<tr>
						<th class="text-right">Genre:</th>
						<td><?php echo $row["genre"];?></td>
					</tr>
					<tr>
						<th class="text-right">Milliseconds:</th>
						<td><?php echo $row["milliseconds"];?></td>
					</tr>
					<tr>
						<th class="text-right">Bytes:</th>
						<td><?php echo $row["bytes"];?></td>
					</tr>
					<tr>
						<th class="text-right">Price:</th>
						<td><?php echo $row["unit_price"];?></td>
					</tr>
				</table>
<?php endif; ?>

			</div> <!-- .col -->
		</div> <!-- .row -->
		<div class="row mt-4 mb-4">
			<div class="col-12">
				<a href="search_results.php" role="button" class="btn btn-primary">Back to Search Results</a>
			</div> <!-- .col -->
		</div> <!-- .row -->
	</div> <!-- .container -->
</body>
</html>