//                 file new_user.js               
// ===============================================
//          Original Author: fateh kabbani        
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-04-01 - Add variety management functionality: implement create, update, and delete APIs with AJAX integration + changed the database - fateh kabbani
//   raspberry pi/web/admin/src/js/new_user.js | 30 ++++++++++++++++++++----------
//   1 file changed, 20 insertions(+), 10 deletions(-)
//
// 2025-03-30 - Add user creation functionality: implement form validation, AJAX submission, and backend processing - fateh kabbani
//   raspberry pi/web/admin/src/js/new_user.js | 44 +++++++++++++++++++++++++++++++
//   1 file changed, 44 insertions(+)
//
// 2025-03-29 - Refactor user management: remove admin dashboard, update links, and add new user creation functionality with password generation - fateh kabbani
//   raspberry pi/web/admin/src/js/new_user.js | 32 +++++++++++++++++++++++++++++++
//   1 file changed, 32 insertions(+)
//
// ============================================================

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

$('#new_user').click(function (e) {
  e.preventDefault();
  
  const username = $('#username').val().trim();
  const password = $('#password').val();
  const confirmPassword = $('#confirm-password').val();
  const role = $('#role').val();

  if (!username || !password || !confirmPassword || !role) {
    alert('All fields must be filled!');
    return;
  }

  if (password !== confirmPassword) {
    alert('Passwords do not match!');
    return;
  }

  const formData = {
    username: username,
    password: password,
    role: role
  };

  $.ajax({
    url: '../backend/php/register-process.php',
    method: 'POST',
    data: formData,
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
