//             file time_management.js
// ===============================================
//        Original Author: Romain Provencel
// ===============================================

const { act } = require("react");

// COMMIT HISTORY:
// ============================================================
// 2025-04-02 - Add time management features: include time management button in index, enhance time_management page layout, and implement back button functionality - Romain Provencel
//   raspberry_pi/web/src/js/time_management.js | 4 ++++
//   1 file changed, 4 insertions(+)
//
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-27 - Add manual time setting feature with input validation and UI enhancements - Romain Provencel
//   raspberry pi/web/src/js/time_management.js | 58 +++++++++++++++---------------
//   1 file changed, 30 insertions(+), 28 deletions(-)
//
// 2025-03-27 - Enhance manual time update feature with input validation and styling improvements - Romain Provencel
//   raspberry pi/web/src/js/time_management.js | 13 ++++++++++---
//   1 file changed, 10 insertions(+), 3 deletions(-)
//
// 2025-03-27 - Add manual time update feature and improve time synchronization UI - Romain Provencel
//   raspberry pi/web/src/js/time_management.js | 35 ++++++++++++++++++++++++------
//   1 file changed, 28 insertions(+), 7 deletions(-)
//
// 2025-03-27 - get time - Romain Provencel
//   raspberry pi/web/src/js/time_management.js | 15 ++++++++-------
//   1 file changed, 8 insertions(+), 7 deletions(-)
//
// 2025-03-26 - Implement time management feature with RTC synchronization and UI enhancements - Romain Provencel
//   raspberry pi/web/src/js/time_management.js | 54 ++++++++++++++++++++++++++++++
//   1 file changed, 54 insertions(+)
//
// ============================================================

$(document).ready(function () {
  // Initialisation
  refreshTime();

  // Événements
  $("#back").on("click", function () {
    window.history.back();
  });

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
    $.post("backend/php/api/rtc_sync.php", { action: "get_times" })
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

    $.post("backend/php/api/rtc_sync.php", {
      action: action,
      datetime: postData.datetime,
    })
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
