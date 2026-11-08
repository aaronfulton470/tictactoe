//Variables
let boardState = ["", "", "", "", "", "", "", "", ""];
let currentPlayer = "X";
let gameActive = true;
const statusDisplay = document.getElementById("status");
const resetBtn = document.getElementById("reset-btn");
const playerSymbolSelect = document.getElementById("player-symbol");


const winningConditions = [
    [0, 1, 2],
    [3, 4, 5],
    [6, 7, 8],
    [0, 3, 6],
    [1, 4, 7],
    [2, 5, 8],
    [0, 4, 8],
    [2, 4, 6]   
];

const cells = document.querySelectorAll(".cell");

//Function for handling cell clicks
function handleCellClick(event) {

  //Function Variables
  const clickedCell = event.target;
  const clickedCellIndex = parseInt(clickedCell.id.replace("cell-", ""));

  //Check to ensure the cell is empty and the game is active
  if (boardState[clickedCellIndex] !== "" || gameActive === false) {
    return;
  }
  
  boardState[clickedCellIndex] = currentPlayer;
  clickedCell.textContent = currentPlayer;

  if (playerSymbolSelect) {
    playerSymbolSelect.disabled = true;
  }

  //Function call to check for a winner or draw
  checkResult();
}


//Function to check for a winner or draw
function checkResult() {
  let roundWon = false;

  for (let i = 0; i < winningConditions.length; i++) {
    const winCondition = winningConditions[i];
    const a = boardState[winCondition[0]];
    const b = boardState[winCondition[1]];
    const c = boardState[winCondition[2]];

    if (a === "" || b === "" || c === "") {
      continue;
    }

    if (a === b && b === c) {
      roundWon = true;
      break;
    }
}
    // Conditional check for a win
    if (roundWon) {
      statusDisplay.textContent = `Player ${currentPlayer} Wins!`;
      gameActive = false;

      const chosenSymbol = playerSymbolSelect ? playerSymbolSelect.value : "X";
      const outcome = (currentPlayer === chosenSymbol) ? "win" : "loss";
      recordMatch(outcome);

      return;
    }

    // Conditional check for a draw
    if (boardState.includes("") === false) {
      statusDisplay.textContent = "Draw!";
      gameActive = false;

      recordMatch("draw");
      return;
    }

    // Switch the current player
    if (currentPlayer === "X") {
        currentPlayer = "O";
      } else {
        currentPlayer = "X";
      }

    // Display the current player's turn  
    statusDisplay.textContent = `Player ${currentPlayer}'s Turn`;
}

// Function to reset the game
function resetGame() {
  boardState = ["", "", "", "", "", "", "", "", ""];
  currentPlayer = "X";
  gameActive = true;
  statusDisplay.textContent = `Player ${currentPlayer}'s Turn`;
  cells.forEach(cell => cell.textContent = "");


if (playerSymbolSelect) {
  playerSymbolSelect.disabled = false;
  }
}

// Event Listeners
cells.forEach((cell, index) => {
  cell.addEventListener("click", handleCellClick);
});

resetBtn.addEventListener("click", resetGame);

//Sends match outcome to the server
function recordMatch(outcome) {
  fetch("record_match.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({ result:outcome })
  })
  .then(response => response.json())
  .then(data => {
    console.log("Match recorded:", data);
  })
  .catch(error => {
    console.error("Error recording match:", error);
  });
}