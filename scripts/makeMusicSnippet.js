// functions to turn basic chord/scale specs into arrays of notes in ABC notation
// requires Note.js for transpose() and NOTES[]

// generic of makeScale() and makeChord()
function makeThing(type, quality, key) {
	// read data.csv
	var data = new XMLHttpRequest();
	data.open("GET", "/scripts/data.csv");
	data.send();
	// get scale instructions
	var thing;
	var lines = data.response.split("\n");
	for (var i = lines.length - 1; i >= 0; i--) {
		var fields = lines[i].split(","); // fields = a line of the csv
		if (fields[0]== type && fields[1]==quality) {
			thing = fields[2].split(" "); // thing = the notes in an array
		};
	};


	// transpose
	scale = transpose(thing, findShift(key));

	// return assembled scale
	return thing;
}

// returns a scale of the given type in the given key
function makeScale(scaleType, key) {
	// open data.csv read-only
	var data = new XMLHttpRequest();
	data.open("GET", "data.csv");
	data.send();
	// get scale instructions
	var scale;
	var lines = data.response.split("\n");
	for (var i = lines.length - 1; i >= 0; i--) {
		var fields = lines[i].split(","); // fields = a line of the csv
		if (fields[0]=="scale" && fields[1]==scaleType) {
			scale = fields[2].split(" "); // scale = the notes in an array
		};
	};


	// transpose
	scale = transpose(scale, findShift(key));

	// return assembled scale
	return scale;
}

// returns a chord of the given type in the given key
function makeChord(chordType, key) {
	// open data.csv read-only
	var data = new XMLHttpRequest();
	data.open("GET", "data.csv");
	data.send();
	// get chord instructions
	var chord;
	var lines = data.response.split("\n");
	for (var i = lines.length - 1; i >= 0; i--) {
		var fields = lines[i].split(",");
		if (fields[0]=="chord" && fields[1]==chordType) {
			chord = fields[2];
		};
	};


	// transpose
	chord = transpose(chord, findShift(key));

	// return assembled scale
	return chord;
}

// returns an interval... maybe?
function makeInterval() {
	// I don't know what to do here
}

// silly little function for finding number of fifths from C for transpose()
function findShift(note) {
	for (var i = 0; i < NOTES.length; i++) {
		if (NOTES[i] == note) {
			break;
		}
	}

	return i-15 // 15 is the index of "C"
}