var lastKey = "I"; //Keep track of last key played so that you don't replay it
const FADE_ALL_LENGTH = 100;	//Number of milliseconds to fade all notes out
//Play styles (pass into play())
const BLOCK = "block";			//Playing notes blocked
const ASCENDING = "asc";		//Playing notes broken and ascending
const DESCENDING = "desc";		//Playing notes broken and descending

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
function MusicSnippet(notes, type, quality, category) {
	/* Constants */
	//Types
	const CHORD = "chord";
	const SCALE = "scale";
	//Categories
	const TWENTIETH = "20th Century";
	const SEVENTH = "7th";
	//Numeric Values
	const DEFAULT_BPM = 80;			//Default beats per minute
	const DEFAULT_FADE = 1.5;		//Default number of beats to fade broken notes out
	const CHORD_DELAY = 2;			//Number of beats between playing chord blocked then broken

	/* Variables */
	var bpm = DEFAULT_BPM;			//Beats per minute
	var bps = bpm/60;				//Beats per second

	var baseNotes = notes;			//Store the base notes to transpose from
	var type = type;				//Chord or scale
	var quality = quality;			//Major, minor, etc
	var category = category;		//20th century, jazz, etc
	var numNotes = notes.length;	//Number of notes

	var tempNotes = [];				//Holds current notes
	var tempSounds = [];			//Holds current Howl files
	var fading = [];				//Notes that are currently fading out all the way
	var timeouts = [];				//Timeout objects to keep track of when playing broken
	var numLoaded = 0;				//Number of loaded files
	var numEnded = 0;				//Number of files that finished playing

	var user_onload = function(){};	//User-supplied function to be invoked when the sound files are loaded

	/**
	 * Plays the loaded notes with the given style. If style not given, plays the notes quiz style:
	 * all chords played blocked, then broken and ascending, except for 20th century chords which 
	 * are played just blocked; all scales played broken and ascending.
	 *
	 * @method play
	 * @param {String} style (Optional) How to play the notes. If left out, plays quiz style
	 */
	this.play = function(style) {
		//Play blocked
		if(style == BLOCK) {
			playBlock();
		}
		//Play broken and ascending
		else if(style == ASCENDING) {
			playBroken(DEFAULT_FADE);
		}
		//Play broken and descending
		else if(style == DESCENDING) {
			playBroken(DEFAULT_FADE, DESCENDING);
		}
		//Play quiz style
		else {
			//Playing chords
			if(type == CHORD) {
				//If 20th century chord, just play blocked
				if(category == TWENTIETH) {
					playBlock();
				}
				//Otherwise, play blocked, then argpegiated
				else {
					playBlock(CHORD_DELAY);
					timeouts.push(setTimeout(
						function() {
							playBroken(DEFAULT_FADE);
						},
						(1/bps)*1000*CHORD_DELAY)
					);
				}
			}
			//Play scales broken and ascending
			else if(type == SCALE) {
				var rand = Math.floor(Math.random()*2);
				if(rand == 0) {
					playBroken(DEFAULT_FADE);
				}
				else {
					playBroken(DEFAULT_FADE, DESCENDING);
				}
			}
			//If something else, just play it broken
			else {
				setTimeout(function() {playBroken(DEFAULT_FADE);}, 1000);
			}
		}
	}

	/**
	 * Plays the note at the given index in the tempSounds array.
	 * 
	 * @method playNote
	 * @param {Integer} i Index of the note to be played
	 * @param {Float} fade (Optional) Number of beats to fade out
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
	 * Plays all notes in tempSounds at the same time.
	 *
	 * @method playBlock
	 * @param {Integer} fade (Optional) Number of beats to fade out
	 * @private
	 */
	function playBlock(fade) {
		for(var i = 0; i < numNotes; i++) {
			playNote(i, fade);
		}
	}

	/**
	 * Plays all notes in sequence.
	 *
	 * @method playBroken
	 * @param {Float} fade (Optional) Number of beats to fade each note by
	 * @param {String} style (Optional) Play notes ascending (default) or descending
	 * @private
	 */
	function playBroken(fade, style) {
		//Play descending
		if(style == DESCENDING) {
			playNote(numNotes-1, fade);
			playBrokenHelp(numNotes-2, fade, style);
		}
		//By default, play ascending
		else {
			playNote(0, fade);
			playBrokenHelp(1, fade);
		}
	}

	/**
	 * Recursive helper for playBroken()
	 *
	 * @method playBrokenHelp
	 * @param {Integer} note Index of the note to be played
	 * @param {Float} fade Number of beats to fade each note by
	 * @param {String} style Ascending (default) or descending
	 * @private
	 */
	function playBrokenHelp(note, fade, style) {
		if(note < numNotes && note >= 0) {
			timeouts.push(setTimeout(
				function() {
					playNote(note, fade);
					if(style == DESCENDING) {
						playBrokenHelp(note-1, fade, style);
					}
					else {
						playBrokenHelp(note+1, fade);
					}
				}, 
				(1/bps)*1000) //How many milliseconds per note given the bpm
			);
		}
	}

	/**
	 * Generates the Howl files to be played.
	 * 
	 * @method generate
	 * @param {Function} user_function (Optional) Function to call when the files are loaded
	 * @param {String} key (Optional) The key to transpose to
	 * @param {Integer} inversion (Optional) Which inversion to use
	 */
	this.generate = function(user_function, key, inversion) {
		console.log("Generating "+quality+" "+type);
		
		// store/update the user's onload function
		if (user_function != undefined) {
			user_onload = user_function;
		}

		//If given just the notes, assumes that the octave is given as well
		if(type == undefined) {
			tempSounds = loadFiles(baseNotes);
		}
		//Just given notes and no octaves. Apply necessary transformations
		else {
			tempNotes = transposeAndInvert(key, inversion);
			tempSounds = loadFiles(tempNotes);
		}
	}

	/**
	 * Transposes notes to a given or random key and inverts the notes a given or random amount
	 *
	 * @method transposeAndInvert
	 * @param {String} key (Optional) Which key to tranpose to
	 * @param {Integer} inversion (Optional) Amount to invert by
	 * @return {String[]} An array of the notes with octave numbers
	 * @private 
	 */
	function transposeAndInvert(key, invert) {
		var newNotes;		//New array of notes
		var shift;			//Transposition value
		var inv;			//Inversion value

		do {
			//If given no arguments, generate random key and random inversion
			if(key == undefined) {
				do {
					shift = Math.floor(Math.random()*13)-6;	//Get Random key between -6 and 6
					newNotes = transpose(baseNotes, shift);				//Array of transposed keys
				} while(newNotes[0] == lastKey);			//Don't generate the previously played key
			}
			//If given a key, transpose to the given key.
			else {
				shift = findShift(baseNotes[0], key);
				newNotes = transpose(baseNotes, shift);
			}

			//If inversion is given, set it
			if(invert != undefined) {
				inv = invert;
				newNotes = setInversion(newNotes, inv);
			}
			//If not, give a random inversion only for 7th chords
			else if(category == SEVENTH) {
				inv = Math.floor(Math.random()*numNotes);	//Get random inversion based on the number of notes
				newNotes = setInversion(newNotes, inv);
				quality = invert7thQuality(quality, inv);
			}

			newNotes = setOctave(newNotes);				//Set a random octave
		} while(newNotes == null);

		lastKey = newNotes[0];						//Save the new key
		return newNotes;
	}

	/**
	 * Returns a new string with the proper quality given the inversion. Only use on 
	 * 7th chords.
	 *
	 * @method invert7thQuality 
	 * @param {String} quality Original string
	 * @param {Integer} inversion Inversion value
	 * @return {String} Inverted String
	 * @private
	 */
	function invert7thQuality(quality, inv) {
		var baseQuality;
		//Check what the current inversion value is
		if(quality[quality.length-1] == '7') {
			baseQuality = quality.substring(0, quality.length-1);
		}
		else {
			baseQuality = quality.substring(0, quality.length-3);
		}

		//Apply inversion changes
		if(inv == 3) {
			return baseQuality+"4|2";
		}
		else if(inv == 2) {
			return baseQuality+"4|3";
		}
		else if(inv == 1) {
			return baseQuality+"6|5";
		}
		else {
			return baseQuality+"7";
		}
	}

	/**
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
					//If all notes displayed, call user function
					if(numLoaded == numNotes) {
						user_onload();
						numLoaded = 0;
					}
				},
				onloaderror : function() {console.log(quality+" "+type+" loading error")},
				onend : function() {
					numEnded++;
					if(type == CHORD && category != TWENTIETH) {
						if(numEnded == 2*numNotes) {
							document.getElementById("stopbtn").style.display = "none";
							document.getElementById("playbtn").style.display = "block";
							numEnded = 0;
						}
					}
					else {
						if(numEnded == numNotes) {
							document.getElementById("stopbtn").style.display = "none";
							document.getElementById("playbtn").style.display = "block";
							numEnded = 0;
						}
					}
				}
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

	/**
	 * Fades all sound out
	 *
	 * @method fadeOut
	 */
	this.fadeOut = function() {
		clear();
		fade(0);
	}

	/**
	 * Recursive helper function for this.fadeOut. When sound finishes fading, resets the file.
	 *
	 * @method fade
	 * @param {Integer} note Which note to fade
	 * @private
	 */
	function fade(note) {
		if(note < numNotes) {
			tempSounds[note].fadeOut(0.0, FADE_ALL_LENGTH, 
				function() {
					tempSounds[note].stop();		//After fade, reset position to 0
					tempSounds[note].volume(1.0);	//After fade, reset volume to max
					numEnded = -1;
				}
			);
			fade(note+1);
		}
	}

	/**
	 * Immediately stops all sound
	 *
	 * @method stop
	 */
	this.stop = function() {
		stop();
		clear();
		setVolume(1.0);
		numEnded = 0;
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
	 * Stops all sound currently playing
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
	 * Sets the volume of all files
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
	 * Sets the bpm
	 *
	 * @method setBPM
	 * @param {Integer} bpm Desired beats per minute
	 */
	this.setBPM = function(newbpm) {
		bpm = newbpm;
		bps = bpm/60; 				//Beats per second
	}
}
