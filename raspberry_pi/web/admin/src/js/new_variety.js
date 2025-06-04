//               file new_variety.js              
// ===============================================
//          Original Author: fateh kabbani        
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-04-01 - Add variety management functionality: implement create, update, and delete APIs with AJAX integration + changed the database - fateh kabbani
//   raspberry pi/web/admin/src/js/new_variety.js | 24 ++++++++++++++++++++++++
//   1 file changed, 24 insertions(+)
//
// ============================================================

$('#newVarietyForm').on('submit', function (e) {
    e.preventDefault();

    const formData = {
        name: $('#name').val(),
        max_temperature: $('#max_temperature').val(),
        min_temperature: $('#min_temperature').val(),
        duree_de_sechage: $('#duree_de_sechage').val()
    };

    $.post('../backend/php/api/varieties-create.php', formData)
        .done(function (response) {
            const data = JSON.parse(response);
            console.log(data);
            if (data.status === 'success') {
                window.location.href = 'varieties.php';
            } else {
                alert(data.message);
            }
        })
        .fail(function () {
            alert('Erreur lors de la requête AJAX.');
        });
});