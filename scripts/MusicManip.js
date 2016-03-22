/*
 * This file contains a variety of methods that perform useful
 * manipulations on a collection of musical notes.
 */

// An array of musical notes in order of the circle of fifths.
// DO NOT CHANGE THIS ORDER!!!
const NOTES = ["Fbb", "Cbb", "Gbb", "Dbb", "Abb", "Ebb", "Bbb", "Fb",
  "Cb", "Gb", "Db", "Ab", "Eb", "Bb", "F", "C", "G", "D", "A", "E",
  "B", "Fx", "Cx", "Gx", "Dx", "Ax", "Ex", "Bx", "Fxx", "Cxx", "Gxx",
  "Dxx", "Axx", "Exx", "Bxx"];

/**
 * Returns the position of a note in the circle of fifths.
 * @method ordinal
 * @param {String} note
 * @return {Integer} ordinal
 */
function ordinal(note) {
  for (var i = 0; i < NOTES.length; i++) {
    // Test Strings for equality.
    if (note == NOTES[i]) return i;
  }
}

/**
 * Takes an array of notes and shifts them over by the specified
 * amount in the circle of fifths. Take care with providing the
 * amount to shift; you don't want to shift far enough to create
 * an unused spelling. Since the shift argument must be in fifths,
 * it is a range that can be easily determined.
 * @method transpose
 * @param {String[]} notes
 * @param {Integer} shift
 * @return {String[]} transposed notes
 */
function transpose(notes, shift) {
  var transposedNotes = [];

  for (var i = 0; i < notes.length; i++) {
    transposedNotes[transposedNotes.length] = NOTES[ordinal(notes[i]) + shift];
  }
  return transposedNotes;
}

/**
 * Reverses the direction of a collection of notes.
 * To be used for scales and intervals, but NOT chords!
 * Do this AFTER the octave is set!
 * @method reverseDirection
 * @param {String[]} notes
 * @return {String[]} notes
 */
function reverseDirection(notes) {
  return notes.reverse();
}

/**
 * Reorders the notes so that they are in the specified inversion.
 * To be used for major triads, minor triads, and 7th chords only!
 * NEVER to be used for scales, intervals, jazz chords, and 20th
 * century chords!!!
 * @method setInversion
 * @param {String[]} notes in the chord
 * @param {Integer} number between the values 0 and notes.length-1, inclusive
 * @return {String[]} inverted chord
 */
function setInversion(chord, inversion) {
  // inversion should be an int between the values 0 and notes.length-1
  for (i = 0; i < inversion; i++) {
    chord.push(chord.shift());
  }
  return chord;
}



// The number of half steps in an octave.
const OCTAVE = 12;

/**
 * Calculate the interval between two notes.
 * Returns the interval as a number of half steps.
 * @method calcInterval
 * @param {String} note1
 * @param {String} note2
 * @return {Integer} interval in half steps
 */
function calcInterval(note1, note2) {

  /*
   * Reference array for the ascending distance to an interval.
   * Each number corresponds to the number of half steps between
   * the following intervals, respectively:
   * unison, p5, M2, M6, M3, M7, tritone, m2, m3, m7, p4
   * DO NOT CHANGE THE ORDER!
   */
  const INTERVALS = [0, 7, 2, 9, 4, 11, 6, 1, 8, 3, 10, 5];
  // Subtract Note1's ordinal from Note2's ordinal
  var ordinalDiff = ordinal(note2) - ordinal(note1);

  // Normalize
  if (!(ordinalDiff < OCTAVE && ordinalDiff > -OCTAVE)) {
    ordinalDiff %= OCTAVE;
  }

  // Bring negative numbers up into the positives!
  if (ordinalDiff < 0) ordinalDiff += OCTAVE;

  return INTERVALS[ordinalDiff];
}

/**
 * Calculates the span of a collection of notes. Returns an
 * integer representing the number of half steps between the
 * lowest note and highest note in the given note collection.
 * @method calcSpan
 * @param {String[]} notes
 * @return {Integer} span
 */
function calcSpan(notes) {
  var span = 0;
  for (var i = 0; i < notes.length - 1; i++) {
    span += calcInterval(notes[i], notes[i + 1]);
  }
  return span;
}

/**
 * Sets an octave appropriate for the given span of notes at random.
 * Does so by appending a number to the end of each String.
 * DO NOT USE ON A SCALE OR INTERVAL THAT HAS BEEN REVERSED.
 * PLEASE REVERSE AFTER SETTING THE OCTAVE!!!
 * Inverted chords are O.K.
 * @method setOctave
 * @param {String[]} notes
 * @return {String[]} notes with octave numbers appended to each
 */
function setOctave(notes) {

  // The note name of the lowest note available for playback
  const LOW = 15;

  // The note name fo the highest note available for playback
  const HIGH = 20;

  // The number of notes available for playback ranging from
  // C3 to B5.
  const NUM_NOTES = 35;

  var span = calcInterval(NOTES[LOW], notes[0])+ calcSpan(notes)
    + calcInterval(notes[notes.length - 1], NOTES[HIGH]);

  // Calculate the number of places this sonority will fit.
  var numPlaces = NUM_NOTES/span;

  if (numPlaces < 1) {
    // This note collection will not fit.
      console.log("Warning: This note collection either has too "
        + "large a span, or the span is large with too high of"
        + " a starting note. Please check your inputs!")
      return null;
  }

  /**
   * Go up one note name. G will loop back to A.
   * @method increment
   * @private
   * @param {String} note name
   * @return {String} one letter name higher
   */
  function increment(noteName) {
    noteName = String.fromCharCode(noteName.charCodeAt(0) + 1);
    if (noteName == "H") noteName = "A";
    return noteName
  }

  /**
   * Generate a random number for the octave in which a note
   * collection may begin.
   * @method genRandomNum
   * @private
   * @param {Integer} number of places in which the notes will fit
   * @return {Integer} random number from [lowestOctave, numPlaces)
   */
  function genRandomNum(numPlaces) {
    // 3 is the lowest octave in which a note collection may begin.
    var lowestOctave = 3;

    // There is a different lowestOctave for notes enharmonically
    // equivalent to our lowest note.
    if (notes[0] == "Bx" || notes[0] == "Bxx") {
      lowestOctave = 2;
    }

    return Math.floor((Math.random() * (numPlaces)) + lowestOctave);
  }

  var octave = genRandomNum(numPlaces);
  var octavizedNotes = [];
  var notename = notes[0].charAt(0);

  for (var i = 0; i < notes.length; i++) {
    while (notes[i].charAt(0) != notename) {
      // Creep up note-by-note
      notename = increment(notename);
      if (notename == "C") octave++;
    }
    // Concat octave onto the note name!
    octavizedNotes.push(notes[i] + octave);
  }

  return octavizedNotes;
}

/**
 * Determines whether two notenames are enharmonically equivalent.
 * @method
 * @param {String} note1
 * @param {String} note2
 * @return {Boolean} true or false
 */
function isEnharmonic(note1, note2) {
  var ord1 = ordinal(note1) % 12;
  var ord2 = ordinal(note2);

  while (ord1 < NOTES.length) {
    if (ord1 == ord2) return true;
    ord1 += 12;
  }

  return false;

}
