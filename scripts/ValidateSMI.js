/**
 * Validation rules for successive melodic intervals.
 * @method validateSMI
 * @param {String} notes
 * @return {Boolean} true or false
 */
 function validateSMI(notes) {

   if (notes.length == 1) return true;

   if (checkSpan() && checkDirections() && checkIntervals() && checkForHarmony()) {
     return true;
   } else return false;


   var sort = sortNotes();

   /**
    * Sort the notes in order from lowest to highest.
    * @method sortNotes
    * @return {Note[]} sorted notes
    */
   function sortNotes() {
     // Copy notes to new array.
     var sortedNotes = notes.slice(0);



   }

   /**
    * Make sure no two notes are repeated.
    * @method checkRepeats
    * @return {Boolean} true if valid, false if not
    */
   function checkRepeats() {
     for (var i = 0; i < notes.length; i++) {
       for (var j = 0; j < notes.length; j++) {
         if (i == j) continue;
         if (notes[i].compareTo(notes[j]) == 0) return false;
       }
     }
     return true;
   }

   /**
    * Ensure that the melody does not exceed the span of an octave.
    * @method checkSpan
    * @return {Boolean}
    */
   function checkSpan() {
     if (Math.abs(sort(notes[0].compareTo(notes[notes.length-1]))) > 12) return false;
     return true;

   }

   /**
    * Ensure that the intervals do not occur all in the same direction.
    * @method checkDirections
    * @return {Boolean}
    */
   function checkDirections() {
     var count = 0;

     for (var i = 0; i < notes.length-1; i++) {
       count += notes[i].compareTo(notes[i + 1]);
     }

     if (Math.abs(count) == notes.length - 1) return true;
     return false;

   }

   /**
    * Ensure that any interval is used no more than once.
    * @method checkIntervals
    * @return {Boolean}
    */
   function checkIntervals() {

   }

   /**
    * Ensure that the melody does not outline a triad or 7th chord.
    * @method checkForHarmony
    * @return {Boolean}
    */
   function checkForHarmony() {
     // An array containing the intervals found between major and minor triads
     // Diminished and augmented triads, and seventh chords (e.g MM7, mm7, Mm7,
     // dm7, and dd7) need not be explicitly spelled because they contain
     // repeating intervals, which will be prevented with another rule.
     var badChords = [[4, 3, 7], [3, 4, 7]];

   }
 }
