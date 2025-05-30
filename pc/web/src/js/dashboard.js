// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
  // window.dashboardManager = new DashboardManager();
  console.log('Dashboard initialized');
});
// let variety = fetch('getVariety');
fetch('backend/getVariety.php', {
  method: 'POST',
})
.then(response => {
  if (!response.ok) {
    throw new Error('Network response was not ok');
  }
  return response.json();
})
.then(data => {
  if (data.status === 'success') {
    const select = document.getElementById('variety-filter');
    data.data.forEach(item => {
      const option = document.createElement('option');
      option.value = item.variety_name;
      option.textContent = item.variety_name;
      select.appendChild(option);
    });
  } else {
    console.error('Server error:', data.message);
  }
})
.catch(error => {
  console.error('Fetch error:', error);
});

// chart 1
const timeLabels = [
  '10:00:00', '10:30:00', '11:00:00', '11:30:00', '12:00:00',
  '12:30:00', '13:00:00', '13:30:00', '14:00:00', '14:30:00',
  '15:00:00', '15:30:00', '16:00:00', '16:30:00', '17:00:00',
  '17:30:00', '18:00:00', '18:30:00', '19:00:00'
];

const dateLabels = [
  '01/01/2000', '01/01/2002', '01/01/2004', '01/01/2006', '01/01/2008',
  '01/01/2010', '01/01/2012', '01/01/2014', '01/01/2016', '01/01/2018',
  '01/01/2020', '01/01/2022', '01/01/2024'
];

const chart1 = document.getElementById('chart1').getContext('2d');
const chart1Visual = new Chart(chart1, {
    type: 'bar',
    data: {
        labels: [], // Will be populated by fetchData
        datasets: [
            {
                label: 'Durée séchage (heures)',
                data: [],
                backgroundColor: '#4A90E2',
                borderColor: '#4A90E2',
                borderWidth: 1,
                barThickness: 20
            },
            {
                label: 'Durée bruleur (heures)',
                data: [],
                backgroundColor: '#D32F2F',
                borderColor: '#D32F2F',
                borderWidth: 1,
                barThickness: 20
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            title: {
                display: true,
                text: 'Sélectionnez une variété',
                font: {
                    size: 16,
                    weight: 'normal'
                },
                color: '#666',
                padding: 20
            },
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    padding: 20,
                    font: {
                        size: 12
                    }
                }
            }
        },
        scales: {
            x: {
                display: true,
                title: {
                    display: false
                },
                grid: {
                    display: true,
                    color: '#e0e0e0'
                },
                ticks: {
                    maxRotation: 45,
                    minRotation: 45,
                    font: {
                        size: 10
                    }
                }
            },
            y: {
                display: true,
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Heures'
                },
                grid: {
                    display: true,
                    color: '#e0e0e0'
                },
                ticks: {
                    stepSize: 2,
                    font: {
                        size: 11
                    }
                }
            }
        },
        interaction: {
            mode: 'index',
            intersect: false
        },
        elements: {
            bar: {
                borderWidth: 1
            }
        }
    }
});
;

// Function to get all filter values
function getFilterValues() {
    return {
        variety: document.getElementById('variety-filter').value,
        startDate: document.getElementById('start-date').value,
        endDate: document.getElementById('end-date').value,
        etage: document.getElementById('etage-filter').value
    };
}

// Function to update all charts and statistics
function updateAllCharts() {
    const filters = getFilterValues();
    console.log('Updating with filters:', filters);

    // Show loading state for chart1
    chart1Visual.data.labels = ['Loading...'];
    chart1Visual.data.datasets[0].data = [0];
    chart1Visual.data.datasets[1].data = [0];
    chart1Visual.update();

    // Update chart1
    fetchData('historique_1er_chargement', filters)
        .then(data => {
            console.log('Fetched data:', data);

            chart1Visual.data.labels = data.labels;
            chart1Visual.data.datasets[0].data = data.dryingDuration;
            chart1Visual.data.datasets[1].data = data.burnerDuration;

            const title = filters.variety ? 
                `Variété ${filters.variety} séchage 1er chargement` :
                'Sélectionnez une variété';
            chart1Visual.options.plugins.title.text = title;

            chart1Visual.update();
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            chart1Visual.data.labels = ['Error'];
            chart1Visual.data.datasets[0].data = [0];
            chart1Visual.data.datasets[1].data = [0];
            chart1Visual.update();
        });

    // Update chart2
    fetchData('graph_timeline', filters)
        .then(data => {
            console.log('Fetched timeline data:', data);

            chart2Visual.data.labels = data.label || [];
            chart2Visual.data.datasets[0].data = data.burner_state || [];
            chart2Visual.data.datasets[1].data = buildStepArray(timeLabels, data.etage_change || []);

            const title = filters.variety ? 
                `Timeline pour ${filters.variety}` :
                'Sélectionnez une variété';
            chart2Visual.options.plugins.title.text = title;
            chart2Visual.update();
        })
        .catch(error => {
            console.error('Error fetching timeline data:', error);
            chart2Visual.data.labels = ['Error'];
            chart2Visual.data.datasets[0].data = [0];
            chart2Visual.data.datasets[1].data = [0];
            chart2Visual.update();
        });

    // Update statistics
    fetchData('statistics', filters)
        .then(stats => {
            updateStatisticsDisplay(stats);
        })
        .catch(error => {
            console.error('Error fetching statistics:', error);
            document.getElementById('statistics-container').innerHTML = '<p class="error">Error loading statistics</p>';
        });
}

// Add event listeners for all filters
document.getElementById('variety-filter').addEventListener('change', updateAllCharts);
document.getElementById('start-date').addEventListener('change', updateAllCharts);
document.getElementById('end-date').addEventListener('change', updateAllCharts);
document.getElementById('etage-filter').addEventListener('change', updateAllCharts);

// Validate date range
document.getElementById('end-date').addEventListener('change', function() {
    const startDate = document.getElementById('start-date').value;
    const endDate = this.value;
    
    if (startDate && endDate && startDate > endDate) {
        alert('La date de fin doit être postérieure à la date de début');
        this.value = '';
    }
});

function updateStatisticsDisplay(stats) {
    const container = document.getElementById('statistics-container');
    const varietyStats = stats.variety_stats;
    const floorStats = stats.floor_stats;
    const campaignStats = stats.campaign_stats;
    const tableCounts = stats.table_counts;

    let html = `
        <div class="statistics-grid">
            <div class="stat-card">
                <h3>Statistiques de la Variété</h3>
                <div class="stat-content">
                    <p><strong>Variété:</strong> ${varietyStats.variety_name || 'Toutes'}</p>
                    <p><strong>Nombre Total de Cycles:</strong> ${varietyStats.cycle_count}</p>
                    <p><strong>Premier Cycle:</strong> ${new Date(varietyStats.first_cycle).toLocaleDateString()}</p>
                    <p><strong>Dernier Cycle:</strong> ${new Date(varietyStats.last_cycle).toLocaleDateString()}</p>
                    <p><strong>Durée Moyenne des Cycles:</strong> ${varietyStats.avg_cycle_hours} heures</p>
                    <p><strong>Durée Moyenne du Brûleur:</strong> ${varietyStats.avg_burner_hours} heures</p>
                </div>
            </div>

            <div class="stat-card">
                <h3>Durées Moyennes par Étage (Heures)</h3>
                <div class="stat-content">
                    <p><strong>Étage 1:</strong> ${floorStats.floor_1_avg || 'N/A'}</p>
                    <p><strong>Étage 2:</strong> ${floorStats.floor_2_avg || 'N/A'}</p>
                    <p><strong>Étage 3:</strong> ${floorStats.floor_3_avg || 'N/A'}</p>
                    <p><strong>Étage 4:</strong> ${floorStats.floor_4_avg || 'N/A'}</p>
                </div>
            </div>

            <div class="stat-card">
                <h3>Statistiques des Campagnes</h3>
                <div class="stat-content">
                    <p><strong>Nombre Total de Campagnes:</strong> ${campaignStats.total_campaigns}</p>
                    <p><strong>Première Campagne:</strong> ${new Date(campaignStats.first_campaign).toLocaleDateString()}</p>
                    <p><strong>Dernière Campagne:</strong> ${new Date(campaignStats.last_campaign).toLocaleDateString()}</p>
                    <p><strong>Moyenne de Cycles par Campagne:</strong> ${campaignStats.avg_cycles_per_campaign}</p>
                </div>
            </div>

            <div class="stat-card">
                <h3>Nombre Total d'Enregistrements</h3>
                <div class="stat-content">
                    <p><strong>Campagnes:</strong> ${tableCounts['Campaigns']}</p>
                    <p><strong>Cycles de Séchage:</strong> ${tableCounts['Drying Cycles']}</p>
                    <p><strong>Étages:</strong> ${tableCounts['Etages']}</p>
                </div>
            </div>
        </div>
    `;

    container.innerHTML = html;
}

const chart2 = document.getElementById('chart2').getContext('2d');
const chart2Visual = new Chart(chart2, {
    type: 'line',
    data: {
        labels: [],
        datasets: [
            {
                label: 'État bruleur',
                data: [],
                borderColor: '#4A90E2',
                backgroundColor: 'rgba(74, 144, 226, 0.1)',
                borderWidth: 2,
                fill: false,
                tension: 0
            },
            {
                label: 'étage panier chargement N°1',
                data: [],
                borderColor: '#D32F2F',
                backgroundColor: 'rgba(211, 47, 47, 0.1)',
                borderWidth: 2,
                fill: false,
                tension: 0,
                stepped: true
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        spanGaps: true,
        plugins: {
            title: {
                display: true,
                text: 'Sélectionnez une variété et un étage',
                font: {
                    size: 16,
                    weight: 'normal'
                },
                color: '#666',
                padding: 20
            },
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    padding: 20,
                    font: {
                        size: 12
                    }
                }
            }
        },
        scales: {
            x: {
                display: true,
                title: {
                    display: false
                },
                grid: {
                    display: true,
                    color: '#e0e0e0'
                },
                ticks: {
                    maxRotation: 45,
                    minRotation: 45,
                    font: {
                        size: 10
                    }
                }
            },
            y: {
                display: true,
                beginAtZero: true,
                max: 4.5,
                title: {
                    display: false
                },
                grid: {
                    display: true,
                    color: '#e0e0e0'
                },
                ticks: {
                    stepSize: 0.5,
                    font: {
                        size: 11
                    }
                }
            }
        },
        elements: {
            point: {
                radius: 3,
                hoverRadius: 5
            }
        }
    }
});
async function updateChart2(variety, etage) {
  try {
    // Get selected variety and etage from the form
    let = variety = document.getElementById('variety-filter').value;
    const data = await fetchData('graph_timeline', {
      variety: variety,
    });

    const labels = data.label || [];
    const burnerDataset = data.burner_state || [];
    const etageChanges = data.etage_change || [];
    const etageDataset = buildStepArray(timeLabels, etageChanges);

    chart2Visual.data.labels = timeLabels;

    chart2Visual.data.datasets[0].data = burnerDataset;
    chart2Visual.data.datasets[1].data = etageDataset;

    chart2Visual.options.plugins.title.text = `timeline pour ${variety} - étage ${etage}`;
    chart2Visual.update();

  } catch (error) {
    console.error("Erreur lors de la mise à jour du graphique:", error);
  }
}

function buildStepArray(timeLabels, etageChanges) {
    // etageChanges: [{timestamp: "2024-06-08 10:00:00", etage_number: 4}, ...]
    let result = [];
    let currentEtage = null;
    let changeIdx = 0;

    // Preprocess: convert all etageChanges timestamps to time only
    const processedChanges = etageChanges.map(e => ({
        ...e,
        timestamp: e.timestamp.length > 8 ? e.timestamp.slice(-8) : e.timestamp // get HH:MM:SS
    }));

    for (let i = 0; i < timeLabels.length; i++) {
        const label = timeLabels[i];

        while (
            changeIdx < processedChanges.length &&
            label >= processedChanges[changeIdx].timestamp
        ) {
            currentEtage = processedChanges[changeIdx].etage_number;
            changeIdx++;
        }
        result.push(currentEtage !== null ? currentEtage : 0);
    }
    return result;
}

async function fetchData(chartType = 'historique', params = {}) {
  try {
    const queryParams = new URLSearchParams();
    queryParams.append('chart_type', chartType);

    Object.entries(params).forEach(([key, value]) => {
      if (value) queryParams.append(key, value);
    });

    queryParams.append('_t', Date.now());

    const url = `backend/data.php?${queryParams.toString()}`;
    console.log('Fetching from URL:', url);

    const response = await fetch(url, {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Cache-Control': 'no-cache'
      }
    });

    if (!response.ok) {
      const text = await response.text();
      throw new Error(`Server error ${response.status}: ${text}`);
    }

    const result = await response.json();

    if (result.status === 'error') {
      throw new Error(result.message);
    }

    return result.data || result;
  } catch (error) {
    console.error('Fetch error:', error);
    this.showMessage('Erreur de chargement des données: ' + error.message, 'error');
    throw error;
  }
}
