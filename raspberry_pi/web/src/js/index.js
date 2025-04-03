$(document).ready(function () {
  let temperatureChart = null;
  let timePoints = ['Time 1', 'Time 2', 'Time 3', 'Time 4', 'Time 5', 'Time 6'];
  let historicalData = [];

  function getTemperatureData() {
    $.post("backend/php/api/get_temperatures.php", {}, function (data) {
      const temperatures = JSON.parse(data);

      historicalData.push(temperatures);
      if (historicalData.length > 6) {
        historicalData.shift();
      }

      updateTable(temperatures);
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
        select.append(`<option value="${variety.id}">${variety.name}</option>`);
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
            <div class="varieties-badge" id="${selectedVarietyId}">
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

    $(".close, .btn-cancel").on("click", function () {
      $("#varietiesSelect").val("");
      $(".varieties-list").empty();
      $("#userModal").hide();
      $(".varieties-list").append(
        `<p id="varietiesNone">Aucune variété ajoutée</p>`
      );
    });

    $("#save").on("click", function (e) {
      $.post("backend/php/api/start_drying.php", {}, function () {
        $("#dryingStatus").text("Status: Drying in progress...");
      }).fail(function () {
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
