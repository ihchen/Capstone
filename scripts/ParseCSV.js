/**
 * This script loads the content of data.csv into a global variable, 'data'.
 */


/**
 * A 2D array containing the contents of the csv file
 * @property
 * @type Array
 */
var data = [];

// get csv from the server
var file = new XMLHttpRequest();
file.open("GET", "scripts/data2.csv", false);
file.send();

// break response into lines
var lines = file.response.split("\n");

// process each line
for (var i = 0; i < lines.length; i++) {
	if (lines[i] != "" && !lines[i].startsWith("//")) { // if not an empty line or comment
		var tmp = lines[i].split(","); // split line
		tmp[2] = tmp[2].split(" "); // change list of notes from string into array of strings
		data.push(tmp); // push the line into the global
	}
}
