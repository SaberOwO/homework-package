/* Yu Fu
   CSE 154, Spring 2015
   Assignment 6
    
   speedreader.js

   The program reads text from user input, split them into
   words, and show each word on the screen based on the
   user's choice of speed and font size.
*/

(function() {
    "use strict";

    window.onload = function() {
        var startButton = document.getElementById("start");
        startButton.onclick = start;

        var stopButton = document.getElementById("stop");
        stopButton.onclick = stop;

        var speedSelector = document.getElementById("speedSelector");
        speedSelector.onchange = changeSpeed;

        var sizeButtons = document.querySelectorAll("input[type='radio'][name='size']");
        for (var i = sizeButtons.length - 1; i >= 0; i--) {
            sizeButtons[i].onclick = changeFontSize;
        }
    };

    var frameSet = [];               /* Array of word frames. */
    var frameCount = 0;              /* The index of current showing word frame. */
    var frameDelayTime = 171;        /* The time interval between word frames. */
    var frameTimer = null;           /* The timer controling the animation. */

    /* Read text from textarea and start the animation.
       Also disable start button and enable stop button. */
    function start() {
        document.getElementById("stop").disabled = false;
        document.getElementById("start").disabled = true;
        frameSet = getText();
        frameTimer = setInterval(playNext, frameDelayTime);
    }

    /* Change the speed by altering the time interval between frames
       based on user's selection. */
    function changeSpeed() {
        var speeds = [300, 200, 171, 150, 133, 120];
        var index = document.getElementById("speedSelector").selectedIndex;
        frameDelayTime = speeds[index];
        if (frameTimer !== null) {
            clearInterval(frameTimer);
            frameTimer = setInterval(playNext, frameDelayTime);
        }
    }

    /* Change the font in the reader with given size selection. */
    function changeFontSize() {
        var reader = document.getElementById("reader");
        if (document.getElementById("size_medium").checked) {
            reader.style.fontSize = "36pt";
        } else if (document.getElementById("size_big").checked) {
            reader.style.fontSize = "48pt";
        } else if (document.getElementById("size_bigger").checked) {
            reader.style.fontSize = "60pt";
        }
    }

    /* Display the next frame in the frameset. */
    function playNext() {
        document.getElementById("readerText").innerHTML = frameSet[frameCount];
        frameCount = frameCount + 1;
        if (frameCount >= frameSet.length) {
            stop();
        }
    }

    /* Return a frame set from the given text in input textarea.
       Each frame is divided in the input by white space.
       If a word frame ends with punctuation, it appears twice in the set. */
    function getText() {
        var inputWords = document.getElementById("inputText").value.split(/\s+/);
        var outputWords = [];
        for (var i = 0; i < inputWords.length; i++) {
            if (endsWithPunctuation(inputWords[i])) {
                var wordWithoutPunctuation = inputWords[i].substring(0, inputWords[i].length - 1);
                if (wordWithoutPunctuation.length > 0) {
                    outputWords.push(wordWithoutPunctuation);
                    outputWords.push(wordWithoutPunctuation);
                }
            } else {
                outputWords.push(inputWords[i]);
            }
        }
        return outputWords;
    }

    /* Return whether the given string is ended with punctuation. */
    function endsWithPunctuation(str) {
        var punctuations = [",", ".", "!", "?", ";", ":"];
        for (var i = punctuations.length - 1; i >= 0; i--) {
            if (str.substr(str.length - 1) === punctuations[i]) {
                return true;
            }
        }
        return false;
    }

    /* Stop running the animation, disable stop button,
       enable start button, set the div empty, 
       initialize all related variables. */
    function stop() {
        document.getElementById("stop").disabled = true;
        document.getElementById("start").disabled = false;
        clearInterval(frameTimer);
        document.getElementById("readerText").innerHTML = "";
        frameSet = [];
        frameCount = 0;
    }

}());