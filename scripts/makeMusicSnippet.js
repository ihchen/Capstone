class MakeMusicSnippet {
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

		// return assembled scale
	}

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

		// return assembled scale
	}

	function makeInterval() {
		// I don't know what to do here
	}
}