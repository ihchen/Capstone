/**
 * This script loads the content of data.csv into a global variable, 'data'.
 * 'data' is a 2D array where the first level is a line of the csv, and the
 * second level is a column of the csv.
 */

var data = [];

// get csv from the server
var file = new XMLHttpRequest();
file.open("GET", "scripts/data.csv", false);
file.send();

// break response into lines
var lines = file.response.split("\n");

// process each line
for (var i = 1; i < lines.length; i++) { // ignore first line
	if (lines[i] != "") { // if not an empty line
		var tmp = lines[i].split(","); // split line
		tmp[2] = tmp[2].split(" "); // change list of notes from string into array of strings
		data.push(tmp); // push the line into the global
	}
}
