$(document).ready(function () {
  // Initialisation
  refreshTime();

  // Événements
  $("#refreshTime").on("click", refreshTime);

  $("#syncSystemTime").on("click", function () {
    syncTime("system");
  });

  $("#syncRtcTime").on("click", function () {
    syncTime("rtc");
  });

  // Fonctions
  function refreshTime() {
    $.get("api/rtc_sync.php?action=get_time")
      .done(updateTimeDisplay)
      .fail(showError);
  }

  $("#setManualTime").on("click", function () {
    let manualTime = $("#manual-time-input").val().trim();
    let dateRegex = /^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/; // Format: YYYY-MM-DD HH:MM:SS

    if (!dateRegex.test(manualTime)) {
      alert("Veuillez entrer une date et heure valides (AAAA-MM-JJ HH:MM:SS).");
      return;
    }

    if (
      !confirm("Voulez-vous vraiment mettre à jour l'heure avec cette valeur ?")
    )
      return;

    $.post(
      "set_time.php",
      { manualTime: manualTime },
      function (response) {
        if (response.error) {
          showError(response);
        } else {
          showStatus(response.message, "success");
          refreshTime();
        }
      },
      "json"
    ).fail(showError);
  });

  function syncTime(direction) {
    const action = direction === "system" ? "sync_system" : "sync_rtc";
    $.post(`api/rtc_sync.php?action=${action}`)
      .done(function (data) {
        if (data.error) showError(data);
        else {
          showStatus(data.message, "success");
          refreshTime();
        }
      })
      .fail(showError);
  }

  function updateTimeDisplay(data) {
    $("#system-time").text(data.system_time);
    $("#rtc-time").text(data.rtc_time);
  }

  function showError(error) {
    showStatus("Erreur: " + (error.message || error), "error");
  }

  function showStatus(message, type) {
    $("#status-message")
      .text(message)
      .removeClass("error success")
      .addClass(type)
      .show()
      .delay(5000)
      .fadeOut();
  }
});
