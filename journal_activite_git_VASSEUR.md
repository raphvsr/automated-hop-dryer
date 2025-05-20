# Journal d'activité Git - Projet Universitaire
## Auteur: Raphael Vasseur
## Date de génération: 2025-05-20


================================================================================
                     RÉSUMÉ GLOBAL DU PROJET
                     Auteur: Raphael Vasseur
================================================================================

Nombre total de commits: 98

### Activité par mois:
- 2025-03: 59 commits
- 2025-04: 31 commits
- 2025-05: 8 commits

### Répartition du travail par catégorie:
- PHP: 199 fichiers (50.1%)
- Code Python: 59 fichiers (14.9%)
- JavaScript: 50 fichiers (12.6%)
- CSS: 41 fichiers (10.3%)
- Documentation: 15 fichiers (3.8%)
- Données: 11 fichiers (2.8%)
- Configuration: 9 fichiers (2.3%)
- Base de données: 9 fichiers (2.3%)
- Autre: 4 fichiers (1.0%)


================================================================================
                     JOURNAL D'ACTIVITÉ DU PROJET
                     Auteur: Raphael Vasseur
================================================================================



## 2025-03-13 (10 commits)

### Résumé de la journée:
- Total fichiers modifiés: 32
- Lignes ajoutées: 689
- Lignes supprimées: 40
- Bilan lignes: +649

#### Catégories de travail:
- PHP: 26 fichiers
- Documentation: 4 fichiers
- CSS: 4 fichiers
- JavaScript: 4 fichiers
- Code Python: 2 fichiers
- Base de données: 1 fichiers
----------------------------

### drying config
- Hash: 2f42a85
- Auteur: Raphael Vasseur
- Description: drying config
- Fichiers modifiés:
  * web/backend/php/api/shutdown.php
  * web/backend/php/classes/dryingConfig.php
  * web/backend/php/classes/dryingControl.php
- Changements: 3 files changed, 27 insertions(+), 9 deletions(-)

### database
- Hash: 8a0db2e
- Auteur: Raphael Vasseur
- Description: database
- Fichiers modifiés:
  * web/database.sql
- Changements: 1 file changed, 356 insertions(+)

### .
- Hash: 9fb4fe9
- Auteur: Raphael Vasseur
- Description: .
- Fichiers modifiés:
  * web/backend/php/classes/dryingConfig.php
- Changements: 1 file changed, 4 insertions(+), 8 deletions(-)

### Refactor drying control script execution and update configuration file structure
- Hash: 627d462
- Auteur: Raphael Vasseur
- Description: Refactor drying control script execution and update configuration file structure
- Fichiers modifiés:
  * web/backend/php/classes/dryingControl.php
  * web/config/config.php
- Changements: 2 files changed, 11 insertions(+), 11 deletions(-)

### .
- Hash: 13bb80c
- Auteur: Raphael Vasseur
- Description: .
- Fichiers modifiés:
  * web/backend/php/api/start_drying.php
  * web/backend/php/api/stop_drying.php
  * web/sturcture.md
- Changements: 3 files changed, 6 insertions(+)

### Add drying configuration and control classes with initial database setup + structure
- Hash: e2a4fb9
- Auteur: Raphael Vasseur
- Description: Add drying configuration and control classes with initial database setup + structure
- Fichiers modifiés:
  * web/backend/php/classes/dryingConfig.php
  * web/backend/php/classes/dryingControl.php
  * web/backend/python/drying_control.py
  * web/backend/script/start_drying.php
  * web/backend/script/stop_drying.php
  * ... et 3 autres fichiers
- Changements: 8 files changed, 86 insertions(+)

### Replace shutdown script with a new API endpoint for Raspberry Pi shutdown functionality
- Hash: c065b7a
- Auteur: Raphael Vasseur
- Description: Replace shutdown script with a new API endpoint for Raspberry Pi shutdown functionality
- Fichiers modifiés:
  * web/backend/php/api/shutdown.php
  * web/backend/php/script/shutdown.php
- Changements: 2 files changed, 7 insertions(+), 11 deletions(-)

### Refactor project structure by moving backend files to a new directory and re-implementing login and registration functionality
- Hash: e43ccb5
- Auteur: Raphael Vasseur
- Description: Refactor project structure by moving backend files to a new directory and re-implementing login and ...
- Fichiers modifiés:
  * web/backend/database.php
  * web/backend/php/login-process.php
  * web/backend/php/register-process.php
  * web/backend/php/script/shutdown.php
  * web/index.php
  * ... et 5 autres fichiers
- Changements: 10 files changed, 11 insertions(+)

### Add initial project structure with login and registration functionality
- Hash: 5fc1c67
- Auteur: Raphael Vasseur
- Description: Add initial project structure with login and registration functionality
- Fichiers modifiés:
  * README.md
  * backend/database.php
  * backend/php/login-process.php
  * backend/php/register-process.php
  * index.php
  * ... et 5 autres fichiers
- Changements: 10 files changed, 180 insertions(+), 1 deletion(-)

### Initial commit
- Hash: d81f54b
- Auteur: Raphael Vasseur
- Description: Initial commit
- Fichiers modifiés:
  * README.md
- Changements: 1 file changed, 1 insertion(+)



## 2025-03-14 (3 commits)

### Résumé de la journée:
- Total fichiers modifiés: 6
- Lignes ajoutées: 69
- Lignes supprimées: 7
- Bilan lignes: +62

#### Catégories de travail:
- PHP: 3 fichiers
- Code Python: 3 fichiers
- Documentation: 1 fichiers
----------------------------

### settings the time
- Hash: e4e5772
- Auteur: Raphael Vasseur
- Description: settings the time
- Fichiers modifiés:
  * gpio.md
  * web/backend/php/api/settings_the_time.php
  * web/backend/python/drying_control.py
- Changements: 3 files changed, 27 insertions(+), 4 deletions(-)

### Add GPIO control for drying process
- Hash: 4d13d7a
- Auteur: Raphael Vasseur
- Description: Add GPIO control for drying process
- Fichiers modifiés:
  * web/backend/python/drying_control.py
  * web/config/config.py
- Changements: 2 files changed, 22 insertions(+), 1 deletion(-)

### Implement start and stop drying API endpoints
- Hash: e578f98
- Auteur: Raphael Vasseur
- Description: Implement start and stop drying API endpoints
- Fichiers modifiés:
  * web/backend/php/api/start_drying.php
  * web/backend/php/api/stop_drying.php
- Changements: 2 files changed, 20 insertions(+), 2 deletions(-)



## 2025-03-15 (3 commits)

### Résumé de la journée:
- Total fichiers modifiés: 11
- Lignes ajoutées: 327
- Lignes supprimées: 82
- Bilan lignes: +245

#### Catégories de travail:
- PHP: 8 fichiers
- JavaScript: 3 fichiers
- CSS: 1 fichiers
----------------------------

### Enhance drying control interface with real-time data visualization, improved layout, and new temperature configuration options
- Hash: 9d08588
- Auteur: Raphael Vasseur
- Description: Enhance drying control interface with real-time data visualization, improved layout, and new tempera...
- Fichiers modifiés:
  * index.php
  * web/backend/php/api/get_drying_data.php
  * web/backend/php/classes/dryingConfig.php
  * web/index.php
  * web/src/css/styles.css
  * ... et 3 autres fichiers
- Changements: 8 files changed, 243 insertions(+), 24 deletions(-)

### Refactor drying configuration and control classes for improved error handling and code safety against sql injection
- Hash: 23fc0d8
- Auteur: Raphael Vasseur
- Description: Refactor drying configuration and control classes for improved error handling and code safety agains...
- Fichiers modifiés:
  * web/backend/php/api/shutdown.php
  * web/backend/php/classes/dryingConfig.php
  * web/backend/php/classes/dryingControl.php
- Changements: 3 files changed, 61 insertions(+), 43 deletions(-)

### Improve time setting API with error handling and output feedback
- Hash: 461ab0a
- Auteur: Raphael Vasseur
- Description: Improve time setting API with error handling and output feedback
- Fichiers modifiés:
  * web/backend/php/api/settings_the_time.php
- Changements: 1 file changed, 23 insertions(+), 15 deletions(-)



## 2025-03-16 (1 commits)

### Résumé de la journée:
- Total fichiers modifiés: 2
- Lignes ajoutées: 46
- Lignes supprimées: 1
- Bilan lignes: +45

#### Catégories de travail:
- PHP: 1 fichiers
- CSS: 1 fichiers
----------------------------

### Add login page styles
- Hash: e68ea9e
- Auteur: Raphael Vasseur
- Description: Add login page styles
- Fichiers modifiés:
  * web/login.php
  * web/src/css/login.css
- Changements: 2 files changed, 46 insertions(+), 1 deletion(-)



## 2025-03-17 (2 commits)

### Résumé de la journée:
- Total fichiers modifiés: 3
- Lignes ajoutées: 38
- Lignes supprimées: 13
- Bilan lignes: +25

#### Catégories de travail:
- PHP: 4 fichiers
----------------------------

### Refactor login process for improved SQL query safety and add temperature data API
- Hash: 0f911dc
- Auteur: Raphael Vasseur
- Description: Refactor login process for improved SQL query safety and add temperature data API
- Fichiers modifiés:
  * web/backend/php/api/get_temperatures.php
  * web/backend/php/login-process.php
- Changements: 2 files changed, 15 insertions(+), 2 deletions(-)

### Enhance time setting API with improved date validation, error handling, and SQL injection protection
- Hash: 371a30f
- Auteur: Raphael Vasseur
- Description: Enhance time setting API with improved date validation, error handling, and SQL injection protection
- Fichiers modifiés:
  * web/backend/php/api/settings_the_time.php
  * web/backend/php/login-process.php
- Changements: 2 files changed, 23 insertions(+), 11 deletions(-)



## 2025-03-19 (1 commits)

### Résumé de la journée:
- Total fichiers modifiés: 1
- Lignes ajoutées: 158
- Lignes supprimées: 0
- Bilan lignes: +158

#### Catégories de travail:
- PHP: 1 fichiers
----------------------------

### Add DryingCampaigns class for managing drying campaigns and associated hop varieties
- Hash: 451e0ae
- Auteur: Raphael Vasseur
- Description: Add DryingCampaigns class for managing drying campaigns and associated hop varieties
- Fichiers modifiés:
  * web/backend/php/classes/drying_campaigns.php
- Changements: 1 file changed, 158 insertions(+)



## 2025-03-20 (4 commits)

### Résumé de la journée:
- Total fichiers modifiés: 29
- Lignes ajoutées: 404
- Lignes supprimées: 18
- Bilan lignes: +386

#### Catégories de travail:
- PHP: 21 fichiers
- Code Python: 3 fichiers
- CSS: 2 fichiers
- JavaScript: 2 fichiers
- Base de données: 1 fichiers
- Documentation: 1 fichiers
----------------------------

### move the file to raspberry pi
- Hash: 34832be
- Auteur: Raphael Vasseur
- Description: move the file to raspberry pi
- Fichiers modifiés:
  * raspberry pi/web/backend/database.php
  * raspberry pi/web/backend/php/api/get_drying_data.php
  * raspberry pi/web/backend/php/api/get_temperatures.php
  * raspberry pi/web/backend/php/api/settings_the_time.php
  * raspberry pi/web/backend/php/api/shutdown.php
  * ... et 20 autres fichiers
- Changements: 25 files changed, 0 insertions(+), 0 deletions(-)

### Add GPIO control script for LED and button interaction with event detection
- Hash: 2de8419
- Auteur: Raphael Vasseur
- Description: Add GPIO control script for LED and button interaction with event detection
- Fichiers modifiés:
  * raspberry pi/software/validate.py
- Changements: 1 file changed, 38 insertions(+)

### Refactor database interactions in sensorData and DryingCampaigns classes for improved error handling and SQL injection protection; add alerts class for managing alert data.
- Hash: 2874bc4
- Auteur: Raphael Vasseur
- Description: Refactor database interactions in sensorData and DryingCampaigns classes for improved error handling...
- Fichiers modifiés:
  * web/backend/php/classes/alerts.php
  * web/backend/php/classes/drying_campaigns.php
  * web/backend/php/classes/sensor_data.php
- Changements: 3 files changed, 222 insertions(+), 18 deletions(-)

### Add sensorData class for managing sensor data retrieval, addition, update, and deletion
- Hash: da1f632
- Auteur: Raphael Vasseur
- Description: Add sensorData class for managing sensor data retrieval, addition, update, and deletion
- Fichiers modifiés:
  * web/backend/php/classes/sensor_data.php
- Changements: 1 file changed, 144 insertions(+)



## 2025-03-22 (1 commits)

### Résumé de la journée:
- Total fichiers modifiés: 2
- Lignes ajoutées: 8
- Lignes supprimées: 2
- Bilan lignes: +6

#### Catégories de travail:
- PHP: 1 fichiers
- Code Python: 1 fichiers
----------------------------

### teste api python
- Hash: 5573add
- Auteur: Raphael Vasseur
- Description: teste api python
- Fichiers modifiés:
  * index.php
  * raspberry pi/software/main.py
- Changements: 2 files changed, 8 insertions(+), 2 deletions(-)



## 2025-03-25 (1 commits)

### Résumé de la journée:
- Total fichiers modifiés: 4
- Lignes ajoutées: 47
- Lignes supprimées: 11
- Bilan lignes: +36

#### Catégories de travail:
- PHP: 2 fichiers
- Données: 1 fichiers
- Code Python: 1 fichiers
----------------------------

### utilisation de l'api et creation de data.csv
- Hash: 3721900
- Auteur: Raphael Vasseur
- Description: utilisation de l'api et creation de data.csv
- Fichiers modifiés:
  * raspberry pi/software/data.csv
  * raspberry pi/software/main.py
  * raspberry pi/web/backend/php/api/get_temperatures.php
  * raspberry pi/web/backend/php/api/start_drying.php
- Changements: 4 files changed, 47 insertions(+), 11 deletions(-)



## 2025-03-26 (6 commits)

### Résumé de la journée:
- Total fichiers modifiés: 20
- Lignes ajoutées: 823
- Lignes supprimées: 81
- Bilan lignes: +742

#### Catégories de travail:
- PHP: 12 fichiers
- CSS: 3 fichiers
- Documentation: 3 fichiers
- JavaScript: 2 fichiers
- Code Python: 2 fichiers
- Données: 1 fichiers
----------------------------

### Implement time management feature with RTC synchronization and UI enhancements
- Hash: 4d09fba
- Auteur: Raphael Vasseur
- Description: Implement time management feature with RTC synchronization and UI enhancements
- Fichiers modifiés:
  * raspberry pi/web/backend/php/api/rtc-sync.php
  * raspberry pi/web/backend/php/api/shutdown.php
  * raspberry pi/web/backend/php/api/stop_drying.php
  * raspberry pi/web/src/css/time_management.css
  * raspberry pi/web/src/js/time_management.js
  * ... et 2 autres fichiers
- Changements: 7 files changed, 162 insertions(+), 3 deletions(-)

### Add project documentation for automated hop drying system
- Hash: ea797c9
- Auteur: Raphael Vasseur
- Description: Add project documentation for automated hop drying system
- Fichiers modifiés:
  * readme.md
- Changements: 1 file changed, 43 insertions(+)

### Enhance dashboard functionality and styling; add admin role check and new CSS for improved layout
- Hash: 7a568d9
- Auteur: Raphael Vasseur
- Description: Enhance dashboard functionality and styling; add admin role check and new CSS for improved layout
- Fichiers modifiés:
  * raspberry pi/web/backend/php/api/stop_drying.php
  * raspberry pi/web/backend/php/login-process.php
  * raspberry pi/web/dashboard.php
  * raspberry pi/web/index.php
  * raspberry pi/web/src/css/dashboard.css
  * ... et 1 autres fichiers
- Changements: 6 files changed, 464 insertions(+), 3 deletions(-)

### edit main.py
- Hash: c94a392
- Auteur: Raphael Vasseur
- Description: edit main.py
- Fichiers modifiés:
  * raspberry pi/software/data.csv
  * raspberry pi/software/main.py
  * raspberry pi/web/backend/php/api/start_drying.php
- Changements: 3 files changed, 18 insertions(+), 16 deletions(-)

### Remove max temperature configuration from web interface and add sensor reading script for DS18B20 temperature sensor
- Hash: 815ac5c
- Auteur: Raphael Vasseur
- Description: Remove max temperature configuration from web interface and add sensor reading script for DS18B20 te...
- Fichiers modifiés:
  * raspberry pi/read_sensor.py
  * raspberry pi/web/index.php
  * raspberry pi/web/src/js/index.js
- Changements: 3 files changed, 57 insertions(+), 21 deletions(-)

### Remove obsolete GPIO and time settings scripts; add new RTC synchronization API for improved time management.
- Hash: 7f0c454
- Auteur: Raphael Vasseur
- Description: Remove obsolete GPIO and time settings scripts; add new RTC synchronization API for improved time ma...
- Fichiers modifiés:
  * commad-Raspberry-Romain.md
  * raspberry pi/web/backend/php/api/rtc-sync.php
  * raspberry pi/web/backend/php/api/settings_the_time.php
- Changements: 3 files changed, 79 insertions(+), 38 deletions(-)



## 2025-03-27 (9 commits)

### Résumé de la journée:
- Total fichiers modifiés: 13
- Lignes ajoutées: 259
- Lignes supprimées: 137
- Bilan lignes: +122

#### Catégories de travail:
- PHP: 15 fichiers
- JavaScript: 4 fichiers
- Code Python: 3 fichiers
- CSS: 3 fichiers
----------------------------

### Implement drying control status retrieval and enhance existing drying methods
- Hash: 8f96dbc
- Auteur: Raphael Vasseur
- Description: Implement drying control status retrieval and enhance existing drying methods
- Fichiers modifiés:
  * raspberry pi/web/backend/php/api/get_drying_status.php
  * raspberry pi/web/backend/php/api/start_drying.php
  * raspberry pi/web/backend/php/api/stop_drying.php
  * raspberry pi/web/backend/php/classes/dryingControl.php
  * raspberry pi/web/backend/python/drying_control.py
- Changements: 5 files changed, 47 insertions(+), 22 deletions(-)

### Add manual time setting feature with input validation and UI enhancements
- Hash: 702cc4a
- Auteur: Raphael Vasseur
- Description: Add manual time setting feature with input validation and UI enhancements
- Fichiers modifiés:
  * raspberry pi/web/backend/php/api/rtc-sync.php
  * raspberry pi/web/src/css/time_management.css
  * raspberry pi/web/src/js/time_management.js
  * raspberry pi/web/time_management.php
- Changements: 4 files changed, 98 insertions(+), 47 deletions(-)

### Enhance manual time update feature with input validation and styling improvements
- Hash: 2ab96af
- Auteur: Raphael Vasseur
- Description: Enhance manual time update feature with input validation and styling improvements
- Fichiers modifiés:
  * raspberry pi/web/src/css/time_management.css
  * raspberry pi/web/src/js/time_management.js
  * raspberry pi/web/time_management.php
- Changements: 3 files changed, 19 insertions(+), 4 deletions(-)

### .
- Hash: 9dfa6a7
- Auteur: Raphael Vasseur
- Description: .
- Fichiers modifiés:
  * raspberry pi/software/validate.py
  * raspberry pi/web/backend/php/api/get_drying_data.php
- Changements: 2 files changed, 5 insertions(+), 7 deletions(-)

### Add manual time update feature and improve time synchronization UI
- Hash: 4c55a4b
- Auteur: Raphael Vasseur
- Description: Add manual time update feature and improve time synchronization UI
- Fichiers modifiés:
  * raspberry pi/web/backend/php/api/rtc-sync.php
  * raspberry pi/web/src/css/time_management.css
  * raspberry pi/web/src/js/time_management.js
  * raspberry pi/web/time_management.php
- Changements: 4 files changed, 44 insertions(+), 9 deletions(-)

### .
- Hash: aee1d3d
- Auteur: Raphael Vasseur
- Description: .
- Fichiers modifiés:
  * raspberry pi/web/backend/php/api/rtc-sync.php
  * raspberry pi/web/backend/php/classes/sensor_data.php
  * raspberry pi/web/backend/python/drying_control.py
- Changements: 3 files changed, 3 insertions(+), 4 deletions(-)

### get time
- Hash: 506bbcd
- Auteur: Raphael Vasseur
- Description: get time
- Fichiers modifiés:
  * raspberry pi/web/backend/php/api/rtc-sync.php
  * raspberry pi/web/src/js/time_management.js
- Changements: 2 files changed, 16 insertions(+), 8 deletions(-)

### Translate user interface text to French for improved localization
- Hash: 82d3023
- Auteur: Raphael Vasseur
- Description: Translate user interface text to French for improved localization
- Fichiers modifiés:
  * raspberry pi/web/index.php
- Changements: 1 file changed, 15 insertions(+), 15 deletions(-)

### Refactor sensor data queries to remove humidity field and optimize SQL statements
- Hash: 195a62f
- Auteur: Raphael Vasseur
- Description: Refactor sensor data queries to remove humidity field and optimize SQL statements
- Fichiers modifiés:
  * raspberry pi/web/backend/php/classes/sensor_data.php
- Changements: 1 file changed, 12 insertions(+), 21 deletions(-)



## 2025-03-28 (10 commits)

### Résumé de la journée:
- Total fichiers modifiés: 23
- Lignes ajoutées: 665
- Lignes supprimées: 363
- Bilan lignes: +302

#### Catégories de travail:
- PHP: 14 fichiers
- Code Python: 9 fichiers
- CSS: 3 fichiers
- Données: 2 fichiers
- JavaScript: 1 fichiers
- Base de données: 1 fichiers
----------------------------

### Add USB device management and dashboard functionality
- Hash: 951abe2
- Auteur: Raphael Vasseur
- Description: Add USB device management and dashboard functionality
- Fichiers modifiés:
  * pc/web/dashboard.php
  * pc/web/index.php
  * pc/web/src/css/dashboard.css
  * raspberry pi/web/backend/check_usb.php
  * raspberry pi/web/backend/export_to_usb.php
  * ... et 7 autres fichiers
- Changements: 12 files changed, 514 insertions(+), 296 deletions(-)

### .
- Hash: 544dd87
- Auteur: Raphael Vasseur
- Description: .
- Fichiers modifiés:
  * raspberry pi/web/backend/python/drying_control.py
  * raspberry pi/web/config/config.py
- Changements: 2 files changed, 7 insertions(+), 3 deletions(-)

### Simplify shutdown API by removing unnecessary variable assignment
- Hash: 553e219
- Auteur: Raphael Vasseur
- Description: Simplify shutdown API by removing unnecessary variable assignment
- Fichiers modifiés:
  * raspberry pi/web/backend/php/api/shutdown.php
- Changements: 1 file changed, 2 insertions(+), 2 deletions(-)

### .
- Hash: 9fcef6d
- Auteur: Raphael Vasseur
- Description: .
- Fichiers modifiés:
  * raspberry pi/web/backend/python/drying_control.py
  * raspberry pi/web/config/config.php
  * raspberry pi/web/config/config.py
- Changements: 3 files changed, 3 insertions(+), 4 deletions(-)

### main.py update
- Hash: 0711a69
- Auteur: Raphael Vasseur
- Description: main.py update
- Fichiers modifiés:
  * raspberry pi/software/data.csv
  * raspberry pi/software/main.py
- Changements: 2 files changed, 25 insertions(+), 25 deletions(-)

### Refactor drying control to import API configuration and update GPIO handling
- Hash: 7c786f5
- Auteur: Raphael Vasseur
- Description: Refactor drying control to import API configuration and update GPIO handling
- Fichiers modifiés:
  * raspberry pi/web/backend/python/drying_control.py
  * raspberry pi/web/config/config.py
- Changements: 2 files changed, 7 insertions(+), 5 deletions(-)

### Add API for saving drying status and enhance session management
- Hash: 9a7b089
- Auteur: Raphael Vasseur
- Description: Add API for saving drying status and enhance session management
- Fichiers modifiés:
  * raspberry pi/web/backend/php/api/save_drying_status.php
  * raspberry pi/web/backend/php/api/stop_drying.php
  * raspberry pi/web/backend/php/classes/dryingControl.php
  * raspberry pi/web/backend/python/drying_control.py
  * raspberry pi/web/time_management.php
- Changements: 5 files changed, 28 insertions(+), 6 deletions(-)

### Add API endpoint definition to configuration file
- Hash: f133e82
- Auteur: Raphael Vasseur
- Description: Add API endpoint definition to configuration file
- Fichiers modifiés:
  * raspberry pi/web/config/config.php
- Changements: 1 file changed, 4 insertions(+), 1 deletion(-)

### added the database_pc file
- Hash: fe48b1a
- Auteur: Raphael Vasseur
- Description: added the database_pc file
- Fichiers modifiés:
  * raspberry pi/web/database_pc.sql
- Changements: 1 file changed, 48 insertions(+)

### test
- Hash: 1155967
- Auteur: Raphael Vasseur
- Description: test
- Fichiers modifiés:
  * raspberry pi/software/main.py
- Changements: 1 file changed, 27 insertions(+), 21 deletions(-)



## 2025-03-29 (4 commits)

### Résumé de la journée:
- Total fichiers modifiés: 19
- Lignes ajoutées: 895
- Lignes supprimées: 152
- Bilan lignes: +743

#### Catégories de travail:
- PHP: 11 fichiers
- CSS: 8 fichiers
- JavaScript: 2 fichiers
- Autre: 2 fichiers
- Données: 1 fichiers
----------------------------

### Refactor user management: remove admin dashboard, update links, and add new user creation functionality with password generation
- Hash: 59f64a6
- Auteur: Raphael Vasseur
- Description: Refactor user management: remove admin dashboard, update links, and add new user creation functional...
- Fichiers modifiés:
  * raspberry pi/web/admin/dashboard.php
  * raspberry pi/web/admin/new_user.php
  * raspberry pi/web/admin/src/css/new_user.css
  * raspberry pi/web/admin/src/css/users.css
  * raspberry pi/web/admin/src/js/new_user.js
  * ... et 2 autres fichiers
- Changements: 7 files changed, 441 insertions(+), 130 deletions(-)

### Refactor admin dashboard and user management: move files to admin directory and update CSS/JS
- Hash: dcbe664
- Auteur: Raphael Vasseur
- Description: Refactor admin dashboard and user management: move files to admin directory and update CSS/JS
- Fichiers modifiés:
  * .gitignore
  * raspberry pi/web/admin/csv.php
  * raspberry pi/web/admin/dashboard.php
  * raspberry pi/web/admin/new_user.php
  * raspberry pi/web/admin/src/css/csv.css
  * ... et 6 autres fichiers
- Changements: 11 files changed, 199 insertions(+), 5 deletions(-)

### Add user account creation page and styling
- Hash: 1b471a6
- Auteur: Raphael Vasseur
- Description: Add user account creation page and styling
- Fichiers modifiés:
  * raspberry pi/web/src/css/users.css
  * raspberry pi/web/users.php
- Changements: 2 files changed, 198 insertions(+)

###  improve CSV file sorting, display the newest first
- Hash: 0b5bf21
- Auteur: Raphael Vasseur
- Description: improve CSV file sorting, display the newest first
- Fichiers modifiés:
  * .gitignore
  * raspberry pi/web/backend/export_to_usb.php
  * raspberry pi/web/csv.php
  * raspberry pi/web/src/css/csv.css
- Changements: 4 files changed, 57 insertions(+), 17 deletions(-)



## 2025-03-30 (2 commits)

### Résumé de la journée:
- Total fichiers modifiés: 7
- Lignes ajoutées: 194
- Lignes supprimées: 125
- Bilan lignes: +69

#### Catégories de travail:
- PHP: 6 fichiers
- JavaScript: 1 fichiers
- CSS: 1 fichiers
----------------------------

### Add user creation functionality: implement form validation, AJAX submission, and backend processing
- Hash: 273a00a
- Auteur: Raphael Vasseur
- Description: Add user creation functionality: implement form validation, AJAX submission, and backend processing
- Fichiers modifiés:
  * raspberry pi/web/admin/new_user.php
  * raspberry pi/web/admin/src/js/new_user.js
  * raspberry pi/web/backend/php/register-process.php
- Changements: 3 files changed, 108 insertions(+), 6 deletions(-)

### Refactor sidebar navigation: extract to a separate component and update links for consistency
- Hash: 535aeca
- Auteur: Raphael Vasseur
- Description: Refactor sidebar navigation: extract to a separate component and update links for consistency
- Fichiers modifiés:
  * raspberry pi/web/admin/components/sidebar.php
  * raspberry pi/web/admin/csv.php
  * raspberry pi/web/admin/new_user.php
  * raspberry pi/web/admin/src/css/users.css
  * raspberry pi/web/admin/users.php
- Changements: 5 files changed, 86 insertions(+), 119 deletions(-)



## 2025-03-31 (2 commits)

### Résumé de la journée:
- Total fichiers modifiés: 6
- Lignes ajoutées: 125
- Lignes supprimées: 23
- Bilan lignes: +102

#### Catégories de travail:
- Code Python: 2 fichiers
- PHP: 2 fichiers
- Configuration: 1 fichiers
- JavaScript: 1 fichiers
----------------------------

### Add validation logic and JSON configuration for drying status
- Hash: 5badb17
- Auteur: Raphael Vasseur
- Description: Add validation logic and JSON configuration for drying status
- Fichiers modifiés:
  * raspberry pi/software/etage.json
  * raspberry pi/software/main.py
  * raspberry pi/software/validate.py
- Changements: 3 files changed, 27 insertions(+), 7 deletions(-)

### Add user deletion functionality: implement AJAX request and backend processing for user removal
- Hash: dffbfc8
- Auteur: Raphael Vasseur
- Description: Add user deletion functionality: implement AJAX request and backend processing for user removal
- Fichiers modifiés:
  * raspberry pi/web/admin/src/js/users.js
  * raspberry pi/web/admin/users.php
  * raspberry pi/web/backend/php/api/users-delete.php
- Changements: 3 files changed, 98 insertions(+), 16 deletions(-)



## 2025-04-01 (4 commits)

### Résumé de la journée:
- Total fichiers modifiés: 20
- Lignes ajoutées: 725
- Lignes supprimées: 493
- Bilan lignes: +232

#### Catégories de travail:
- PHP: 11 fichiers
- JavaScript: 4 fichiers
- Code Python: 3 fichiers
- Base de données: 1 fichiers
- Configuration: 1 fichiers
- Documentation: 1 fichiers
----------------------------

### Add varieties page and update sidebar navigation: include varieties link and enhance drying duration display (it display hours now) (:
- Hash: 137060b
- Auteur: Raphael Vasseur
- Description: Add varieties page and update sidebar navigation: include varieties link and enhance drying duration...
- Fichiers modifiés:
  * raspberry pi/web/admin/components/sidebar.php
  * raspberry pi/web/admin/varieties.php
- Changements: 2 files changed, 54 insertions(+), 38 deletions(-)

### Add variety management functionality: implement create, update, and delete APIs with AJAX integration + changed the database
- Hash: ec9fdc8
- Auteur: Raphael Vasseur
- Description: Add variety management functionality: implement create, update, and delete APIs with AJAX integratio...
- Fichiers modifiés:
  * raspberry pi/web/admin/new_variety.php
  * raspberry pi/web/admin/src/js/new_user.js
  * raspberry pi/web/admin/src/js/new_variety.js
  * raspberry pi/web/admin/src/js/varieties.js
  * raspberry pi/web/admin/varieties.php
  * ... et 7 autres fichiers
- Changements: 12 files changed, 521 insertions(+), 392 deletions(-)

### Update etage status management: initialize CSV and JSON, refactor validation logic, and implement etage update functionality
- Hash: caab3e4
- Auteur: Raphael Vasseur
- Description: Update etage status management: initialize CSV and JSON, refactor validation logic, and implement et...
- Fichiers modifiés:
  * raspberry pi/software/etage.json
  * raspberry pi/software/etage_update.py
  * raspberry pi/software/main.py
  * raspberry pi/software/todo.txt
  * raspberry pi/software/validate.py
- Changements: 5 files changed, 82 insertions(+), 42 deletions(-)

### Implement user editing functionality: add modal for editing user details and AJAX request for updates
- Hash: a3b1231
- Auteur: Raphael Vasseur
- Description: Implement user editing functionality: add modal for editing user details and AJAX request for update...
- Fichiers modifiés:
  * raspberry pi/web/admin/src/js/users.js
  * raspberry pi/web/admin/users.php
- Changements: 2 files changed, 68 insertions(+), 21 deletions(-)



## 2025-04-02 (7 commits)

### Résumé de la journée:
- Total fichiers modifiés: 71
- Lignes ajoutées: 137
- Lignes supprimées: 20
- Bilan lignes: +117

#### Catégories de travail:
- PHP: 37 fichiers
- Code Python: 12 fichiers
- CSS: 9 fichiers
- JavaScript: 9 fichiers
- Documentation: 3 fichiers
- Données: 2 fichiers
- Autre: 2 fichiers
- Base de données: 2 fichiers
- Configuration: 1 fichiers
----------------------------

### Add CSV import functionality and user database setup
- Hash: 323646f
- Auteur: Raphael Vasseur
- Description: Add CSV import functionality and user database setup
- Fichiers modifiés:
  * pc/usb/csv_to_sql.py
  * pc/usb/data.csv
  * pc/usb/wait_4_usb.py
  * pc/web/backend/import_csv.py
  * pc/web/backend/test.py
  * ... et 2 autres fichiers
- Changements: 7 files changed, 103 insertions(+), 2 deletions(-)

### Add time management features: include time management button in index, enhance time_management page layout, and implement back button functionality
- Hash: 1b55f53
- Auteur: Raphael Vasseur
- Description: Add time management features: include time management button in index, enhance time_management page ...
- Fichiers modifiés:
  * raspberry_pi/web/index.php
  * raspberry_pi/web/src/css/time_management.css
  * raspberry_pi/web/src/js/time_management.js
  * raspberry_pi/web/time_management.php
- Changements: 4 files changed, 20 insertions(+), 1 deletion(-)

### .
- Hash: d4a3da2
- Auteur: Raphael Vasseur
- Description: .
- Fichiers modifiés:
  * index.php
- Changements: 1 file changed, 2 insertions(+), 2 deletions(-)

### Refactor data loading: remove redundant validation function and integrate drying status reporting directly in load_etage_data
- Hash: e114589
- Auteur: Raphael Vasseur
- Description: Refactor data loading: remove redundant validation function and integrate drying status reporting di...
- Fichiers modifiés:
  * raspberry pi/software/main.py
- Changements: 1 file changed, 4 insertions(+), 8 deletions(-)

### changed folder name removed the space
- Hash: 6895c08
- Auteur: Raphael Vasseur
- Description: changed folder name removed the space
- Fichiers modifiés:
  * .gitignore
  * raspberry_pi/read_sensor.py
  * raspberry_pi/software/data.csv
  * raspberry_pi/software/etage.json
  * raspberry_pi/software/etage_update.py
  * ... et 57 autres fichiers
- Changements: 62 files changed, 2 insertions(+), 2 deletions(-)

### Refactor database connection to use environment variables from .env file
- Hash: f61c860
- Auteur: Raphael Vasseur
- Description: Refactor database connection to use environment variables from .env file
- Fichiers modifiés:
  * raspberry pi/web/backend/database.php
- Changements: 1 file changed, 5 insertions(+), 5 deletions(-)

### Update .gitignore to exclude backend environment file
- Hash: 0e9d68f
- Auteur: Raphael Vasseur
- Description: Update .gitignore to exclude backend environment file
- Fichiers modifiés:
  * .gitignore
- Changements: 1 file changed, 1 insertion(+)



## 2025-04-03 (16 commits)

### Résumé de la journée:
- Total fichiers modifiés: 16
- Lignes ajoutées: 952
- Lignes supprimées: 452
- Bilan lignes: +500

#### Catégories de travail:
- PHP: 13 fichiers
- JavaScript: 13 fichiers
- Code Python: 5 fichiers
- Configuration: 4 fichiers
- CSS: 2 fichiers
- Base de données: 1 fichiers
----------------------------

### fix
- Hash: 2e06ca9
- Auteur: Raphael Vasseur
- Description: fix
- Fichiers modifiés:
  * raspberry_pi/web/backend/php/api/start_drying.php
  * raspberry_pi/web/config/config-drying.json
  * raspberry_pi/web/src/js/index.js
- Changements: 3 files changed, 19 insertions(+), 10 deletions(-)

### fix
- Hash: 93d3545
- Auteur: Raphael Vasseur
- Description: fix
- Fichiers modifiés:
  * raspberry_pi/web/backend/php/api/start_drying.php
  * raspberry_pi/web/backend/php/classes/dryingControl.php
  * raspberry_pi/web/backend/python/drying_control.py
  * raspberry_pi/web/src/js/index.js
- Changements: 4 files changed, 5 insertions(+), 3 deletions(-)

### .
- Hash: e21b68a
- Auteur: Raphael Vasseur
- Description: .
- Fichiers modifiés:
  * raspberry_pi/web/backend/php/api/start_drying.php
  * raspberry_pi/web/config/config-drying.json
  * raspberry_pi/web/src/js/index.js
- Changements: 3 files changed, 1 insertion(+), 2 deletions(-)

### Enhance drying process by calculating minimum drying time for selected varieties; update start_drying.php to include drying time in configuration and modify response handling. Update index.js to pass drying time data for selected varieties.
- Hash: 1477626
- Auteur: Raphael Vasseur
- Description: Enhance drying process by calculating minimum drying time for selected varieties; update start_dryin...
- Fichiers modifiés:
  * raspberry_pi/web/backend/php/api/start_drying.php
  * raspberry_pi/web/config/config-temp.json
  * raspberry_pi/web/src/js/index.js
- Changements: 3 files changed, 23 insertions(+), 15 deletions(-)

### Implement variety temperature handling in drying process; update start_drying.php to validate and store temperature data, and enhance index.js to pass selected variety temperatures during drying initiation.
- Hash: bae380d
- Auteur: Raphael Vasseur
- Description: Implement variety temperature handling in drying process; update start_drying.php to validate and st...
- Fichiers modifiés:
  * raspberry_pi/web/backend/php/api/start_drying.php
  * raspberry_pi/web/config/config-temp.json
  * raspberry_pi/web/src/js/index.js
- Changements: 3 files changed, 82 insertions(+), 6 deletions(-)

### removing unensserry ()
- Hash: e947046
- Auteur: Raphael Vasseur
- Description: removing unensserry ()
- Fichiers modifiés:
  * raspberry_pi/web/database_pc.sql
- Changements: 1 file changed, 41 insertions(+), 26 deletions(-)

### .
- Hash: cd4d171
- Auteur: Raphael Vasseur
- Description: .
- Fichiers modifiés:
  * pc/usb/csv_to_sql.py
  * pc/usb/wait_4_usb.py
  * pc/web/backend/import_csv.py
  * pc/web/backend/test.py
- Changements: 4 files changed, 20 insertions(+), 80 deletions(-)

### Enhance UI by adding button styles and new delete functionality for varieties; update CSS for button classes and improve JavaScript for variety management.
- Hash: 8df567b
- Auteur: Raphael Vasseur
- Description: Enhance UI by adding button styles and new delete functionality for varieties; update CSS for button...
- Fichiers modifiés:
  * raspberry_pi/web/index.php
  * raspberry_pi/web/src/css/styles.css
  * raspberry_pi/web/src/js/index.js
- Changements: 3 files changed, 33 insertions(+), 14 deletions(-)

### Remove temperature chart from index.php and clean up commented code
- Hash: f000dcf
- Auteur: Raphael Vasseur
- Description: Remove temperature chart from index.php and clean up commented code
- Fichiers modifiés:
  * raspberry_pi/web/index.php
- Changements: 1 file changed, 1 insertion(+), 2 deletions(-)

### REMOVED THE CHART + updated the sensor read each 3 seconds
- Hash: 75eb508
- Auteur: Raphael Vasseur
- Description: REMOVED THE CHART + updated the sensor read each
3 seconds
- Fichiers modifiés:
  * raspberry_pi/web/src/js/index.js
- Changements: 1 file changed, 3 insertions(+), 111 deletions(-)

### Refactor temperature data fetching and visualization; update get_temperatures.php to return numeric values and enhance index.js for improved charting and table display.
- Hash: 5825431
- Auteur: Raphael Vasseur
- Description: Refactor temperature data fetching and visualization; update get_temperatures.php to return numeric ...
- Fichiers modifiés:
  * raspberry_pi/web/backend/php/api/get_temperatures.php
  * raspberry_pi/web/src/js/index.js
- Changements: 2 files changed, 129 insertions(+), 41 deletions(-)

### fix
- Hash: f78db8b
- Auteur: Raphael Vasseur
- Description: fix
- Fichiers modifiés:
  * raspberry_pi/web/src/js/index.js
- Changements: 1 file changed, 1 insertion(+), 1 deletion(-)

### .
- Hash: b9a22ac
- Auteur: Raphael Vasseur
- Description: .
- Fichiers modifiés:
  * raspberry_pi/web/src/js/index.js
- Changements: 1 file changed, 94 insertions(+), 24 deletions(-)

### fixed merge
- Hash: b04f5d5
- Auteur: Raphael Vasseur
- Description: fixed merge
- Fichiers modifiés:
  * raspberry_pi/web/src/js/index.js
- Changements: 4 files changed, 193 insertions(+), 37 deletions(-)

### Remove get_drying_data.php and update get_temperatures.php to return sensor data with temperature; enhance index.js to fetch and display temperature data in the table.
- Hash: 185b1cf
- Auteur: Raphael Vasseur
- Description: Remove get_drying_data.php and update get_temperatures.php to return sensor data with temperature; e...
- Fichiers modifiés:
  * raspberry_pi/web/backend/php/api/get_drying_data.php
  * raspberry_pi/web/backend/php/api/get_temperatures.php
  * raspberry_pi/web/src/js/index.js
- Changements: 3 files changed, 18 insertions(+), 25 deletions(-)

### Add user variety selection modal and implement variety management functionality
- Hash: 11a57a2
- Auteur: Raphael Vasseur
- Description: Add user variety selection modal and implement variety management functionality
- Fichiers modifiés:
  * raspberry_pi/web/backend/php/api/get_varieties.php
  * raspberry_pi/web/index.php
  * raspberry_pi/web/src/css/styles.css
  * raspberry_pi/web/src/js/index.js
- Changements: 4 files changed, 289 insertions(+), 55 deletions(-)



## 2025-04-07 (1 commits)

### Résumé de la journée:
- Total fichiers modifiés: 2
- Lignes ajoutées: 21
- Lignes supprimées: 19
- Bilan lignes: +2

#### Catégories de travail:
- Code Python: 1 fichiers
- Données: 1 fichiers
----------------------------

### t
- Hash: aaef99d
- Auteur: Raphael Vasseur
- Description: t
- Fichiers modifiés:
  * pc/usb/csv_to_sql.py
  * pc/usb/data.csv
- Changements: 2 files changed, 21 insertions(+), 19 deletions(-)



## 2025-04-09 (1 commits)

### Résumé de la journée:
- Total fichiers modifiés: 4
- Lignes ajoutées: 27
- Lignes supprimées: 8
- Bilan lignes: +19

#### Catégories de travail:
- Code Python: 2 fichiers
- PHP: 1 fichiers
- Configuration: 1 fichiers
----------------------------

### Refactor drying control logic to load drying time from configuration; update start_drying.php to remove debug output and enhance drying_control.py to manage drying duration with a timer. Update config.py to define a default drying time.
- Hash: 8493612
- Auteur: Raphael Vasseur
- Description: Refactor drying control logic to load drying time from configuration; update start_drying.php to rem...
- Fichiers modifiés:
  * raspberry_pi/web/backend/php/api/start_drying.php
  * raspberry_pi/web/backend/python/drying_control.py
  * raspberry_pi/web/config/config-drying.json
  * raspberry_pi/web/config/config.py
- Changements: 4 files changed, 27 insertions(+), 8 deletions(-)



## 2025-04-10 (2 commits)

### Résumé de la journée:
- Total fichiers modifiés: 7
- Lignes ajoutées: 130
- Lignes supprimées: 86
- Bilan lignes: +44

#### Catégories de travail:
- Code Python: 4 fichiers
- Base de données: 2 fichiers
- Données: 2 fichiers
----------------------------

### Refactor database schema: streamline table definitions and add foreign key constraints
- Hash: 206ae8f
- Auteur: Raphael Vasseur
- Description: Refactor database schema: streamline table definitions and add foreign key constraints
- Fichiers modifiés:
  * raspberry_pi/web/database_pc.sql
- Changements: 1 file changed, 41 insertions(+), 47 deletions(-)

### edit database_pc et csv_to_sql
- Hash: 0f2e17c
- Auteur: Raphael Vasseur
- Description: edit database_pc et csv_to_sql
- Fichiers modifiés:
  * pc/usb/csv_to_sql.py
  * pc/usb/data.csv
  * pc/usb/test.py
  * raspberry_pi/software/data.csv
  * raspberry_pi/software/etage_update.py
  * ... et 2 autres fichiers
- Changements: 7 files changed, 89 insertions(+), 39 deletions(-)



## 2025-05-12 (1 commits)

### Résumé de la journée:
- Total fichiers modifiés: 4
- Lignes ajoutées: 217
- Lignes supprimées: 124
- Bilan lignes: +93

#### Catégories de travail:
- CSS: 3 fichiers
- PHP: 1 fichiers
----------------------------

### responsive
- Hash: fe63891
- Auteur: Raphael Vasseur
- Description: responsive
- Fichiers modifiés:
  * raspberry_pi/web/admin/src/css/csv.css
  * raspberry_pi/web/admin/src/css/new_user.css
  * raspberry_pi/web/admin/src/css/users.css
  * raspberry_pi/web/backend/php/login-process.php
- Changements: 4 files changed, 217 insertions(+), 124 deletions(-)



## 2025-05-13 (2 commits)

### Résumé de la journée:
- Total fichiers modifiés: 4
- Lignes ajoutées: 51
- Lignes supprimées: 31
- Bilan lignes: +20

#### Catégories de travail:
- PHP: 2 fichiers
- Configuration: 1 fichiers
- Code Python: 1 fichiers
----------------------------

### Refactor drying control methods for consistency; update drying time in configuration files to align with new defaults.
- Hash: 1c65bf5
- Auteur: Raphael Vasseur
- Description: Refactor drying control methods for consistency; update drying time in configuration files to align ...
- Fichiers modifiés:
  * raspberry_pi/web/backend/php/classes/dryingControl.php
  * raspberry_pi/web/config/config-drying.json
  * raspberry_pi/web/config/config.py
- Changements: 3 files changed, 47 insertions(+), 31 deletions(-)

### mb
- Hash: ae982fb
- Auteur: Raphael Vasseur
- Description: mb
- Fichiers modifiés:
  * raspberry_pi/web/backend/php/login-process.php
- Changements: 1 file changed, 4 insertions(+)



## 2025-05-14 (1 commits)

### Résumé de la journée:
- Total fichiers modifiés: 4
- Lignes ajoutées: 107
- Lignes supprimées: 60
- Bilan lignes: +47

#### Catégories de travail:
- Code Python: 2 fichiers
- Données: 1 fichiers
- Documentation: 1 fichiers
----------------------------

### Enhance CSV data handling and database updates: - Updated CSV structure to include 'variety_name'. - Modified data processing logic in csv_to_sql.py to accommodate new CSV format. - Improved database interaction by adding checks for varieties and campaigns. - Added GPIO control in read_sensor.py to manage drying based on temperature thresholds. - Created txt.txt to outline future enhancements for burner time, variety, and end time.
- Hash: 1578d45
- Auteur: Raphael Vasseur
- Description: Enhance CSV data handling and database updates:
- Updated CSV structure to include 'variety_name'.
-...
- Fichiers modifiés:
  * pc/usb/csv_to_sql.py
  * pc/usb/data.csv
  * pc/usb/txt.txt
  * raspberry_pi/read_sensor.py
- Changements: 4 files changed, 107 insertions(+), 60 deletions(-)



## 2025-05-18 (1 commits)

### Résumé de la journée:
- Total fichiers modifiés: 3
- Lignes ajoutées: 254
- Lignes supprimées: 17
- Bilan lignes: +237

#### Catégories de travail:
- PHP: 2 fichiers
- JavaScript: 1 fichiers
----------------------------

### no need for  login in the pc + FINALLY GOT THE DHART BACK
- Hash: 0f7d76b
- Auteur: Raphael Vasseur
- Description: no need for  login in the pc + FINALLY GOT THE DHART BACK
- Fichiers modifiés:
  * pc/web/dashboard.php
  * pc/web/index.php
  * pc/web/src/js/dashboard.js
- Changements: 3 files changed, 254 insertions(+), 17 deletions(-)



## 2025-05-19 (1 commits)

### Résumé de la journée:
- Total fichiers modifiés: 5
- Lignes ajoutées: 1069
- Lignes supprimées: 213
- Bilan lignes: +856

#### Catégories de travail:
- PHP: 3 fichiers
- CSS: 1 fichiers
- JavaScript: 1 fichiers
----------------------------

### feat: Add filtering interface and data visualization for dashboard
- Hash: 540f4ab
- Auteur: Raphael Vasseur
- Description: feat: Add filtering interface and data visualization for dashboard

- Implemented a filtering sectio...
- Fichiers modifiés:
  * pc/web/backend/data.php
  * pc/web/backend/database.php
  * pc/web/dashboard.php
  * pc/web/src/css/dashboard.css
  * pc/web/src/js/dashboard.js
- Changements: 5 files changed, 1069 insertions(+), 213 deletions(-)



## 2025-05-20 (2 commits)

### Résumé de la journée:
- Total fichiers modifiés: 6
- Lignes ajoutées: 655
- Lignes supprimées: 436
- Bilan lignes: +219

#### Catégories de travail:
- Code Python: 3 fichiers
- PHP: 2 fichiers
- JavaScript: 2 fichiers
- Documentation: 1 fichiers
----------------------------

### feat: Implement CSV import functionality with GUI; add database update and preview features
- Hash: 3ff7102
- Auteur: Raphael Vasseur
- Description: feat: Implement CSV import functionality with GUI; add database update and preview features
- Fichiers modifiés:
  * pc/usb/csv_to_sql.py
  * pc/usb/test.py
  * pc/usb/txt.txt
  * raspberry_pi/web/backend/php/api/get_config.php
  * raspberry_pi/web/backend/php/api/get_temperatures.php
  * ... et 1 autres fichiers
- Changements: 6 files changed, 372 insertions(+), 228 deletions(-)

### Refactor CSV data import and database update functions; add GUI for file selection and improve error handling.
- Hash: 946299a
- Auteur: Raphael Vasseur
- Description: Refactor CSV data import and database update functions; add GUI for file selection and improve error...
- Fichiers modifiés:
  * pc/usb/csv_to_sql.py
  * raspberry_pi/web/src/js/index.js
- Changements: 2 files changed, 283 insertions(+), 208 deletions(-)
