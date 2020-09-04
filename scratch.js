function loadScript(url, callback) {
    // Adding the script tag to the head as suggested before
    var head = document.head;
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.src = url;

    // Then bind the event to the callback function.
    // There are several events for cross browser compatibility.
    script.onreadystatechange = callback;
    script.onload = callback;

    // Fire the loading
    head.appendChild(script);
}

function loadCSS(url, id) {
    //    console.log("attempting to inject: " + id + " from " + url);
    var head = document.getElementsByTagName("head")[0];
    var element = document.getElementById(id + "-css");
    var has_element = element != null;

    if (!has_element) {
        //        console.log(id + " doesn't yet exist, loading now");
        var css = document.createElement("link");
        css.setAttribute("id", id + "-css");
        css.setAttribute("rel", "stylesheet");
        css.setAttribute("type", "text/css");
        css.setAttribute("href", url);
        head.appendChild(css);
    }
}

function lJ(callback) {
    var urlParams = new URLSearchParams(window.location.search);
    var xobj = new XMLHttpRequest();
    xobj.overrideMimeType("application/json");
    xobj.open("GET", window.location.pathname + "/layout", true);
    xobj.withCredentials = true;
    xobj.setRequestHeader(
        "Authorization",
        "Bearer " + urlParams.get("access_token")
    );
    xobj.onreadystatechange = function () {
        if (xobj.readyState == 4 && xobj.status == "200") {
            callback(xobj.responseText);
        }
    };
    xobj.send(null);
}

// This runs once we have jQuery available
function toRun() {
    // This is a helper, leave it here
    $.fn.onAvailable = function (selector, fn) {
        var timer;
        if (this.length > 0) {
            fn.call(this);
        } else {
            timer = setInterval(function () {
                if ($(selector).length > 0) {
                    fn.call($(selector));
                    clearInterval(timer);
                }
            }, 50);
        }
    };

    // DON'T call this directly, use the wrappers below!
    function handleJson(operation, returnCallback, newJson) {

        $.fn.onAvailable = function (selector, fn) {
            var timer;
            if (this.length > 0) {
                fn.call(this);
            } else {
                timer = setInterval(function () {
                    if ($(selector).length > 0) {
                        fn.call($(selector));
                        clearInterval(timer);
                    }
                }, 500);
            }
        };

        $("head style")
            .last()
            .after(
                '<style id="popup-container" type="text/css">.popup-container {opacity: .1;}</style>'
            );
        // $("i:contains(settings)").css("background-color", "red");
        $("i:contains(settings)").one("click", function () {
            console.log("on click 1");
            $("div.navLink:contains(Advanced)").onAvailable(
                "div.navLink:contains(Advanced)",
                function () {
                    console.log("Advanced is now available");
                    $("div.navLink:contains(Advanced)").one("click", function () {
                        console.log("on click 2");
                        $("textarea.exportBox").onAvailable(
                            "textarea.exportBox",
                            function () {
                                console.log("textarea.exportBox");
                                var eb = $("textarea.exportBox");
                                if (operation === "get") {
                                    var r = eb.val();
                                    $("div.navLink:contains(X)").trigger("click");
                                    $("#popup-container").remove();
                                    returnCallback(r);
                                } else {
                                    // console.log("New JSON: ", newJson);
                                    eb.val(newJson);
                                    // To "commit" the new JSON this event needs to fire in the DOM:
                                    eb[0].dispatchEvent(new Event("input"));
                                    // Now "click" the save button
                                    $("div.inline-block:contains(Save Layout JSON)").trigger(
                                        "click"
                                    );
                                    $("div.importError").onAvailable(
                                        "div.importError",
                                        function () {
                                            $("div.navLink:contains(X)").trigger("click");
                                            $("#popup-container").remove();
                                        }
                                    );
                                }
                            }
                        );
                    });
                    $("div.navLink:contains(Advanced)").trigger("click");
                }
            );
        });
        $("i:contains(settings)").trigger("click");
    }
    // Function wrapper
    function getJson(returnCallback) {
        handleJson("get", returnCallback, "");
    }
    // Function wrapper
    function updateJson(jsonData) {
        handleJson("update", null, jsonData);
    }

    // EXAMPLE USAGE:
    // ************ This is just an example of first retrieving the current JSON from the DOM
    // ************ then updating it with a small change.

/*
    getJson(function (returnedJson) {
        var co = JSON.parse(returnedJson);
        if (co["fontSize"] !== 13) {
            co["fontSize"] = 13;
        } else if (co["fontSize"] == 13) {
            co["fontSize"] = 16;
        }
        updateJson(JSON.stringify(co, null, 2));
        // console.log(JSON.stringify(co, null, 2));
    });

 */
    // ************ End of example

    // EXAMPLE: This is how you would replace the current JSON if you have it as an object:
    // updateJson(JSON.stringify({ myData: "hello" }, null, 2));

    //    loadScript("https://cdn.jsdelivr.net/npm/micromodal/dist/micromodal.min.js", "initModal");

    function initGridstack() {
        MicroModal.init({ debugMode: true });
        //        MicroModal.show("modal-1");

        // this onload is assuming jquery is loaded before gridstack, but this
        // should be improved upon to ensure everything is loaded before we start

        console.log("gridstack and jquery are loaded!");

        var grid_tiles = [];

        lJ(function (response) {
            var inputJSON = JSON.parse(response);
            console.log(inputJSON.tiles, "ORIG JSON TILES");
            for (let [index, tile] of Object.entries(inputJSON.tiles)) {
                if (
                    inputJSON.tiles[index]["template"] != "smartly" &&
                    inputJSON.tiles[index]["templateExtra"] != "javascript"
                ) {
                    // extract innerHTML from each tile, except for our JSinject tile
                    var tile_element = $("#tile-" + tile.id);

                    // inject innerHTML for each tile
                    // screw it, let's pull the tile html in with id and everything..  who cares if there are duplicate ids (for now).
                  //  inputJSON.tiles[index]["innerHTML"] = tile_element[0].outerHTML;


                    grid_tiles.push({
                        x: inputJSON.tiles[index]["col"] - 1,
                        y: inputJSON.tiles[index]["row"] - 1,
                        width: inputJSON.tiles[index]["colSpan"],
                        height: inputJSON.tiles[index]["rowSpan"],
                        id: tile.id,
                        innerHTML: tile_element[0].outerHTML
                    });

                    // convert to gridstack x/y/width/height format and add to the tile object.
                    // we'll clobber the actual values before saving and delete these temporary properties

             /*       inputJSON.tiles[index]["width"] = inputJSON.tiles[index]["colSpan"];
                    inputJSON.tiles[index]["height"] = inputJSON.tiles[index]["rowSpan"];
                    inputJSON.tiles[index]["x"] = inputJSON.tiles[index]["col"] - 1;
                    inputJSON.tiles[index]["y"] = inputJSON.tiles[index]["row"] - 1;

              */
                } else {
                    // @TODO: need a way to prevent gridstack from rendering these without actually removing them
                 //   delete inputJSON.tiles[index];
                }
            }

            //            console.log(inputJSON.tiles, "tiles modded");

            // because gridstack uses variable-width tiles based on container width, we need
            // to calculate a rough width based on colWidth and gridGap (will need to update
            // gridgap css to be specific on width gap, so using 20 for now

            $("<style>")
                .prop("type", "text/css")
                .html(
                    ".grid-stack>.grid-stack-item>.grid-stack-item-content { left: " +
                    inputJSON.gridGap +
                    "px; right: 0; }"
                )
                .appendTo("head");

            var stack_width = (inputJSON.colWidth + inputJSON.gridGap) * 12;
            $(".grid-stack").css({ width: stack_width + "px" });

            var grid = GridStack.init({
                float: true,
                cellHeight: inputJSON.rowHeight,
                //   column: 12, //inputJSON.cols,
                //disableResize: true,
                disableOneColumnMode: true,
                verticalMargin: inputJSON.gridGap,
                resizable: true,
                auto: true,
                // itemClass: ['grid-stack-item', 'css']
            });

            // standard gridstack utility functions and logging

            grid.on("added", function (e, items) {
                log("added ", items);
            });
            grid.on("removed", function (e, items) {
                log("removed ", items);
            });
            grid.on("change", function (e, items) {
                log("change ", items);
            });
            function log(type, items) {
                var str = "";
                items.forEach(function (item) {
                    str += " (x,y)=" + item.x + "," + item.y;
                });
                console.log(type + items.length + " items." + str);
            }

            /*  the minimum data gridstack expects
                            var serializedData = [
                                {x: 0, y: 0, width: 2, height: 2},
                                {x: 3, y: 1, width: 1, height: 2},
                                {x: 4, y: 1, width: 1, height: 1},
                                {x: 2, y: 3, width: 3, height: 1},
                                {x: 1, y: 3, width: 1, height: 1}
                            ];
                 */

            loadGrid = function () {
                grid.removeAll();
                var items = GridStack.Utils.sort(grid_tiles); //inputJSON.tiles); //serializedData);
                grid.batchUpdate();
                items.forEach(function (item) {
                    grid.addWidget(
                        '<div><div class="grid-stack-item-content">' +
                        item.innerHTML +
                        "</div></div>",
                        item
                    );
                });
                grid.commit();
            };

            saveGrid = function () {

                grid.engine.nodes.forEach(function (node) {
                    index = null;
                    index = inputJSON.tiles.findIndex(x => x.id == node.id);

                    if (index) {
                        inputJSON.tiles[index]["colSpan"] = node.width;
                        inputJSON.tiles[index]["rowSpan"] = node.height;
                        inputJSON.tiles[index]["col"] = node.x + 1;
                        inputJSON.tiles[index]["row"] = node.y + 1;
                    }
               });

               console.log(inputJSON.tiles, "SAVED JSON TILES");

               updateJson(JSON.stringify(inputJSON, null, 2));

               //console.log(outgoing_tiles, "SAVED TILES");
            };

            clearGrid = function () {
               grid.removeAll();
            };

        loadGrid();

        }); // callback for lJ()
    }

    loadScript("https://cdn.jsdelivr.net/npm/gridstack@1.1.2/dist/gridstack.all.js", initGridstack);

}

        loadCSS("https://cdn.jsdelivr.net/npm/gridstack@1.1.2/dist/gridstack.min.css", "gridstack");

        loadCSS("http://localhost:8888/scratch.css", "scratch");

        loadScript("/ui2/js/jquery-3.4.0.min.js", function () {
            loadScript(
            "https://cdn.jsdelivr.net/npm/micromodal/dist/micromodal.min.js",
            toRun
        );
    });

/* I haven't even done a once over to clean either of these up.  We'll minify them both after cleaning.

"customHTML": "<div id=\"open-modal-btn\"><a href=\"#\" data-micromodal-trigger=\"modal-1\">Open Modal</a></div>\r\n<div id=\"modal-1\" class=\"modal\" aria-hidden=\"true\">\r\n    <div tabindex=\"-1\" data-micromodal-close>\r\n        <div role=\"dialog\" aria-modal=\"true\" aria-labelledby=\"modal-1-title\" >\r\n            <header>\r\n                <h2 id=\"modal-1-title\">Modal Title2</h2>\r\n                <button class=\"modal-close-btn\" aria-label=\"Close modal\" data-micromodal-close>Close Me</button><img /> <img />\r\n                <a onclick=\"saveGrid()\" class=\"btn btn-primary\" href=\"#\">Save</a>\r\n                <a onclick=\"loadGrid()\" class=\"btn btn-primary\" href=\"#\">Load</a>\r\n                <a onclick=\"clearGrid()\" class=\"btn btn-primary\" href=\"#\">Clear</a>\r\n                <div class=\"grid-stack\"></div>\r\n                <textarea id=\"saved-data\" cols=\"100\" rows=\"2\" readonly=\"readonly\"></textarea>\r\n            </header>\r\n            <div id=\"modal-1-content\">  <div class=\"grid-stack\"></div></div>\r\n        </div>\r\n    </div>\r\n</div>",
"customJS": "var body = document.getElementsByTagName(\"body\")[0];\r\nvar script = document.getElementById(\"inserted-body-script\");\r\nvar hasScript = script != null;\r\nif(!hasScript) {\r\n    script = document.createElement(\"script\");\r\n    script.setAttribute(\"id\", \"inserted-body-script\")\r\n}\r\n\r\nscript.type = \"text/javascript\";\r\n\r\nscript.src = \"http://localhost:8888/scratch.js\";\r\n//script.src = \"https://cdn.jsdelivr.net/npm/micromodal/dist/micromodal.min.js\";\r\nif(!hasScript) {\r\n    body.appendChild(script);\r\n//alert(6);\r\n} else {\r\n//    MicroModal.show(\"modal-1\");\r\n//alert(10);\r\n}\r\n\r\nvar div = document.getElementById(\"inserted-body-html\");\r\nvar hasDiv = div != null;\r\nif(!hasDiv) {\r\n    div = document.createElement(\"div\")\r\n    div.setAttribute(\"id\", \"inserted-body-html\")\r\n}\r\n\r\ndiv.innerHTML = \"\";\r\nif(!hasDiv) {\r\n    body.prepend(div);\r\n}\r\nscript.onload = function() {\r\n//    MicroModal.init({debugMode: true});\r\n//    MicroModal.show(\"modal-1\");\r\n//    alert(2);\r\n}",

*/