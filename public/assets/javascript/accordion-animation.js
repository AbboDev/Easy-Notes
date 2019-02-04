$(document).ready(function () {
  var acc = $(".accordion");
  acc.each(function () {
    toggleAccordion($(this));
  });
});

function toggleAccordion($this) {
  $this.click(function () {
    $this.toggleClass("active");
    var panel = $this.next();
    panel.slideToggle(500);
  });
}
