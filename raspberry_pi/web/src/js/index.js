$(document).ready(function () {
  let temperatureChart = null;
  function updateDataVisualization() {
    $.post('backend/php/api/get_drying_data.php',
      {  },
      function (data) {
        const temperatures = JSON.parse(data);
        updateChart(temperatures);
      }
    ).fail(function () {
      console.log('Error fetching drying data.');
    });
  }
  function getTemperatureData() {
    $.post('backend/php/api/get_temperatures.php', {} , function(data) {
      const temperatures = JSON.parse(data);
      updateTable(temperatures);
    }).fail(function () {
      console.log('Error fetching temperature data.');
    });
  }
  function updateChart(data) {
    const ctx = document.getElementById('temperatureChart').getContext('2d');
    if (temperatureChart) {
      temperatureChart.destroy();
    }
    temperatureChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: data.map(entry => entry.time),
        datasets: [{
          label: 'Temperature (°C)',
          data: data.map(entry => entry.temperature),
          borderColor: 'rgba(75, 192, 192, 1)',
          fill: false
        }]
      },
      options: {
        responsive: true,
        scales: {
          x: { display: true, title: { display: true, text: 'Time' } },
          y: { display: true, title: { display: true, text: 'Temperature (°C)' } }
        }
      }
    });
  }
  function updateTable(data) {
    const tableBody = $('#dataTable tbody');
    tableBody.empty();
    data.forEach(entry => {
      tableBody.append(`<tr><td>${entry.sensor}</td><td>${entry.temperature}</td></tr>`);
    });
  }
  $('#startDrying').click(function () {
    $.post('backend/php/api/start_drying.php',
      {  },
      function () {
        $('#dryingStatus').text('Status: Drying in progress...');
      }
    ).fail(function () {
      $('#dryingStatus').text('Error starting drying process.');
    });
  });
  $('#stopDrying').click(function () {
    $.post('backend/php/api/stop_drying.php',
      {  },
      function () {
        $('#dryingStatus').text('Status: Drying stopped.');
      }
    ).fail(function () {
      $('#dryingStatus').text('Error stopping drying process.');
    });
  });

  $('#shutdownSystem').click(function () {
    $.post('backend/php/api/shutdown.php',
      {  },
      function () {
        $('#shutdownStatus').text('System is shutting down...');
      }
    ).fail(function () {
      $('#shutdownStatus').text('Error shutting down system.');
    });
  });
  updateDataVisualization();
  getTemperatureData();
  setInterval(updateDataVisualization, 5000);
  setInterval(getTemperatureData, 5000);
});
