<?php

// Import in the config.php file that holds our credentials
// To import, use either require or include keywords. Using require means the file MUST exist and import properly, otherwise the program will not continue
require "config/config.php";
// include "config/confi.php";

// Validation - make sure that edit_form has received a track_id. 
if( !isset($_GET["track_id"]) || empty($_GET["track_id"]) ) {

	echo "Invalid Track ID!";
	// Terminates the program at this point. Subsequent code does not run.
	exit();
}


// If this code runs, that means a track id was given

// DB Connection.
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ( $mysqli->connect_errno ) {
	echo $mysqli->connect_error;
	exit();
}

$mysqli->set_charset('utf8');

// -- Get details of this track
$sql = "SELECT * FROM tracks 
	WHERE track_id = " . $_GET["track_id"] . ";";

// Submit the query
$results = $mysqli->query($sql);
if(!$results) {
	echo $mysqli->error;
	exit();
} 

// The query will return just ONE song result
$row_track = $results->fetch_assoc();

var_dump($row_track);

// Genres:
$sql_genres = "SELECT * FROM genres;";
$results_genres = $mysqli->query($sql_genres);
if ( $results_genres == false ) {
	echo $mysqli->error;
	exit();
}

// Close DB Connection
$mysqli->close();

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Edit Form | Song Database</title>
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
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="index.php">Home</a></li>
		<li class="breadcrumb-item"><a href="search_form.php">Search</a></li>
		<li class="breadcrumb-item"><a href="search_results.php">Results</a></li>
		<li class="breadcrumb-item"><a href="details.php">Details</a></li>
		<li class="breadcrumb-item active">Edit</li>
	</ol>
	<div class="container">
		<div class="row">
			<h1 class="col-12 mt-4 mb-4">Edit a Song</h1>
		</div> <!-- .row -->
	</div> <!-- .container -->
	<div class="container">

<form action="edit_confirmation.php?track=<?php echo $_GET['track_id']; ?>" method="POST">

			<div class="form-group row">
				<label for="name-id" class="col-sm-3 col-form-label text-sm-right">
					Track Name: <span class="text-danger">*</span>
				</label>
				<div class="col-sm-9">
<input type="text" class="form-control" id="name-id" name="track_name" value="<?php echo $row_track['name']; ?>">
				</div>
			</div> <!-- .form-group -->

			<div class="form-group row">
				<label for="genre-id" class="col-sm-3 col-form-label text-sm-right">
					Genre: <span class="text-danger">*</span>
				</label>
				<div class="col-sm-9">
					<select name="genre" id="genre-id" class="form-control">
						<option value="" selected disabled>-- Select One --</option>

<?php while( $row = $results_genres->fetch_assoc() ): ?>

	<!-- If the genre option matches the one we pulled from the db, add the "selected" attribute to that <option> tag -->

	<?php if( $row["genre_id"] == $row_track["genre_id"] ) :?>

		<option selected value="<?php echo $row['genre_id']; ?>">
			<?php echo $row['name']; ?>
		</option>

	<?php else: ?>

		<option value="<?php echo $row['genre_id']; ?>">
			<?php echo $row['name']; ?>
		</option>

	<?php endif; ?>

<?php endwhile; ?>
					</select>
				</div>
			</div> <!-- .form-group -->

			<div class="form-group row">
				<label for="composer-id" class="col-sm-3 col-form-label text-sm-right">
					Composer:
				</label>
				<div class="col-sm-9">
					<input type="text" name="composer" id="composer-id" class="form-control" value="<?php echo $row_track['composer'];?>">
				</div>
			</div> <!-- .form-group -->

<!-- Can pass the track_id information to edit_confirmation.php by using a input tag with type hidden -->
<input type="hidden" name="track_id" value="<?php echo $_GET['track_id']; ?>">




			<div class="form-group row">
				<div class="ml-auto col-sm-9">
					<span class="text-danger font-italic">* Required</span>
				</div>
			</div> <!-- .form-group -->

			<div class="form-group row">
				<div class="col-sm-3"></div>
				<div class="col-sm-9 mt-2">
					<button type="submit" class="btn btn-primary">Submit</button>
					<button type="reset" class="btn btn-light">Reset</button>
				</div>
			</div> <!-- .form-group -->
		</form>
	</div> <!-- .container -->
</body>
</html>