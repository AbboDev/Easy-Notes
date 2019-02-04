var sub1, sub2, sub3;
$(document).ready(function(){
  var acc = $(".accordion");
  acc.each(function () {
    var $this = $(this);
    $this.click(function () {
      completeAccordion($this);
    });
  });
  sub1 = $("#sub1");
  sub2 = $("#sub2");
  sub3 = $("#sub3");

  sub1.change(function () {
    var num = sub1.find(":selected").val();
    if (num != "0") {
      sub2.removeClass("is-disabled");
      $('#sub2 option').each(function() {
        if ($(this).val() == num) {
          $(this).hide();
        } else {
          $(this).show();
        }
        if (sub2.find(":selected").val() == num) {
          sub2.val("0");
        }
      });
      $('#sub3 option').each(function() {
        if ($(this).val() == num) {
          $(this).hide();
        } else {
          $(this).show();
        }
        if (sub3.find(":selected").val() == num) {
          sub3.val("0");
        }
      });
    } else {
      sub2.addClass("is-disabled");
      sub2.val("0");
      sub3.addClass("is-disabled");
      sub3.val("0");
    }
  });

  sub2.change(function () {
    var num = sub2.find(":selected").val();
    var val = sub1.find(":selected").val();
    if (num != "0") {
      sub3.removeClass("is-disabled");
      $('#sub3 option').each(function() {
        if ($(this).val() == num || $(this).val() == val) {
          $(this).hide();
        } else {
          $(this).show();
        }
        if (sub3.find(":selected").val() == num) {
          sub3.val("0");
        }
      });
    } else {
      sub3.addClass("is-disabled");
      sub3.val("0");
    }
  });
  $(".clock").each(function () {
    var $this = $(this);
    $this.click(function () {
      showCurrentDate($this);
    });
  });
  $(".clear").each(function () {
    var $this = $(this);
    $this.click(function () {
      clearText($this);
    });
  });
});
/**
 *
 */
function completeAccordion($this) {
  if (!$this.hasClass("is-loaded")) {
    if ($.isNumeric($this.next().attr("id"))) {
      $.get("get", {"parent": $this.next().attr("id")}, function (result) {
        $this.append('<span class="icon"><i class="fa fa-spin fa-gear fa-fw"></i></span>');
        $this.addClass("is-loaded");
        var next = $this.next();
        var json = $.parseJSON(result);
        var index, rows, row;
        var id, title, description;
        for (index in json) {
          rows = $.parseJSON(json[index]);
          for (row in rows) {
            switch (row) {
              case "id":
                id = rows[row];
                break;
              case "title":
                title = rows[row];
                break;
              case "description":
                description = rows[row];
                break;
              default:
                break;
            }
          }
          var desc = "";
          for (var i = 0; i < description.length; ++i) {
            desc += "<span>"+decodeURI(description[i])+"</span>";
            if (i < description.length) {
              desc += "<br>";
            }
          }
          var button = $('<button class="accordion button is-outlined is-fullwidth" id="'+id+'"><span>'+title+'</span></button>')
          button.click(function () {
            completeAccordion($(this));
          });
          var div = $(
            '<div class="panel notification" id="'+id+'" hidden>'+desc+
            ' <div class="columns">'+
            '  <div class="column">'+
            '   <a class="button is-success is-small is-fullwidth" href="new_child_'+id+'">'+
            '    <span class="icon is-small"><i class="fa fa-plus"></i></span>'+
            '    <span>Add new Note to this one!</span>'+
            '   </a>'+
            '  </div><div class="column">'+
            '   <a class="button is-info is-small is-fullwidth" href="'+id+'">'+
            '    <span class="icon is-small"><i class="fa fa-file"></i></span>'+
            '    <span>Show this Note!</span>'+
            '   </a>'+
            '  </div><div class="column">'+
            '   <a class="button is-warning is-small is-fullwidth" href="'+id+'/modify">'+
            '    <span class="icon is-small"><i class="fa fa-gears"></i></span>'+
            '    <span>Modify this Note!</span>'+
            '   </a>'+
            '  </div><div class="column">'+
            '   <button class="button is-danger is-small is-fullwidth" onclick="deleteNote(this)">'+
            '    <span class="icon is-small"><i class="fa fa-close"></i></span>'+
            '    <span>Delete this Note!</span>'+
            '   </button>'+
            '  </div>'+
            ' </div>'+
            '</div>');
          next.append(button, div);
        }
        toggleAccordion($this);
        $this.find("span.icon").remove();
      });
    } else {
      toggleAccordion($this);
    }
  } else {
    toggleAccordion($this);
  }
}
/**
 *
 */
function toggleAccordion($this) {
  $this.toggleClass("active");
  var id = "#"+$this.attr("id");
  var panel = $this.siblings(id);
  panel.slideToggle(500);
}
/**
 *
 */
function showCurrentDate($this) {
  var parent = $this.parent();
  var prev = parent.prev();
  var input = prev.find("input");
  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth()+1; //January is 0!
  var year = today.getFullYear();
  input.val(year+"/"+mm+"/"+dd);
}
/**
 *
 */
function clearText($this) {
  var parent = $this.parent();
  var prev = parent.prev();
  var input = prev.find("input");
  input.val("");
}
/**
 *
 */
function openModal() {
  $("#modal").toggleClass("is-active")
}
/**
 *
 */
function updateTable() {
  var asc = $("#asc").attr('class');
  var desc = $("#desc").attr('class');
  var search = $("#search").val();
  var sub1 = $("#sub1").find(":selected").val();
  var sub2 = $("#sub2").find(":selected").val();
  var sub3 = $("#sub3").find(":selected").val();
  var from = $("#from").val();
  var to = $("#to").val();
  var insert = $("#insert").is(":checked");
  var write = $("#write").is(":checked");
  var order;

  if (asc == "button is-primary") {
    order = "ASC";
  } else {
    order = "DESC";
  }
  var params = {
    "order" : order, "search" : search,
    "from" : from, "to" : to,
    "insert" : insert, "write" : write,
    "sub1" : sub1, "sub2" : sub2,
    "sub3" : sub3
  };

  $.get("filter", params, function (result) {
      var json = $.parseJSON(result);
      var index, rows, row;
      var id, title, description, desc = "";
      for (index in json) {
        rows = $.parseJSON(json[index]);
        for (row in rows) {
          switch (row) {
            case "id":
              id = rows[row];
              break;
            case "title":
              title = rows[row];
              break;
            case "description":
              description = rows[row];
              break;
            default:
              break;
          }
        }
        for (var i = 0; i < description.length; ++i) {
          desc += "<span>"+description[i]+"</span>";
          if (i != description.length) {
            desc += "<br>";
          }
        }
      }
    }
  );
}
/**
 *
 */
function switchButton() {
  if ($('#desc').attr('class') == "button is-primary") {
    $("#asc").addClass("is-primary");
    $("#desc").removeClass("is-primary");
  } else {
    $("#desc").addClass("is-primary");
    $("#asc").removeClass("is-primary");
  }
}
/**
 *
 */
function deleteNote(self) {
  var ok = confirm("Do you really want to delete this Note, along with its children?");
  var button = $(self);
  var parents = button.parentsUntil(".panel");
  var panel = parents.last().parent();
  if (ok) {
    window.location.href = panel.attr("id")+"/delete";
  }
}
