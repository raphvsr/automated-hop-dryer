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
