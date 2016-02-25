/*
 * Script that generates a random question. Parameter should
 * be a list of integers that refer to indeces in the CSV file
 * over which the user would like to be tested.
 */
function QuestionGenerator(list) {

  // The array of questions
  var musicSnippets = makeMusicSnippets(list);

  function makeMusicSnippets(array) {
    var ms = [];
    for (var i = 0; i < array.length; i++) {
      ms.push(new MusicSnippet(data[list[i]][2], data[list[i]][0]), data[list[i]][1]);
    }
    return ms;
  }

  /*
   *
   */
  this.getNextQuestion() {
    return musicSnippets[Math.floor(Math.random() * musicSnippets.length)];
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

}
