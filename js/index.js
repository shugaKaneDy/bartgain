$(document).ready(function() {

  // this creates dynamic padding top
  let myNavHeight = $('.my-nav').outerHeight();
  $('body').css('padding-top', `${myNavHeight + 10}px`);
  
});
