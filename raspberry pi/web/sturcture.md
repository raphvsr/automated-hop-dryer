web:sechoir-houblon/
│
├── backend/ # Code backend (PHP, Python, etc.)
│ ├── php/ # Code PHP pour l'interface web et la logique métier
| |
| ├── api/
| | ├── shutdown.php
| | ├── start_drying.php
| | ├── stop_drying.php
| |
│ │ ├── classes/ # Classes PHP (ex: TimeManager, DryingControl, etc.)
│ │ │ ├── TimeManager.php
│ │ │ ├── DryingControl.php
│ │ │ └── DryingConfig.php
│ │ ├── scripts/ # Scripts PHP pour les endpoints API ou les tâches spécifiques
│ │ │ ├── start_drying.php
│ │ │ └── stop_drying.php
│ │ └── index.php # Point d'entrée de l'application web
│ │
│ ├── python/ # Code Python pour le contrôle des actionneurs et capteurs
│ │ ├── drying_control.py # Script pour démarrer/arrêter le séchage
│ │ ├── temperature_sensors.py # Script pour lire les capteurs de température
│ │ └── humidity_sensor.py # Script pour mesurer l'humidité (si applicable)
│ │
│ └── database/ # Scripts et fichiers liés à la base de données
│ ├── schema.sql # Schéma de la base de données
│ └── migrations/ # Migrations de la base de données (si nécessaire)
│
├── frontend/ # Code frontend (HTML, CSS, JavaScript)
│ ├── css/ # Feuilles de style CSS
│ │ └── styles.css
│ ├── js/ # Scripts JavaScript
│ │ └── main.js
│ ├── assets/ # Images, icônes, etc.
│ └── index.html # Page d'accueil de l'interface web
│
├── config/ # Fichiers de configuration
│ ├── config.php # Configuration PHP (accès à la base de données, etc.)
│ └── gpio_config.py # Configuration des broches GPIO pour le Raspberry Pi
│
│
├── logs/ # Fichiers de logs
│ ├── app.log # Logs de l'application
│ └── drying.log # Logs spécifiques au processus de séchage
│
│
└── README.md # Documentation générale du projet
