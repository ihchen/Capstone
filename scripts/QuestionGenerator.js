/*
 * Script that generates a random question. Parameter should
 * be a list of integers that refer to indeces in the CSV file
 * over which the user would like to be tested.
 */
function QuestionGenerator(list) {

  // The array of questions
  var musicSnippets = makeMusicSnippets(list);
  //Keep track of the current snippet playing
  var currSnippet = 0;

  function makeMusicSnippets(array) {
    var ms = [];
    for (var i = 0; i < array.length; i++) {
      // console.log(data[list[i]][0] + " " + data[list[i]][1]);
      ms.push(new MusicSnippet(data[list[i]][0], data[list[i]][1],
        data[list[i]][2]));
    }
    return ms;
  }

  /*
   * Picks a random MusicSnippet from the array.
   */
  this.getNextQuestion = function() {
    musicSnippets[currSnippet].stopSound();
    currSnippet = Math.floor(Math.random() * musicSnippets.length);
    return musicSnippets[currSnippet];
  }
}
