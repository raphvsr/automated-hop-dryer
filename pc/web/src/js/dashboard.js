
/*
 * Dashboard Diagnostic Tool
 *
 * This script helps diagnose network issues with your dashboard's data fetch.
 * Add this to your HTML file temporarily for debugging.
 */

function runDiagnostics() {
  console.log("==== STARTING DIAGNOSTICS ====");

  // Test 1: Check if the backend URL is accessible
  console.log("Test 1: Testing backend URL accessibility...");
  fetch('backend/data.php', {
    method: 'HEAD',
  })
  .then(response => {
    console.log(`Backend endpoint status: ${response.status} ${response.statusText}`);
  })
  .catch(error => {
    console.error('Backend endpoint not accessible:', error);
  });

  // Test 2: Test with minimal parameters
  console.log("Test 2: Testing with minimal parameters...");
  fetch('backend/data.php?minimal=true')
  .then(response => {
    console.log(`Minimal request status: ${response.status} ${response.statusText}`);
    return response.text();
  })
  .then(text => {
    console.log("Response preview:", text.substring(0, 100) + (text.length > 100 ? "..." : ""));
    try {
      JSON.parse(text);
      console.log("Response is valid JSON");
    } catch (e) {
      console.error("Response is NOT valid JSON:", e);
    }
  })
  .catch(error => {
    console.error('Minimal request failed:', error);
  });

  // Test 3: Check server info
  console.log("Test 3: Checking server environment...");
  const serverUrl = window.location.origin;
  console.log("Server URL:", serverUrl);
  console.log("Page URL:", window.location.href);

  // Test 4: Verify the API path
  const basePath = window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/'));
  console.log("Base path:", basePath);
  console.log("Expected API path:", `${window.location.origin}${basePath}/backend/data.php`);

  // Test 5: Show all active parameters
  const variety = document.getElementById('variety-filter')?.value || 'Not found';
  const startDate = document.getElementById('start-date')?.value || 'Not found';
  const endDate = document.getElementById('end-date')?.value || 'Not found';
  const etage = document.getElementById('etage-filter')?.value || 'Not found';

  console.log("Current form values:");
  console.log("- Variety:", variety);
  console.log("- Start Date:", startDate);
  console.log("- End Date:", endDate);
  console.log("- Etage:", etage);

  console.log("==== DIAGNOSTICS COMPLETE ====");
}

// Add a button to the page to run diagnostics
function addDiagnosticButton() {
  const button = document.createElement('button');
  button.innerText = 'debug';
  button.style.position = 'fixed';
  button.style.bottom = '10px';
  button.style.right = '10px';
  button.style.zIndex = '9999';
  button.style.padding = '8px 16px';
  button.style.backgroundColor = '#ff5722';
  button.style.color = 'white';
  button.style.border = 'none';
  button.style.borderRadius = '4px';
  button.style.cursor = 'pointer';
  button.onclick = runDiagnostics;
  document.body.appendChild(button);
}

debug = true // mettere true pour le mode debug
if(debug){
  window.addEventListener('DOMContentLoaded', addDiagnosticButton);
}
// Function to fetch data from the backend
function fetchData(params = {}) {
  return new Promise((resolve, reject) => {
    const queryParams = new URLSearchParams();
    if (params.variety) queryParams.append('variety', params.variety);
    if (params.startDate) queryParams.append('startDate', params.startDate);
    if (params.endDate) queryParams.append('endDate', params.endDate);
    if (params.etage) queryParams.append('etage', params.etage);

    queryParams.append('_t', Date.now());

    const url = 'backend/data.php?' + queryParams.toString();
    console.log('Fetching from URL:', url);

    fetch(url, {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Cache-Control': 'no-cache'
      },
    })
    .then(response => {
      console.log('Response status:', response.status);

      if (!response.ok) {
        return response.text().then(text => {
          console.error('Error response:', text);
          throw new Error(`Server responded with status: ${response.status}. Details: ${text}`);
        });
      }

      return response.text().then(text => {
        try {
          return JSON.parse(text);
        } catch (e) {
          console.error('Failed to parse JSON:', text);
          throw new Error('Invalid JSON response from server');
        }
      });
    })
    .then(data => resolve(data))
    .catch(error => {
      console.error('Detailed fetch error:', error);
      reject(error);
    });
  });
}
// Function to process data for the charts
function processDataForCharts(data) {
  // Group data by campaign and etage
  const campaignData = {};
  const etageData = {};

  data.forEach(row => {
    const campaignId = row.campaign_id;
    const etageNumber = row.etage_number;

    // Initialize campaign data if not exists
    if (!campaignData[campaignId]) {
      campaignData[campaignId] = {
        variety: row.variety_name,
        startTime: new Date(row.start_time),
        endTime: row.end_time ? new Date(row.end_time) : null,
        etages: {}
      };
    }

    // Initialize etage data if not exists
    if (etageNumber && !campaignData[campaignId].etages[etageNumber]) {
      campaignData[campaignId].etages[etageNumber] = {
        startTime: row.etage_start ? new Date(row.etage_start) : null,
        endTime: row.etage_end ? new Date(row.etage_end) : null,
        burnerStatus: []
      };
    }

    // Add burner status data
    if (row.burner_status !== null && etageNumber) {
      campaignData[campaignId].etages[etageNumber].burnerStatus.push({
        status: parseInt(row.burner_status),
        changedAt: new Date(row.changed_at)
      });
    }
  });

  // Process etage data for chart 4
  Object.values(campaignData).forEach(campaign => {
    Object.entries(campaign.etages).forEach(([etageNumber, etage]) => {
      const etageKey = `${campaign.variety}-${etageNumber}`;

      if (!etageData[etageKey]) {
        etageData[etageKey] = {
          variety: campaign.variety,
          etageNumber: parseInt(etageNumber),
          dryingTime: 0,
          burnerTime: 0,
          count: 0
        };
      }

      // Calculate drying time in hours
      if (etage.endTime && etage.startTime) {
        const dryingTime = (etage.endTime - etage.startTime) / (1000 * 60 * 60);
        etageData[etageKey].dryingTime += dryingTime;
        etageData[etageKey].count += 1;

        // Calculate burner active duration
        let burnerActiveTime = 0;
        let lastStatusChange = null;
        let lastStatus = 0;

        // Sort burner status by time
        const sortedStatus = [...etage.burnerStatus].sort(
          (a, b) => new Date(a.changedAt) - new Date(b.changedAt)
        );

        sortedStatus.forEach(status => {
          if (lastStatusChange) {
            if (lastStatus === 1) { // Burner was active
              burnerActiveTime += (new Date(status.changedAt) - lastStatusChange) / (1000 * 60 * 60);
            }
          }
          lastStatusChange = new Date(status.changedAt);
          lastStatus = status.status;
        });

        etageData[etageKey].burnerTime += burnerActiveTime;
      }
    });
  });

  return { campaignData, etageData };
}

// Update chart 1: Varieté historique séchage 1er chargement
function updateChart1(campaignData) {
  const labels = [];
  const dryingDuration = [];
  const burnerDuration = [];

  // Group data by variety
  const varietyData = {};

  // Process data for chart 1
  Object.values(campaignData).forEach(campaign => {
    if (campaign.etages[1]) { // First loading (etage 1)
      const variety = campaign.variety;
      const date = campaign.startTime.toLocaleDateString('fr-FR');

      if (!varietyData[date]) {
        varietyData[date] = {
          dryingTime: 0,
          burnerTime: 0,
          count: 0
        };

        labels.push(date);
      }

      // Calculate drying duration in hours
      const dryingTimeHours = campaign.etages[1].endTime && campaign.etages[1].startTime ?
        (campaign.etages[1].endTime - campaign.etages[1].startTime) / (1000 * 60 * 60) : 0;

      // Calculate burner active duration
      let burnerActiveTime = 0;
      let lastStatusChange = null;
      let lastStatus = 0;

      // Sort burner status by time
      const sortedStatus = [...campaign.etages[1].burnerStatus].sort(
        (a, b) => new Date(a.changedAt) - new Date(b.changedAt)
      );

      sortedStatus.forEach(status => {
        if (lastStatusChange) {
          if (lastStatus === 1) { // Burner was active
            burnerActiveTime += (new Date(status.changedAt) - lastStatusChange) / (1000 * 60 * 60);
          }
        }
        lastStatusChange = new Date(status.changedAt);
        lastStatus = status.status;
      });

      varietyData[date].dryingTime += dryingTimeHours;
      varietyData[date].burnerTime += burnerActiveTime;
      varietyData[date].count++;
    }
  });

  // Calculate average values for each date
  labels.forEach(date => {
    const data = varietyData[date];
    dryingDuration.push(parseFloat((data.dryingTime / data.count).toFixed(1)));
    burnerDuration.push(parseFloat((data.burnerTime / data.count).toFixed(1)));
  });

  // Update chart 1
  chart1.data.labels = labels;
  chart1.data.datasets[0].data = dryingDuration;
  chart1.data.datasets[1].data = burnerDuration;
  chart1.update();
}

// Update chart 2: Varieté date N°chargement
function updateChart2(campaignData) {
  // Group data by date
  const dateData = {};

  Object.values(campaignData).forEach(campaign => {
    const date = campaign.startTime.toLocaleDateString('fr-FR');

    if (!dateData[date]) {
      dateData[date] = {
        etageCount: 0,
        totalDryingTime: 0
      };
    }

    // Count number of etages
    const etageCount = Object.keys(campaign.etages).length;
    dateData[date].etageCount = Math.max(dateData[date].etageCount, etageCount);

    // Calculate total drying time
    Object.values(campaign.etages).forEach(etage => {
      if (etage.endTime && etage.startTime) {
        dateData[date].totalDryingTime += (etage.endTime - etage.startTime) / (1000 * 60 * 60);
      }
    });
  });

  // Prepare data for chart
  const labels = Object.keys(dateData);
  const etageCount = labels.map(date => dateData[date].etageCount);

  // Update chart 2
  chart2.data.labels = labels;
  chart2.data.datasets[0].data = etageCount;
  chart2.update();
}

// Update chart 3: Varieté date - tous les paniers
function updateChart3(campaignData) {
  // Group data by campaign
  const campaigns = Object.values(campaignData).sort((a, b) => a.startTime - b.startTime);

  // Take the most recent campaign if available
  if (campaigns.length === 0) return;

  const recentCampaign = campaigns[campaigns.length - 1];

  // Prepare time series data
  const timeData = {};
  const etageStatusData = {};
  const bruleurStatus = [];

  // Extract data points for plotting
  Object.entries(recentCampaign.etages).forEach(([etageNumber, etage]) => {
    // Create entry for each etage
    etageStatusData[etageNumber] = [];

    if (etage.burnerStatus && etage.burnerStatus.length > 0) {
      // Add burner status data points
      etage.burnerStatus.forEach(status => {
        bruleurStatus.push({
          time: status.changedAt,
          status: status.status
        });
      });
    }
  });

  // Sort burner status by time
  bruleurStatus.sort((a, b) => a.time - b.time);

  // Create time series for the chart
  const times = [];
  const burnerData = [];
  const etageDatasets = [];

  // If we have etage data, create the chart data
  if (Object.keys(etageStatusData).length > 0) {
    // Create time intervals for the chart (every 30 minutes)
    const startTime = recentCampaign.startTime;
    let endTime = recentCampaign.endTime || new Date();

    if (endTime - startTime < 60 * 60 * 1000) {
      // If less than one hour, extend to at least one hour
      endTime = new Date(startTime.getTime() + 60 * 60 * 1000);
    }

    // Generate time labels for X-axis (hourly format)
    for (let time = new Date(startTime); time <= endTime; time = new Date(time.getTime() + 60 * 60 * 1000)) {
      times.push(time.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }));

      // Find current burner status at this time
      const lastBurnerStatus = bruleurStatus
        .filter(s => s.time <= time)
        .sort((a, b) => b.time - a.time)[0];

      burnerData.push(lastBurnerStatus ? lastBurnerStatus.status : 0);

      // For each etage, track its status at this time
      Object.entries(recentCampaign.etages).forEach(([etageNum, etage]) => {
        if (!etageStatusData[etageNum]) {
          etageStatusData[etageNum] = [];
        }

        // Calculate etage status:
        // 4 = etage has houblon and is at position 4
        // 3 = etage has houblon and is at position 3
        // 2 = etage has houblon and is at position 2
        // 1 = etage has houblon and is at position 1
        // 0 = etage is empty or finished
        let status = 0;

        if (etage.startTime <= time && (!etage.endTime || etage.endTime >= time)) {
          // This etage is currently in the drying process
          status = parseInt(etageNum, 10);
        }

        etageStatusData[etageNum].push(status);
      });
    }

    // Create datasets for each etage
    const colors = [
      { bg: 'rgba(239, 68, 68, 0.2)', border: 'rgb(239, 68, 68)' }, // Red
      { bg: 'rgba(56, 189, 248, 0.2)', border: 'rgb(56, 189, 248)' }, // Blue
      { bg: 'rgba(5, 150, 105, 0.2)', border: 'rgb(5, 150, 105)' },   // Green
      { bg: 'rgba(124, 58, 237, 0.2)', border: 'rgb(124, 58, 237)' }  // Purple
    ];

    Object.entries(etageStatusData).forEach(([etageNum, data], index) => {
      etageDatasets.push({
        label: `étage panier chargement N°${etageNum}`,
        data: data,
        backgroundColor: colors[index % colors.length].bg,
        borderColor: colors[index % colors.length].border,
        borderWidth: 1,
        type: 'line'
      });
    });
  }

  // Add burner status dataset
  etageDatasets.unshift({
    label: 'Etat bruleur',
    data: burnerData,
    backgroundColor: 'rgba(76, 29, 149, 0.2)',
    borderColor: 'rgb(76, 29, 149)',
    borderWidth: 1,
    type: 'line',
    yAxisID: 'y1'
  });

  // Update chart 3
  chart3.data.labels = times;
  chart3.data.datasets = etageDatasets;

  // Update options for dual y-axis
  chart3.options = {
    ...chartOptions,
    scales: {
      ...chartOptions.scales,
      y: {
        title: {
          display: true,
          text: 'Étage'
        },
        min: 0,
        max: 5
      },
      y1: {
        type: 'linear',
        display: true,
        position: 'right',
        title: {
          display: true,
          text: 'État brûleur'
        },
        min: 0,
        max: 1,
        grid: {
          drawOnChartArea: false
        }
      }
    }
  };

  chart3.update();
}

// Update chart 4: Durée séchage par panier
function updateChart4(etageData) {
  // Get latest campaign data
  const campaignData = Object.values(etageData).filter(data => data.count > 0);

  if (campaignData.length === 0) return;

  // Organize data by panier (etage)
  const panierData = {};

  campaignData.forEach(data => {
    const etageKey = parseInt(data.etageNumber);

    if (!panierData[etageKey]) {
      panierData[etageKey] = {
        dryingTime: 0,
        burnerTime: 0
      };
    }

    panierData[etageKey].dryingTime = data.dryingTime / data.count;
    panierData[etageKey].burnerTime = data.burnerTime / data.count;
  });

  // Prepare data for the bar chart
  const labels = ["1"];  // Single label for grouped bars
  const datasets = [];

  // Add drying time bars for each panier
  for (let i = 1; i <= 4; i++) {
    if (panierData[i]) {
      datasets.push({
        label: `durée séchage panier${i}`,
        data: [formatTimeValue(panierData[i].dryingTime)],
        backgroundColor: getBarColor(i, false),
        borderColor: getBorderColor(i, false),
        borderWidth: 1
      });

      datasets.push({
        label: `durée brûleur panier${i}`,
        data: [formatTimeValue(panierData[i].burnerTime)],
        backgroundColor: getBarColor(i, true),
        borderColor: getBorderColor(i, true),
        borderWidth: 1
      });
    }
  }

  // Update chart 4
  chart4.data.labels = labels;
  chart4.data.datasets = datasets;
  chart4.options = {
    ...chartOptions,
    plugins: {
      ...chartOptions.plugins,
      tooltip: {
        callbacks: {
          label: function(context) {
            const value = context.parsed.y;
            return `${context.dataset.label}: ${formatHoursMinutes(value)}`;
          }
        }
      }
    },
    scales: {
      ...chartOptions.scales,
      y: {
        title: {
          display: true,
          text: 'Durée (heures)'
        },
        ticks: {
          callback: function(value) {
            return formatHoursMinutes(value);
          }
        }
      }
    }
  };
  chart4.update();
}

// Helper function to format time value for chart
function formatTimeValue(hours) {
  return parseFloat(hours.toFixed(2));
}

// Helper function to format hours:minutes for display
function formatHoursMinutes(hours) {
  const h = Math.floor(hours);
  const m = Math.round((hours - h) * 60);
  return `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}`;
}

// Helper functions for consistent colors
function getBarColor(panierIndex, isBurner) {
  const colors = [
    'rgba(56, 189, 248, 0.2)',  // Blue
    'rgba(239, 68, 68, 0.2)',   // Red
    'rgba(5, 150, 105, 0.2)',   // Green
    'rgba(124, 58, 237, 0.2)',  // Purple
    'rgba(245, 158, 11, 0.2)',  // Orange
    'rgba(17, 94, 89, 0.2)',    // Teal
    'rgba(190, 24, 93, 0.2)',   // Pink
    'rgba(55, 65, 81, 0.2)'     // Gray
  ];

  const index = (panierIndex - 1) * 2 + (isBurner ? 1 : 0);
  return colors[index % colors.length];
}

function getBorderColor(panierIndex, isBurner) {
  const colors = [
    'rgb(56, 189, 248)',  // Blue
    'rgb(239, 68, 68)',   // Red
    'rgb(5, 150, 105)',   // Green
    'rgb(124, 58, 237)',  // Purple
    'rgb(245, 158, 11)',  // Orange
    'rgb(17, 94, 89)',    // Teal
    'rgb(190, 24, 93)',   // Pink
    'rgb(55, 65, 81)'     // Gray
  ];

  const index = (panierIndex - 1) * 2 + (isBurner ? 1 : 0);
  return colors[index % colors.length];
}

// Create charts with initial empty data
const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'top',
      labels: {
        font: {
          family: "'Inter', sans-serif",
          size: 11
        },
        maxRotation: 45,
        minRotation: 45
      }
    }
  }
};

// Chart 1: Varieté historique séchage 1er chargement
const ctx1 = document.getElementById('chart1').getContext('2d');
const chart1 = new Chart(ctx1, {
  type: 'bar',
  data: {
    labels: [],
    datasets: [
      {
        label: 'Durée séchage',
        data: [],
        backgroundColor: 'rgba(56, 189, 248, 0.2)',
        borderColor: 'rgb(56, 189, 248)',
        borderWidth: 1
      },
      {
        label: 'Durée bruleur',
        data: [],
        backgroundColor: 'rgba(239, 68, 68, 0.2)',
        borderColor: 'rgb(239, 68, 68)',
        borderWidth: 1
      }
    ]
  },
  options: {
    ...chartOptions,
    scales: {
      ...chartOptions.scales,
      y: {
        max: 14
      }
    }
  }
});

// Chart 2: Varieté date N°chargement
const ctx2 = document.getElementById('chart2').getContext('2d');
const chart2 = new Chart(ctx2, {
  type: 'line',
  data: {
    labels: [],
    datasets: [
      {
        label: 'Etat bruleur',
        data: [],
        backgroundColor: 'rgba(76, 29, 149, 0.2)',
        borderColor: 'rgb(76, 29, 149)',
        borderWidth: 1,
        yAxisID: 'y1'
      },
      {
        label: 'étage panier chargement N°1',
        data: [],
        backgroundColor: 'rgba(239, 68, 68, 0.2)',
        borderColor: 'rgb(239, 68, 68)',
        borderWidth: 1,
        yAxisID: 'y'
      }
    ]
  },
  options: {
    ...chartOptions,
    scales: {
      ...chartOptions.scales,
      y: {
        title: {
          display: true,
          text: 'Étage'
        },
        min: 0,
        max: 5
      },
      y1: {
        type: 'linear',
        display: true,
        position: 'right',
        title: {
          display: true,
          text: 'État brûleur'
        },
        min: 0,
        max: 1,
        grid: {
          drawOnChartArea: false
        }
      }
    }
  }
});

// Chart 3: Varieté date - tous les paniers
const ctx3 = document.getElementById('chart3').getContext('2d');
const chart3 = new Chart(ctx3, {
  type: 'line',
  data: {
    labels: [],
    datasets: [
      {
        label: 'Etat bruleur',
        data: [],
        backgroundColor: 'rgba(76, 29, 149, 0.2)',
        borderColor: 'rgb(76, 29, 149)',
        borderWidth: 1,
        yAxisID: 'y1'
      },
      {
        label: 'étage panier chargement N°1',
        data: [],
        backgroundColor: 'rgba(239, 68, 68, 0.2)',
        borderColor: 'rgb(239, 68, 68)',
        borderWidth: 1
      },
      {
        label: 'étage panier chargement N°2',
        data: [],
        backgroundColor: 'rgba(5, 150, 105, 0.2)',
        borderColor: 'rgb(5, 150, 105)',
        borderWidth: 1
      },
      {
        label: 'étage panier chargement N°3',
        data: [],
        backgroundColor: 'rgba(124, 58, 237, 0.2)',
        borderColor: 'rgb(124, 58, 237)',
        borderWidth: 1
      },
      {
        label: 'étage panier chargement N°4',
        data: [],
        backgroundColor: 'rgba(245, 158, 11, 0.2)',
        borderColor: 'rgb(245, 158, 11)',
        borderWidth: 1
      }
    ]
  },
  options: {
    ...chartOptions,
    scales: {
      ...chartOptions.scales,
      y: {
        title: {
          display: true,
          text: 'Étage'
        },
        min: 0,
        max: 5
      },
      y1: {
        type: 'linear',
        display: true,
        position: 'right',
        title: {
          display: true,
          text: 'État brûleur'
        },
        min: 0,
        max: 1,
        grid: {
          drawOnChartArea: false
        }
      }
    }
  }
});

// Chart 4: Durée séchage par panier
const ctx4 = document.getElementById('chart4').getContext('2d');
const chart4 = new Chart(ctx4, {
  type: 'bar',
  data: {
    labels: [],
    datasets: []
  },
  options: {
    ...chartOptions,
    plugins: {
      ...chartOptions.plugins,
      tooltip: {
        callbacks: {
          label: function (context) {
            const value = context.parsed.y;
            const hours = Math.floor(value);
            const minutes = Math.round((value - hours) * 60);
            return `${context.dataset.label}: ${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
          }
        }
      }
    }
  }
});

// Function to update all charts
function updateAllCharts(data) {
  const { campaignData, etageData } = processDataForCharts(data);
  updateChart1(campaignData);
  updateChart2(campaignData);
  updateChart3(campaignData);
  updateChart4(etageData);
}

// Add event listeners for filter forms
document.addEventListener('DOMContentLoaded', function() {

  // Initial data load
  fetchData()
    .then(data => {
      updateAllCharts(data);
    })
    .catch(error => {
      console.error('Error fetching data:', error);
    });

  // Assuming you have a form with id 'filterForm'
  const filterForm = document.getElementById('filter-form');
  if (filterForm) {
    filterForm.addEventListener('submit', function(e) {
      e.preventDefault();
      console.log('Form submitted');

      const formData = new FormData(filterForm);
      const params = {
        variety: formData.get('variety'),
        startDate: formData.get('startDate'),
        endDate: formData.get('endDate'),
        etage: formData.get('etage')
      };
      console.log('Form data:', params);
      fetchData(params)
        .then(data => {
          updateAllCharts(data);
        })
        .catch(error => {
          console.error('Error fetching data:', error);
        });
    });
  }
});
