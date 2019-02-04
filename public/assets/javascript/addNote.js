var title, dateWrite, dateInsert, subject, note, confirm;
$(document).ready(function () {
  title = $("#title");
  dateWrite = $("#date-write");
  dateInsert = $("#date-insert");
  subject = $("#subject");
  hidden = $("#note");
  note = hidden.prev();
  button = $("#confirm");
  title.on({
    blur: function () {
      activeButton();
    },
    keyup: function () {
      activeButton();
    }
  });
  dateWrite.on({
    blur: function () {
      activeButton();
    },
    keyup: function () {
      activeButton();
    }
  });
  dateInsert.on({
    blur: function () {
      activeButton();
    },
    keyup: function () {
      activeButton();
    }
  });
  subject.on({
    blur: function () {
      activeButton();
    },
    keyup: function () {
      activeButton();
    }
  });
  note.on({
    blur: function () {
      activeButton();
    },
    keyup: function () {
      activeButton();
    }
  });
  var insert = $("#datepicker-insert");
  if (insert.length == 0) {
    if (isMobile) {
      $("#datepicker-write").datepicker({
        showOtherMonths: true,
        selectOtherMonths: true,
        numberOfMonths: 1,
        showButtonPanel: true,
        altField: "#date-write",
        altFormat: "yy-mm-dd"
      });
    } else {
      $("#datepicker-write").datepicker({
        showOtherMonths: true,
        selectOtherMonths: true,
        numberOfMonths: 3,
        showButtonPanel: true,
        altField: "#date-write",
        altFormat: "yy-mm-dd"
      });
    }
  } else {
    $("#datepicker-write").datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      numberOfMonths: 1,
      showButtonPanel: true,
      altField: "#date-write",
      altFormat: "yy-mm-dd"
    });
    insert.datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      numberOfMonths: 1,
      showButtonPanel: true,
      altField: "#date-insert",
      altFormat: "yy-mm-dd"
    });
  }
  // var params = [
  //   ['font', 'color=\"black\"'],
  //   ['font', 'color=\"red\"'],
  //   ['font', 'color=\"blue\"'],
  //   ['font', 'color=\"green\"'],
  //   ['font', 'color=\"yellow\"'],
  //   ['font', 'color=\"purple\"']
  // ];
  // $("#buttonMenu").find("button").each(function(index) {
  //   var $this = $(this);
  //   $this.click(function(e) {
  //     e.preventDefault();
  //     addHTML(params[index][0], params[index][1]);
  //   });
  // });
  button.click(function() {
    hidden.val(htmlEscape(note.val()));
  });
});
/**
*
*/
function activeButton() {
  if (title.val() != "" && dateWrite.val() != "" &&
  dateInsert.val() != "" && subject.val() != "") {
    button.removeClass("is-disabled");
  } else if (!button.hasClass("is-disabled")) {
    button.addClass("is-disabled");
  }
}
/**
 *
 */
function htmlEscape(str) {
  return str
    .replace(/&/g, '&amp;')
    .replace(/"/g, '&#34;')
    .replace(/'/g, '&#39;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;');
}
/**
 *
 */
function addHTML(html, attr) {
  var str = getSelectionText();
  if (str != null || str != "") {
    if (attr != null) {
      var openTag = "<"+html+" "+attr+">";
    } else {
      var openTag = "<"+html+">";
    }
    var closeTag = "</"+html+">";
    str = str.replace(str, openTag+str+closeTag);
    pasteHtmlAtCaret(str, true);
  }
}
/**
 *
 */
 function pasteHtmlAtCaret(html, selectPastedContent) {
  var sel, range;
  if (window.getSelection) {
    // IE9 and non-IE
    sel = window.getSelection();
    if (sel.getRangeAt && sel.rangeCount) {
      range = sel.getRangeAt(0);
      range.deleteContents();
      // if (getSelectionParentElement() !=)
      // getSelectionParentElement().remove();
      // Range.createContextualFragment() would be useful here but is
      // only relatively recently standardized and is not supported in
      // some browsers (IE9, for one)
      var el = document.createElement("div");
      el.innerHTML = html;
      var frag = document.createDocumentFragment(), node, lastNode;
      while ( (node = el.firstChild) ) {
        lastNode = frag.appendChild(node);
      }
      var firstNode = frag.firstChild;
      range.insertNode(frag);
      // Preserve the selection
      if (lastNode) {
        range = range.cloneRange();
        range.setStartAfter(lastNode);
        if (selectPastedContent) {
          range.setStartBefore(firstNode);
        } else {
          range.collapse(true);
        }
        sel.removeAllRanges();
        sel.addRange(range);
      }
    }
  } else if ( (sel = document.selection) && sel.type != "Control") {
    // IE < 9
    var originalRange = sel.createRange();
    originalRange.collapse(true);
    sel.createRange().pasteHTML(html);
    var range = sel.createRange();
    range.setEndPoint("StartToStart", originalRange);
    range.select();
  }
}
/**
 *
 */
function getSelectionText() {
  var text = "";
  var activeEl = document.activeElement;
  var activeElTagName = activeEl ? activeEl.tagName.toLowerCase() : null;
  if (
    (activeElTagName == "textarea") || (activeElTagName == "div") ||
    (activeElTagName == "input" && /^(?:text|search|password|tel|url)$/i.test(activeEl.type)) &&
    (typeof activeEl.selectionStart == "number")
  ) {
    text = activeEl.value.slice(activeEl.selectionStart, activeEl.selectionEnd);
  } else if (window.getSelection) {
    text = window.getSelection().toString();
  }
  return text;
}
/**
 *
 */
function getSelectionParentElement() {
  var parentEl = null, sel;
  if (window.getSelection) {
    sel = window.getSelection();
    if (sel.rangeCount) {
      parentEl = sel.getRangeAt(0).commonAncestorContainer;
      if (parentEl.nodeType != 1) {
        parentEl = parentEl.parentNode;
      }
    }
  } else if ( (sel = document.selection) && sel.type != "Control") {
    parentEl = sel.createRange().parentElement();
  }
  return parentEl;
}
