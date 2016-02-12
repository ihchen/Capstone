// This file contains a variety of methods that perform musical
// algorithms on a collection of notes.

// An array of musical notes in order of the circle of fifths.
const NOTES = ["Fbb", "Cbb", "Gbb", "Dbb", "Abb", "Ebb", "Bbb", "Fb",
  "Cb", "Gb", "Db", "Ab", "Eb", "Bb", "F", "C", "G", "D", "A", "E",
  "B", "Fx", "Cx", "Gx", "Dx", "Ax", "Ex", "Bx", "Fxx", "Cxx", "Gxx",
  "Dxx", "Axx", "Exx", "Bxx"];

// Takes an array of notes and shifts them over by the specified
// amount in the circle of fifths.
// Take care with providing the amount to shift; you don't want to
// shift far enough to create an unused spelling. Because the shift
// argument must be in fifths, it is a range that can be easily
// determined.
function transpose(notes, shift) {
  var transposedNotes = [];

  // For each note in the array arg, find it in our circle of
  // fifths array.
  for (i = 0; i < notes.length; i++) {
    for (j = 0; j < NOTES.length; j++) {
      // Test Strings for equality.
      if (NOTES[j] == notes[i]) {
        // We have found the note in the circle of fifths!
        // Offset it and add it to the new array.
        transposedNotes[transposedNotes.length] = NOTES[j + shift];
        break;
      }
    }
  }
  return transposedNotes;
}

// Reverses the direction of a collection of notes.
// To be used for scales and intervals, but NOT chords!
// Do this AFTER the octave is set!
function reverseDirection(notes) {
  return notes.reverse();
}

// Reorders the notes so that they are in the specified inversion.
// To be used for major triads, minor triads, and 7th chords only!
// NEVER to be used for scales, intervals, jazz chords, and 20th
// century chords!!!
function setInversion(chord, inversion) {
  // inversion should be an int between the values 0 and notes.length-1
  for (i = 0; i < inversion; i++) {
    chord.push(chord.shift());
  }
  return chord;
}

// Reference array for the distance up to get to an interval, in order
// of the circle of fifths. Each number corresponds to the number of
// half steps between the following intervals, respectively:
// d5, m2, m6, m3, m7, p4, p8, p5, M2, M6, M3, M7, a4
// DO NOT CHANGE THE ORDER!
const INTERVALS = [6, 1, 8, 3, 10, 5, 12, 7, 2, 9, 4, 11, 6];
// The space containing 12 really represents unison, but for our purposes,
// we will assume that calculating that interval of unison is trivial.
// If we get two of the same note in a row, (which we won't in the
// actual program) we will treat it as the octave above.

// The difference between the placement of two notes in the circle
// of fifths needs to be offset by this number to reach the correct
// index in the INTERVAL array.
const SHIFT = 6;

// Calculate the interval between two notes.
// Returns the interval as a number of half steps.
function calcInterval(note1, note2) {
  // Find note1 in our circle of fifths array.
  var i;
  for (i = 0; i < NOTES.length; i++) {
    if (note1 == NOTES[i]) break;
  }
  // Find note2 in our circle of fifths array.
  var j;
  for (j = 0; j < NOTES.length; j++) {
    if (note2 == NOTES[j]) break;
  }

  // Subtract Note1's index from Note2's index and add SHIFT to
  // obtain the correct index for INTERVAL.
  return INTERVALS[j - i + SHIFT];
}

// Calculates the span of a collection of notes. Returns an
// integer representing the number of half steps between the lowest
// note and highest note in the given note collection.
function calcSpan(notes) {
  var span = 0;
  for (i = 0; i < notes.length - 1; i++) {
    span += calcInterval(notes[i], notes[i + 1]);
  }
  return span;
}

// Sets an octave appropriate for the given span of notes.
// Does so by appending a number to the end of each String.
function setOctave(notes) {
  var span = calcSpan(notes);
  // TODO: WRITE THIS!!!
}

// The number of half steps in an octave.
const OCTAVE = 12;

// Basically a "compareTo()" method to test for enharmonic equivalence
function isEnharmonic(note1, note2) {
  // Find the first note.
  var i = 0;
  for (i; i < NOTES.length; i++) {
    if(NOTES[i] == note1) {
      // We found it!
      break;
    }
  }

  // Check octave above.
  var j = i + OCTAVE;
  while (j < notes.length) {
    if (NOTES[j] == NOTES[i]) return true;
    above += OCTAVE;
  }

  // Check octave below.
  j = i - OCTAVE;
  while (j > 0) {
    if (NOTES[j] == NOTES[i]) return true;
    above -= OCTAVE;
  }

  // If we've made it here, we couldn't find equivalence.
  return false;
}
