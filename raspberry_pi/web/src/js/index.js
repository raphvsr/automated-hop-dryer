$(document).ready(function () {
  let temperatureChart = null;
  function updateDataVisualization() {
    $.post("backend/php/api/get_drying_data.php", {}, function (data) {
      const temperatures = JSON.parse(data);
      updateChart(temperatures);
      updateTable(temperatures);
    }).fail(function () {
      console.log("Error fetching drying data.");
    });
  }
  function updateChart(data) {
    const ctx = document.getElementById("temperatureChart").getContext("2d");
    if (temperatureChart) {
      temperatureChart.destroy();
    }
    temperatureChart = new Chart(ctx, {
      type: "line",
      data: {
        labels: data.map((entry) => entry.time),
        datasets: [
          {
            label: "Temperature (°C)",
            data: data.map((entry) => entry.temperature),
            borderColor: "rgba(75, 192, 192, 1)",
            fill: false,
          },
        ],
      },
      options: {
        responsive: true,
        scales: {
          x: { display: true, title: { display: true, text: "Time" } },
          y: {
            display: true,
            title: { display: true, text: "Temperature (°C)" },
          },
        },
      },
    });
  }
  function updateTable(data) {
    const tableBody = $("#dataTable tbody");
    tableBody.empty();
    data.forEach((entry) => {
      tableBody.append(
        `<tr><td>${entry.time}</td><td>${entry.temperature}</td></tr>`
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

      if ($(".varieties-badge").length === 0) {
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
  updateDataVisualization();
  setInterval(updateDataVisualization, 5000);
});
