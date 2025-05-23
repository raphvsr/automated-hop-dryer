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
