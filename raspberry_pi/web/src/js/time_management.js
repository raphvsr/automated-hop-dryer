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

  $("#setManualTime").on("click", function () {
    let manualTime = $("#manual-time-input").val();

    if (!manualTime || manualTime.length === 0) {
      showStatus("Veuillez entrer une date et une heure", "error");
      return;
    }

    let dateregex = /^\d{2}-\d{2}-\d{4} \d{2}:\d{2}:\d{2}$/; // JJ-MM-AAAA HH:MM:SS
    if (!dateregex.test(manualTime)) {
      showStatus("Format de date invalide", "error");
      return;
    }

    syncTime("manual", manualTime);
  });

  // Fonctions
  function refreshTime() {
    $.get("api/rtc_sync.php?action=get_time")
      .done(updateTimeDisplay)
      .fail(showError);
  }

  function syncTime(direction, manualTime = null) {
    let action;
    let postData = {};

    if (direction === "system") {
      action = "sync_system";
    } else if (direction === "rtc") {
      action = "sync_rtc";
    } else if (direction === "manual" && manualTime) {
      action = "set_manual";
      postData.datetime = manualTime;
    } else {
      console.error("Direction invalide ou date manquante");
      return;
    }

    $.post(`api/rtc_sync.php?action=${action}`, postData)
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
