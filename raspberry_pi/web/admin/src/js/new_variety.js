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