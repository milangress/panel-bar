if (!jQuery) {
  // remove switch if jQuery isn't loaded
  var switchbtn = document.getElementById("panelbar_switch");
  switchbtn.remove();
  var panelbar = document.getElementById("panelbar");
  panelbar.style.paddingRight = 0;
  panelbar.classList.remove("hidden");

} else {
  $(function() {

    // Visibility toggle
    $(".panelbar__switch").on("click", function () {
      $(".panelbar").toggleClass("hidden");
    });

    // Element: toggle
    if (enhancedJS === true) {
      $(".panelbar--toggle > a").on("click", function (e) {
        e.preventDefault();

        var status = $(this).find('span').text() == 'Visible' ? 'hide' : 'publish';
        var url    = siteURL + "/panel/api/pages/" + status + "/" + currentURI;

        $.ajax({
          type: "POST",
          url: url
        });

        $(this).find('.fa').toggleClass('fa-toggle-off fa-toggle-on');
        $(this).find('span').text(status == "hide" ? "Invisible" : "Visible");

        setTimeout(function() {
          location.reload();
        }, 100);

      });
    }

  });
}
