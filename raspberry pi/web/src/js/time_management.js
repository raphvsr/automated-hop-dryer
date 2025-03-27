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
  -(
    // Fonctions
    function refreshTime() {
      $.get("api/rtc_sync.php?action=get_time")
        .done(updateTimeDisplay)
        .fail(showError);
    }
  );

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
