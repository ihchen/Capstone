/**
 * Represents a musical note.
 * @class
 * @constructor
 * @param {String} notename
 * @param {Integer} octave
 */
function Note(notename, octave) {
  // Parse notename
  var lettername = notename.charAt(0);
  var accidental = toInt(notename.charAt(1) + notename.charAt(2));

  // /**
  //  * Convert accidentals to numbers.
  //  * @method toInt
  //  * @return {Integer} accidental
  //  */
  // function toInt(acc) {
  //   const ACCIDENTALS = ["bb", "b", "", "x", "xx"];
  //   for (var i = 0; i < ACCIDENTALS.length; i++) {
  //     if (acc == ACCIDENTALS[i]) return i - 2;
  //   }
  // }

  /**
   * @method getLettername
   * @return {Char} lettername
   */
  this.getLettername = function() {
    return lettername;
  }

  /**
   * @method getAccidental
   * @return {String} accidental
   */
  this.getAccidental = function() {
    return accidental;
  }

  /**
   * @method getNotename
   * @return {String} notename
   */
  this.getNotename = function() {
    return notename;
  }

  /**
   * @method getOctave
   * @return {Integer} octave
   */
  this.getOctave = function() {
    return octave;
  }

  /**
   * Compares two notes by pitch. Returns positive integer if the first note is
   * higher than the second note, negative integer if the first note is lower
   * than the second note, and 0 if they are the same note.
   * @method compareTo
   * @param {Note} otherNote
   * @return {Integer} interval in half steps
   */
  this.compareTo = function(otherNote) {
    return noteToFileNum[notename + octave] - noteToFileNum[otherNote.getNotename() + otherNote.getOctave()];
  }

  /**
   * Calculates the interval between two notes in standard notation.
   * @method getInterval
   * @param {Note} otherNote
   * @return {Integer} interval
   */
  this.getInterval = function(otherNote) {

  }

  /**
   * String representation of a musical note.
   * @method toString
   * @return {String} notename + octave
   */
   this.toString = function() {
     return notename + octave;
   }
 }
