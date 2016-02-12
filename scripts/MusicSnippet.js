/* 
 * Music Snippet Object:
 * Determines what files to be played and how to play them given ABC notes
 */
function MusicSnippet(abc) {
	//Variables
	const bpm = 80; //Beats per minute
	bps = bpm/60; 	//Beats per second

	midi = [];		//Store notes as midi numbers
	files = [];		//Store file paths to corresponding files
	sounds = [];	//Howl objects of files
	timeouts = [];	//Timeout objects to keep track of when playing broken
	broken

	//Get midi numbers of given notes
	for(i = 0; i < abc.length; i++) {
		midi.push(noteToFileNum[abc[i]]);
	}

	//Convert midi numbers to their corresponding file names
	files = convMidiToFile(midi);

	//Load sound files
	for(i = 0; i < midi.length; i++) {
		sounds.push(new Howl({
			urls : [files[i]]
		}));
	}
	
	/*
	 * Plays the note at the given index in the array
	 */
	this.playNote = function(i) {
		stop();
		sounds[i].play();
	}

	/*
	 * Plays all notes at the same time
	 */
	this.playBlock = function() {
		stop();
		for(i = 0; i < midi.length; i++) {
			sounds[i].play();
		}
	}

	/*
	 * Plays all notes in sequence with timing based on bpm
	 */
	this.playBroken = function() {
		stop();
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
	 * Play Chord
	 */
	this.playChord = function() {
		playBroken();
		timeouts.push(setTimeout(function() {
			playBlock();
		}, (sounds.length/bps)*1000 + (1/bps)*2000));
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
		var initLength = timeouts.length;
		for(i = 0; i < initLength; i++) {
			clearTimeout(timeouts[timeouts.length-1]);
			timeouts.pop();
		}
	}
}