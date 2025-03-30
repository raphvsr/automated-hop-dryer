function generatePassword() {
  let length = 12;
  let charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#";
  let password = "";
  for (let i = 0, n = charset.length; i < length; ++i) {
    password += charset.charAt(Math.floor(Math.random() * n));
  }
  return password;
}

$('#generatePassword').click(function () {
  $('#password').val(generatePassword());
  $('#confirm-password').val($('#password').val());
  navigator.clipboard.writeText($('#password').val()).then(function () {
    alert('Password copied to clipboard');
  }, function (err) {
    console.error('Could not copy text: ', err);
  });
});

$('.toggle-password').click(function () {
  const targetId = $(this).data('target');
  const input = $('#' + targetId);

  if (input.attr('type') === 'password') {
    input.attr('type', 'text');
    $(this).removeClass('fa-eye-slash').addClass('fa-eye');
  } else {
    input.attr('type', 'password');
    $(this).removeClass('fa-eye').addClass('fa-eye-slash');
  }
});

$('#new_user').click(function (){
  if($('#password').val() !== $('#confirm-password').val()) {
    alert('Passwords do not match!');
    return;
  }
  if($('#username').val() === '' || $('#password').val() === '' || $('#confirm-password').val() === '' || $('#role').val() === '') {
    alert('All fields must be filled!');
    return;
  }

  $.ajax({
    url: '../backend/php/register-process.php',
    method: 'POST',
    data: {
      username: $('#username').val(),
      password: $('#password').val(),
      role: $('#role').val()
    },
    dataType: 'json',
    success: function(response) {
      if (response.status === 'success') {
        alert(response.message);
        $('#username').val('');
        $('#password').val('');
        $('#confirm-password').val('');
        $('#role').val('');
        window.location.href = 'users.php';
      } else {
        alert('Error: ' + response.message);
      }
    },
    error: function(xhr, status, error) {
      let errorMessage = 'An error occurred while creating the user.';
      try {
        const response = JSON.parse(xhr.responseText);
        errorMessage = response.message || errorMessage;
      } catch (e) {
        console.error('Error parsing response:', e);
      }
      alert(errorMessage);
    }
  });
});
