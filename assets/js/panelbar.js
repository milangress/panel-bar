if (!jQuery) {
  // remove switch if jQuery isn't loaded
  var switchbtn = document.getElementById("panelbar_switch");
  switchbtn.remove();
  var panelbar = document.getElementById("panelbar");
  panelbar.style.paddingRight = 0;


} else {
  $(".panelbar__switch").on("click", function () {
    $(".panelbar, .panelbar__switch").toggleClass("hidden");
  });
}
