//                file varieties.js               
// ===============================================
//          Original Author: fateh kabbani        
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-04-01 - Add variety management functionality: implement create, update, and delete APIs with AJAX integration + changed the database - fateh kabbani
//   raspberry pi/web/admin/src/js/varieties.js | 66 ++++++++++++++++++++++++++++++
//   1 file changed, 66 insertions(+)
//
// ============================================================

function deleteVariety(id) {
  if (confirm('Êtes-vous sûr de vouloir supprimer cette variété ?')) {
    $.post('../backend/php/api/varieties-delete.php', { id: id })
      .done(function (response) {
        const data = JSON.parse(response);
        if (data.status === 'success') {
          $('#variety-' + id).remove();
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
  const varietyId = $(this).data('id');
  const row = $(this).closest('tr');
  const name = row.find('td:first').text();
  const maxTemp = row.find('td:nth-child(2)').text().replace('°C', '');
  const minTemp = row.find('td:nth-child(3)').text().replace('°C', '');
  const duree = row.find('td:nth-child(4)').text();

  $('#varietyModal').show();
  $('#varietyId').val(varietyId);
  $('#name').val(name);
  $('#max_temperature').val(maxTemp);
  $('#min_temperature').val(minTemp);
  $('#duree_de_sechage').val(duree);
});

$('.close, .btn-cancel').on('click', function () {
  $('#varietyModal').hide();
});

$('#save').on('click', function (e) {
  const varietyId = $('#varietyId').val();
  const name = $('#name').val();
  const maxTemp = $('#max_temperature').val();
  const minTemp = $('#min_temperature').val();
  const duree = $('#duree_de_sechage').val();

  $.post('../backend/php/api/varieties-update.php',
    {
      id: varietyId,
      name: name,
      max_temperature: maxTemp,
      min_temperature: minTemp,
      duree_de_sechage: duree
    }
  )
    .done(function (response) {
      const data = JSON.parse(response);
      console.log(data)
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