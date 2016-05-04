var lastKey = "I"; 				//Keep track of last key played so that you don't replay it
const FADE_ALL_LENGTH = 100;	//Number of milliseconds to fade all notes out
//Play styles (pass into play())
const BLOCK = "block";			//Playing notes blocked
const ASCENDING = "asc";		//Playing notes broken and ascending
const DESCENDING = "desc";		//Playing notes broken and descending

/**
 * Converts the given notes into Howl objects that can be played.
 * Can do transformations such as transposition and inversion.
 * Plays the transformed notes in a given/preset style.
 * 
 * @class MusicSnippet
 * @constructor
 * @param {String[]} notes Array of notes that define the sonority
 * @param {String} type Type of the sonority
 * @param {String} quality Quality of the sonority
 * @param {String} category Category of the sonority
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
	var tempSounds = [];			//Holds current Howl objects
	var timeouts = [];				//Timeout objects to keep track of playing notes in succession
	var scaleStyle = ASCENDING;		//Default style to play broken notes is ascending
	var numLoaded = 0;				//Number of loaded files (resets to 0 once all are loaded)
	var numNotesPlayed = 0;			//Number of notes that have been played since play was called
	var stoppedSound = false;		//Whether or not fadeOut() was called since the last play()

	var user_onload = function(){};	//User-supplied function to be invoked when the sound files are loaded

	/**
	 * Plays the loaded notes with the given style. If style not given, plays the notes quiz style.
	 *
	 * @method play
	 * @param {String} style (Optional) How to play the notes
	 */
	this.play = function(style) {
		//Reset variables
		numNotesPlayed = 0;
		stoppedSound = false;

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
					playBlock(4);		//Fade out after 4 beats
				}
				//Otherwise, play blocked, then arpegiated
				else {
					playBlock(CHORD_DELAY);
					timeouts.push(setTimeout(		//Played argegiated after CHORD_DELAY beats
						function() {
							playBroken(DEFAULT_FADE);
						},
						(1/bps)*1000*CHORD_DELAY)
					);
				}
			}
			//Play scales broken and ascending/descending (as set in generate())
			else if(type == SCALE) {
				playBroken(DEFAULT_FADE, scaleStyle);
			}
			//If something else, just play it broken
			else {
				playBroken(DEFAULT_FADE);
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
				//Reset Howl position and volume values after fade completes
				function() {
					tempSounds[i].stop();
					tempSounds[i].volume(1.0);
					//Keep track of how many notes have been played to know when it ends
					if(!stoppedSound)
						numNotesPlayed++;
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
				//Play next note after one beat
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
	 * @param {Integer} octave (Optional) Octave the first note starts in
	 */
	this.generate = function(user_function, key, inversion, octave) {
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
			//Transpose, invert, and add octave
			tempNotes = TOI(key, inversion, octave);
			//If a scale, randomly select ascending and descending
			if(type == SCALE) {
				var rand = Math.floor(Math.random()*2);
				if(rand == 0) {
					scaleStyle = ASCENDING;
				}
				else {
					scaleStyle = DESCENDING;
				}
			}
			tempSounds = loadFiles(tempNotes);
		}
	}

	/**
	 * Transposes notes to a given or random key, gives notes a given or random octave,
	 *  and inverts the notes a given or random amount.
	 *
	 * @method TOI
	 * @param {String} key (Optional) Which key to tranpose to
	 * @param {Integer} inversion (Optional) Amount to invert by
	 * @param {Integer} octave (Optional) Which octave to apply
	 * @return {String[]} An array of the notes with octave numbers
	 * @private 
	 */
	function TOI(key, invert, octave) {
		var newNotes;		//New array of notes
		var shift;			//Transposition value
		var inv;			//Inversion value
		var octaves;		//Possible octave values

		do {
			//If given no key, generate random transposition
			if(key == undefined) {
				do {
					shift = Math.floor(Math.random()*13)-6;	//Get Random key between -6 and 6
					newNotes = transpose(baseNotes, shift);	//Array of transposed keys
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
				newNotes = setInversion(newNotes, inv);		//Update notes with the new inversion
				quality = invert7thQuality(quality, inv);	//Update the answer
			}

			//If octave given, set it
			if(octave != undefined) {
				newNotes = setOctaveNumbers(newNotes, octave);
			}
			//If not, give it a random octave
			else {
				//Old way
				// setOctave(newNotes);

				//New way
				octaves = getOctaveLocations(newNotes);
				//If octaves is null, that means no viable octave was found
				if(octaves == null)
					continue;
				var rand = Math.floor(Math.random()*octaves.length);	//Pick random octave
				newNotes = setOctaveNumbers(newNotes, octaves[rand]);	//Set new octave
			}
		//If newNotes is null for some reason, just try again (Mystic Chord issues?)
		} while(newNotes == null);

		lastKey = newNotes[0];			//Save the new key
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
		//Grab the base quality
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
	 * Takes the given notes and converts them into Howl objects with the appropriate
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
				console.log("Error: Check notation/octave for "+quality+" "+type);
			}
		}

		//Convert midi numbers to their corresponding file names
		for(var i = 0; i < numNotes; i++) {
			files.push("audio/piano/piano"+midi[i]+".mp3");
		}

		//Load sound files
		for(var i = 0; i < numNotes; i++) {
			sounds.push(new Howl({
				urls : [files[i]],
				//When all sounds finish loading, call user given function
				onload : function() {
					numLoaded++;
					//If all notes loaded, call user function
					if(numLoaded == numNotes) {
						user_onload();
						numLoaded = 0;
					}
				},
				onloaderror : function() {console.log(quality+" "+type+" loading error")},
				//When all notes have finished playing, change stop button to play button
				onend : function() {
					var requiredNumPlayed = numNotes;
					if(type == CHORD && category != TWENTIETH)
						requiredNumPlayed *= 2;
					if(numNotesPlayed == requiredNumPlayed) {
						document.getElementById("playbtn").style.display = "block";
						document.getElementById("stopbtn").style.display = "none";
						numNotesPlayed = 0;
					}
				}
			}));
		}
		return sounds;
	}

	/**
	 * Fades all sound out
	 *
	 * @method fadeOut
	 */
	this.fadeOut = function() {
		stoppedSound = true;
		clear();
		fade(0);
	}

	/**
	 * Recursive helper function for this.fadeOut.
	 * Have to do this recursively in order to use Howl's fadeOut()'s 
	 * third parameter.
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
				}
			);
			fade(note+1);
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