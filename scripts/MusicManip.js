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

/*
 * Returns the position of a note in the circle of fifths.
 */
function ordinal(note) {
  for (var i = 0; i < NOTES.length; i++) {
    // Test Strings for equality.
    if (note == NOTES[i]) return i;
  }
}

/*
 * Takes an array of notes and shifts them over by the specified
 * amount in the circle of fifths. Take care with providing the
 * amount to shift; you don't want to shift far enough to create
 * an unused spelling. Since the shift argument must be in fifths,
 * it is a range that can be easily determined.
 */
function transpose(notes, shift) {
  var transposedNotes = [];

  // For each note in the array arg, find it in our circle of
  // fifths array.
  for (var i = 0; i < notes.length; i++) {
    for (var j = 0; j < NOTES.length; j++) {
      var ord = ordinal(notes[i]);
      // Test Strings for equality.
      if (NOTES[j] == notes[i]) {
        // TODO: Simplify this!!!
        // var ordinal = ordinal(notes[i]);
        // We have found the note in the circle of fifths!
        // Offset it and add it to the new array.
        transposedNotes[transposedNotes.length] = NOTES[j + shift];
        // transposedNotes[transposedNotes.length] = NOTES[ord + shift];

        break;
      }
    }
  }
  return transposedNotes;
}

/*
 * Reverses the direction of a collection of notes.
 * To be used for scales and intervals, but NOT chords!
 * Do this AFTER the octave is set!
 */
function reverseDirection(notes) {
  return notes.reverse();
}

/*
 * Reorders the notes so that they are in the specified inversion.
 * To be used for major triads, minor triads, and 7th chords only!
 * NEVER to be used for scales, intervals, jazz chords, and 20th
 * century chords!!!
 */
function setInversion(chord, inversion) {
  // inversion should be an int between the values 0 and notes.length-1
  for (i = 0; i < inversion; i++) {
    chord.push(chord.shift());
  }
  return chord;
}

/*
 * Reference array for the ascending distance to an interval.
 * Each number corresponds to the number of half steps between
 * the following intervals, respectively:
 * unison, p5, M2, M6, M3, M7, tritone, m2, m3, m7, p4
 * DO NOT CHANGE THE ORDER!
 */
const INTERVALS = [0, 7, 2, 9, 4, 11, 6, 1, 8, 3, 10, 5];

// The number of half steps in an octave.
const OCTAVE = 12;

/*
 * Calculate the interval between two notes.
 * Returns the interval as a number of half steps.
 */
function calcInterval(note1, note2) {
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

/*
 * Calculates the span of a collection of notes. Returns an
 * integer representing the number of half steps between the
 * lowest note and highest note in the given note collection.
 */
function calcSpan(notes) {
  var span = 0;
  for (var i = 0; i < notes.length - 1; i++) {
    span += calcInterval(notes[i], notes[i + 1]);
  }
  return span;
}

// The number of notes available for playback ranging from
// C3 to B5.
const NUM_NOTES = 35;

// The lowest note available for playback is C3, and its
// ordinal in the circle of fifths is 15.
const C = 15;

/*
 * Sets an octave appropriate for the given span of notes.
 * Does so by appending a number to the end of each String.
 * DO NOT USE ON A SCALE OR INTERVAL THAT HAS BEEN REVERSED.
 * PLEASE REVERSE AFTER SETTING THE OCTAVE!!!
 * Inverted chords are O.K.
 */
function setOctave(notes) {

  // The distance from C (the lowest note available) to the
  // first note of the note collection.
  var span = calcInterval(NOTES[C], notes[0]);

  // Incorporate the total span of the note collection.
  span += calcSpan(notes);

  var numPlaces = 1;
  while (span <= NUM_NOTES/numPlaces && numPlaces <=5) {
    numPlaces++;
  }
  // This note collection will fit in (numPlaces - 1) places.
  // Decrement to reflect that.
  numPlaces--;

  if (numPlaces == 0) {
    // This note collection will not fit.
    console.log("Warning: This note collection either has too "
      + "large a span, or the span is large with too high of"
      + " a starting note. Please check your inputs!")
    return null;
  }


  // Let's pick one of those places at random.
  var octave = genRandomNum(numPlaces);
  var octavizedNotes = [];
  var notename = notes[0].charAt(0);
  console.log(notes);

  for (var i = 0; i < notes.length; i++) {
    while (notes[i].charAt(0) != notename) {
      notename = String.fromCharCode(notename.charCodeAt(0) + 1);
      console.log(notename);
      if (notename == "H") notename = "A";
      //document.write(notename);
      if (notename == "C") octave++;

    }
    // Concat octave onto the note name!
    octavizedNotes.push(notes[i] + octave);
  }


  return octavizedNotes;
}

// /*
//  * Checks to see if a C was passed between two notes.
//  * This includes Cbb, C, Cx, and Cxx
//  */
// function passedC(note1, note2) {
//   console.log(note1 + " to C is " + calcInterval(note1, NOTES[C]));
//   console.log(note1 + " to " + note2 + " is " + calcInterval(note1, note2));
//
//   // We need a special case if the first note IS C.
//   if (note1.charAt(0) == 'C' || calcInterval(note1, NOTES[C]) == 0) {
//     console.log("case1");
//     // We literally just changed the octave, so don't do it again!
//     return false;
//   }
//   // Likewise, if the second note is some kind of C
//   else if (note2.charAt(0) == 'C') {
//     console.log("case2");
//     // We have reached C! Time to increment the octave!
//     return true;
//   }
//   // Otherwise, check the distance to the next note.
//   else if (calcInterval(note1, NOTES[C]) < calcInterval(note1, note2)) {
//     console.log(note1 + " to C is " + calcInterval(note1, NOTES[C]));
//     console.log(note1 + " to " + note2 + " is " + calcInterval(note1, note2));
//     console.log("case3");
//     return true;
//   } else {
//     console.log("case4");
//     return false;
//   }
// }

/*
 * Generate a random number for the octave in which a note
 * collection may begin.
 */
const lowestOctave = 3;
function genRandomNum(numPlaces) {
  // 3 is the lowest octave in which a note collection may begin,
  // and numPlaces provides a range. This method will return a
  // random number from [lowestOctave, numPlaces).
  return Math.floor((Math.random() * (numPlaces)) + lowestOctave);
}
