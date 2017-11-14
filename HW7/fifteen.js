/* 
   CSE 154 
   Assignment 7
   fifteen.js
   
   Yu Fu
   
   The program controls the behavior of 15-puzzle.
   It creates the puzzle board, allows the user to make move,
   controls the behavior of movable tiles, and enable shuffling
   of the tiles.
*/

(function(){
    "use strict";

    var BOARD_DIMENSION = 4;    /* Dimension of the puzzle board. */
    var TILE_SIZE = 100;        /* Size of each tile. */
    var emptyRow;               /* Row of empty tile. */
    var emptyCol;               /* Column of empty tile. */

    /* Create the puzzle board and enable onclick behavior of each tile. */
    window.onload = function() {
        createBoard();
        var shuffleButton = document.getElementById("shufflebutton");
        shuffleButton.onclick = shuffle;
    };

    /* Initialize the board containing the tiles, in the correct order. */
    function createBoard() {
        emptyRow = 3;
        emptyCol = 3;
        var puzzleArea = document.getElementById("puzzlearea");
        for (var i = 0; i < BOARD_DIMENSION * BOARD_DIMENSION - 1; i++) {
            var col = i % BOARD_DIMENSION;
            var row = Math.floor(i / BOARD_DIMENSION);
            puzzleArea.appendChild(createTile(i + 1, col, row));
        }
        updateMovable();
    }

    /* Create the tile in given column and row and given number. */
    function createTile(num, col, row) {
        var tile = document.createElement("div");
        var xPosition = col * TILE_SIZE;
        var yPosition = row * TILE_SIZE;

        tile.classList.add("tile");
        tile.id = "tile_" + col + "_" + row;
        tile.innerHTML = num;
        tile.style.left = xPosition + "px";
        tile.style.top = yPosition + "px";
        tile.style.backgroundPosition = (-1 * xPosition) + "px " + (-1 * yPosition) + "px";
        tile.onclick = clickAndMove;

        return tile;
    }

    /* Move the tile based if it has class "movable" when clicked on it. */
    function clickAndMove() {
        if (this.classList.contains("movable")) {
            move(this);
        }
    }

    /* Move the given tile to the empty space. */
    function move(tile) {
        var colAndRow = getColAndRow(tile);

        var newXPosition = emptyCol * TILE_SIZE;
        var newYPosition = emptyRow * TILE_SIZE;
        tile.style.left = newXPosition + "px";
        tile.style.top = newYPosition + "px";
        tile.id = "tile_" + emptyCol + "_" + emptyRow;

        emptyCol = colAndRow[0];
        emptyRow = colAndRow[1];

        updateMovable();
    }

    /* Test each tiles in the board, and update their movable status. */
    function updateMovable() {
        var tiles = document.querySelectorAll(".tile");
        for (var i = tiles.length - 1; i >= 0; i--) {
            var tile = tiles[i];
            if (isMovable(tile)) {
                tile.classList.add("movable");
            } else {
                tile.classList.remove("movable");
            }
        }
    }

    /* Return true if a tile is movable. */
    function isMovable(tile) {
        var colAndRow = getColAndRow(tile);
        return (Math.abs(colAndRow[0] - emptyCol) == 1 && colAndRow[1] == emptyRow) ||
               (Math.abs(colAndRow[1] - emptyRow) == 1 && colAndRow[0] == emptyCol);
    }

    /* Return an array in the form [col, row] of a given tile. */
    function getColAndRow(tile) {
        var row = parseInt(tile.style.top) / 100;
        var col = parseInt(tile.style.left) / 100;
        var colAndRow = [col, row];
        return colAndRow;
    }

    /* Shuffle the board by making 1000 random moves. */
    function shuffle() {
        for (var i = 0; i < 1000; i++) {
            var movableTileList = movableTiles();
            var randIndex = Math.floor(Math.random() * movableTileList.length);
            move(movableTileList[randIndex]);
        }
    }

    /* Return an array of movable tiles of empty space. */
    function movableTiles() {
        var tiles = document.querySelectorAll(".tile");
        var movableTileList = [];
        for (var i = tiles.length - 1; i >= 0; i--) {
            if (tiles[i].classList.contains("movable")) {
                movableTileList.push(tiles[i]);
            }
        }
        return movableTileList;
    }
})();