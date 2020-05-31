// allow access within functions
var smartlyDATA = '';
var hubitatJSON = '';
var smartlyMODS = [];

var debug = true;

$(document).ready(function() {

  // retrieve smartly_mods from json
  $.getJSON("assets/data/smartly_mods.json", function(data) {
    smartlyMODS = data;
  });

  $("#update_colors").prop("disabled", true);
  $("#update_css").prop("disabled", true);
  $("#update_settings").prop("disabled", true);


  // retrieve github smartly-skins repo listing and add those options to the skin selector

  $.getJSON("https://api.github.com/repos/ezeek/smartly-skins/contents/", function(data) {
    jQuery.each(data, function(index, skin) {
      if (skin.name != "README.md") {
        $('#skinselect').append(new Option(skin.name, skin.path));
      }
    });
  });


  // enable skin selection background image switcher

  $("#skinselect").change(function() {
    if (debug) {  console.log($(this).val()); }
    skin_chooser_bg($(this).val());
  })


  // if anything changes, remind the user to update

  $(":input").change(function() { //triggers change in all input fields including text type
    if (debug) { console.log("SOMETHING CHANGED"); }
    if (!($(this).hasClass("stealth"))) {
      if (debug) { console.log($(this), "NOT STEALTH"); }
      smartly_update();
    } else {
      if (debug) { console.log("STEALTH"); }
    }
  });


  // the form submit function

  $('form').submit(function(e) {
    $('#copyclick').finish();
    $("#inputjson").addClass("stealth");

    $("#inputjson").prop("readonly", false); // must enable a form field to allow it to submit its data
    $("#update_colors").prop("disabled", false);
    $("#update_css").prop("disabled", false);
    $("#update_settings").prop("disabled", false);

    e.preventDefault();
    var values = $(this).serialize();

    // various UI/UX runtime tweaks
    $("#inputjson").addClass('disabled');
    $('#inputjson').removeClass('error');
    $("#copyclick").css({
      'display': 'none'
    });
    $("#update_success").css({
      'display': 'none'
    });
    $("#update_warning").empty();
    $("#smartly_update").html('Updating...  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
    $("#update_options").collapse('hide');

    $.ajax({
      url: "smartly-helper.php",
      type: "post",
      data: values,
      success: function(res) {

        $("#update_colors").prop("disabled", true);
        $("#update_css").prop("disabled", true);
        $("#update_settings").prop("disabled", true);
        $("#inputjson").prop("readonly", true);

        // use an additional internal error detector and act accordingly
        if (res == "JSON Error") {
          $('#inputjson').addClass('error');
          $("#inputjson").prop("readonly", false);
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
        $("#copyclick").css({
          'display': 'unset'
        });
        $("#smartly_update").css({
          'display': 'none'
        });
        $("#resetclick").css({
          'display': 'unset'
        });

        $("#update_success").css({
          'display': 'inline'
        });
        $("#update_success").fadeIn().delay(3000).fadeOut();
        $("html, body").animate({
          scrollTop: $(document).height()
        }, 1000);
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

    $.getJSON("assets/data/device_cals.json", function(data) {

      var elt = $('#cal_devices');
      var elt2 = $('#cal_devices_2col')
      var found = '';

      jQuery.each(smartlyDATA['settings']['calibration']['devices'], function(id, device) {
        found = data.filter(x => x.value === device).map(x => x.text);
        if (found !== "") {
          if (debug) { console.log("[" + found + "]", "FOUND"); }
          elt.addClass("stealth");
          elt.tagsinput('add', {
            "value": device,
            "text": found
          });
          elt.removeClass("stealth");
        }
      });

      jQuery.each(smartlyDATA['settings']['calibration']['devices_2col'], function(id, device) {
        found = data.filter(x => x.value === device).map(x => x.text);
        if (found !== "") {
          if (debug) { console.log("[" + found + "]", "FOUND"); }
          elt2.addClass("stealth");
          elt2.tagsinput('add', {
            "value": device,
            "text": found
          });
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
  $("#inputjson").prop("readonly", false);
  $('#inputjson').removeClass('disabled');
  $('#inputjson').removeClass('error');
  $("#copyclick").css({
    'display': 'none'
  });
  $("#resetclick").css({
    'display': 'none'
  });
  $("#smartly_update").css({
    'display': 'unset'
  });
  $("#update_success").css({
    'display': 'none'
  });
  $("#smartlydata").html('');
  $("#gridwrapper").css({
    'display': 'none'
  });
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

    if (debug) { console.log("no tile id sent!"); }
    $("#smartly_settings_modal").modal()

    // var modal_label = $("#modalLabel");
    // modal_label.html("Advanced Settings" + " [ <span style='color:grey;'>zoom calibration</span> ]");
    //editor.empty();

    //  editor.append('someting');

  } else { // if editing a specific tile

    var data = smartlyDATA['tiles'][tile_id];

    // open the editor modal
    $("#smartly_modal").modal();
    var modal_label = $("#modalLabel");
    modal_label.html("Now editing ID " + tile_id + " [ <span style='color:grey;'>" + data.template + "</span> ]");

    //console.log(smartlyDATA, "outside smartlyDATA");
    //console.log(smartlyDATA[tile_id],"passed tile_id within smartlyDATA"); 

    editor.empty();

    editor.append('<input type="hidden" id="smart_edit_id" name="smart_edit_id" value="' + tile_id + '">');

    var section_build = {};

    for (let [section, mods] of Object.entries(smartlyMODS.layout.tiles)) {
      var section_html = [];

      mods.forEach(function(mod){ 

        if (typeof data.mods[mod] !== 'undefined') {

          console.log(mod + " enabled within " + section, "MOD ACTIVE");

          // retrieve and process value for specific input type
          var formValue = '';
          var formInsert = '';

          var helpText = '';
          var labelText = '';

          var formHtml = '';
          var modWrap = false;

          if (typeof smartlyMODS.tiletype[mod].modifier !== 'undefined') {
            modWrap = true;
            formHtml += '<fieldset class="form-group">';
          }

          // prep mod text if available



formHtml += build_form(tile_id, data, data.mods[mod], smartlyMODS.tiletype[mod], mod);


          if (typeof smartlyMODS.tiletype[mod].text !== 'undefined') {

            if (typeof smartlyMODS.tiletype[mod].text[data.template] !== 'undefined') {
              helpText = smartlyMODS.tiletype[mod].text[data.template];
            } else if (typeof smartlyMODS.tiletype[mod].text['default'] !== 'undefined') {
              helpText = smartlyMODS.tiletype[mod].text.default;
            }
          }

          switch (smartlyMODS.tiletype[mod].type) {
            case 'checkbox':

              if (data.mods[mod].value === true) {
                formValue = 'checked';
              }

              formHtml += '<div class="form-group row"><label class="col-4">' + smartlyMODS.tiletype[mod].label + '</label><div class="col-8"><div class="custom-control custom-checkbox custom-control-inline"><input name="smart_edit_' + mod + '" id="smart_edit_' + mod + '" type="checkbox" class="custom-control-input" value="' + mod + '" ' + formValue + '>         <label for="smart_edit_' + mod + '" class="custom-control-label">' + helpText + '</label></div></div></div>';

              break;

            case 'select':

              formHtml += '<div class="form-group row"><label for="select" class="col-4 col-form-label">' + smartlyMODS.tiletype[mod].label + '</label><div class="col-8"><select id="smart_edit_' + mod + '" name="smart_edit_' + mod + '" class="custom-select">';

              for (let [value, name] of Object.entries(smartlyMODS.tiletype[mod]['options'])) {
                console.log(`${value}: ${name}`);

                formValue = '';

                if (data.mods[mod].value === value) {
                  formValue = 'selected';
                }

                formHtml += '<option value="' + value + '" ' + formValue + '>' + name + '</option>';
              };

              formHtml += '</select><span id="selectHelpBlock" class="form-text text-muted">' + helpText + '</span></div></div>';

              break;

            case 'fieldset':

              if (data.states) {
                formHtml += '<fieldset id="wrapper_states_' + tile_id + '"><legend>' + smartlyMODS.tiletype[mod].label + '</legend></fieldset>';
              }

              break;

            default:

              formValue = data.mods[mod].value ? data.mods[mod].value : '';

              formHtml += '<div class="form-group row"><label class="col-4 col-form-label" for="title">' + smartlyMODS.tiletype[mod].label + '</label><div class="col-8">';
              formHtml += '<input id="smart_edit_' + mod + '" name="smart_edit_' + mod + '" type="' + smartlyMODS.tiletype[mod].type + '" class="form-control" aria-describedby="' + mod + 'HelpBlock" value="' + formValue + '" ' + formInsert + '><span id="' + mod + 'HelpBlock" class="form-text text-muted">' + helpText + '</span></div></div>';

          } // switch




          if (modWrap) {
            formHtml +='</fieldset>';
          }

          section_html.push(formHtml);

//          editor.append(formHtml);

        }; // for mods

        // if the section has elements, add to section_build object for building later

        if (section_html.length > 0) {
          section_build[section] = section_html;
        }

      });
    };

    var sections_count = Object.keys(section_build);
    var sections_exist = false;

    // build a reusable indicator for sections
    
    if (sections_count.length > 0) {
      sections_exist = true;
    }


    // build menu and tabs

    var section_menu = '';
    var section_tab_html = '';
    var section_counter = 0;
    var active_class = '';
  
    if (sections_exist) {
      section_menu += '<ul class="nav nav-pills">';
      section_tab_html += '<div class="tab-content">';
    }

    for (let [section, section_html] of Object.entries(section_build)) {
      if (section_counter < 1) { active_class = 'active'; }
      section_menu += '<li class="nav-item"><a class="nav-link ' + active_class + '" data-toggle="pill" href="#' + section + '">' + section + '</a></li>';

      if (section_counter < 1) { active_class = 'active'; } else { active_class = 'fade'; }
      section_tab_html += '<div class="tab-pane container ' + active_class + '" id="' + section + '">' + section_html.join('')   + '</div>';
      section_counter++;
      active_class = '';
    }

    if (sections_exist) {
      section_menu += '</ul>';
      section_tab_html += '</div>';
    }

    editor.append(section_menu);
    editor.append(section_tab_html);




    // STATE ICONS

    if (data.states) {

      // ICON REPLACEMENT

//      editor.append("<fieldset id='wrapper_states_" + tile_id + "'><legend>icon replace/add</legend></fieldset>");

      $('#wrapper_states_' + tile_id).collapse({
        toggle: false
      })

      jQuery.each(data.states, function(statename, icon) {

        statename_pretty = statename.replace("_", " ");
        //console.log(statename, "STATENAMEX");
        //console.log(icon.class, "ICONX");
        $('#wrapper_states_' + tile_id).append("<button class='icon-picker-reset btn btn-link btn-sm' onClick='iconpicker_reset(\"" + statename + "\")'>reset icon</button><span class='icon-picker-state'>" + statename_pretty + "</span><div class='icon-picker' id='state_" + statename + "'><div id='" + statename + "_picker'></div></div>");

         $('#wrapper_states_' + tile_id).append("<input type='hidden' name='" + statename + "' id='state_" + statename + "_value'>");
        //console.log(data, "DATA");

        //console.log($('#'+statename+'_picker'));
        if (debug) { console.log(icon.code, "CODE LOOKUP"); }
        $('#' + statename + '_picker').iconpicker({
          align: 'center', // Only in div tag
          arrowClass: 'btn-submit',
          arrowPrevIconClass: 'mdi mdi-arrow-left-thick',
          arrowNextIconClass: 'mdi mdi-arrow-right-thick',
          cols: 7,
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
          $('#state_' + statename + '_value').val(icon.code);
        }

        $('#' + statename + '_picker').on('change', function(e) {
          $('#state_' + statename + '_value').val(e.code);
          if (debug) { console.log(e.code); }
        });
      });

    } else { // this tile type has no states or icon replacement is not permitted

      el_tab_icon.append("There are no configurable icons for this tile.");

    }
  }
} // end


/*
 * smartly_grid()
 * generates the clickable preview grid after updating
 */

function smartly_grid(smartly_data, hubitat_json) {
  if (debug) {
    console.log('smartly_grid()');
    console.log(smartly_data, "sgrid smartly_data input");
    console.log(hubitat_json, "sgrid hubitat_json input");
  }

  var $gridheight = (Number(hubitat_json.rows)) * 19 + 4;
  var $gridwidth = (Number(hubitat_json.cols)) * 37 + 4;

  // populate the hidden smartly_datablock
  var smartly_datablock = $("#smartlydata");
  smartly_datablock.text(JSON.stringify(smartly_data));
  $gridwrapper = $("#gridwrapper");
  $("#gridwrapper").css({
    'display': 'block'
  });
  $gridheader = $("#gridheader");
  $grid = $("#grid");
  $gridheader.empty();
  $grid.empty();
  $gridheader.html("'" + hubitat_json.name + "' tile editor" + "<br><span style='font-size: 70%;'>Click on a tile below to change title, label and/or icons. <i class='fa fa-cog' onclick='smartly_editor();'></i>");
  $grid.css({
    width: $gridwidth,
    height: $gridheight,
    display: 'grid'
  });
  //console.log(hubitat_json, "HUBITAT JSON ALL");

  jQuery.each(smartly_data['tiles'], function(id, data) {

    //console.log(data, "SMARTLY DATA: " + id);
    var he_tile = hubitat_json.tiles[data.pos];
    var he_colors = hubitat_json.customColors;
    //console.log(he_tile, "HE JSON: " + id);
    const colors = he_colors.find(({
      template
    }) => template === data.template);
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
 * smartly_update()
 * processes tile_editor input and saves back into JSON awaiting re-update
 * provides a warning to the user that something has been changed and they should update
 */

function smartly_update() {

  var $locked = $("#inputjson").prop("readonly"); // preserve initial locked state of inputJSON

  $("#inputjson").prop("readonly", false);
  $("#smartly_modal").modal("hide");
  $("#update_warning").html("There are unsaved changes! Click <i>Update</i> again.");
  $("#copyclick").css({
    'display': 'none'
  });
  $("#smartly_update").css({
    'display': 'unset'
  });


  if ($("#smart_edit_id").val()) {
    var smart_id = $("#smart_edit_id").val();

    // iterate through all available tiletype mods, check if they are being used and if so, save their values.
    $.each(Object.getOwnPropertyNames(smartlyMODS.tiletype), function (index, mod) {

      if ($("#smart_edit_" + mod).length) {
        switch(smartlyMODS.tiletype[mod].type) {
          case 'checkbox':
  
            if ($("#smart_edit_" + mod).is(":checked")) {
              if (debug) { console.log("SMART_EDIT_" + mod  + " PRESENT"); }
              smartlyDATA['tiles'][smart_id]['mods'][mod]['value'] = true;
            } else {
              console.log(smartlyDATA['tiles'][smart_id]['mods'], "smartlyDATA['tiles'][" + smart_id + "]['mods'][" + mod + "]['value']");
              smartlyDATA['tiles'][smart_id]['mods'][mod]['value'] = null;
            }

            break;

          default:

            if ($("#smart_edit_" + mod).val()) {
              if (debug) { console.log("SMART_EDIT_TITLE PRESENT"); }
              smartlyDATA['tiles'][smart_id]['mods'][mod]['value'] = $("#smart_edit_" + mod).val();
            } else {
              if ($("#smart_edit_" + mod).length) {
                smartlyDATA['tiles'][smart_id]['mods'][mod]['value'] = null;
              }
            }
          // switch
        }
      }
    });

    // SAVE ICONS

    $('input[id ^= "state_"]').each(function(index, element) {
      if (debug) { console.log("SMART STATES PRESENT"); }
      var icon_code = $(this).val();
      var icon_class = $(this).attr('name');
      if (debug) { console.log("VAL: " + $(this).val() + " | NAME: " + $(this).attr('name')); }
      //console.log($(this).val());
      //console.log($(this).attr('name'));
      if ($(this).val()) {
        smartlyDATA['tiles'][smart_id]['states'][icon_class]['code'] = icon_code;
      } else {
        smartlyDATA['tiles'][smart_id]['states'][icon_class]['code'] = null;
      }
    });

    // SAVE SMARTLY DATABLOCK

    // populate the hidden smartly_datablock
    var smartly_datablock = $("#smartlydata");
    smartly_datablock.text(JSON.stringify(smartlyDATA));

    if (debug) {console.log(smartlyDATA); }
  }

  if ($locked) {
    $("#inputjson").prop("readonly", true);
  }

}


/*
 * smartly_settings_update()
 * processes settings_editor input and saves back into JSON awaiting re-update
 * provides a warning to the user that something has been changed and they should update
 */

function smartly_settings_update() {

  var $locked = $("#inputjson").prop("readonly"); // preserve initial locked state of inputJSON

  $("#inputjson").prop("readonly", false);
  $("#smartly_settings_modal").modal("hide");
  $("#update_warning").html("There are unsaved changes! Click <i>Update</i> again.");
  $("#copyclick").css({
    'display': 'none'
  });
  $("#smartly_update").css({
    'display': 'unset'
  });

  // check calibration values
  var cal_devices_val = $("#cal_devices").val() ? $("#cal_devices").val() : null;
  var cal_devices_2col_val = $("#cal_devices_2col").val() ? $("#cal_devices_2col").val() : null;;

  // if calibration values, split into array
  var cal_devices = cal_devices_val ? cal_devices_val.split(',') : null;
  var cal_devices_2col = cal_devices_2col_val ? cal_devices_2col_val.split(',') : null;

  if (debug) { 
    console.log(cal_devices);
    console.log(cal_devices_2col);
  }

  // create parent arrays to avoid warnings
  smartlyDATA['settings'] = {};
  smartlyDATA['settings']['calibration'] = {};

  // save calibration devices
  smartlyDATA['settings']['calibration']['devices'] = cal_devices;
  smartlyDATA['settings']['calibration']['devices_2col'] = cal_devices_2col;

  // populate the hidden smartly_datablock
  var smartly_datablock = $("#smartlydata");
  smartly_datablock.text(JSON.stringify(smartlyDATA));

  if (debug) { console.log(smartlyDATA); }

  if ($locked) {
    $("#inputjson").prop("readonly", true);
  }
}


/*
 * helper functions
 */



function build_form(tile_id, tile_data, tile_mod, mod_construct, mod_name) {

         var formValue = '';
          var formInsert = '';

          var helpText = '';
          var labelText = '';

          var formHtml = '';

  // prep mod text if available

  if (typeof mod_construct.text !== 'undefined') {

    if (typeof mod_construct.text[tile_data.template] !== 'undefined') {
      helpText = mod_construct.text[tile_data.template];
    } else if (typeof mod_construct.text['default'] !== 'undefined') {
      helpText = mod_construct.text.default;
    }
  }

  switch (mod_construct.type) {
    case 'checkbox':

      if (tile_mod.value === true) {
        formValue = 'checked';
      }

      formHtml += '<div class="form-group row"><label class="col-4">' + mod_construct.label + '</label><div class="col-8"><div class="custom-control custom-checkbox custom-control-inline"><input name="smart_edit_' + mod_name + '" id="smart_edit_' + mod_name + '" type="checkbox" class="custom-control-input" value="' + mod_name + '" ' + formValue + '>         <label for="smart_edit_' + mod_name + '" class="custom-control-label">' + helpText + '</label></div></div></div>';

      break;

    case 'select':

      formHtml += '<div class="form-group row"><label for="select" class="col-4 col-form-label">' + mod_construct.label + '</label><div class="col-8"><select id="smart_edit_' + mod_name + '" name="smart_edit_' + mod_name + '" class="custom-select">';

      for (let [value, name] of Object.entries(mod_construct['options'])) {
        console.log(`${value}: ${name}`);

        formValue = '';

        if (tile_mod.value === value) {
          formValue = 'selected';
        }

        formHtml += '<option value="' + value + '" ' + formValue + '>' + name + '</option>';
      };

      formHtml += '</select><span id="selectHelpBlock" class="form-text text-muted">' + helpText + '</span></div></div>';

      break;

    case 'fieldset':

      if (tile_data.states) {
        formHtml += '<fieldset id="wrapper_states_' + tile_id + '"><legend>' + mod_construct.label + '</legend></fieldset>';
      }

      break;

    default:

      formValue = tile_mod.value ? tile_mod.value : '';

      formHtml += '<div class="form-group row"><label class="col-4 col-form-label" for="title">' + mod_construct.label + '</label><div class="col-8">';
      formHtml += '<input id="smart_edit_' + mod_name + '" name="smart_edit_' + mod_name + '" type="' + mod_construct.type + '" class="form-control" aria-describedby="' + mod_name + 'HelpBlock" value="' + formValue + '" ' + formInsert + '><span id="' + mod_name + 'HelpBlock" class="form-text text-muted">' + helpText + '</span></div></div>';

  } // switch

return formHtml;

}


function iconpicker_reset(target) {
  var target_id = "#" + target + "_picker";
  $(target_id).iconpicker('setIcon', '');
}

function skin_chooser_bg(skin) {
  var skin_bg = "url(https://hubitat.ezeek.us/smartly-skins/" + skin + "/assets/images/bg_" + skin + ".jpg)";
  $('#skin_chooser').css({
    'background-image': skin_bg
  });
}

function disableEnterKey(e) {
  var key;
  if (window.event)
    key = window.event.keyCode; //IE
  else
    key = e.which; //firefox      

  return (key != 13);
}
