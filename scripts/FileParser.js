// functions to turn basic chord/scale/interval specs into arrays
// of notes
function FileParser() {
  var data = loadFile();

  /*
  * Load the CSV file.
  */
  function loadFile() {
	   // open data.csv read-only
	    var data = new XMLHttpRequest();

      // TODO: CHANGE PATH ON SERVER
      data.open("GET", "file:///Users/Kaylene/capstone/Capstone/scripts/data.csv", false);
      data.send();
      return data;
  }

  /*
  * Parses the CSV file for the specified args. Type1 refers to
  * the type of note collection: scale, chord, or interval.
  * Type2 refers to either tonality, quality, or the interval
  * name, respectively, depending on what type1 is.
  */
  this.getNoteCollection(type1, type2) {

	   // get note collection instructions
    var notes;
    var lines = data.response.split("\n");
    for (var i = lines.length - 1; i >= 0; i--) {
      var fields = lines[i].split(",");
      if (fields[0]==type1 && fields[1]==type2) {
        // Turn the note string into an array
        return fields[2].split(" ");
      }
    }
  }
  /*
   * Parses the CSV file for a list of all chords, scales,
   * and intervals and their types.
   */
   this.getTypeSet() {
     var data = loadFile();

     // get note collection instructions
     var typeSet = [];
     var lines = data.response.split("\n");
     for (var i = 0; i < lines.length; i++) {
       // fields = a line of the CSV file
       var fields = lines[i].split(",");
       // don't put empty lines into the new array
       if (fields[0] == null || fields[1] == null) {
         continue;
       }
       // Put every type pair into the array.
       typeSet.push({type1 : fields[0], type2 : fields[1]});
     }
     return typeSet;
   }
}
