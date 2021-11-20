class Board {
    constructor(state = ["", "", "", "", "", "", "", "", ""]) {
        this.state = state;
    }

    getDBboard() {
        var s = "";
        for (let cell of this.state) {
            s += cell !== "" ? cell : "1";
        }

        return s;
    }

    getTurn() {
        var x = (this.getDBboard().match(/X/g) || []).length;
        var o = (this.getDBboard().match(/O/g) || []).length;

        if (x > o) {
            return false;
        } else {
            return true;
        }
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
            return {
                winner: this.state[2],
                direction: "D",
                diagonal: "counter",
            };
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
                const nodeValue = this.getBestMove(
                    child,
                    false,
                    callback,
                    depth + 1
                );
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

                let nodeValue = this.getBestMove(
                    child,
                    true,
                    callback,
                    depth + 1
                );

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
    #login;
    #opponent;
    #url;
    #side;
    #mode;
    #modeDescribe;
    #field;
    #fieldDB;
    #player1side;
    #player2side;
    #winSide = "";
    #gameRunning = 1;
    #clock = true;
    #computer = new Player();
    #scoreAdd = 0;
    #draw = false;
    #scorePrint;

    constructor(fieldDB, login, url, side, mode, opponent = null) {
        if (side == 0) {
            this.#side = document.querySelector(
                'input[name="side"]:checked'
            ).value;
        } else {
            this.#side = side;
        }

        if (mode == 0) {
            this.#mode = document.querySelector(
                'input[name="mode"]:checked'
            ).value;
        } else {
            this.#mode = mode;
        }

        this.#login = login;
        this.#opponent = opponent;
        this.#fieldDB = fieldDB;
        this.#url = url;
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
        }
        document.querySelector(".game").innerHTML = "";
        this.createField();
        if (
            this.#field.isEmpty() &&
            this.#player1side == "O" &&
            this.#mode == "single"
        ) {
            this.#addMoveComp();
            return;
        }
    }

    #createDBField(newGame = false, exitGame = false, fromOld = false) {
        if (newGame == true) {
            if (exitGame == true) {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                });

                axios
                    .post("http://localhost:8000/game", {
                        saveField: "1",
                        side: this.#side,
                        mode: this.#mode,
                        winSide: this.#winSide,
                        gameRunning: this.#gameRunning,
                        newGame: true,
                        exitGame: true,
                        fromOld: false,
                        draw: this.#draw,
                    })
                    .then((response) => {
                        console.log("from handle submit", response);
                    });
            } else {
                if (fromOld == false) {
                    $.ajaxSetup({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                    });

                    axios
                        .post("http://localhost:8000/game", {
                            saveField: "1",
                            side: this.#side,
                            mode: this.#mode,
                            winSide: this.#winSide,
                            gameRunning: this.#gameRunning,
                            newGame: true,
                            exitGame: false,
                            fromOld: false,
                            draw: this.#draw,
                        })
                        .then((response) => {
                            console.log("sus", response);
                        });
                } else if (fromOld == true) {
                    $.ajaxSetup({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                    });

                    axios
                        .post("http://localhost:8000/game", {
                            saveField: "1",
                            side: this.#side,
                            mode: this.#mode,
                            winSide: this.#winSide,
                            gameRunning: this.#gameRunning,
                            newGame: true,
                            exitGame: false,
                            fromOld: true,
                            draw: this.#draw,
                        })
                        .then((response) => {
                            console.log("From old!!!", response);
                        });
                }
            }
        } else {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });

            axios
                .post("http://localhost:8000/game", {
                    saveField: "1",
                    side: this.#side,
                    field: this.#field.getDBboard(),
                    mode: this.#mode,
                    winSide: this.#winSide,
                    gameRunning: -1,
                    newGame: false,
                })
                .then((response) => {
                    console.log("from handle submit", response);
                });
        }
    }

    get getSide() {
        return this.#side;
    }

    get getMode() {
        return this.#mode;
    }

    #toArr(s) {
        var a = [];
        for (let letter of s) {
            a.push(letter !== "1" ? letter : "");
        }

        return a;
    }

    restart() {
        console.log(`Field = ${this.#fieldDB}`);
        this.createField(true, ``, true);
    }

    createField(newField = true, result = "", fromOld = false) {
        var lside = this.#mode == "local" ? "" : `Сторона: ${this.#side}`;

        document.querySelector(".game").innerHTML =
            '<div class="row">' +
            '<div class="col">' +
            `<p class="lead text-center">Режим: ${this.#modeDescribe}</p>` +
            "</div>" +
            '<div class="col">' +
            `<p class="lead text-center">${lside}</p>` +
            "</div>" +
            "</div>" +
            '<div class="field">';

        if (newField == true) {
            if (this.#fieldDB == 0) {
                if (fromOld == true) {
                    this.#createDBField(true, false, true);
                    this.#field = new Board();
                    //this.#createDBField(true);
                } else {
                    this.#field = new Board();
                    this.#createDBField(true);
                }
            } else {
                this.#field = new Board(this.#toArr(this.#fieldDB));
            }

            this.#clock = this.#field.getTurn();
            this.#field.state.forEach((e, i) => {
                document.querySelector(
                    ".field"
                ).innerHTML += `<div id="block_${i}" class="block" onclick="t.addMove(${i})"><h1>${e}</h1></div>`;
            });
            document.querySelector(".field").innerHTML += `</div>`;
            document.querySelector(
                ".game"
            ).innerHTML += `<div class="container"><div class="row" id="gameBtns">`;
            document.querySelector(
                "#gameBtns"
            ).innerHTML += `<div class="col text-center"><button type="button" class="btn btn-danger" onclick="t.openMenu()">Выйти из игры</button></div>`;
            document.querySelector(".game").innerHTML += `</div></div>`;
        } else {
            this.#field.state.forEach((e, i) => {
                document.querySelector(
                    ".field"
                ).innerHTML += `<div id="block_${i}" class="block"><h1>${e}</h1></div>`;
            });
            document.querySelector(".field").innerHTML += `</div>`;
            document.querySelector(".game").innerHTML += `<div>${result}</div>`;
            if (result != "<div><h2 class='text-center'>Ничья!</h2></div>") {
                if (this.#player1side == this.#scorePrint) {
                    document.querySelector(
                        ".game"
                    ).innerHTML += `<div><h2 class="text-center">Поздравляем, вам начислено 3 очка!</h2></div>`;
                } else {
                    document.querySelector(
                        ".game"
                    ).innerHTML += `<div><h2 class="text-center">Проигрыш, вы потеряли 1 очко!</h2></div>`;
                }
            }
            document.querySelector(
                ".game"
            ).innerHTML += `<div class="container"><div class="row" id="gameBtns">`;
            document.querySelector(
                "#gameBtns"
            ).innerHTML += `<div class="col text-center"><button type="button" class="btn btn-success" onclick="t.restart()">Перезапустить</button></div>`;
            document.querySelector(
                "#gameBtns"
            ).innerHTML += `<div class="col text-center"><button type="button" class="btn btn-danger" onclick="t.openMenu()">Выйти из игры</button></div>`;
            document.querySelector(".game").innerHTML += `</div></div>`;
        }
    }

    openMenu() {
        this.#createDBField(true, true);
        document.querySelector(
            ".game"
        ).innerHTML = `<p class="lead text-center">Выберите сторону</p>
        <div class="row">
            <div class="col text-center">
                <input type="radio" class="btn-check" name="side" id="cross-ttt" value="X" autocomplete="off" checked>
                <label class="btn btn-outline-dark btn-lg rounded-circle" for="cross-ttt">X</label>
            </div>
            <div class="col text-center">
                <input type="radio" class="btn-check" name="side" id="circle-ttt" value="O" autocomplete="off">
                <label class="btn btn-outline-dark btn-lg rounded-circle" for="circle-ttt">O</label>
            </div>
        </div>
        <p class="lead text-center">Режим игры</p>
        <div class="row">
            <div class="col text-center">
                <input type="radio" class="btn-check" name="mode" id="single" value="single" autocomplete="off" checked>
                <label class="btn btn-outline-dark btn-lg" for="single">Одиночная игра</label>
            </div>
            <div class="col text-center">
                <input type="radio" class="btn-check" name="mode" id="local" value="local" autocomplete="off">
                <label class="btn btn-outline-dark btn-lg" for="local">Локальная игра</label>
            </div>
        </div>
        <div class="text-center m-3">
            <button type="button" class="btn btn-success btn-lg" onclick="initGame()">
                <h1>Начать игру</h1>
            </button>
        </div>`;
    }

    #checkWin(statusObj, side, role = "Игрок", name = "") {
        if (!statusObj) return;
        const { winner, direction, row, column, diagonal } = statusObj;
        var result = "";
        if (winner == "draw") {
            result = "<div><h2 class='text-center'>Ничья!</h2></div>";
            this.#draw = true;
        } else if (winner.includes(side)) {
            if (role == "Игрок") {
                this.#winSide = side;
            }
            this.#scorePrint = side;
            result = `<div><h2 class="text-center mb-2">${role} ${name} (${side}) победил!</h2></div>`;
        }
        this.#gameRunning = 0;
        this.createField(false, result);
    }

    #addMoveComp() {
        var tLoc = this.#computer.getBestMove(this.#field);
        document.getElementById(`block_${tLoc}`).firstChild.innerText =
            this.#player2side;
        if (this.#field.insert(this.#player2side, parseInt(tLoc))) {
            this.#checkWin(
                this.#field.isTerminal(),
                this.#player2side,
                "Компьютер"
            );
        }
        this.#clock = true;
        this.#createDBField();
    }

    addMove(element) {
        if (this.#mode == "local") {
            if (this.#field.getTurn()) {
                if (this.#field.insert("X", element)) {
                    document.getElementById(
                        `block_${element}`
                    ).firstChild.innerText = "X";
                    this.#checkWin(this.#field.isTerminal(), "X");
                    this.#clock = false;
                    this.#createDBField();
                }
            } else {
                if (this.#field.insert("O", element)) {
                    document.getElementById(
                        `block_${element}`
                    ).firstChild.innerText = "O";
                    this.#checkWin(this.#field.isTerminal(), "O");
                    this.#clock = false;
                    this.#createDBField();
                }
            }
        } else if (this.#mode == "single") {
            if (this.#clock == true) {
                if (this.#field.insert(this.#player1side, element)) {
                    document.getElementById(
                        `block_${element}`
                    ).firstChild.innerText = this.#player1side;
                    this.#checkWin(this.#field.isTerminal(), this.#player1side);
                    this.#clock = false;
                    this.#createDBField();
                    this.#addMoveComp();
                }
            }
        }
    }
}
