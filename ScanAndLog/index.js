let log = document.getElementById('log');

// Counting the cores for the amount of webworkers used by quagga
let dom_implemented = navigator.hardwareConcurrency;
let running = false;
let cores = 2;

function run() {
    if (running) return;
    running = true;

    navigator.getHardwareConcurrency(function(result) {
        running = false;
        cores = result;
    }, {use_cache: false});
}

if (dom_implemented || navigator.mimeTypes["application/x-pnacl"]) {
    run();
}

log.innerHTML += cores + " Cores detected<br>";
console.log(cores + " Cores detected");

// Initializing quagga

//TODO:  locate: false and nice square in the middle
// Quagga.init({
//     inputStream : {
//         name : "Live",
//         type : "LiveStream",
//         target: document.getElementById('videoStream'),
//         constraints: {
//             facingMode: 'environment'
//         }
//     },
//     decoder : {
//         readers : ["code_128_reader"]
//     },
//     locator: {
//         halfSample: false,
//         patchSize: "small",
//     },
//     numOfWorkers: cores,
//     locate: true,
// }, function(err) {
//     if (err) {
//         log.innerHTML += err + "<br>";
//         console.log(err);
//         return;
//     }
//     console.log("Initialization finished. Ready to start");
//     log.innerHTML += "Initialization finished. Ready to start<br>";
//     Quagga.start();
// });

$(function() {
    let resultCollector = Quagga.ResultCollector.create({
        capture: true,
        capacity: 20,
        blacklist: [{
            code: "WIWV8ETQZ1", format: "code_93"
        }, {
            code: "EH3C-%GU23RK3", format: "code_93"
        }, {
            code: "O308SIHQOXN5SA/PJ", format: "code_93"
        }, {
            code: "DG7Q$TV8JQ/EN", format: "code_93"
        }, {
            code: "VOFD1DB5A.1F6QU", format: "code_93"
        }, {
            code: "4SO64P4X8 U4YUU1T-", format: "code_93"
        }],
        filter: function (codeResult) {
            // only store results which match this constraint
            // e.g.: codeResult
            return true;
        }
    });

    let App = {
        init: function () {
            let self = this;

            Quagga.init(this.state, function (err) {
                if (err) {
                    return self.handleError(err);
                }
                // Quagga.registerResultCollector(resultCollector);
                console.log("Initialization finished. Ready to start");
                log.innerHTML += "Initialization finished. Ready to start<br>";
                Quagga.start();
            });
        },
        handleError: function (err) {
            console.log(err);
            log.innerHTML += err + "<br>";
        },
        _printCollectedResults: function () {
            let results = resultCollector.getResults(),
                $ul = $("#result_strip ul.collector");

            console.log(results);
            results.forEach(function (result) {

                log.innerHTML += result.codeResult.code + "<br>";
                let $li = $('<li><div class="thumbnail"><div class="imgWrapper"><img /></div><div class="caption"><h4 class="code"></h4></div></div></li>');

                $li.find("img").attr("src", result.frame);
                $li.find("h4.code").html(result.codeResult.code + " (" + result.codeResult.format + ")");
                $ul.prepend($li);
            });
        },
        state: {
            inputStream: {
                type: "LiveStream",
                constraints: {
                    width: {min: 640},
                    height: {min: 480},
                    facingMode: "environment",
                    aspectRatio: {min: 1, max: 2}
                }
            },
            locator: {
                patchSize: "small",
                halfSample: false
            },
            numOfWorkers: cores,
            frequency: 10,
            decoder: {
                readers: [{
                    format: "code_128_reader",
                    config: {}
                }]
            },
            locate: true
        },
        lastResult: null
    };

    App.init();

    Quagga.onProcessed(function (result) {
        let drawingCtx = Quagga.canvas.ctx.overlay,
            drawingCanvas = Quagga.canvas.dom.overlay;

        if (result) {
            if (result.boxes) {
                drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
                result.boxes.filter(function (box) {
                    return box !== result.box;
                }).forEach(function (box) {
                    Quagga.ImageDebug.drawPath(box, {x: 0, y: 1}, drawingCtx, {color: "green", lineWidth: 2});
                });
            }

            if (result.box) {
                Quagga.ImageDebug.drawPath(result.box, {x: 0, y: 1}, drawingCtx, {color: "#00F", lineWidth: 2});
            }

            if (result.codeResult && result.codeResult.code) {
                Quagga.ImageDebug.drawPath(result.line, {x: 'x', y: 'y'}, drawingCtx, {color: 'red', lineWidth: 3});
            }
        }
    });

    Quagga.onDetected(function (result) {
        let code = result.codeResult.code;
        document.getElementById('studentCode').value = code;

        if (App.lastResult !== code) {
            App.lastResult = code;
            let $node = null, canvas = Quagga.canvas.dom.image;

            $node = $('<li><div class="thumbnail"><div class="imgWrapper"><img /></div><div class="caption"><h4 class="code"></h4></div></div></li>');
            $node.find("img").attr("src", canvas.toDataURL());
            $node.find("h4.code").html(code);
            $("#result_strip ul.thumbnails").prepend($node);
        }
    });

});