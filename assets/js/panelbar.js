// Simple class handler library
var hasClass = function (elem, className) {
    return new RegExp(' ' + className + ' ').test(' ' + elem.className + ' ');
}
var addClass = function (elem, className) {
    if (!hasClass(elem, className)) {
        elem.className += ' ' + className;
    }
}
var removeClass = function (elem, className) {
    var newClass = ' ' + elem.className.replace( /[\t\r\n]/g, ' ') + ' ';
    if (hasClass(elem, className)) {
        while (newClass.indexOf(' ' + className + ' ') >= 0 ) {
            newClass = newClass.replace(' ' + className + ' ', ' ');
        }
        elem.className = newClass.replace(/^\s+|\s+$/g, '');
    }
}


var switchbtn = document.getElementById('panelbar_switch');
var panelbar = document.getElementById('panelbar');
var flip = document.getElementById('panelbar_flip');
var body = document.getElementsByTagName("body")[0];

if ( 'querySelector' in document && 'addEventListener' in window ) {

    // Push body down if panelbar::hide is true
    if(!(hasClass(panelbar, 'hidden') && hasClass(panelbar, 'top'))){
      body.setAttribute("style", "margin-top:48px;");
    }
    
    // Visibility toggle & flip
    switchbtn.addEventListener('click', function(e) {
        
        if ( hasClass(panelbar, 'hidden') ) {
          removeClass(panelbar, 'hidden');
          if(hasClass(panelbar, 'top')){
            body.setAttribute("style", "margin-top:48px;");
          }
        }
        else {
          addClass(panelbar, 'hidden');
          if(hasClass(panelbar, 'top')){
            body.setAttribute("style", "margin-top:0px;");
          }
        }

  });
    flip.addEventListener('click', function(e) {
        
        if ( hasClass(panelbar, 'top') ) {
          removeClass(panelbar, 'top');
            }
        else {
          addClass(panelbar, 'top');
            }
        if ( hasClass(panelbar, 'bottom') ) {
          removeClass(panelbar, 'bottom');
            }
        else {
          addClass(panelbar, 'bottom');
            }

  });
}
else{
  // remove switch in legacy Browser
  switchbtn.remove();
  panelbar.style.paddingRight = 0;
  panelbar.classList.remove("hidden");
}

$(function() {

    // Visibility toggle & flip
    $(".panelbar__switch").on("click", function () {
      $(".panelbar").toggleClass("hidden");
    });

    $(".panelbar__flip").on("click", function () {
      $(".panelbar").toggleClass("top bottom");
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
