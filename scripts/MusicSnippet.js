/* 
 * Music Snippet Object:
 * Determines what files to be played and how to play them given ABC notes
 */
function MusicSnippet(abc, type) {
	/* Variables */
	const CHORD = "chord";
	const SCALE = "scale";
	const INTERVAL = "interval";

	const BPM = 80; //Beats per minute
	var bps = BPM/60; 	//Beats per second

	var numNotes = abc.length;

	var midi = [];		//Store notes as midi numbers
	var files = [];		//Store file paths to corresponding files
	var sounds = [];	//Howl objects of files
	var timeouts = [];	//Timeout objects to keep track of when playing broken

	/*
	 * Set up audio
	 */
	//Get midi numbers of given notes
	for(i = 0; i < abc.length; i++) {
		midi.push(noteToFileNum[abc[i]]);
	}

	//Convert midi numbers to their corresponding file names
	var files = convMidiToFile(midi);

	//Load sound files
	for(i = 0; i < midi.length; i++) {
		sounds.push(new Howl({
			urls : [files[i]],
			onload : function() { console.log("sound file has loaded")}
		}));
	}
	/* Done setting up Audio */
	
	/*
	 * Plays the note at the given index in the array
	 */
	this.playNote = function(i) {
		// stop();
		clear();
		sounds[i].play();
	}

	function playNote(i) {
		//Don't let notes bleed if playing a scale
		if(type == SCALE) {
			stop();
		}
		sounds[i].play();
	}

	/*
	 * Plays all notes at the same time
	 */
	this.playBlock = function(arg) {
		stop();
		clear();
		for(i = 0; i < midi.length; i++) {
			sounds[i].play();
		}
	}

	/*
	 * Plays all notes in sequence with timing based on bpm
	 */
	this.playBroken = function() {
		stop();
		clear();
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
		if(note < midi.length) {
			timeouts.push(setTimeout(function() {
				playNote(note);
				playBrokenHelp(note+1);
			}, (1/bps)*1000)); //How many seconds per note given the bpm
		}
	}

	/*
	 * Plays the chord broken, then blocked
	 */
	this.playChord = function() {
		this.playBroken();
		timeouts.push(setTimeout(this.playBlock, (sounds.length/bps)*1000 + (1/bps)*1000));
	}

	/* 
 	 * Converts midi numbers to their corresponding file names
 	 */
	function convMidiToFile(midi) {
		var files = [];
		for(i = 0; i < midi.length; i++) {
			files.push("audio/piano/piano"+midi[i]+".wav");
		}
		return files;
	}

	/*
	 * Stops all sound immediately
	 */
	function stop() {
		for(i = 0; i < midi.length; i++) {
			sounds[i].stop();
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
}