$(document).ready(function () {
    let temperatureChart = null;
    let timePoints = ["Time 1", "Time 2", "Time 3", "Time 4", "Time 5", "Time 6"];
    let historicalData = [];

    let alertedSensors = new Set(); // Pour éviter les alertes répétées

    function getTemperatureData() {
    $.post("backend/php/api/get_temperatures.php", {}, function (data) {
        const temperatures = JSON.parse(data);

        historicalData.push(temperatures);
        if (historicalData.length > 6) {
        historicalData.shift();
        }

        updateTable(temperatures);

        // Vérification de dépassement de température
        const maxTemperatures = $(".varieties-badge")
        .map(function () {
            return $(this).data("max-temperature");
        })
        .get();

        if (maxTemperatures.length === 0) return;

        const globalMaxTemp = Math.max(...maxTemperatures);

        temperatures.forEach((entry) => {
        if (entry.temperature > globalMaxTemp) {
            if (!alertedSensors.has(entry.sensor)) {
            alertedSensors.add(entry.sensor); // Marque ce capteur comme déjà alerté
            alert(
                `⚠️ Capteur ${entry.sensor} a dépassé la température maximale !\nTempérature actuelle : ${entry.temperature}°C\nLimite : ${globalMaxTemp}°C`
            );
            }
        } else {
            // Si la température redevient normale, on autorise à réalerter plus tard
            alertedSensors.delete(entry.sensor);
        }
        });
    }).fail(function () {
        console.log("Erreur lors de la récupération des données de température.");
    });
    }


    function updateTable(data) {
        const tableBody = $("#dataTable tbody");
        tableBody.empty();
        data.forEach((entry) => {
        tableBody.append(
            `<tr><td>${entry.sensor}</td><td>${entry.temperature}</td></tr>`
        );
        });
    }




    $("#startDrying").click(function () {
        $.post("backend/php/api/get_varieties.php", {}, function (data) {
        const response = JSON.parse(data);

        if (!response.varieties || !Array.isArray(response.varieties)) {
            alert("Erreur: les variétés ne sont pas disponibles.");
            return;
        }

        const varieties = response.varieties;
        const select = $("#varietiesSelect");

        if (varieties.length === 0) {
            alert("No varieties available.");
            return;
        }

        select.empty();
        varieties.forEach(function (variety) {
            if (!variety.id || !variety.name) {
            console.warn("Objet variété invalide:", variety);
            return;
            }
            select.append(
            `<option value="${variety.id}" data-max-temperature="${variety.max_temperature}" data-min-temperature="${variety.min_temperature}" data-drying-time="${variety.duree_de_sechage}">${variety.name}</option>`
            );
        });
        });
        $("#userModal").show();

        $("#addVariety").on("click", function () {
        const selectedVariety = $("#varietiesSelect").val();
        const varietyList = $(".varieties-list");

        if (!selectedVariety) {
            return;
        }

        const selectedOption = $("#varietiesSelect option:selected");
        const selectedVarietyName = selectedOption.text();
        const selectedVarietyId = selectedOption.val();
        const selectedVarietyMaxTemperature =
            selectedOption.data("max-temperature");
        const selectedVarietyMinTemperature =
            selectedOption.data("min-temperature");
        const selectedVarietyDryingTime = selectedOption.data("drying-time");

        if (!selectedVarietyName || !selectedVarietyId) {
            console.error("Invalid selected variety:", selectedVariety);
            return;
        }

        if ($(`#${selectedVarietyId}`).length > 0) {
            console.warn("Cette variété est déjà ajoutée.");
            return;
        }

        $("#varietiesNone").remove();

        const html = `
                <div class="varieties-badge" id="${selectedVarietyId}" data-max-temperature="${selectedVarietyMaxTemperature}" data-min-temperature="${selectedVarietyMinTemperature}" data-drying-time="${selectedVarietyDryingTime}">
                <p>${selectedVarietyName}</p>
                <span class="deleteVariety">&times;</span>
            </div>
        `;

        varietyList.append(html);
        });

        $(document).on("click", ".deleteVariety", function () {
        $(this).parent().remove();

        if ($(".varieties-badge").length === 0 && !$("#varietiesNone").length) {
            $(".varieties-list").append(
            `<p id="varietiesNone">Aucune variété ajoutée</p>`
            );
        }
        });

        $("#deleteAllVariety").on("click", function () {
        $(".varieties-list").empty();
        $(".varieties-list").append(
            `<p id="varietiesNone">Aucune variété ajoutée</p>`
        );
        });

        $(".close, .btn-cancel").on("click", function () {
        $("#varietiesSelect").val("");
        $("#userModal").hide();
        });

        $("#save").on("click", function (e) {
        const variety = $(".varieties-badge")
            .map(function () {
            const varietyId = $(this).attr("id");
            const varietyName = $(this).find("p").text();
            const varietyMaxTemperature = $(this).data("max-temperature");
            const varietyMinTemperature = $(this).data("min-temperature");
            const varietyDryingTime = $(this).data("drying-time");
            return {
                id: varietyId,
                name: varietyName,
                max_temperature: varietyMaxTemperature,
                min_temperature: varietyMinTemperature,
                drying_time: varietyDryingTime,
            };
            })
            .get();
        $("#userModal").hide();

        $.post(
            "backend/php/api/start_drying.php",
            { variety: JSON.stringify(variety) },
            function (data) {
            const response = JSON.parse(data);
            $("#dryingStatus").text(`Status: ${response.message}`);
            }
        ).fail(function (jqXHR, textStatus, errorThrown) {
            console.log("Error details:", {
            status: jqXHR.status,
            textStatus: textStatus,
            errorThrown: errorThrown,
            });
            $("#dryingStatus").text("Error starting drying process.");
        });
        });
    });

    $("#stopDrying").click(function () {
        $.post("backend/php/api/stop_drying.php", {}, function () {
        $("#dryingStatus").text("Status: Drying stopped.");
        }).fail(function () {
        $("#dryingStatus").text("Error stopping drying process.");
        });
    });

    $("#shutdownSystem").click(function () {
        $.post("backend/php/api/shutdown.php", {}, function () {
        $("#shutdownStatus").text("System is shutting down...");
        }).fail(function () {
        $("#shutdownStatus").text("Error shutting down system.");
        });
    });
    getTemperatureData();
    setInterval(getTemperatureData, 3000);
});
