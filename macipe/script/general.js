function deleteCookie(name) {
  document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

function logout() {
  deleteCookie('PHP_SESSID');
  deleteCookie('identifier');
  deleteCookie('hashed_password');
  window.location = window.location;
}

$(document).click(function(event) {
  if(!$(event.target).closest('.popup').length) {
    if($('#overlay').is(":visible")) {
      $('#overlay').fadeOut();
    }
  }
});
