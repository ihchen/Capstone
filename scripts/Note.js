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

   /**
    * Convert accidentals to numbers.
    * @method
    * @return {Integer} accidental
    */
   function toInt(acc) {
     const ACCIDENTALS = ["bb", "b", "", "x", "xx"];
     for (var i = 0; i < ACCIDENTALS.length; i++) {
       if (acc == ACCIDENTALS[i]) return i - 2;
     }
   }

   /**
    * Returns the lettername of this note.
    * @method
    * @return {Char} lettername
    */
   this.getLettername = function() {
     return lettername;
   }

   /**
    * Returns the accidental of this note.
    * @method
    * @return {String} accidental
    */
   this.getAccidental = function() {
     return accidental;
   }

   /**
    * Returns the notename.
    * @method
    * @return {String} notename
    */
   this.getNotename = function() {
     return notename;
   }

   /**
    * Returns the octave that this note is in.
    * @method
    * @return {Integer} octave
    */
   this.getOctave = function() {
     return octave;
   }

   /**
    * Compares two notes by pitch. Returns 1 if the first note is higher than
    * the second note, -1 if the first note is lower than the second note, and
    * 0 if they are the same note.
    * @method
    * @param {Note} otherNote
    * @return {Integer} compare
    */
   this.compareTo = function(otherNote) {
     if (octave > otherNote.getOctave()) {
       return 1;
     } else if (octave < otherNote.getOctave()) {
       return -1;
     } else {
       // These notes are in the same octave.
       // Time to compare the notenames!
       if (isEnharmonic(notename, otherNote.getNotename())) {
         // These notes are the same.
         return 0;
       }

       if (lettername == otherNote.getLettername()) {
         // Same letter name!
         // Check the accidentals. We already know they are not the same!
         if (accidental > otherNote.getAccidental()) return 1;
         else return -1;
       }

       var charcode1 = lettername.charCodeAt(0);
       var charcode2 = otherNote.getLettername().charCodeAt(0);

       // Normalize
       if (charcode1 < 'C') charcode1 += 7;
       if (charcode2 < 'C') charcode1 += 7;

       if (charcode1 > charcode2) {
         return 1;
       } else {
         return -1;
       }
     }
   }

   this.getInterval = function(otherNote) {

   }

   this.getIntervalInHalfSteps = function(otherNote) {

   }

   /**
    * String representation of a musical note.
    * @return {String} notename + octave
    */
   this.toString = function() {
     return notename + octave;
   }
 }
