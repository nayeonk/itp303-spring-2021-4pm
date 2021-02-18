function displayResults(results) {

	// Clear out all previous results before showing new ones

	let tbody = document.querySelector("tbody");
	// .hasChildNodes() -- returns true/false
	// .removeChild() -- removes chlildnodes
	while( tbody.hasChildNodes() ) {
		tbody.removeChild( tbody.lastChild )
	}


	console.log(results);

	// Results are currently a STRING in JSON format. Let's convert this string into JS objects
	let convertedResults = JSON.parse(results);

	console.log(convertedResults);
	console.log(convertedResults.results[0].artistName);

	// Iterate through all the results. Create new elemenets per result

	for(let i = 0; i < convertedResults.results.length; i++) {

		// Create a <tr> tag 
		let trTag = document.createElement("tr");

		// Create a <td> tag for album art
		let coverTd = document.createElement("td");
		// Create an <img> for the album art image
		let imgTag = document.createElement("img");
		imgTag.src = convertedResults.results[i].artworkUrl100;
		// Append the img to the td tag
		coverTd.appendChild(imgTag);

		// Create <td> tag for the artist
		let artistTd = document.createElement("td");
		artistTd.innerHTML = convertedResults.results[i].artistName;

		// Create <td> tag for the track
		let trackTd = document.createElement("td");
		trackTd.innerHTML = convertedResults.results[i].trackName;

		// Create <td> tag for the album name
		let albumTd = document.createElement("td");
		albumTd.innerHTML = convertedResults.results[i].collectionName;

		// Create <td> tag for the audio
		let audioTd = document.createElement("td");
		let audioTag = document.createElement("audio");
		audioTag.src = convertedResults.results[i].previewUrl;
		audioTag.controls = true;
		// Append audio tag to audio TD tag
		audioTd.appendChild(audioTag);


		// Append all the td tags to the <tr>
		trTag.appendChild(coverTd);
		trTag.appendChild(artistTd);
		trTag.appendChild(trackTd);
		trTag.appendChild(albumTd);
		trTag.appendChild(audioTd);

		console.log(trTag);

		// Append the <tr> tag to <tbody> to make it show up on the browser!

		let tbody = document.querySelector("tbody");
		tbody.appendChild(trTag);

	}
}

// Seperate function for AJAX
// first param is endpoint
// second param is the name of function that runs after we get a response
function ajax(endpoint, returnFunction) {
	// Make AJAX request using the XMLHttpRequest object
	let httpRequest = new XMLHttpRequest();

	// .open() opens a new http request. Two parameters: 1) METHOD, 2) endpoint
	httpRequest.open("GET", endpoint);
	//.send() sends the request to the specified endpoint
	httpRequest.send();
	// Create an event handler so that when iTunes does respond, we make it run some code
	httpRequest.onreadystatechange = function() {
		console.log(httpRequest.readyState);

		// readyState == 4 when we have a full response from itunes
		if( httpRequest.readyState == 4) {

			// Check if we got a success or error response from itunes
			if(httpRequest.status == 200) {
				// 200 means success! we got something back
				//.responseText will give us whatever iTunes sent back to us as a STRING
				//console.log(httpRequest.responseText);
				returnFunction(httpRequest.responseText);
			}
			else {
				console.log("ERRORRRR!!");
				console.log(httpRequest.status);
			}

		}

	}
}

// When user submits the search form
document.querySelector("#search-form").onsubmit = function(event) {

	// Prevent the form from actually submitting
	event.preventDefault();

	console.log("submitted!");

	// Store whatever the user typed in
	let searchInput = document.querySelector("#search-id").value.trim();

	let limitInput = document.querySelector("#limit-id").value;

	console.log(searchInput);
	console.log(limitInput);
	
	let endpoint = "https://itunes.apple.com/search?term=" + searchInput + "&limit=" + limitInput;

	// Call the ajax function
	ajax(endpoint, displayResults);

}