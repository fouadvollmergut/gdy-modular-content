/*
	
		@name GDYMC Error
		@author Johannes Grandy

	*/

// The main object

var gdymc = new Object();

// Functions holder

gdymc.functions = new Object();
gdymc.actions = new Object();
gdymc.info = new Object();
gdymc.selection = new Object();
gdymc.editor = new Object();

// Info

gdymc.info.isSaved = true;
gdymc.info.isSaving = false;
gdymc.info.selectionRange = "";
gdymc.info.selectionRangeActiveElement = null;
gdymc.info.overlayOpen = false;
gdymc.info.overlayScroll = "";
gdymc.info.blockScroll = true;
gdymc.info.activeImage = "";
gdymc.info.activeWidth = "";
gdymc.info.activeHeight = "";

/**
 * Handles GDYMC lang behavior.
 *
 * @param {*} id Id value.
 */
gdymc.lang = function (id) {
  return gdymc_lang[id];
};

/******************************* FUNCTIONS *******************************/

/**
 * Displays a GDYMC error notification with configurable options.
 *
 * @param {*} options Options value.
 */
gdymc.functions.error = function (options) {
  var settings = jQuery.extend(
    {
      title: gdymc.lang("error-title"),
      text: "",
      details: "",
      classes: "",

      background: "#ffba00",
      buttons: new Array({
        text: gdymc.lang("error-ok"),
        action: function (object) {
          object.close();
        },
      }),

      close: function (object) {
        errorWindow.remove();
        errorShadow.remove();
        settings.onClose(object);
      },

      onClose: function (object) {},
    },
    options,
  );

  var errorShadow = jQuery('<div id="gdymc_error_shadow"></div>');
  var errorWindow = jQuery(
    '<div id="gdymc_error_window" class="gdymc_inside"></div>',
  );
  var errorTitle = jQuery(
    '<div id="gdymc_error_title" style="background: ' +
      settings.background +
      ';">' +
      settings.title +
      "</div>",
  ).appendTo(errorWindow);
  var errorClose = jQuery('<button id="gdymc_error_close"></button>').appendTo(
    errorTitle,
  );
  var errorText = jQuery(
    '<div id="gdymc_error_text">' + settings.text + "</div>",
  ).appendTo(errorWindow);
  if (settings.details != "")
    var errorDetails = jQuery(
      '<details id="gdymc_error_details"><summary>' +
        gdymc.lang("error-details") +
        '</summary><div id="gdymc_error_detailcontent">' +
        settings.details +
        "</div></details>",
    ).appendTo(errorText);
  var errorButtons = jQuery(
    '<div id="gdymc_error_buttons" class="gdymc_fix"></div>',
  ).appendTo(errorWindow);
  var errorLastButton = null;

  // Buttons
  jQuery.each(settings.buttons, function (key) {
    var currentButton = jQuery(
      '<button class="gdymc_button">' +
        settings.buttons[key].text +
        "</button>",
    )
      .appendTo(errorButtons)
      .click(
        { button: settings.buttons[key], settings: settings },
        function (event) {
          event.data.button.action(event.data.settings);
        },
      );

    errorLastButton = currentButton;
  });

  errorWindow.appendTo("body");
  errorWindow.addClass(settings.classes);

  errorShadow.appendTo("body").click(function () {
    settings.close(settings);
  });

  errorClose.click(function () {
    settings.close(settings);
  });

  /**
   * Sets GDYMC set timeout data.
   *
   * @param {*} function( Function( value.
   */
  setTimeout(function () {
    errorWindow.addClass("gdymc_active");
    errorShadow.addClass("gdymc_active");
    errorLastButton.focus();
  }, 10);
};

/**
 * Toggles GDYMC disable softpreview behavior.
 */
gdymc.functions.disable_softpreview = function () {
  jQuery.event.trigger("gdymc_disable_softpreview");

  wpCookies.remove(
    "gdymc_softpreview",
    gdymc_dynamic_data.cookie_path,
    gdymc_dynamic_data.cookie_domain,
  );
  jQuery("body").removeClass("gdymc_softpreview");
  jQuery("body").addClass("gdymc_edit");
  jQuery(".gdymc_text")
    .attr("contenteditable", true)
    .addClass("gdymc_editable");
};

/**
 * Toggles GDYMC enable softpreview behavior.
 */
gdymc.functions.enable_softpreview = function () {
  jQuery.event.trigger("gdymc_enable_softpreview");

  wpCookies.set(
    "gdymc_softpreview",
    "1",
    3600 * 24,
    gdymc_dynamic_data.cookie_path,
    gdymc_dynamic_data.cookie_domain,
  );
  jQuery("body").addClass("gdymc_softpreview");
  jQuery("body").removeClass("gdymc_edit");
  jQuery(".gdymc_text")
    .attr("contenteditable", false)
    .removeClass("gdymc_editable");

  gdymc.functions.close_overlay();
};

/**
 * Closes the GDYMC close overlay interface.
 */
gdymc.functions.close_overlay = function () {
  jQuery(".gdymc_overlay_window").hide().removeClass("gdymc_active");
  jQuery("#gdymc_overlay_shadow").hide().removeClass("gdymc_active");
  jQuery(".gdymc_overlay_images").remove();
  jQuery(".gdymc_overlay_link").remove();

  gdymc.info.overlayOpen = false;

  gdymc.selection.restore();
  return false;
};

/******************************* AJAX *******************************/

/**
 * Handles GDYMC ajax behavior.
 *
 * @param {*} action Action value.
 *
 * @param {*} data Data value.
 *
 * @param {*} callback Callback value.
 */
gdymc.ajax = function (action, data, callback) {
  // Create data if not exists

  if (!data) data = {};

  // Adjustments

  data.action = action;

  // Settings

  var settings = {
    url: gdymc_dynamic_data.ajax_url,
    type: "POST",
    data: data,
    success: callback,

    beforeSend: function (jqXHR, settings) {
      jQuery(document.body).addClass("gdymc_progress");
    },

    complete: function (jqXHR, textStatus) {
      jQuery(document.body).removeClass("gdymc_progress");
    },

    error: function (jqXHR, textStatus, errorThrown) {
      console.log(jqXHR);
      console.log(textStatus);
      console.log(errorThrown);

      var error =
        jqXHR.responseText == "" ? "No response text" : jqXHR.responseText;

      gdymc.functions.error({
        text: gdymc.lang("ajaxerror-text"),
        details: errorThrown + ": " + error,
      });
    },
  };

  // The ajax

  jQuery.ajax(settings);
};

/******************************* ACTIONS *******************************/

/**
 * Deletes a GDYMC module and optionally reloads or runs a callback.
 *
 * @param {*} moduleID Module id value.
 *
 * @param {*} reload Reload value.
 *
 * @param {*} callback Callback value.
 */
gdymc.actions.deletemodule = function (moduleID, reload, callback) {
  // Jump to last scroll position

  wpCookies.set(
    "gdymc_scrollpos",
    jQuery(window).scrollTop(),
    3600 * 24,
    gdymc_dynamic_data.cookie_path,
    gdymc_dynamic_data.cookie_domain,
  );

  var moduleContainer = jQuery("#gdymc_module_" + moduleID);

  var data = {
    object_id: gdymc_dynamic_data.object_id,
    object_type: gdymc_dynamic_data.object_type,
    id: moduleID,
  };

  gdymc.ajax("gdymc_action_deletemodule", data, function (response) {
    if (reload) {
      window.location.href = window.location.href.split("#")[0];
    }

    if (typeof callback != "undefined") {
      callback();
    }
  });
};

/**
 * Deletes a GDYMC module type and optionally reloads or runs a callback.
 *
 * @param {*} moduleType Module type value.
 *
 * @param {*} reload Reload value.
 *
 * @param {*} callback Callback value.
 */
gdymc.actions.deletemoduletype = function (moduleType, reload, callback) {
  // Jump to last scroll position

  wpCookies.set(
    "gdymc_scrollpos",
    jQuery(window).scrollTop(),
    3600 * 24,
    gdymc_dynamic_data.cookie_path,
    gdymc_dynamic_data.cookie_domain,
  );

  var data = {
    object_id: gdymc_dynamic_data.object_id,
    object_type: gdymc_dynamic_data.object_type,
    type: moduleType,
  };

  gdymc.ajax("gdymc_action_deletemoduletype", data, function (response) {
    if (reload) {
      window.location.href = window.location.href.split("#")[0];
    }

    if (typeof callback != "undefined") {
      callback();
    }
  });
};

/**
 * Changes all modules from one GDYMC module type to another.
 *
 * @param {*} oldModule Old module value.
 *
 * @param {*} newModule New module value.
 *
 * @param {*} reload Reload value.
 *
 * @param {*} callback Callback value.
 */
gdymc.actions.changemoduletype = function (
  oldModule,
  newModule,
  reload,
  callback,
) {
  // Jump to last scroll position

  wpCookies.set(
    "gdymc_scrollpos",
    jQuery(window).scrollTop(),
    3600 * 24,
    gdymc_dynamic_data.cookie_path,
    gdymc_dynamic_data.cookie_domain,
  );

  var data = {
    object_id: gdymc_dynamic_data.object_id,
    object_type: gdymc_dynamic_data.object_type,
    oldModule: oldModule,
    newModule: newModule,
  };

  gdymc.ajax("gdymc_action_changemoduletype", data, function (response) {
    if (reload) {
      window.location.href = window.location.href.split("#")[0];
    }

    if (typeof callback != "undefined") {
      callback();
    }
  });
};

/**
 * Changes a single GDYMC module to another module type.
 *
 * @param {*} moduleid Moduleid value.
 *
 * @param {*} moduletype Moduletype value.
 *
 * @param {*} reload Reload value.
 *
 * @param {*} callback Callback value.
 */
gdymc.actions.changesinglemoduletype = function (
  moduleid,
  moduletype,
  reload,
  callback,
) {
  // Jump to last scroll position

  wpCookies.set(
    "gdymc_scrollpos",
    jQuery(window).scrollTop(),
    3600 * 24,
    gdymc_dynamic_data.cookie_path,
    gdymc_dynamic_data.cookie_domain,
  );

  var data = {
    object_id: gdymc_dynamic_data.object_id,
    object_type: gdymc_dynamic_data.object_type,
    moduleid: moduleid,
    moduletype: moduletype,
  };

  gdymc.ajax("gdymc_action_changesinglemoduletype", data, function (response) {
    if (reload) {
      window.location.href = window.location.href.split("#")[0];
    }

    if (typeof callback != "undefined") {
      callback();
    }
  });
};

/******************************* EDITOR *******************************/

/**
 * Opens and configures the editor link window.
 *
 * @param {*} options Options value.
 */
gdymc.editor.link = function (options) {
  var settings = jQuery.extend(
    {
      return: function (object) {},
    },
    options,
  );

  var link = {};

  link.url = prompt("Hehoy");

  settings.set(link);
};

/**
 * Applies a formatting command to the current editor selection.
 *
 * @param {*} command Command value.
 *
 * @param {*} value Value value.
 */
gdymc.editor.format = function (command, value) {
  gdymc.selection.restore();

  if (jQuery("body").hasClass("gdymc_edit")) {
    if (
      gdymc.selection.in(".gdymc_editable") ||
      gdymc.selection.in(".gdymc_option-editable")
    ) {
      document.execCommand(command, false, value);
    } else {
      gdymc.functions.error({
        title: gdymc.lang("focus-title"),
        text: gdymc.lang("focus-text"),
      });
    }
  } else {
    gdymc.functions.error({
      title: gdymc.lang("leavepreview-title"),
      text: gdymc.lang("leavepreview-text"),
      buttons: new Array(
        {
          text: gdymc.lang("button-no"),
          action: function (object) {
            object.close();
          },
        },
        {
          text: gdymc.lang("leavepreview-button"),
          action: function (object) {
            object.close();

            jQuery("#gdymc_togglesoftpreview").click();
          },
        },
      ),
    });
  }
};

/**
 * Handles GDYMC applyclass behavior.
 *
 * @param {*} classes Classes value.
 *
 * @param {*} options Options value.
 */
gdymc.editor.applyclass = function (classes, options) {
  window.setTimeout(function () {
    if (document.activeElement.isContentEditable) {
      var handler = rangy.createClassApplier(classes, options);

      //console.log( handler.cssClass = 'asdf' );

      handler.toggleSelection();
    }
  }, 2);
};

/**
 * Adds GDYMC addtag data or controls.
 *
 * @param {*} tag Tag value.
 *
 * @param {*} attributes Attributes value.
 */
gdymc.editor.addtag = function (tag, attributes) {
  gdymc.editor.applyclass("gdymc_tag_" + tag, {
    elementTagName: tag,
    elementProperties: attributes,
  });
};

/**
 * Adds GDYMC addclass data or controls.
 *
 * @param {*} classname Classname value.
 *
 * @param {*} attributes Attributes value.
 */
gdymc.editor.addclass = function (classname, attributes) {
  gdymc.editor.applyclass(classname, {
    elementTagName: "span",
    elementProperties: attributes,
  });
};

/**
 * Saves the current editor selection.
 */
gdymc.selection.save = function () {
  if (gdymc.info.selectionRange) {
    rangy.removeMarkers(gdymc.info.selectionRange);
  }
  gdymc.info.selectionRange = rangy.saveSelection();
  gdymc.info.selectionRangeActiveElement = document.activeElement;
};

/**
 * Handles GDYMC restore behavior.
 */
gdymc.selection.restore = function () {
  if (gdymc.info.selectionRange) {
    rangy.restoreSelection(gdymc.info.selectionRange, true);
    gdymc.info.selectionRange = null;
    window.setTimeout(function () {
      if (
        gdymc.info.selectionRangeActiveElement &&
        typeof gdymc.info.selectionRangeActiveElement.focus != "undefined"
      ) {
        gdymc.info.selectionRangeActiveElement.focus();
      }
    }, 1);
  }
};

/**
 * Handles GDYMC parent behavior.
 */
gdymc.selection.parent = function () {
  var parentEl = null,
    sel;
  if (window.getSelection) {
    sel = window.getSelection();
    if (sel.rangeCount) {
      parentEl = sel.getRangeAt(0).commonAncestorContainer;
      if (parentEl.nodeType != 1) {
        parentEl = parentEl.parentNode;
      }
    }
  } else if ((sel = document.selection) && sel.type != "Control") {
    parentEl = sel.createRange().parentElement();
  }

  return jQuery(parentEl);
};

/**
 * Handles GDYMC in behavior.
 *
 * @param {*} selector Selector value.
 */
gdymc.selection.in = function (selector) {
  var parent = gdymc.selection.parent();

  if (parent.closest(selector).length > 0) {
    return true;
  } else {
    return false;
  }
};
