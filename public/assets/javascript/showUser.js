$(document).ready(function () {
  $("#modify").click(function (event) {
    var $this = $(this);
    var icon = $this.find("i.fa");
    var iconClass = icon.attr("class");
    var span = $this.find("#btn");
    var h2 = $("form h2");
    var params = $("#params");
    var control = params.find("p.control");
    var cancel = $this.next();
    if (iconClass == "fa fa-gears") {
      event.preventDefault();
      $this.toggleClass("is-info").toggleClass("is-success");
      icon.toggleClass("fa-gears").toggleClass("fa-check-square-o");
      span.text("Confirm modifies?");
      control.show();
      h2.hide();
      cancel.show();
    }
  });
  $("form").submit(function () {
    var pwd = $("#pwd");
    var val = pwd.prev().val();
    if (val != "") {
      var sha = SHA1(val);
      pwd.val(sha);
    }
  });
  $("#cancel").click(function (event) {
    event.preventDefault();
    var $this = $(this);
    var mod = $("#modify");
    var icon = mod.find("i.fa");
    var span = mod.find("#btn");
    var h2 = $("form h2");
    var params = $("#params");
    var control = params.find("p.control");
    control.hide();
    h2.show();
    $this.hide();
    span.text("Modify");
    mod.toggleClass("is-info").toggleClass("is-success");
    icon.toggleClass("fa-gears").toggleClass("fa-check-square-o");
  });
});
