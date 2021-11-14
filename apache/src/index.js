class Board {
  constructor(state = ["", "", "", "", "", "", "", "", ""]) {
    this.state = state;
  }

  isEmpty() {
    return this.state.every((cell) => !cell);
  }

  isFull() {
    return this.state.every((cell) => cell);
  }

  insert(symbol, position) {
    if (![0, 1, 2, 3, 4, 5, 6, 7, 8].includes(position)) {
      throw new Error("Cell index does not exist!");
    }
    if (!["X", "O"].includes(symbol)) {
      console.log(
        `Input char: '${symbol}', it need to be: 'X' or 'O'. ${
          symbol == "X" || symbol == "O"
        }`
      );
      throw new Error("The symbol can only be x or o!");
    }
    if (this.state[position]) {
      return false;
    }
    this.state[position] = symbol;
    return true;
  }

  getAvailableMoves() {
    const moves = [];
    this.state.forEach((cell, index) => {
      if (!cell) moves.push(index);
    });
    return moves;
  }

  isTerminal() {
    if (this.isEmpty()) return false;

    if (
      this.state[0] === this.state[1] &&
      this.state[0] === this.state[2] &&
      this.state[0]
    ) {
      return { winner: this.state[0], direction: "H", row: 1 };
    }
    if (
      this.state[3] === this.state[4] &&
      this.state[3] === this.state[5] &&
      this.state[3]
    ) {
      return { winner: this.state[3], direction: "H", row: 2 };
    }
    if (
      this.state[6] === this.state[7] &&
      this.state[6] === this.state[8] &&
      this.state[6]
    ) {
      return { winner: this.state[6], direction: "H", row: 3 };
    }

    if (
      this.state[0] === this.state[3] &&
      this.state[0] === this.state[6] &&
      this.state[0]
    ) {
      return { winner: this.state[0], direction: "V", column: 1 };
    }
    if (
      this.state[1] === this.state[4] &&
      this.state[1] === this.state[7] &&
      this.state[1]
    ) {
      return { winner: this.state[1], direction: "V", column: 2 };
    }
    if (
      this.state[2] === this.state[5] &&
      this.state[2] === this.state[8] &&
      this.state[2]
    ) {
      return { winner: this.state[2], direction: "V", column: 3 };
    }

    if (
      this.state[0] === this.state[4] &&
      this.state[0] === this.state[8] &&
      this.state[0]
    ) {
      return { winner: this.state[0], direction: "D", diagonal: "main" };
    }
    if (
      this.state[2] === this.state[4] &&
      this.state[2] === this.state[6] &&
      this.state[2]
    ) {
      return { winner: this.state[2], direction: "D", diagonal: "counter" };
    }

    if (this.isFull()) {
      return { winner: "draw" };
    }

    return false;
  }
}

class Player {
  constructor(maxDepth = -1) {
    this.maxDepth = maxDepth;
    this.nodesMap = new Map();
  }

  getBestMove(board, maximizing = true, callback = () => {}, depth = 0) {
    if (depth == 0) this.nodesMap.clear();

    if (board.isTerminal() || depth === this.maxDepth) {
      if (board.isTerminal().winner === "X") {
        return 100 - depth;
      } else if (board.isTerminal().winner === "O") {
        return -100 + depth;
      }
      return 0;
    }
    if (maximizing) {
      let best = -100;
      board.getAvailableMoves().forEach((index) => {
        const child = new Board([...board.state]);
        child.insert("X", index);
        const nodeValue = this.getBestMove(child, false, callback, depth + 1);
        best = Math.max(best, nodeValue);

        if (depth == 0) {
          const moves = this.nodesMap.has(nodeValue)
            ? `${this.nodesMap.get(nodeValue)},${index}`
            : index;
          this.nodesMap.set(nodeValue, moves);
        }
      });

      if (depth == 0) {
        let returnValue;
        if (typeof this.nodesMap.get(best) == "string") {
          const arr = this.nodesMap.get(best).split(",");
          const rand = Math.floor(Math.random() * arr.length);
          returnValue = arr[rand];
        } else {
          returnValue = this.nodesMap.get(best);
        }
        callback(returnValue);
        return returnValue;
      }
      return best;
    }

    if (!maximizing) {
      let best = 100;
      board.getAvailableMoves().forEach((index) => {
        const child = new Board([...board.state]);

        child.insert("O", index);

        let nodeValue = this.getBestMove(child, true, callback, depth + 1);

        best = Math.min(best, nodeValue);

        if (depth == 0) {
          const moves = this.nodesMap.has(nodeValue)
            ? this.nodesMap.get(nodeValue) + "," + index
            : index;
          this.nodesMap.set(nodeValue, moves);
        }
      });

      if (depth == 0) {
        let returnValue;
        if (typeof this.nodesMap.get(best) == "string") {
          const arr = this.nodesMap.get(best).split(",");
          const rand = Math.floor(Math.random() * arr.length);
          returnValue = arr[rand];
        } else {
          returnValue = this.nodesMap.get(best);
        }
        callback(returnValue);
        return returnValue;
      }
      return best;
    }
  }
}

class TicTacToe {
  #side;
  #mode;
  #modeDescribe;
  #field = new Board();
  #player1side;
  #player2side;
  #clock = true;
  #computer = new Player();

  constructor() {
    this.#side = document.querySelector('input[name="side"]:checked').value;
    this.#mode = document.querySelector('input[name="mode"]:checked').value;
    switch (this.#mode) {
      case "single":
        this.#modeDescribe = "Однопользовательская игра";
        this.#player1side = this.#side;
        this.#player2side = this.#player1side == "X" ? "O" : "X";
        break;
      case "local":
        this.#modeDescribe = "Локальная игра";
        this.#player1side = this.#side;
        this.#player2side = this.#player1side == "X" ? "O" : "X";
        break;
      case "multi":
        this.#modeDescribe = "Игра по сети";
        break;
    }
    document.querySelector(".game").innerHTML = "";
    this.#createField();
    if (this.#field.isEmpty() && this.#player1side == "O") {
      this.#addMoveComp();
      return;
    }
  }

  get getSide() {
    return this.#side;
  }

  get getMode() {
    return this.#mode;
  }

  #createField(newField = true, result = "") {
    document.querySelector(".game").innerHTML =
      '<div class="row">' +
      '<div class="col">' +
      `<p class="lead">Сторона: ${this.#side}</p>` +
      "</div>" +
      '<div class="col">' +
      `<p class="lead">Режим: ${this.#modeDescribe}</p>` +
      "</div>" +
      "</div>" +
      '<div class="field">';

    if (newField == true) {
      this.#field.state.forEach((e, i) => {
        document.querySelector(
          ".field"
        ).innerHTML += `<div id="block_${i}" class="block" onclick="t.addMove(${i})"><h1>${e}</h1></div>`;
      });
    } else {
      this.#field.state.forEach((e, i) => {
        document.querySelector(
          ".field"
        ).innerHTML += `<div id="block_${i}" class="block"><h1>${e}</h1></div>`;
      });
    }

    document.querySelector(".game").innerHTML += `${result}</div>`;
  }

  #checkWin(statusObj, side, role = "Игрок") {
    if (!statusObj) return;
    const { winner, direction, row, column, diagonal } = statusObj;
    var result = "";
    if (winner == "draw") {
      result = "<div><h2>Ничья!</h2></div>";
    } else if (winner.includes(side)) {
      result = `<div><h2>${role} (${side}) победил!</h2></div>`;
    }
    this.#createField(false, result);
  }

  #addMoveComp() {
    var tLoc = this.#computer.getBestMove(this.#field);
    document.getElementById(`block_${tLoc}`).firstChild.innerText =
      this.#player2side;
    if (this.#field.insert(this.#player2side, parseInt(tLoc))) {
      this.#checkWin(this.#field.isTerminal(), this.#player2side, "Компьютер");
    }
    this.#clock = true;
  }

  addMove(element) {
    if (this.#mode == "local") {
      if (this.#field.isEmpty() && this.#player1side == "O") {
        if (this.#field.insert(this.#player2side, element)) {
          document.getElementById(`block_${element}`).firstChild.innerText =
            this.#player2side;
          this.#checkWin(this.#field.isTerminal(), this.#player2side);
          this.#clock = true;
        }
      }
      if (this.#clock) {
        if (this.#field.insert(this.#player1side, element)) {
          document.getElementById(`block_${element}`).firstChild.innerText =
            this.#player1side;
          this.#checkWin(this.#field.isTerminal(), this.#player1side);
          this.#clock = false;
        }
      } else {
        if (this.#field.insert(this.#player2side, element)) {
          document.getElementById(`block_${element}`).firstChild.innerText =
            this.#player2side;
          this.#checkWin(this.#field.isTerminal(), this.#player2side);
          this.#clock = true;
        }
      }
    } else if (this.#mode == "single") {
      if (this.#clock == true) {
        if (this.#field.insert(this.#player1side, element)) {
          document.getElementById(`block_${element}`).firstChild.innerText =
            this.#player1side;
          this.#checkWin(this.#field.isTerminal(), this.#player1side);
          this.#clock = false;
          this.#addMoveComp();
        }
      }
    }
  }
}

var t;

function initGame() {
  t = new TicTacToe();
  console.log(t.getSide);
  console.log(t.getMode);
}
