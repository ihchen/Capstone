/**
 * Generates a series of four random twelve-tone notes.
 * @class SuccessiveMelodicIntervals
 * @constructor
 */
function SuccessiveMelodicIntervals() {
  const LENGTH = 4;
  var notes = generateMelody();
  var answers = calculateAnswers();

  /**
   * @method getAnswers
   * @return {String[]} intervals
   */
  this.getAnswers = function() {
    return answers;
  }

  /**
   * Returns the notes as Strings
   * @method getNotes
   * @return {String[]} notes
   */
  this.getNotes = function() {
    var notes1 = [];

    for (var i = 0; i < notes.length; i++) {
      notes1.push(notes[i].toString());
    }
    return notes1;
  }

  /**
   * Generates a 4-note 12-tone melody using backtracking.
   * @method
   * @return {Note[]} notes
   */
  function generateMelody() {

  }

  /**
   * Create a palette of notes to try based on a starting note.
   * @method getPalette
   * @param {Note} starting note
   * @return {Note[]} palette
   */
  function getPalette(note) {

  }




}



 // Use notes 9 through 25 (Gb—A#)
