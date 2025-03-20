$('document').ready(function() {
  $('#login_submit').click(function() {
    if ($('#username').val() == '') {
      alert('Please enter your username');
      return;
    }
    if ($('#password').val() == '') {
      alert('Please enter your password');
      return;
    }
    $.post('backend/php/login-process.php', {
      username: $('#username').val(),
      password: $('#password').val()
    }, function(data) {
      if (data == 'success') {
        window.location.href = 'index.php';
      } else {
        $('#error').html(data);
      }
    });
  });
});
