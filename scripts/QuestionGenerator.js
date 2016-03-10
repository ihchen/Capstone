/**
 * Script that generates a random question. Parameter should
 * be a list of integers that refer to indeces in the CSV file
 * over which the user would like to be tested.
 * @class QuestionGenerator
 * @constructor
 */
function QuestionGenerator(list) {

  // The array of questions
  var musicSnippets = makeMusicSnippets(list);

  /**
   * Builds an array of MusicSnippets
   * @method makeMusicSnippets
   * @private
   * @param {Integer[]} indices
   * @return {MusicSnippet[]} array of MusicSnippets
   */
  function makeMusicSnippets(array) {
    var ms = [];
    for (var i = 0; i < array.length; i++) {
      // console.log(data[list[i]][0] + " " + data[list[i]][1]);
      ms.push(new MusicSnippet(data[list[i]][0], data[list[i]][1],
        data[list[i]][2], data[list[i]][4]));
    }
    return ms;
  }

  /**
   * Picks a random MusicSnippet from the array.
   * @method getNextQuestion
   * @return {MusicSnippet} random MusicSnippet
   */
  this.getNextQuestion = function() {
    currSnippet = Math.floor(Math.random() * musicSnippets.length);
    return musicSnippets[currSnippet];
  }
}
