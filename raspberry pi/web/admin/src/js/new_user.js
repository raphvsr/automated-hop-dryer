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
