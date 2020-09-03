loadScript("https://code.jquery.com/jquery-3.4.1.min.js", "jquery");
//loadScript("https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js", "jquery-touchpunch");
loadScript("https://cdn.jsdelivr.net/npm/gridstack@1.1.2/dist/gridstack.all.js", "gridstack");
loadScript("https://cdn.jsdelivr.net/npm/micromodal/dist/micromodal.min.js", "micromodal");
loadCSS("https://cdn.jsdelivr.net/npm/gridstack@1.1.2/dist/gridstack.min.css", "gridstack");
//loadCSS("https://gridstackjs.com/demo/demo.css", "gridstackdemo");
loadCSS("http://localhost:8888/scratch.css", "scratch");

//loadScript("https://getbootstrap.com/docs/4.3/dist/js/bootstrap.bundle.min.js", "bootstrap");
//loadScript("", "");
//loadScript("", "");
//loadScript("", "");

// as gridstack was loaded via loadScript() we'll need to get access to it
var script_jquery = document.getElementById("gridstack-script");
script_jquery.onload = function() {
    $( document ).ready(function() {

        // this onload is assuming jquery is loaded before gridstack, but this
        // should be improved upon to ensure everything is loaded before we start

        console.log( "gridstack and jquery are loaded!" );

        lJ(function(response) {
            var inputJSON = JSON.parse(response);

            for (let [index, tile] of Object.entries(inputJSON.tiles)) {

                if (inputJSON.tiles[index]['template'] != 'smartly' && inputJSON.tiles[index]['templateExtra'] != 'javascript') {

                    // extract innerHTML from each tile, except for our JSinject tile
                    var tile_element = $('#tile-' + tile.id);

                    // inject innerHTML for each tile
                    // screw it, let's pull the tile html in with id and everything..  who cares if there are duplicate ids (for now).
                    inputJSON.tiles[index]['innerHTML'] = tile_element[0].outerHTML;

                    // convert to gridstack x/y/width/height format and add to the tile object.
                    // we'll clobber the actual values before saving and delete these temporary properties
                    inputJSON.tiles[index]['width'] = inputJSON.tiles[index]['colSpan'] ;
                    inputJSON.tiles[index]['height'] = inputJSON.tiles[index]['rowSpan'] ;
                    inputJSON.tiles[index]['x'] = inputJSON.tiles[index]['col'] - 1;
                    inputJSON.tiles[index]['y'] = inputJSON.tiles[index]['row'] - 1;
                } else {
                    // @TODO: need a way to prevent gridstack from rendering these without actually removing them
                    delete inputJSON.tiles[index];
                }
            }

//            console.log(inputJSON.tiles, "tiles modded");

            // because gridstack uses variable-width tiles based on container width, we need
            // to calculate a rough width based on colWidth and gridGap (will need to update
            // gridgap css to be specific on width gap, so using 20 for now

            var stack_width = ((inputJSON.colWidth + 20) * (12));
            $('.grid-stack').css({ 'width': stack_width + 'px' });

            var grid = GridStack.init({
                float: true,
                cellHeight: inputJSON.rowHeight,
             //   column: 12, //inputJSON.cols,
                //disableResize: true,
                disableOneColumnMode: true,
                verticalMargin: inputJSON.gridGap,
                resizable: true,
                auto: true
               // itemClass: ['grid-stack-item', 'css']
            });

            // standard gridstack utility functions and logging

            grid.on('added', function(e, items) {log('added ', items)});
            grid.on('removed', function(e, items) {log('removed ', items)});
            grid.on('change', function(e, items) {log('change ', items)});
            function log(type, items) {
                var str = '';
                items.forEach(function(item) { str += ' (x,y)=' + item.x + ',' + item.y; });
                console.log(type + items.length + ' items.' + str );
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

            loadGrid = function() {
                grid.removeAll();
                var items = GridStack.Utils.sort(inputJSON.tiles); //serializedData);
                grid.batchUpdate();
                items.forEach(function (item) {
                    grid.addWidget('<div><div class="grid-stack-item-content">' + item.id + " " + item.template + item.innerHTML + '</div></div>', item);
                });
                grid.commit();
            };

            saveGrid = function() {
                serializedData = [];
                grid.engine.nodes.forEach(function(node) {
                    serializedData.push({
                        x: node.x,
                        y: node.y,
                        width: node.width,
                        height: node.height,
                        id: node.id,
                        template: node.template
                    });
                });
                document.querySelector('#saved-data').value = JSON.stringify(serializedData, null, '  ');
            };

            clearGrid = function() {
                grid.removeAll();
            }

            loadGrid();

        }); // callback for lJ()
    }); // callback for ready()
} // script_jquery.onload()


function lJ(callback) {
    var urlParams = new URLSearchParams(window.location.search);
    var xobj = new XMLHttpRequest();
    xobj.overrideMimeType("application/json");
    xobj.open("GET", window.location.pathname + "/layout", true);
    xobj.withCredentials = true;
    xobj.setRequestHeader("Authorization","Bearer " + urlParams.get("access_token"));
    xobj.onreadystatechange = function () {
        if (xobj.readyState == 4 && xobj.status == "200") {
            callback(xobj.responseText);
        }
    };
    xobj.send(null);
}

function loadScript(url, id)
{
//    console.log("attempting to inject: " + id + " from " + url);
    var head = document.getElementsByTagName('head')[0];
    var element = document.getElementById(id + "-script");
    var has_element = element != null;

    if (!has_element) {
//        console.log(id + " doesn't yet exist, loading now");
        var script = document.createElement('script');
        script.setAttribute("id", id + "-script")
        script.type = 'text/javascript';
        script.src = url;
        head.appendChild(script);
    }
}

function loadCSS(url, id)
{
//    console.log("attempting to inject: " + id + " from " + url);
    var head = document.getElementsByTagName('head')[0];
    var element = document.getElementById(id + "-css");
    var has_element = element != null;

    if (!has_element) {
//        console.log(id + " doesn't yet exist, loading now");
        var css = document.createElement('link');
        css.setAttribute("id", id + "-css");
        css.setAttribute("rel", "stylesheet");
        css.setAttribute("type", "text/css");
        css.setAttribute("href", url);
        head.appendChild(css);
    }
}

/*

  "customJS": "var body = document.getElementsByTagName(\"body\")[0];\r\nvar script = document.getElementById(\"inserted-body-script\");\r\nvar hasScript = script != null;\r\nif(!hasScript) {\r\n    script = document.createElement(\"script\");\r\n    script.setAttribute(\"id\", \"inserted-body-script\")\r\n}\r\n\r\nscript.type = \"text/javascript\";\r\n\r\nscript.src = \"http://localhost:8888/scratch.js\";\r\n//script.src = \"https://cdn.jsdelivr.net/npm/micromodal/dist/micromodal.min.js\";\r\nif(!hasScript) {\r\n    body.appendChild(script);\r\n//alert(6);\r\n} else {\r\n//    MicroModal.show(\"modal-1\");\r\n//alert(10);\r\n}\r\n\r\nvar div = document.getElementById(\"inserted-body-html\");\r\nvar hasDiv = div != null;\r\nif(!hasDiv) {\r\n    div = document.createElement(\"div\")\r\n    div.setAttribute(\"id\", \"inserted-body-html\")\r\n}\r\n\r\ndiv.innerHTML = \"\";\r\nif(!hasDiv) {\r\n    body.prepend(div);\r\n}\r\nscript.onload = function() {\r\n//    MicroModal.init({debugMode: true});\r\n//    MicroModal.show(\"modal-1\");\r\n//    alert(2);\r\n}",
  "customHTML": "<div class=\"container-fluid\">\r\n    <a onClick=\"saveGrid()\" class=\"btn btn-primary\" href=\"#\">Save</a>\r\n    <a onClick=\"loadGrid()\" class=\"btn btn-primary\" href=\"#\">Load</a>\r\n    <a onClick=\"clearGrid()\" class=\"btn btn-primary\" href=\"#\">Clear</a>\r\n    <div class=\"grid-stack\"></div>\r\n    <textarea id=\"saved-data\" cols=\"100\" rows=\"2\" readonly=\"readonly\"></textarea>\r\n</div>",

 */