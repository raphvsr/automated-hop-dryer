function deleteUser(id) {
  if (confirm('Vous êtes sûr de vouloir supprimer cet utilisateur ?')) {
    $.post('../backend/php/api/users-delete.php', { id: id })
      .done(function (response) {
        const data = JSON.parse(response);
        if (data.status === 'success') {
          $('#user-' + id).remove();
        } else {
          alert(data.message);
        }
      })
      .fail(function () {
        alert('Erreur lors de la requête AJAX.');
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
