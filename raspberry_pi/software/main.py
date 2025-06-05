#                   file main.py                  
# ================================================
#         Original Author: Raphael Vasseur        
# ================================================

# COMMIT HISTORY:
# ============================================================
# 2025-04-10 - edit database_pc et csv_to_sql - Raphael Vasseur
#   raspberry_pi/software/main.py | 25 ++++++++++++-------------
#   1 file changed, 12 insertions(+), 13 deletions(-)
#
# 2025-04-02 - changed folder name removed the space - fateh kabbani
#   1 file changed, 0 insertions(+), 0 deletions(-)
#
# 2025-04-01 - Update etage status management: initialize CSV and JSON, refactor validation logic, and implement etage update functionality - Raphael Vasseur
#   raspberry pi/software/main.py | 95 +++++++++++++++++++++++++++----------------
#   1 file changed, 61 insertions(+), 34 deletions(-)
#
# 2025-03-31 - Add validation logic and JSON configuration for drying status - Raphael Vasseur
#   raspberry pi/software/main.py | 22 +++++++++++++++++-----
#   1 file changed, 17 insertions(+), 5 deletions(-)
#
# 2025-03-28 - main.py update - Raphael Vasseur
#   raspberry pi/software/main.py | 39 +++++++++++++++++++++------------------
#   1 file changed, 21 insertions(+), 18 deletions(-)
#
# 2025-03-28 - test - Raphael Vasseur
#   raspberry pi/software/main.py | 48 ++++++++++++++++++++++++-------------------
#   1 file changed, 27 insertions(+), 21 deletions(-)
#
# 2025-03-26 - edit main.py - Raphael Vasseur
#   raspberry pi/software/main.py | 18 ++++++++++--------
#   1 file changed, 10 insertions(+), 8 deletions(-)
#
# 2025-03-25 - utilisation de l'api et creation de data.csv - Raphael Vasseur
#   raspberry pi/software/main.py | 35 ++++++++++++++++++++++++++++++++---
#   1 file changed, 32 insertions(+), 3 deletions(-)
#
# 2025-03-22 - teste api python - Raphael Vasseur
#   raspberry pi/software/main.py | 6 ++++++
#   1 file changed, 6 insertions(+)
#
# ============================================================

import time
import requests
import csv
import json

api = "http://127.0.0.1/skl-project/raspberry_pi/web/backend/php/api/"
csv_file = 'data.csv'
json_file = 'etage.json'

default_json = {
    "etage4": True,
    "etage3": False,
    "etage2": False,
    "etage1": False
}

# Initialize CSV
def init_csv():
    with open(csv_file, 'w', newline='') as file:
        writer = csv.writer(file)
        field = ["date", "etage4", "etage3", "etage2", "etage1", "temps_bruleur", "etat_bruleur"]
        writer.writerow(field)
    with open(json_file, 'w', newline='') as file:
        json.dump(default_json, file, indent=4)


# data to CSV
def write_data(date="", etage4="", etage3="", etage2="", etage1="", temps_bruleur="", etat_bruleur=""):
    try:
        with open(csv_file, 'a', newline='') as file:
            writer = csv.writer(file)
            writer.writerow([date, etage4, etage3, etage2, etage1, temps_bruleur, etat_bruleur])
    except Exception as e:
        print(f"Error writing to CSV: {e}")

# drying status
def drying_status(etage4, etage3, etage2, etage1):
    try:
        resp = requests.post(f"{api}get_drying_status.php")
        resp.raise_for_status()
        data = resp.json()
        date = date_hour()

        write_data(date=date, etat_bruleur=data.get("message"), etage4=etage4,etage3=etage3,etage2=etage2,etage1=etage1 )
    except requests.exceptions.RequestException as e:
        print(f"API {e}")
    except json.JSONDecodeError:
        print("JSON e")
    except Exception as e:
        print(e)

def date_hour():
    current_time = time.strftime("%Y-%m-%d %H:%M:%S")
    return current_time

# validate etage data from JSON
def load_etage_data():
    try:
        with open(json_file, "r") as file:
            etages = json.load(file)
            etage = [False] * 4
            for i, value in enumerate(etages.values()):
                etage[i] = True if value else False
            drying_status(etage1=etage[3], etage2=etage[2], etage3=etage[1], etage4=etage[0])


    except json.JSONDecodeError:
        print("etage.json e")
        return False * 4
    except Exception as e:
        print(f"{json_file}: {e}")
        return False * 4




def start_monitoring():
    init_csv()
    while True:
        load_etage_data()
        time.sleep(3)
