  // allow access within functions

  var smartlyDATA = '';
  var hubitatJSON = '';

  $(document).ready(function() {

    $("#update_colors").prop( "disabled", true );
    $("#update_css").prop( "disabled", true );
    $("#update_settings").prop( "disabled", true );


// retrieve github smartly-skins repo listing and add those options to the skin selector

    $.getJSON("https://api.github.com/repos/ezeek/smartly-skins/contents/", function(data) {
      //console.log("TEST");
      //console.log(data);
      jQuery.each(data, function (index, skin) {

        if (skin.name != "README.md") {
          //console.log(skin);
          $('#skinselect').append(new Option(skin.name, skin.path));
 //       <option value="https://github.com/ezeek/smartly-theme_dark">smartly dark</option>
 //       <option value="https://github.com/ezeek/smartly-theme_wander">wander</option>
        }

      });
    });


$("#skinselect").change(function () {
console.log($(this).val());
skin_chooser_bg($(this).val());

/*
    var str = "";
    $("select option:selected").each(function() {
      str += $( this ).text() + " ";
    });
console.log(str);
    $( "div" ).text( str );
*/

  })
/*
  .change();
*/

// if anything changes, remind the user to update

  $(":input").change(function(){ //triggers change in all input fields including text type
    console.log("SOMETHING CHANGED");
    if (!($(this).hasClass("stealth"))) {
      console.log($(this), "NOT STEALTH");
      smartly_update();
    } else {
      console.log("STEALTH");
    }
  });


// the form submit function

    $('form').submit(function(e) {
$('#copyclick').finish();
      $("#inputjson").addClass("stealth");

      $("#inputjson").prop( "readonly", false ); // must enable a form field to allow it to submit its data
      $("#update_colors").prop( "disabled", false );
      $("#update_css").prop( "disabled", false );
      $("#update_settings").prop( "disabled", false ); 

      e.preventDefault();
      var values = $(this).serialize();

            // various UI/UX runtime tweaks
      $("#inputjson").addClass('disabled');
      $('#inputjson').removeClass('error');
      $("#copyclick").css({'display':'none'});
      $("#update_success").css({'display':'none'});
      $("#update_warning").empty();
      $("#smartly_update").html('Updating...  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
      $("#update_options").collapse('hide'); 
   
      $.ajax({
        url: "smartly-helper.php",
        type: "post",
        data: values ,
        success: function (res) {

          $("#update_colors").prop( "disabled", true );
          $("#update_css").prop( "disabled", true );
          $("#update_settings").prop( "disabled", true );
          $("#inputjson").prop( "readonly", true );

   //   $("#inputjson").removeClass("stealth");
          // use an additional internal error detector and act accordingly
          if (res == "JSON Error") {
            $('#inputjson').addClass('error');
            $("#inputjson").prop( "readonly", false );
            $('#inputjson').removeClass('disabled');
            $("label[for='inputjson']").html('An error has occurred. Is your JSON valid?');
            $("#smartly_update").html('Update');

          } 

          // parse all json result data
          var resDATA = JSON.parse(res);

/*
console.log(res);
console.log(smartlyDATA, "smartlydata inherited");
console.log(hubitatJSON, "hubitatJSON inheritied");
*/

          // split smartlydata from hubitatjson data
          smartlyDATA = resDATA.smartlyDATA;
          hubitatJSON = JSON.parse(resDATA.outputJSON);
console.log(smartlyDATA, "smartlyDATA");
console.log(hubitatJSON, "hubitatJSON");
          
          // various UI/UX runtime tweaks
          $("#checkbox_zoomy").prop("checked", false);
          update_calibrations(smartlyDATA);
          smartly_grid(smartlyDATA, hubitatJSON);
          $("#smartly_update").html('Update');
          $('#inputjson').val(resDATA.outputJSON);
       //   $("label[for='inputjson']").html('Your Updated Layout JSON (to copy back into HE)');
       //   $("#copyclick").html('copy to clipboard');
       //   $("#copyclick").removeClass('btn-warning').removeClass('btn-danger');
       //   $("#copyclick").addClass('btn-success');
          $("#copyclick").css({'display':'unset'});
          $("#smartly_update").css({'display':'none'});
          $("#resetclick").css({'display':'unset'});


          $("#update_success").css({'display':'inline'});
          $("#update_success").fadeIn().delay(3000).fadeOut();
          $("html, body").animate({ scrollTop: $(document).height()}, 1000);
       //   $('#copyclick').fadeOut(750).fadeIn(250); 
        },
        error: function(xhr, status, error) {
          console.log(xhr.responseText);
          $("#smartly_update").html('Error, try again.');
        }

      });
    });
  });


/*
 * update_calibrations()
 * retrieves existing selected device calibrations and populates the tags-input form
*/

  function update_calibrations(smartlyDATA) {

    if (smartlyDATA['settings']['calibration']['devices'] || smartlyDATA['settings']['calibration']['devices_2col']) {

      $.getJSON( "assets/data/device_cals.json", function(data) {
        
        var elt = $('#cal_devices');
        var elt2 = $('#cal_devices_2col')
        var found = '';

        jQuery.each(smartlyDATA['settings']['calibration']['devices'], function (id, device) {
          found = data.filter(x => x.value === device).map(x => x.text);
          if (found !== "") {
console.log("[" + found + "]", "FOUND");
            elt.addClass("stealth");
            elt.tagsinput('add', { "value": device , "text": found   });
            elt.removeClass("stealth");
          }
        });

        jQuery.each(smartlyDATA['settings']['calibration']['devices_2col'], function (id, device) {
          found = data.filter(x => x.value === device).map(x => x.text);
          if (found !== "") {
console.log("[" + found + "]", "FOUND");
            elt2.addClass("stealth");
            elt2.tagsinput('add', { "value": device , "text": found   });
            elt2.removeClass("stealth");
          }
        });

      });
    }
  } 


/*
 * smartly_restart()
 * clears and resets all (most) form values to ready for a new inputJSON pasting
*/

  function smartly_restart() {
    $("#inputjson").val("");
    $("label[for='inputjson']").html('Your Layout JSON <span class="asteriskField">*</span>');
    $("#inputjson").prop( "readonly", false );
    $('#inputjson').removeClass('disabled');
    $('#inputjson').removeClass('error');
    $("#copyclick").css({'display':'none'});
    $("#resetclick").css({'display':'none'});
          $("#smartly_update").css({'display':'unset'});
    $("#update_success").css({'display':'none'});    
    $("#smartlydata").html('');
    $("#gridwrapper").css({'display': 'none'});
    $("#gridheader").empty();
    $("#grid").empty();
    $("#grid").removeAttr('style');
    $("#update_options").collapse('show');
    $('#cal_devices').addClass("stealth");
    $('#cal_devices_2col').addClass("stealth");
    $('#cal_devices').tagsinput('removeAll');
    $('#cal_devices_2col').tagsinput('removeAll');
    $('#cal_devices').removeClass("stealth");
    $('#cal_devices_2col').removeClass("stealth");
  }

/*
 * smartly_editor()
 * launches and populates modal that provides editing options for a particular clicked tile
*/

  function smartly_editor(tile_id) {

    var editor = $('#smartly_editor');

    if (!(tile_id) && (tile_id !== 0)) {

console.log("no tile id sent!");
     $("#smartly_settings_modal").modal()

    // var modal_label = $("#modalLabel");
    // modal_label.html("Advanced Settings" + " [ <span style='color:grey;'>zoom calibration</span> ]");
//editor.empty();

      //  editor.append('someting');

    } else {  // if editing a specific tile

      var data = smartlyDATA['tiles'][tile_id];

      $("#smartly_modal").modal()
      var modal_label = $("#modalLabel");
      modal_label.html("Now editing ID " + tile_id + " [ <span style='color:grey;'>" + data.template + "</span> ]");

      //console.log(smartlyDATA, "outside smartlyDATA");
      //console.log(smartlyDATA[tile_id],"passed tile_id within smartlyDATA"); 

      editor.empty();
      editor.append('<input type="hidden" id="smart_edit_id" name="smart_edit_id" value="' + tile_id + '">');

      if (typeof data.title !== 'undefined') { // || data.tile !== null) {

        var title = data.title ? data.title : '';
        //console.log(data.tile_wrap, tile_id + " : TITLEWRAP");

        editor.append('  <div class="form-group row">    <label class="col-4 col-form-label" for="title">Title replacement</label>     <div class="col-8">      <input id="smart_edit_title" name="smart_edit_title" type="text" class="form-control" aria-describedby="titleHelpBlock" value="' + title + '">   </div>  </div>');

      }

      if (typeof data.label !== 'undefined') { // && data.label !== null) {

        var label = data.label ? data.label : '';
        editor.append(' <div class="form-group row">    <label for="label" class="col-4 col-form-label">Add/Replace Label</label>     <div class="col-8">      <input id="smart_edit_label" name="smart_edit_label" type="text" class="form-control" aria-describedby="labelHelpBlock" value="' + label + '">       <span id="labelHelpBlock" class="form-text text-muted">For image and video tiles, this will add a highly visible label.  For others this will replace the existing label.</span>    </div>  </div> ');
      }

      if (typeof data.attribute !== 'undefined') {

        if (typeof data.attribute.unit !== 'undefined') {

          var unit = data.attribute.unit ? data.attribute.unit : '';
          editor.append(' <div class="form-group row">    <label for="label" class="col-4 col-form-label">Add Custom Unit text</label>     <div class="col-8">      <input id="smart_edit_unit" name="smart_edit_unit" type="text" class="form-control" aria-describedby="labelHelpBlock" value="' + unit + '">       <span id="labelHelpBlock" class="form-text text-muted">For attribute tiles, this will add your custom unit label immediately after the tile value.</span>    </div>  </div> ');
        }

        if (typeof data.attribute.numeric !== 'undefined') {

          var numeric = data.attribute.numeric === true ? 'checked' : '';

          editor.append('<div class="form-group row">    <label class="col-4">Increase Font Size</label>     <div class="col-8">      <div class="custom-control custom-checkbox custom-control-inline">        <input name="smart_edit_numeric" id="smart_edit_numeric" type="checkbox" class="custom-control-input" value="numeric" ' + numeric + '>         <label for="smart_edit_numeric" class="custom-control-label">This is normally used to increase attribute tile font size to match temperature, humidity and other numeric-based tile types.</label>      </div>   </div>  </div>');

        }

      }

      if (data.states) {

        if (typeof data.icon_nudge !== 'undefined') {
          var iconnudge = data.icon_nudge === true ? 'checked' : '';
          editor.append('<div class="form-group row">    <label class="col-4">Icon Nudge</label>     <div class="col-8">      <div class="custom-control custom-checkbox custom-control-inline">        <input name="smart_edit_iconnudge" id="smart_edit_iconnudge" type="checkbox" class="custom-control-input" value="icon_nudge" ' + iconnudge + '>         <label for="smart_edit_iconnudge" class="custom-control-label">Nudge the icon to give it more space..</label>      </div>   </div>  </div>');

        }


        editor.append("<fieldset id='wrapper_states_"+tile_id+"'><legend>icon replace/add</legend></fieldset>");
        $('#wrapper_states_'+tile_id).collapse({
          toggle: false
        })

        jQuery.each(data.states, function (statename, icon) {

          statename_pretty = statename.replace("_", " ");
          //console.log(statename, "STATENAMEX");
          //console.log(icon.class, "ICONX");
          $('#wrapper_states_'+tile_id).append("<button class='icon-picker-reset btn btn-link btn-sm' onClick='iconpicker_reset(\"" + statename + "\")'>reset icon</button><span class='icon-picker-state'>"+ statename_pretty +"</span><div class='icon-picker' id='state_" + statename + "'><div id='" + statename + "_picker'></div></div>");
          editor.append("<input type='hidden' name='" + statename + "' id='state_" + statename + "_value'>");
          //console.log(data, "DATA");

          //console.log($('#'+statename+'_picker'));
console.log(icon.code, "CODE LOOKUP");
          $('#'+statename+'_picker').iconpicker({
 	    align: 'center', // Only in div tag
      	    arrowClass: 'btn-submit',
            arrowPrevIconClass: 'mdi mdi-arrow-left-thick',
            arrowNextIconClass: 'mdi mdi-arrow-right-thick',
            cols: 7,
            footer: true,
            header: true,
            icon: icon.code ? icon.code : null, //'F9C1', //'mdi-account',
            iconset: 'materialdesign',
            labelHeader: '{0} of {1} pages',
            labelFooter: '{0} - {1} of {2} icons',
            placement: 'bottom', // Only in button tag
            rows: 4,
            search: true,
            searchText: 'Search',
            selectedClass: 'btn-success',
            unselectedClass: ''
          });

          if (icon.code) {
            $('#state_'+statename+'_value').val(icon.code);
          }

          $('#'+statename+'_picker').on('change', function(e) {
            $('#state_'+statename+'_value').val(e.code);
            console.log(e.code);
          });
        });
      } else { // this tile type has no states or icon replacement is not permitted

        editor.append("There are no configurable icons for this tile.");

      }
    }
  } // end

/*
 * smartly_grid()
 * generates the clickable preview grid after updating
*/

  function smartly_grid(smartly_data, hubitat_json) {
console.log('smartly_grid()');
console.log(smartly_data, "sgrid smartly_data input");
console.log(hubitat_json, "sgrid hubitat_json input");

    var $gridheight = (Number(hubitat_json.rows)) * 19 + 4;
    var $gridwidth = (Number(hubitat_json.cols)) * 37 + 4;

    // populate the hidden smartly_datablock
    var smartly_datablock = $("#smartlydata");
    smartly_datablock.text(JSON.stringify(smartly_data));
    $gridwrapper = $("#gridwrapper");
    $("#gridwrapper").css({'display': 'block'});
    $gridheader = $("#gridheader");
    $grid = $("#grid");
    $gridheader.empty();
    $grid.empty();
    $gridheader.html("'" + hubitat_json.name + "' tile editor" + "<br><span style='font-size: 70%;'>Click on a tile below to change title, label and/or icons. <i class='fa fa-cog' onclick='smartly_editor();'></i>");
    $grid.css({width: $gridwidth, height: $gridheight, display: 'grid'});
    //console.log(hubitat_json, "HUBITAT JSON ALL");

    jQuery.each(smartly_data['tiles'], function (id, data) {

      //console.log(data, "SMARTLY DATA: " + id);
      var he_tile = hubitat_json.tiles[data.pos];
      var he_colors = hubitat_json.customColors;
      //console.log(he_tile, "HE JSON: " + id);
      const colors = he_colors.find( ({ template }) => template === data.template );
      const bgcolor = typeof colors !== 'undefined' && typeof colors.bgColor !== 'undefined' ? " background-color: " + colors.bgColor : "black";

      var clickEdit = "onClick=clickedit('" + id + "');";

      //console.log(he_tile.row, "THEROW FOR: " + id);
      $grid.append("<div id='tile-" + id + "' class='tile " + data.template + "' style='grid-area: " + he_tile.row + " / " + he_tile.col + " / " + (Number(he_tile.row) + Number(he_tile.rowSpan)) + " / " + (Number(he_tile.col) + Number(he_tile.colSpan)) + "; " + bgcolor + "' onClick=smartly_editor(" + id + ");><div class='tile_content'>" + id + "</div></div>");

/*
$form = $("<form></form>");
$form.append("<b>" + data.template + "</b><br>");
$form.append('<label for "' + id + '-title">Title</label><input type="text" id="' + id  + '-title" value="' + data.title + '">');
$("#smartly_editor").append($form);
*/

//"<div id='" + id + "-smartly'><b>" + data.template + "</b><br><br>title: " + data.title +"</div>");

    }); // each tile



  }

/*
 * smartly_update()a
 * processes tile_editor input and saves back into JSON awaiting re-update
 * provides a warning to the user that something has been changed and they should update
*/

  function smartly_update() {
    var $locked = $("#inputjson").prop("readonly"); // preserve initial locked state of inputJSON
    $("#inputjson").prop( "readonly", false );

    $("#smartly_modal").modal("hide");

/*
$('#copyclick').finish();
 $("#copyclick").animate({ opacity: 0 }, 50, function() {
            $("#copyclick").text('unsaved changes!').removeClass('btn-success').addClass('btn-danger').animate({  opacity: 1 }, 250, function() { 
                $("#copyclick").delay(1000).animate({ opacity: 0 }, 500, function() {
                    $("#copyclick").text("changes made - click 'Update' below").removeClass('btn-danger').addClass('btn-warning').animate({  opacity: 1 }, 100)})})});
*/

    $("#update_warning").html("There are unsaved changes! Click <i>Update</i> again.");
//    $('#copyclick').html('copy to clipboard');

    $("#copyclick").css({'display':'none'});
          $("#smartly_update").css({'display':'unset'});


    if ($("#smart_edit_id").val()) {
      var smart_id = $("#smart_edit_id").val();
      //console.log(smartlyDATA[smart_id], "UPDATE lookup");

      if ($("#smart_edit_title").val()) {
console.log("SMART_EDIT_TITLE PRESENT");
        smartlyDATA['tiles'][smart_id]['title'] = $("#smart_edit_title").val();
      } else {

if ($("#smart_edit_title").length) {
        smartlyDATA['tiles'][smart_id]['title'] = null;
}

      }

      //console.log($("#smart_edit_iconnudge").is(":checked"),"CHECKBOX");
     if ($("#smart_edit_iconnudge").is(":checked")) {
console.log("SMART_EDIT_TITLEWRAP PRESENT");
        smartlyDATA['tiles'][smart_id]['icon_nudge'] = true;
      } else {
        smartlyDATA['tiles'][smart_id]['icon_nudge'] = false;
      }

      if ($("#smart_edit_label").val()) {
console.log("SMART_EDIT_LABEL PRESENT");
        smartlyDATA['tiles'][smart_id]['label'] = $("#smart_edit_label").val();
      } else {

if ($("#smart_edit_label").length) {
        smartlyDATA['tiles'][smart_id]['label'] = null;
}
      }


      if ($("#smart_edit_unit").val()) {
console.log("SMART_EDIT_UNIT PRESENT");
        smartlyDATA['tiles'][smart_id]['attribute']['unit'] = $("#smart_edit_unit").val();
      } else {
        if ($("#smart_edit_unit").length) {
          smartlyDATA['tiles'][smart_id]['attribute']['unit'] = null;
        }
      }

    if ($("#smart_edit_numeric").val()) {
     if ($("#smart_edit_numeric").is(":checked")) {
console.log("SMART_EDIT_NUMERIC PRESENT");
        smartlyDATA['tiles'][smart_id]['attribute']['numeric'] = true;
      } else {
        smartlyDATA['tiles'][smart_id]['attribute']['numeric'] = false;
      }
}

      $('input[id ^= "state_"]').each(function(index, element) {
console.log("SMART STATES PRESENT");
        var icon_code = $(this).val();
        var icon_class = $(this).attr('name');
console.log("VAL: " + $(this).val() + " | NAME: " + $(this).attr('name'));
        //console.log($(this).val());
        //console.log($(this).attr('name'));
        if ($(this).val()) {
          smartlyDATA['tiles'][smart_id]['states'][icon_class]['code'] = icon_code;
        } else {
          smartlyDATA['tiles'][smart_id]['states'][icon_class]['code'] = null;
        }
      });

       // populate the hidden smartly_datablock
      var smartly_datablock = $("#smartlydata");
      smartly_datablock.text(JSON.stringify(smartlyDATA));

console.log(smartlyDATA);
    }

    if ($locked) {
      $("#inputjson").prop( "readonly", true );
    }

  }


/*
 * smartly_settings_update()
 * processes settings_editor input and saves back into JSON awaiting re-update
 * provides a warning to the user that something has been changed and they should update
*/

  function smartly_settings_update() {
    var $locked = $("#inputjson").prop("readonly"); // preserve initial locked state of inputJSON

    $("#inputjson").prop( "readonly", false );

    $("#smartly_settings_modal").modal("hide");
/*
$('#copyclick').finish();

$("#copyclick").animate({ opacity: 0 }, 50, function() {
            $("#copyclick").text('unsaved changes!').removeClass('btn-success').addClass('btn-danger').animate({  opacity: 1 }, 250, function() { 
                $("#copyclick").delay(1000).animate({ opacity: 0 }, 500, function() {
                    $("#copyclick").text("changes made - click 'Update' below").removeClass('btn-danger').addClass('btn-warning').animate({  opacity: 1 }, 100)})})});
*/
    $("#update_warning").html("There are unsaved changes! Click <i>Update</i> again.");
//    $('#copyclick').html('copy to clipboard');

    $("#copyclick").css({'display':'none'});
          $("#smartly_update").css({'display':'unset'});

    var cal_devices_val = $("#cal_devices").val() ? $("#cal_devices").val() : null;
    var cal_devices_2col_val = $("#cal_devices_2col").val() ? $("#cal_devices_2col").val() : null;;
  
    var cal_devices = cal_devices_val ? cal_devices_val.split(',') : null;
    var cal_devices_2col = cal_devices_2col_val ? cal_devices_2col_val.split(',') : null;


    console.log(cal_devices);
    console.log(cal_devices_2col);


    smartlyDATA['settings'] = {};
    smartlyDATA['settings']['calibration'] = {};

    smartlyDATA['settings']['calibration']['devices'] = cal_devices;
    smartlyDATA['settings']['calibration']['devices_2col'] = cal_devices_2col;

      //console.log(smartlyDATA[smart_id], "UPDATE lookup");

      // test adding a global settings array to smartlyDATA

       // populate the hidden smartly_datablock
      var smartly_datablock = $("#smartlydata");
      smartly_datablock.text(JSON.stringify(smartlyDATA));

console.log(smartlyDATA);




    if ($locked) {
      $("#inputjson").prop( "readonly", true );
    }

  }

function iconpicker_reset(target) {
  var target_id = "#" + target + "_picker";
  $(target_id).iconpicker('setIcon', '');
}


function skin_chooser_bg(skin) {
  var skin_bg = "url(https://hubitat.ezeek.us/smartly-skins/" + skin + "/assets/images/bg_" + skin + ".jpg)";
$('#skin_chooser').css({'background-image': skin_bg});
}

function disableEnterKey(e)
{
     var key;      
     if(window.event)
          key = window.event.keyCode; //IE
     else
          key = e.which; //firefox      

     return (key != 13);
}

