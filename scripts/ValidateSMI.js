/**
 * Validation rules for successive melodic intervals.
 * @method validateSMI
 * @param {String} notes
 * @return {Boolean} true or false
 */
 function validateSMI(notes) {

  //  console.log("notes.length in validateSMI = " + notes.length);
  //  console.log(notes);

   if (notes.length == 1) return true;
  //  console.log("notes.length in validateSMI = " + notes.length);
   var sorted = sortNotes();

   if (checkRepeats() && checkSpan() && checkDirections() &&
        checkIntervals() && checkForHarmony()) return true;
   else return false ;

   /**
    * Sort the notes in order from lowest to highest.
    * @method sortNotes
    * @return {Note[]} sorted notes
    */
   function sortNotes() {
    // Copy notes to new array.
    var sortedNotes = notes.slice(0);

   	var swapping = true;
   	while (swapping) {
   		swapping = false;
   		for (var i = 0; i < sortedNotes.length - 1; i++) {
        // console.log(i);

   			if (sortedNotes[i].compareTo(sortedNotes[i + 1]) > 0) {
          var tmp = sortedNotes[i];
          sortedNotes[i] = sortedNotes[i + 1];
          sortedNotes[i + 1] = tmp;
   				swapping = true;
   			}
   		}
   	}
   		// Loop will break if nothing changed for an entire pass.
   		// Meaning, we're sorted!!
      // document.write("from sort:<br>");
      // document.write("unsorted: ");
      // document.write(notes);
      // document.write("<br>");
      // document.write("sorted: ");
      // document.write(sortedNotes);
      // document.write("<br>");

   		return sortedNotes;
   }

   /**
    * Make sure no two notes are repeated.
    * @method checkRepeats
    * @return {Boolean} true if valid, false if not
    */
   function checkRepeats() {

     for (var i = 0; i < notes.length; i++) {

       for (var j = 0; j < i; j++) {

         if (i == j) continue;
         if (notes[i].compareTo(notes[j]) % 12 == 0) return false;
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
     if (Math.abs(sorted[0].compareTo(sorted[notes.length-1])) > 12) return false;
     return true;
   }

   /**
    * Ensure that the intervals do not occur all in the same direction.
    * @method checkDirections
    * @return {Boolean}
    */
   function checkDirections() {
    //  return true;
     if (notes.length < 3) {
       // We cannot check this until the melody has reached terminal length.
       return true;
     }

     var precedent = notes[0].compareTo(notes[1]);

     for (var i = 0; i < notes.length - 1; i++) {
       if (i == 2) document.write("2");
      //  document.write("i = " + i + "<br>");
      //  document.write("note1 = " + notes[i] + ", note2 = " + notes[i+1] + "<br>");
      //  document.write("compareTo = " + notes[i].compareTo(notes[i+1]) + "<br>");
      //  document.write(notes[i].compareTo(notes[i+1]) + "<br>");
       if ((notes[i].compareTo(notes[i+1]) > 0 && precedent < 0) ||
          (notes[i].compareTo(notes[i+1]) < 0 && precedent > 0)) return true;

     }

    //  If we've made it here, they were all the same direction.
    // console.log("return false");

    return false;

   }

   /**
    * Ensure that any interval is used no more than once.
    * @method checkIntervals
    * @return {Boolean}
    */
   function checkIntervals() {
    //  return true;

     var intervals = [];

     for (var i = 0; i < notes.length - 1; i++) {
       var compare = notes[i].compareTo(notes[i + 1]);
       if (intervals.indexOf(compare) == -1) {
         intervals.push(compare);
       } else {
         // This interval has already been used!
         return false;
       }
     }
     return true;

   }

   /**
    * Ensure that the melody does not outline a triad or 7th chord.
    * @method checkForHarmony
    * @return {Boolean}
    */
   function checkForHarmony() {

     // An array containing the intervals found between major and minor triads
     // Seventh chords (e.g MM7, mm7, Mm7, dm7, and dd7) need not be explicitly
     // spelled because they contain repeating intervals, which will be prevented
     // with another rule.
     //  var badChords = [[4, 3, 7, 4], [3, 4, 7, 3], [3, 3, 6, 3]];

     var badChords = [[4, 3], [3, 7], [7, 4], [3, 4], [4, 7], [7, 3], [3, 3], [3, 6], [6, 3], [4, 4]];

     for (var i = 0; i < sorted.length - 2; i++) {
       for (var j = i + 1; j < sorted.length - 1; j++) {
         var interval = sorted[j].compareTo(sorted[i]);
         for (var k = 0; k < badChords.length; k++) {
           if (interval == badChords[k][0]) {
             // We have found a potential problem!
            for (var l = j + 1; l < sorted.length; l++) {
              var interval2 = sorted[l].compareTo(sorted[j]);
              if (interval2 == badChords[k][1]) return false;
            }
           }
         }
       }
     }

     // Made it all the way through and found no triads outlined.
     return true;
   }
 }
