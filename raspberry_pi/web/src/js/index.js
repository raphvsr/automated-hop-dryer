//                  file index.js
// ===============================================
//          Original Author: fateh kabbani
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-05-21 - Update index.js - Poubelle26
//   raspberry_pi/web/src/js/index.js | 2 +-
//   1 file changed, 1 insertion(+), 1 deletion(-)
//
// 2025-05-20 - feat: Implement CSV import functionality with GUI; add database update and preview features - Raphael Vasseur
//   raspberry_pi/web/src/js/index.js | 352 +++++++++++++++++++--------------------
//   1 file changed, 173 insertions(+), 179 deletions(-)
//
// 2025-05-20 - Refactor CSV data import and database update functions; add GUI for file selection and improve error handling. - Raphael Vasseur
//   raspberry_pi/web/src/js/index.js | 339 +++++++++++++++++++++------------------
//   1 file changed, 185 insertions(+), 154 deletions(-)
//
// 2025-04-03 - fix - Romain Provencel
//   raspberry_pi/web/src/js/index.js | 8 +++++++-
//   1 file changed, 7 insertions(+), 1 deletion(-)
//
// 2025-04-03 - fix - Romain Provencel
//   raspberry_pi/web/src/js/index.js | 2 +-
//   1 file changed, 1 insertion(+), 1 deletion(-)
//
// 2025-04-03 - . - Romain Provencel
//   raspberry_pi/web/src/js/index.js | 1 -
//   1 file changed, 1 deletion(-)
//
// 2025-04-03 - Enhance drying process by calculating minimum drying time for selected varieties; update start_drying.php to include drying time in configuration and modify response handling. Update index.js to pass drying time data for selected varieties. - Romain Provencel
//   raspberry_pi/web/src/js/index.js | 16 +++++++---------
//   1 file changed, 7 insertions(+), 9 deletions(-)
//
// 2025-04-03 - Implement variety temperature handling in drying process; update start_drying.php to validate and store temperature data, and enhance index.js to pass selected variety temperatures during drying initiation. - Romain Provencel
//   raspberry_pi/web/src/js/index.js | 41 +++++++++++++++++++++++++++++++++++-----
//   1 file changed, 36 insertions(+), 5 deletions(-)
//
// 2025-04-03 - Enhance UI by adding button styles and new delete functionality for varieties; update CSS for button classes and improve JavaScript for variety management. - Romain Provencel
//   raspberry_pi/web/src/js/index.js | 15 +++++++++------
//   1 file changed, 9 insertions(+), 6 deletions(-)
//
// 2025-04-03 - REMOVED THE CHART + updated the sensor read each 3 seconds - fateh kabbani
//   raspberry_pi/web/src/js/index.js | 114 ++-------------------------------------
//   1 file changed, 3 insertions(+), 111 deletions(-)
//
// 2025-04-03 - Refactor temperature data fetching and visualization; update get_temperatures.php to return numeric values and enhance index.js for improved charting and table display. - fateh kabbani
//   raspberry_pi/web/src/js/index.js | 157 ++++++++++++++++++++++++++++++---------
//   1 file changed, 123 insertions(+), 34 deletions(-)
//
// 2025-04-03 - fix - Romain Provencel
//   raspberry_pi/web/src/js/index.js | 2 +-
//   1 file changed, 1 insertion(+), 1 deletion(-)
//
// 2025-04-03 - . - Romain Provencel
//   raspberry_pi/web/src/js/index.js | 118 +++++++++++++++++++++++++++++++--------
//   1 file changed, 94 insertions(+), 24 deletions(-)
//
// 2025-04-03 - Remove get_drying_data.php and update get_temperatures.php to return sensor data with temperature; enhance index.js to fetch and display temperature data in the table. - fateh kabbani
//   raspberry_pi/web/src/js/index.js | 13 +++++++++++--
//   1 file changed, 11 insertions(+), 2 deletions(-)
//
// 2025-04-03 - Add user variety selection modal and implement variety management functionality - Romain Provencel
//   raspberry_pi/web/src/js/index.js | 173 ++++++++++++++++++++++++++++-----------
//   1 file changed, 125 insertions(+), 48 deletions(-)
//
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-26 - Remove max temperature configuration from web interface and add sensor reading script for DS18B20 temperature sensor - fateh kabbani
//   raspberry pi/web/src/js/index.js | 13 +------------
//   1 file changed, 1 insertion(+), 12 deletions(-)
//
// 2025-03-20 - move the file to raspberry pi - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-15 - Enhance drying control interface with real-time data visualization, improved layout, and new temperature configuration options - fateh kabbani
//   web/src/js/index.js | 91 +++++++++++++++++++++++++++++++++++++++++++++++++++++
//   1 file changed, 91 insertions(+)
//
// ============================================================

$(document).ready(function () {
  let temperatureChart = null;
  let timePoints = ["Time 1", "Time 2", "Time 3", "Time 4", "Time 5", "Time 6"];
  let historicalData = [];
  let maxAllowedTemperature = null;

  function loadConfig() {
    $.get("backend/php/api/get_config.php", function (data) {
      const config = JSON.parse(data);
      if (config["max-temperature"] !== undefined) {
        maxAllowedTemperature = config["max-temperature"];
      }
    }).fail(function () {
      console.log("Erreur lors du chargement de la configuration.");
    });
  }

  function getTemperatureData() {
    $.post("backend/php/api/get_temperatures.php", {}, function (data) {
      const temperatures = JSON.parse(data);

      historicalData.push(temperatures);
      if (historicalData.length > 6) {
        historicalData.shift();
      }

      updateTable(temperatures);

      if (maxAllowedTemperature !== null) {
        temperatures.forEach((entry) => {
          if (entry.temperature > maxAllowedTemperature) {
            alert(
              `Température maximale dépassée !\nCapteur: ${entry.sensor}\nTempérature: ${entry.temperature}°C\nLimite: ${maxAllowedTemperature}°C`
            );
          }
        });
      }
    }).fail(function () {
      console.log("Error fetching drying data.");
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

  loadConfig();
  getTemperatureData();
  setInterval(getTemperatureData, 3000);
});
