#               file read_sensor.py               
# ================================================
#          Original Author: fateh kabbani         
# ================================================

# COMMIT HISTORY:
# ============================================================
# 2025-05-23 - Refactor sensor reading logic in read_sensor.py, including improved logging and temperature handling. Update GPIO pin configuration in validate.py. Change API endpoint in users.js for user updates. Enhance error handling and logging in varieties-create.php and login-process.php, including session and request logging. Modify register-process.php to ensure all fields are validated. Add info.php for PHP configuration display. Create log files for login attempts to aid in debugging. - fateh kabbani
#   raspberry_pi/read_sensor.py | 59 ++++++++++++++++++++++++---------------------
#   1 file changed, 32 insertions(+), 27 deletions(-)
#
# 2025-05-14 - Enhance CSV data handling and database updates: - Updated CSV structure to include 'variety_name'. - Modified data processing logic in csv_to_sql.py to accommodate new CSV format. - Improved database interaction by adding checks for varieties and campaigns. - Added GPIO control in read_sensor.py to manage drying based on temperature thresholds. - Created txt.txt to outline future enhancements for burner time, variety, and end time. - Raphael Vasseur
#   raspberry_pi/read_sensor.py | 41 +++++++++++++++++++++++++++++++++++------
#   1 file changed, 35 insertions(+), 6 deletions(-)
#
# 2025-04-02 - changed folder name removed the space - fateh kabbani
#   1 file changed, 0 insertions(+), 0 deletions(-)
#
# 2025-03-26 - Remove max temperature configuration from web interface and add sensor reading script for DS18B20 temperature sensor - fateh kabbani
#   raspberry pi/read_sensor.py | 56 +++++++++++++++++++++++++++++++++++++++++++++
#   1 file changed, 56 insertions(+)
#
# ============================================================

import os
import glob
import time
import datetime
import json
from raspberry_pi.web.backend.python.drying_control import stop_drying
import RPi.GPIO as GPIO

# Active les modules pour que le capteur de température DS18B20 fonctionne
os.system('modprobe w1-gpio')
os.system('modprobe w1-therm')

class DS18B20:
    def __init__(self):
        # Dossier où sont listés les capteurs
        self.base_dir = r'/sys/bus/w1/devices/28*'
        self.sensor_path = []  # Chemins complets des capteurs
        self.sensor_name = []  # Noms des capteurs
        self.temps = []        # (Non utilisé ici)
        self.log = []          # Liste des températures lues

    def find_sensors(self):
        # Cherche tous les capteurs branchés
        self.sensor_path = glob.glob(self.base_dir)
        self.sensor_name = [path.split('/')[-1] for path in self.sensor_path]

    def strip_string(self, temp_str):
        # Récupère juste la température en °C
        i = temp_str.index('t=')
        if i != -1:
            t = temp_str[i+2:]
            temp_c = float(t) / 1000.0
        return temp_c

    def read_temp(self):
        MAX_TEMP = 60  # Température max par défaut

        tstamp = datetime.datetime.now()  # Heure actuelle
        for sensor, path in zip(self.sensor_name, self.sensor_path):
            # Ouvre le fichier du capteur et lit les deux lignes
            with open(path + '/w1_slave','r') as f:
                valid, temp = f.readlines()

            # Vérifie si les données sont bonnes
            if 'YES' in valid:
                # Ajoute la température lue dans le log
                self.log.append((tstamp, sensor) + self.strip_string(temp))

                try:
                    # Lit la config (valeur max personnalisée si dispo)
                    with open("/skl-project/raspberry_pi/web/config/config-drying.json", "r") as f:
                        config = json.load(f)
                        max_temperature = config.get("max-temperature", MAX_TEMP)
                except Exception as e:
                    print(f"Erreur config : {e}")
                    max_temperature = MAX_TEMP

                # Affiche les températures enregistrées
                for t, n, c, f in self.log:
                    print(f'Sensor: {n}  C={c:,.3f}  DateTime: {t}')

                    # Si la température est trop haute
                    if c > max_temperature:
                        GPIO.setup(4, GPIO.OUT)  # Configure la broche GPIO 4

                        try:
                            GPIO.output(4, GPIO.HIGH)  # Allume quelque chose (ex : ventilo)
                            time.sleep(300)            # Attend 5 minutes
                            GPIO.output(4, GPIO.LOW)   # Éteint
                        finally:
                            GPIO.cleanup()  # Remet les GPIO à zéro

                        stop_drying()  # Arrête le séchage
                        time.sleep(1)  # Petite pause
                        break
            else:
                time.sleep(0.2)  # Si les données sont invalides, on attend un peu

    def clear_log(self):
        # Vide le journal des températures
        s.log.clear()

# Lance le programme
s = DS18B20()
s.find_sensors()

while True:
    s.read_temp()     # Lit les températures
    s.print_temps()   # Affiche les températures (attention, cette fonction n'existe pas encore)
    s.clear_log()     # Vide les anciennes données
