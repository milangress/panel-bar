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

//read current margin and add/substract
function SetTopMargin (Value){
  var marginBody = body.style.marginTop.replace('px', '').trim();
  var newMarginBody = (+marginBody) + (+Value);
  var top = marginBody;
  function frame() {
    if (top < newMarginBody){
      top = (+top) + 6 
    }
    else {
      top = (+top) - 6
    }
    body.setAttribute("style", "margin-top:" + top.toString() + "px");
    console.log(top);
    if (top == newMarginBody)  // check finish condition
      clearInterval(id)
  }
  var id = setInterval(frame, 20) // draw every 20ms
}

//Cutting the mustard! (breaking the browser in two groups: html5 and html4)
if ( 'querySelector' in document && 'addEventListener' in window ) { 

    // Push body down if panelbar::hide is true
    if (!(hasClass(panelbar, 'hidden') && hasClass(panelbar, 'top'))){
      SetTopMargin(+48);
    }
    
    // Visibility toggle & flip
    switchbtn.addEventListener('click', function (e) {

      if (hasClass(panelbar, 'hidden') ) {
        removeClass(panelbar, 'hidden');
        if (hasClass(panelbar, 'top')){
            SetTopMargin(+48); // Push body down
          }
        }

        else {
          addClass(panelbar, 'hidden');
          if (hasClass(panelbar, 'top')){
            SetTopMargin(-48); // Push body up
          }
        }

      });
    flip.addEventListener('click', function (e) {

      if (hasClass(panelbar, 'top') ) {
        removeClass(panelbar, 'top');
        SetTopMargin(-48); // Push body up
      }
      else {
        addClass(panelbar, 'top');
      }

      if (hasClass(panelbar, 'bottom') ) {
        removeClass(panelbar, 'bottom');
        SetTopMargin(+48); // Push body down
      }
      else {
        addClass(panelbar, 'bottom');
      }

    });
  }

else {
  // remove switch in legacy Browser
  switchbtn.remove();
  panelbar.style.paddingRight = 0;
  panelbar.classList.remove("hidden");
}

$(function() {
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
