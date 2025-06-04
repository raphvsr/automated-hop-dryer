#              file drying_control.py
# ================================================
#        Original Author: Romain Provencel
# ================================================

# COMMIT HISTORY:
# ============================================================
# 2025-04-09 - Refactor drying control logic to load drying time from configuration; update start_drying.php to remove debug output and enhance drying_control.py to manage drying duration with a timer. Update config.py to define a default drying time. - Romain Provencel
#   raspberry_pi/web/backend/python/drying_control.py | 27 ++++++++++++++++++++---
#   1 file changed, 24 insertions(+), 3 deletions(-)
#
# 2025-04-03 - fix - Romain Provencel
#   raspberry_pi/web/backend/python/drying_control.py | 2 ++
#   1 file changed, 2 insertions(+)
#
# 2025-04-02 - changed folder name removed the space - fateh kabbani
#   1 file changed, 0 insertions(+), 0 deletions(-)
#
# 2025-03-28 - . - Romain Provencel
#   raspberry pi/web/backend/python/drying_control.py | 9 ++++++---
#   1 file changed, 6 insertions(+), 3 deletions(-)
#
# 2025-03-28 - . - Romain Provencel
#   raspberry pi/web/backend/python/drying_control.py | 1 +
#   1 file changed, 1 insertion(+)
#
# 2025-03-28 - Refactor drying control to import API configuration and update GPIO handling - fateh kabbani
#   raspberry pi/web/backend/python/drying_control.py | 9 +++++----
#   1 file changed, 5 insertions(+), 4 deletions(-)
#
# 2025-03-28 - Add API for saving drying status and enhance session management - Romain Provencel
#   raspberry pi/web/backend/python/drying_control.py | 6 +++++-
#   1 file changed, 5 insertions(+), 1 deletion(-)
#
# 2025-03-27 - Implement drying control status retrieval and enhance existing drying methods - Romain Provencel
#   raspberry pi/web/backend/python/drying_control.py | 5 ++++-
#   1 file changed, 4 insertions(+), 1 deletion(-)
#
# 2025-03-27 - . - Romain Provencel
#   raspberry pi/web/backend/python/drying_control.py | 3 +--
#   1 file changed, 1 insertion(+), 2 deletions(-)
#
# 2025-03-20 - move the file to raspberry pi - fateh kabbani
#   1 file changed, 0 insertions(+), 0 deletions(-)
#
# 2025-03-14 - settings the time - Romain Provencel
#   web/backend/python/drying_control.py | 7 +++----
#   1 file changed, 3 insertions(+), 4 deletions(-)
#
# 2025-03-14 - Add GPIO control for drying process - Romain Provencel
#   web/backend/python/drying_control.py | 23 ++++++++++++++++++++++-
#   1 file changed, 22 insertions(+), 1 deletion(-)
#
# 2025-03-13 - Add drying configuration and control classes with initial database setup + structure - Romain Provencel
#   web/backend/python/drying_control.py | 1 +
#   1 file changed, 1 insertion(+)
#
# ============================================================
import RPi.GPIO as GPIO
import time
import json
import requests
import sys, os
from threading import Timer
sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '../../../software')))

from etage_update import update_etage

# === CONFIG ===
BtnPin = 7

sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), "../../config")))
from config import API, RELAY_PIN, DEFAULT_DRYING_TIME

GPIO.setmode(GPIO.BCM)

# === Setup Buttons and Relays ===
GPIO.setup(BtnPin, GPIO.IN, pull_up_down=GPIO.PUD_UP)
GPIO.setup(RELAY_PIN, GPIO.OUT)

CONFIG_PATH = "/skl-project/raspberry_pi/web/config/config-drying.json"

# === Drying Logic ===
def load_drying_config():
    try:
        with open(CONFIG_PATH) as f:
            config = json.load(f)
            return config.get("drying-time", DEFAULT_DRYING_TIME)
    except Exception as e:
        print(f"Erreur lors du chargement de la configuration : {e}")
        return DEFAULT_DRYING_TIME

def start_drying():
    try:
        GPIO.output(RELAY_PIN, GPIO.HIGH)
        print("Séchage en cours : Brûleur allumé")
        sys.stdout.flush()
        save_status()

        drying_time_minutes = load_drying_config()
        drying_time_seconds = drying_time_minutes * 60

        stop_timer = Timer(drying_time_seconds, stop_drying)
        stop_timer.start()

    except KeyboardInterrupt:
        GPIO.output(RELAY_PIN, GPIO.LOW)
        print("Séchage arrêté : Brûleur éteint")
        sys.stdout.flush()
        save_status()
        GPIO.cleanup()

def stop_drying():
    GPIO.output(RELAY_PIN, GPIO.LOW)
    print("Séchage arrêté : Brûleur éteint")
    save_status()

def get_status():
    return GPIO.input(RELAY_PIN) == GPIO.HIGH

def save_status():
    status = get_status()
    try:
        requests.post(API + '/get_drying_status', json={'status': status})
    except Exception as e:
        print(f"Erreur lors de l'envoi du statut : {e}")

# === Callback for Button Press ===
def button_pressed(channel):
    print("Bouton pressé!")
    update_etage()

# === Main Program ===
def main():
    if len(sys.argv) > 1:
        if sys.argv[1] == "start":
            start_drying()
        elif sys.argv[1] == "stop":
            stop_drying()
        else:
            print("Commande inconnue")
            try:
                print("Système prêt. En attente d'entrée...")
                GPIO.add_event_detect(BtnPin, GPIO.FALLING, callback=button_pressed, bouncetime=200)
                while True:
                    time.sleep(1)
            except KeyboardInterrupt:
                print("Sortie...")
            finally:
                GPIO.cleanup()

if __name__ == '__main__':
    main()
