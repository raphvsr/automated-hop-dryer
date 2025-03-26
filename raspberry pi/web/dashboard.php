<?php
session_start();
if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit();
}
if ($_SESSION['admin'] != 1) {
  header('Location: index.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="src/css/dashboard.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
</head>

<body>
  <nav class="sidebar-navigation">
    <ul>
      <li class="active">
        <i class="fa fa-home"></i>
        <span class="tooltip">Accueil</span>
      </li>
      <li>
        <i class="fa fa-file-o"></i>
        <span class="tooltip">Csv</span>
      </li>
      <li>
        <i class="fa fa-user-o"></i>
        <span class="tooltip">Utilisateur</span>
      </li>
      <li>
        <i class="fa fa-sliders"></i>
        <span class="tooltip">Paramètres</span>
      </li>
    </ul>
  </nav>

  <main class="dashboard-content">
    <h1>Tableau de Bord</h1>
    <section class="graphs">
      <div class="graph-container">
        <canvas id="chart1"></canvas>
      </div>
      <div class="graph-container">
        <canvas id="chart2"></canvas>
      </div>
      <div class="graph-container">
        <canvas id="chart3"></canvas>
      </div>
      <div class="graph-container">
        <canvas id="chart4"></canvas>
      </div>
    </section>
  </main>

  <script>
    const chartOptions = {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'top',
          labels: {
            font: {
              family: "'Inter', sans-serif",
              size: 12
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            color: 'rgba(0, 0, 0, 0.05)'
          },
          ticks: {
            font: {
              family: "'Inter', sans-serif",
              size: 11
            }
          }
        },
        x: {
          grid: {
            display: false
          },
          ticks: {
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
    new Chart(ctx1, {
      type: 'bar',
      data: {
        labels: ['15/01/2020', '16/01/2020', '17/01/2020', '18/01/2020', '19/01/2020', '20/01/2020'],
        datasets: [{
          label: 'Durée séchage',
          data: [12, 10, 11, 9, 12, 11],
          backgroundColor: 'rgba(56, 189, 248, 0.2)',
          borderColor: 'rgb(56, 189, 248)',
          borderWidth: 1
        }, {
          label: 'Durée bruleur',
          data: [10, 8, 9, 7, 10, 9],
          backgroundColor: 'rgba(239, 68, 68, 0.2)',
          borderColor: 'rgb(239, 68, 68)',
          borderWidth: 1
        }]
      },
      options: {
        ...chartOptions,
        scales: {
          ...chartOptions.scales,
          y: {
            ...chartOptions.scales.y,
            max: 14
          }
        }
      }
    });

    // Chart 2: Varieté date N°chargement
    const ctx2 = document.getElementById('chart2').getContext('2d');
    new Chart(ctx2, {
      type: 'line',
      data: {
        labels: ['15/01/2020', '16/01/2020', '17/01/2020', '18/01/2020', '19/01/2020', '20/01/2020'],
        datasets: [{
          label: 'Etat bruleur',
          data: [0.5, 0, 0.5, 0, 0.5, 0],
          borderColor: 'rgb(59, 130, 246)',
          borderWidth: 2,
          fill: false,
          stepped: true
        }, {
          label: 'Etage panier chargement N°1',
          data: [4, 4, 3, 2, 1, 1],
          borderColor: 'rgb(239, 68, 68)',
          borderWidth: 2,
          fill: false,
          stepped: true
        }]
      },
      options: {
        ...chartOptions,
        scales: {
          ...chartOptions.scales,
          y: {
            ...chartOptions.scales.y,
            max: 5
          }
        }
      }
    });

    // Chart 3: Varieté date
    const ctx3 = document.getElementById('chart3').getContext('2d');
    new Chart(ctx3, {
      type: 'line',
      data: {
        labels: ['15/01/2020', '16/01/2020', '17/01/2020', '18/01/2020', '19/01/2020', '20/01/2020'],
        datasets: [{
          label: 'Etat bruleur',
          data: [0.5, 0, 0.5, 0, 0.5, 0],
          borderColor: 'rgb(59, 130, 246)',
          borderWidth: 2,
          fill: false,
          stepped: true
        }, {
          label: 'Etage panier chargement N°1',
          data: [4, 4, 3, 2, 1, 1],
          borderColor: 'rgb(239, 68, 68)',
          borderWidth: 2,
          fill: false,
          stepped: true
        }, {
          label: 'Etage panier chargement N°2',
          data: [3, 3, 2, 1, 1, 0],
          borderColor: 'rgb(34, 197, 94)',
          borderWidth: 2,
          fill: false,
          stepped: true
        }, {
          label: 'Etage panier chargement N°3',
          data: [2, 2, 1, 0, 0, 0],
          borderColor: 'rgb(168, 85, 247)',
          borderWidth: 2,
          fill: false,
          stepped: true
        }, {
          label: 'Etage panier chargement N°4',
          data: [1, 1, 0, 0, 0, 0],
          borderColor: 'rgb(234, 179, 8)',
          borderWidth: 2,
          fill: false,
          stepped: true
        }]
      },
      options: {
        ...chartOptions,
        scales: {
          ...chartOptions.scales,
          y: {
            ...chartOptions.scales.y,
            max: 5
          }
        }
      }
    });

    // Chart 4: Durée séchage
    const ctx4 = document.getElementById('chart4').getContext('2d');
    new Chart(ctx4, {
      type: 'bar',
      data: {
        labels: ['1'],
        datasets: [{
          label: 'durée séchage panier1',
          data: [8.5],
          backgroundColor: 'rgb(59, 130, 246)',
          borderColor: 'rgb(59, 130, 246)',
          borderWidth: 1
        }, {
          label: 'durée bruleur panier1',
          data: [8],
          backgroundColor: 'rgb(239, 68, 68)',
          borderColor: 'rgb(239, 68, 68)',
          borderWidth: 1
        }, {
          label: 'durée séchage panier2',
          data: [9],
          backgroundColor: 'rgb(34, 197, 94)',
          borderColor: 'rgb(34, 197, 94)',
          borderWidth: 1
        }, {
          label: 'durée bruleur panier2',
          data: [7],
          backgroundColor: 'rgb(168, 85, 247)',
          borderColor: 'rgb(168, 85, 247)',
          borderWidth: 1
        }, {
          label: 'durée séchage panier3',
          data: [11],
          backgroundColor: 'rgb(234, 179, 8)',
          borderColor: 'rgb(234, 179, 8)',
          borderWidth: 1
        }, {
          label: 'durée bruleur panier3',
          data: [8.5],
          backgroundColor: 'rgb(236, 72, 153)',
          borderColor: 'rgb(236, 72, 153)',
          borderWidth: 1
        }, {
          label: 'durée séchage panier4',
          data: [10],
          backgroundColor: 'rgb(14, 165, 233)',
          borderColor: 'rgb(14, 165, 233)',
          borderWidth: 1
        }, {
          label: 'durée bruleur panier4',
          data: [7.5],
          backgroundColor: 'rgb(249, 115, 22)',
          borderColor: 'rgb(249, 115, 22)',
          borderWidth: 1
        }]
      },
      options: {
        ...chartOptions,
        scales: {
          ...chartOptions.scales,
          y: {
            ...chartOptions.scales.y,
            max: 12,
            ticks: {
              ...chartOptions.scales.y.ticks,
              callback: function(value) {
                return value + ':00';
              }
            }
          }
        }
      }
    });
  </script>

  <script src="https://kit.fontawesome.com/0e4bc9cea5.js" crossorigin="anonymous"></script>
</body>

</html>
