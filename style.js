document.addEventListener("DOMContentLoaded", function () {
  document.getElementById("menubutton").addEventListener("click", function () {
    this.classList.toggle("active");
    document.getElementById("nav").classList.toggle("active");
    document.getElementById("mask").classList.toggle("active");
  });
  document.getElementById("mask").addEventListener("click", function () {
    this.classList.toggle("active");
    document.getElementById("nav").classList.toggle("active");
    document.getElementById("menubutton").classList.toggle("active");
  });
});

$(function () {
  var pagetop = $('#page_top');
  pagetop.hide();
  $(window).scroll(function () {
    if ($(this).scrollTop() > 0) {
      pagetop.fadeIn();
    } else {
      pagetop.fadeOut();
    }
  });

  pagetop.click(function () {
    $('body,html').animate({
      scrollTop: 0
    }, 500);
    return false;
  });
});
