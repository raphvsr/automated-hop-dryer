//                  file users.js
// ===============================================
//          Original Author: fateh kabbani
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-05-23 - Refactor sensor reading logic in read_sensor.py, including improved logging and temperature handling. Update GPIO pin configuration in validate.py. Change API endpoint in users.js for user updates. Enhance error handling and logging in varieties-create.php and login-process.php, including session and request logging. Modify register-process.php to ensure all fields are validated. Add info.php for PHP configuration display. Create log files for login attempts to aid in debugging. - fateh kabbani
//   raspberry_pi/web/admin/src/js/users.js | 2 +-
//   1 file changed, 1 insertion(+), 1 deletion(-)
//
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-04-01 - Implement user editing functionality: add modal for editing user details and AJAX request for updates - fateh kabbani
//   raspberry pi/web/admin/src/js/users.js | 47 ++++++++++++++++++++++++++++++++++
//   1 file changed, 47 insertions(+)
//
// 2025-03-31 - Add user deletion functionality: implement AJAX request and backend processing for user removal - fateh kabbani
//   raspberry pi/web/admin/src/js/users.js | 16 ++++++++++++++++
//   1 file changed, 16 insertions(+)
//
// ============================================================

function deleteUser(id) {
  if (confirm('Vous êtes sûr de vouloir supprimer cet utilisateur ?')) {
    $.post('../backend/php/api/users-delete.php', { id: id })
      .done(function (response) {
        const data = JSON.parse(response);
        if (data.status === 'success') {
          $('#user-' + id).remove(); // ex: user-1
        } else {
          alert(data.message);
        }
      })
      .fail(function () {
        alert('Erreur, l\'utilisateur n\'a pas été supprimé.');
      });
  }
}

$('.btn-edit').on('click', function () {
  const userId = $(this).data('id');
  const username = $(this).closest('tr').find('td:first').text();
  const role = $(this).closest('tr').find('.role-badge').hasClass('admin') ? '1' : '0';
  $('#userModal').show();
  $('#userId').val(userId);
  $('#username').val(username);
  $('#role').val(role);

});

$('.close, .btn-cancel').on('click', function () {
  $('#userModal').hide();
});

$('#save').on('click', function (e) {
  console.log('save');
  const userId = $('#userId').val();
  const username = $('#username').val();
  let password = $('#password').val();
  const role = $('#role').val();

  if(password == ''){
    password = null;
  }

  $.post('../backend/php/api/user-edit.php',
    {
      username: username,
      role: role,
      password: password || "",
      id: userId
    }
  )
    .done(function (response) {
      const data = JSON.parse(response);
      if (data.status === 'success') {
          location.reload();
      } else {
        alert(data.message);
      }
    })
    .fail(function () {
      alert('Erreur lors de la requête AJAX.');
    });
});
