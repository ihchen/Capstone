var lastKey = "I"; //Keep track of last key played so that you don't replay it

/**
 * Determines how to play the given notes based on their type and quality, then loads
 * the corresponding audio files and plays them.
 * 
 * @class MusicSnippet
 * @constructor
 * @param {String} type Type of the sonority
 * @param {String} quality Quality of the sonority
 * @param {String} category Category of the sonority
 * @param {String Array} notes Array of notes
 */
function MusicSnippet(type, quality, notes, category) {
	/* Constants */
	const CHORD = "chord";
	const SCALE = "scale";
	const TWENTIETH = "20th Century";
	const CHORD_DELAY = 2;			//Number of beats between playing chord blocked then broken

	/* Variables */
	var BPM = 80;					//Beats per minute
	var bps = BPM/60; 				//Beats per second
	var delay = (1/bps)*500;		//Time before playing

	var baseNotes = notes;			//Store the base notes to transpose from
	var type = type;				//Chord, scale, or interval
	var quality = quality;			//Answer
	var category = category;		//Category
	var numNotes = notes.length;	//Number of notes

	var tempNotes = [];				//Holds current notes
	var tempSounds = [];			//Holds current sound
	var timeouts = [];				//Timeout objects to keep track of when playing broken
	var numLoaded = 0;				//Number of loaded files

	/**
	 * Unloads the previous files and loads the new files (this is because of the bug with
	 * Howler's fadeOut function). Then plays the notes in a given/default style.
	 *
	 * @method play
	 * @param {String} style How to play the notes. No argument means play it quiz style
	 */
	this.play = function(style) {
		//If no defined style, play from these set of rules
		if(style == undefined) {
			//Play Chord
			if(type == CHORD) {
				//If 20th century chord, just play blocked
				if(category == TWENTIETH) {
					// timeouts.push(setTimeout(function() {playBlock();}, 500));
					playBlock();
				}
				//Otherwise, play argpegiated, then blocked
				else {
					playBlock(CHORD_DELAY);
					timeouts.push(setTimeout(
						function() {
							playBroken(1.5);
						},
					(1/bps)*1000*CHORD_DELAY));
				}
			}
			//Play Scale broken
			else if(type == SCALE) {
				playBroken(1.5);
			}
			//If something else, just play it broken
			else {
				playBroken(1.5);
			}
		}
		else if(style == "desc") {
			tempSounds.reverse();
			playBroken(1.5);
		}
		else if(style == "first") {
			tempSounds.push(tempSounds.shift());
		}
		else if(style == "second") {
			tempSounds.push(tempSounds.shift());
			tempSounds.push(tempSounds.shift());
		}
		else if(style == "third") {
			tempSounds.push(tempSounds.shift());
			tempSounds.push(tempSounds.shift());
			tempSounds.push(tempSounds.shift());
		}
	}

	/**
	 * Stops all sound
	 *
	 * @method stop
	 */
	this.stop = function() {
		stop();
		clear();
		setVolume(1.0);
	}

	/**
	 * Randomly transposes the notes given from the CSV file and gives them a random octave.
	 * If key is given, then transposes to the given key. Loads the corresponding files.
	 * 
	 * @method generate
	 */
	this.generate = function(key) {
		tempNotes = generateTransposition(key);
		tempSounds = loadFiles(tempNotes);
	}

	/**
	 * Returns the type and quality
	 *
	 * @method answer
	 * @return {String} quality + type
	 */
	this.answer = function() {
		return quality+" "+type;
	}

	/**
	 * Fades all sound out
	 *
	 * @method fadeOut
	 */
	this.fadeOut = function() {
		fade(0);
		clear();
	}

	/**
	 * Recursive helper function for this.fadeOut
	 *
	 * @method fade
	 * @param {Integer} note Which note to fade
	 * @private
	 */
	function fade(note) {
		if(note < numNotes) {
			tempSounds[note].fadeOut(0.0, 100, function() {
				tempSounds[note].stop();
				tempSounds[note].volume(1.0);
			});
			fade(note+1);
		}
	}

	/**
	 * Sets the bpm
	 *
	 * @method setBPM
	 * @param {Integer} bpm Desired beats per minute
	 */
	this.setBPM = function(bpm) {
		BPM = bpm;
	}

	/**
	 * Transposes and sets octave of the notes from the CSV file randomly.
	 *
	 * @method generateTransposition
	 * @return {String array} An array of the notes that can be loaded into Howl objects
	 * @private
	 */
	function generateTransposition(key) {
		if(key == undefined) {
			do {
				var randKey = Math.floor(Math.random()*13)-6;	//Get Random key between -7 and 7
				tempNotes = setNotes(randKey);			//Array of transposed keys
			} while(tempNotes[0] == lastKey);			//Don't generate the previously played key
		}
		else {
			tempNotes = setNotes(findShift(baseNotes[0], key));
		}
		
		lastKey = tempNotes[0];						//Save the new key
		tempNotes = setOctave(tempNotes);			//Set a random octave
		return tempNotes;
	}
	
	/**
	 * Tranposes the notes from the CSV file a given number of sharps or flats
	 * 
	 * @method setNotes
	 * @param {Integer} shift How much to transpose the notes by
	 * @return {String Array} Array of the notes after transposition
	 * @private
	 */
	function setNotes(shift) {
		return transpose(baseNotes, shift);
	}

	/**
	 * Plays the note at the given index in the loaded files array. Fades out the note if
	 * given.
	 * 
	 * @method playNote
	 * @param {Integer} i Index of the note to be played
	 * @param {Float} fade Number of beats to fade over
	 * @private
	 */
	function playNote(i, fade) {
		tempSounds[i].play();
		if(fade != undefined) {
			tempSounds[i].fadeOut(0, (1/bps)*1000*fade, 
				function() {
					tempSounds[i].stop();
					tempSounds[i].volume(1.0);
				}
			);
		}
	}

	/**
	 * Plays all notes at the same time
	 *
	 * @method playBlock
	 * @param {Integer} fade Number of beats to fade out in
	 * @private
	 */
	function playBlock(fade) {
		for(var i = 0; i < numNotes; i++) {
			playNote(i, fade);
		}
	}

	/**
	 * Plays all notes in sequence with timing based on bpm
	 *
	 * @method playBroken
	 * @param {Float} fade Number of beats to fade each note by
	 * @private
	 */
	function playBroken(fade) {
		playNote(0, fade);
		playBrokenHelp(1, fade);
	}

	/**
	 * Recursive helper for playBroken()
	 *
	 * @method playBrokenHelp
	 * @param {Integer} note An index representing the note to be played
	 * @param {Float} fade Number of beats to fade each note by
	 * @private
	 */
	function playBrokenHelp(note, fade) {
		if(note < numNotes) {
			timeouts.push(setTimeout(function() {
				playNote(note, fade);
				playBrokenHelp(note+1, fade);
			}, (1/bps)*1000)); //How many seconds per note given the bpm
		}
	}

	/**
	 * Stops all sound immediately
	 * 
	 * @method stop
	 * @private
	 */
	function stop() {
		for(var i = 0; i < tempSounds.length; i++) {
			tempSounds[i].stop();
		}
	}

	/**
	 * Clears all timeouts (delayed sounds)
	 * 
	 * @method clear
	 * @private
	 */
	function clear() {
		var initLength = timeouts.length;
		for(var i = 0; i < initLength; i++) {
			clearTimeout(timeouts[timeouts.length-1]);
			timeouts.pop();
		}
	}

	/**
	 * Sets the volume
	 *
	 * @method setVolume
	 * @param {Float} vol Volume to set to (0.0 - 1.0)
	 * @private
	 */
	function setVolume(vol) {
		for(var i = 0; i < numNotes; i++) {
			tempSounds[i].volume(vol);
		}
	}

	/*
	 * Takes the notes and converts them into Howl objects with the appropriate
	 * sound files.
	 * 
	 * @method loadFiles
	 * @param {String Array} notes Array of notes to be loaded
	 * @return {Howl Array} Array of Howl objects containing the corresponding audio files
	 * @private
	 */
	function loadFiles(notes) {
		var midi = [];		//Store notes as midi numbers
		var files = [];		//Store file paths to corresponding files
		var sounds = [];	//Howl objects of files

		//Get midi numbers of given notes
		for(var i = 0; i < numNotes; i++) {
			midi.push(noteToFileNum[notes[i]]);
			//Error checking
			if(midi[i] == undefined) {
				console.log("Error: Check notation for "+quality+" "+type);
			}
		}

		//Convert midi numbers to their corresponding file names
		files = convMidiToFile(midi);

		//Load sound files
		for(var i = 0; i < numNotes; i++) {
			sounds.push(new Howl({
				urls : [files[i]],
				onload : function() {
					numLoaded++;
					//Display loaded notes
					console.log("Loaded note "+numLoaded);
					//If all notes displayed, show buttons
					if(numLoaded == numNotes) {
						document.getElementById("loading").style.display = "none";
						document.getElementById("allbuttons").style.display = "block";
						numLoaded = 0;
					}
				},
				onloaderror : function() {console.log(quality+" "+type+" loading error")}
			}));
		}
		return sounds;
	}

	/* 
 	 * Converts midi numbers to their corresponding file names
 	 * 
 	 * @method convMidiToFile
 	 * @param {Integer Array} midi Array of midi numbers to be converted into files
 	 * @return {String Array} Array of strings containing the proper file paths to the audio files
 	 * @private
 	 */
	function convMidiToFile(midi) {
		var files = [];
		for(var i = 0; i < numNotes; i++) {
			files.push("audio/piano/piano"+midi[i]+".wav");
		}
		return files;
	}
}
