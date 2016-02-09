// An array of musical notes in order of the circle of fifths.
const NOTES = ["__F", "__C", "__G", "__D", "__A", "__E", "__B", "_F",
  "_C", "_G", "_D", "_A", "_E", "_B", "F", "C", "G", "D", "A", "E",
  "B", "F^", "C^", "G^", "D^", "A^", "E^", "B^", "F^^", "C^^", "G^^",
  "D^^", "A^^", "E^^", "B^^"];

// Takes an array of notes and shifts them over by the specified
// amount in the circle of fifths.
function transpose(notes, shift) {
  var transposedNotes = [];

  // For each note in the array arg, find it in our circle of
  // fifths array.
  for (var i = 0; i < notes.length; i++) {
    for (var j = 0; j < NOTES.length; j++) {
      // Test Strings for equality.
      if (NOTES[j] == notes[i]) {
        // We have found the note in the circle of fifths!
        // Offset it and add it to the new array.
        // TODO: Handle octaves here?
        transposedNotes[transposedNotes.length] = NOTES[j + shift];
        break;
      }
    }
  }
  return transposedNotes;
}

// Basically a "compareTo()" method to test for enharmonic equivalence
function isEnharmonic(note1, note2) {
  // Find the first note.
  var i = 0;
  for (i; i < notes.length; i++) {
    if(notes[i].localCompare(note1)) {
      // We found it!
      break;
    }
  }
  // Check 12 fifths above and 12 fifths below.
  var j = i + 12;
  while (j < notes.length) {
    if (note[j].localCompare(note[i])) return true;
    above += 12;
  }

  j = i - 12;
  while (j > 0) {
    if (note[j].localCompare(note[i])) return true;
    above -= 12;
  }

  // If we've made it here, we couldn't find equivalence.
  return false;
}
