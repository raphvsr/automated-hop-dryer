//                  file login.js                 
// ===============================================
//          Original Author: fateh kabbani        
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-20 - move the file to raspberry pi - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-15 - Enhance drying control interface with real-time data visualization, improved layout, and new temperature configuration options - fateh kabbani
//   web/src/js/login.js | 2 +-
//   1 file changed, 1 insertion(+), 1 deletion(-)
//
// 2025-03-13 - Refactor project structure by moving backend files to a new directory and re-implementing login and registration functionality - Romain Provencel
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-13 - Add initial project structure with login and registration functionality - fateh kabbani
//   src/js/login.js | 22 ++++++++++++++++++++++
//   1 file changed, 22 insertions(+)
//
// ============================================================

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
