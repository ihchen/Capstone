/*
 * Music Snippet Object:
 * Determines what files to be played and how to play them given ABC notes
 */
function MusicSnippet(type1, type2, abc) {
	/* Constants */
	const CHORD = "chord";
	const SCALE = "scale";
	const INTERVAL = "interval";

	const BPM = 120; 				//Beats per minute
	var bps = BPM/60; 				//Beats per second

	/* Variables */
	var baseNotes = abc;			//Store the base notes to transpose from
	var type1 = type1;				//Chord, scale, or interval
	var type2 = type2;				//Answer
	var numNotes = abc.length;		//Number of notes

	var tempSounds = generateTransposition();	//Generate initial sounds
	var timeouts = [];				//Timeout objects to keep track of when playing broken

	/* 
	 * Main play method
	 */
	this.play = function() {
		stop();
		clear();
		//Play arpegiated and then play block
		if(type1 == CHORD) {
			playBroken();
			timeouts.push(setTimeout(playBlock, (numNotes/bps)*1000 + (1/bps)*1000));
		}
		//Play broken
		if(type1 == SCALE) {
			playBroken();
		}
		//Play broken
		if(type1 == INTERVAL) {
			playBroken();
		}
	}

	/*
	 * Loads files based on a random key
	 */
	this.generate = function() {
		clear();
		stop();
		tempSounds = generateTransposition();
	}

	/*
	 * Return the answer
	 */
	this.answer = function() {
		return type2+" "+type1;
	}

	/*
	 * Stops all sound in the current snippet
	 */
	this.stop = function() {
		clear();
		stop();
	}

	/*
	 * Load files based on random key and return Howl array
	 */
	function generateTransposition() {
		var randKey = Math.floor(Math.random()*13)-6;	//Get Random key between -7 and 7
		var tempNotes = setNotes(randKey);		//Array of transposed keys
		tempNotes = setOctave(tempNotes);
		return loadFiles(tempNotes);			//Load the corresponding files
	}
	
	/*
	 * Tranposes the given notes up 'shift' sharps or flats
	 */
	function setNotes(shift) {
		return transpose(baseNotes, shift);
	}

	/*
	 * Plays the note at the given index in the array
	 */
	function playNote(i) {
		//Don't let notes bleed if playing a scale
		if(type1 == SCALE) {
			stop();
		}
		tempSounds[i].play();
	}

	/*
	 * Plays all notes at the same time
	 */
	function playBlock(arg) {
		for(i = 0; i < numNotes; i++) {
			tempSounds[i].play();
		}
	}

	/*
	 * Plays all notes in sequence with timing based on bpm
	 */
	function playBroken() {
		//Play first note instantly
		timeouts.push(setTimeout(function() {
			playNote(0);
			playBrokenHelp(1);		//Play rest of notes
		}, 0));
	}

	/*
	 * Recursive helper for playBroken();
	 */
	function playBrokenHelp(note) {
		if(note < numNotes) {
			timeouts.push(setTimeout(function() {
				playNote(note);
				playBrokenHelp(note+1);
			}, (1/bps)*1000)); //How many seconds per note given the bpm
		}
	}

	/*
	 * Stops all sound immediately
	 */
	function stop() {
		for(i = 0; i < numNotes; i++) {
			tempSounds[i].stop();
		}
	}

	/*
	 * Clears all timeouts
	 */
	function clear() {
		var initLength = timeouts.length;
		for(i = 0; i < initLength; i++) {
			clearTimeout(timeouts[timeouts.length-1]);
			timeouts.pop();
		}
	}

	/*
	 * Takes the ABC notation and converts them into Howl objects with the appropriate
	 * sound files.
	 */
	function loadFiles(notes) {
		var midi = [];		//Store notes as midi numbers
		var files = [];		//Store file paths to corresponding files
		var sounds = [];	//Howl objects of files

		//Get midi numbers of given notes
		for(i = 0; i < numNotes; i++) {
			midi.push(noteToFileNum[notes[i]]);
			//Error checking
			if(midi[i] == undefined) {
				console.log("Error: Check notationg for "+type2+" "+type1);
			}
		}

		//Convert midi numbers to their corresponding file names
		files = convMidiToFile(midi);

		//Load sound files
		for(i = 0; i < numNotes; i++) {
			sounds.push(new Howl({
				urls : [files[i]]
			}));
		}
		return sounds;
	}

	/* 
 	 * Converts midi numbers to their corresponding file names
 	 */
	function convMidiToFile(midi) {
		var files = [];
		for(i = 0; i < numNotes; i++) {
			files.push("audio/piano/piano"+midi[i]+".wav");
		}
		return files;
	}
}