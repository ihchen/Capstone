/**
 * Represents a musical note.
 * @class Note
 * @constructor
 * @param {String} notename
 * @param {Integer} octave
 */
function Note(notename, octave) {
  // Parse notename
  var lettername = notename.charAt(0);
  var accidental = toInt(notename.charAt(1) + notename.charAt(2));

  /**
   * Convert accidentals to numbers.
   * @method toInt
   * @return {Integer} accidental
   */
  function toInt(acc) {
    const ACCIDENTALS = ["bb", "b", "", "x", "xx"];
    for (var i = 0; i < ACCIDENTALS.length; i++) {
      if (acc == ACCIDENTALS[i]) return i - 2;
    }
  }

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
   * Compares two notes by pitch. Returns a positive integer if the first note is
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
   * Reference array for converting the difference between two ordinal positions
   * in the circle of fifths to the arbitrary name of an interval.
   */
  const INTERVALS = {
    "-6" : "d5",
    "-5" : "m2",
    "-4" : "m6",
    "-3" : "m3",
    "-2" : "m7",
    "-1" : "p4",
    "0" : "unison",
    "1" : "p5",
    "2" : "M2",
    "3" : "M6",
    "4" : "M3",
    "5" : "M7",
    "6" : "a4"
  }

  function getNumByInterval(interval) {
    for (var num in INTERVALS) {
      if (INTERVALS.hasOwnProperty(num)) {
        if (INTERVALS[num] == interval) {
          return parseInt(num);
        }
      }
    }
  }

  /**
   * Calculates the interval between two notes in standard notation.
   * @method getInterval
   * @param {Note} otherNote
   * @return {Integer} interval
   */
  this.getInterval = function(otherNote) {
    var higher = ordinal(notename);
    var lower = ordinal(otherNote.getNotename());
    if (this.compareTo(otherNote) < 0) {
      higher = ordinal(otherNote.getNotename());
      lower = ordinal(notename);
    }

    var ordDiff = higher - lower;
    var interval = INTERVALS[ordDiff];
    return interval;

  }

  /**
   * Creates a note a particular interval away from this note.
   * @method getNextNote
   * @param {String} interval
   * @param {Boolean} direction, true ascending, false descending
   * @return {Note} note
   */
  this.getNextNote = function(interval, direction) {
    var ord = ordinal(notename);
    var shift = getNumByInterval(interval);
    var index;
    if (direction) {
      index = ord + shift;
    } else {
      index = ord - shift;
    }
    var newnote = NOTES[index];

    var tempnote = notename.charAt(0);
    var newoctave = octave;

    // Find the octave number.
    while (tempnote != newnote.charAt(0)) {
      // Creep note-by-note
      if (direction) {
        tempnote = increment(tempnote);
        if (tempnote == "C") newoctave++;
      } else {
        tempnote = decrement(tempnote);
        if (tempnote == "B") newoctave--;
      }
    }
    return new Note(newnote, newoctave);
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
