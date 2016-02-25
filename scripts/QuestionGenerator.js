/*
 * Script that generates a random chord, scale, or interval
 * based on various parameters. MusicSnippet will query a
 * Question object for the sonority.
 */
function Question(sonorities) {
  // The String answer
  var answer = selectRandomSonority(sonorities);
  var type;

  // The array of notes
  var sonority = transposeSonority();

  this.getSonority() {
    return sonority;
  }

  this.getAnswer() {
    return answer;
  }

  this.getType() {
    return type;
  }

  /*
   * Picks a random sonority from the user's list of selected
   * sonorities. When called, sets the answer for this question.
   * Also initializes type.
   */
  function selectRandomSonority(sonorities) {
    var i = Math.floor(Math.random() * sonorities.length);
    type = sonorities[i].type1;
    return sonorities[i].type2;
  }

  /*
   * Uses the sonority ID to find the note collection in the
   * CSV file. Then, transpose it randomly.
   */
  function transposeSonority() {
    var notes = getNoteCollection(type, answer);
    notes = transpose(notes, genRandomShift());
    return notes;
  }

  /*
   * Generate a random number by which to shift the key.
   * (-7, 7) inclusive.
   */
  function genRandomShift() {
    return Math.floor((Math.random() * (15)) - 7);
  }


}
