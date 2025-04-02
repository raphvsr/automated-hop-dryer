import time
import requests
import csv
import json

api = "http://127.0.0.1/raspberry pi/web/backend/php/api/"
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
        field = ["date", "heure", "etage4", "etage3", "etage2", "etage1", "temps_bruleur", "etat_bruleur"]
        writer.writerow(field)
    with open(json_file, 'w', newline='') as file:
        json.dump(default_json, file, indent=4)


# data to CSV
def write_data(date="", heure="", etage4="", etage3="", etage2="", etage1="", temps_bruleur="", etat_bruleur=""):
    try:
        with open(csv_file, 'a', newline='') as file:
            writer = csv.writer(file)
            writer.writerow([date, heure, etage4, etage3, etage2, etage1, temps_bruleur, etat_bruleur])
    except Exception as e:
        print(f"Error writing to CSV: {e}")

# drying status
def drying_status(etage4, etage3, etage2, etage1):
    try:
        resp = requests.post(f"{api}get_drying_status.php")
        resp.raise_for_status()
        data = resp.json()
        date, heure = date_hour()

        write_data(date=date, heure=heure, etat_bruleur=data.get("message"), etage4=etage4,etage3=etage3,etage2=etage2,etage1=etage1 )
    except requests.exceptions.RequestException as e:
        print(f"API {e}")
    except json.JSONDecodeError:
        print("JSON e")
    except Exception as e:
        print(e)

def date_hour():
    current_time = time.strftime("%Y-%m-%d %H:%M:%S")
    date, heure = current_time.split()
    return date, heure

# validate etage data from JSON
def load_etage_data():
    try:
        with open(json_file, "r") as file:
            etages = json.load(file)
            etage = ["false"] * 4
            for i, value in enumerate(etages.values()):
                etage[i] = "true" if value else "false"
            print(etage)
            drying_status(etage1=etage[3], etage2=etage[2], etage3=etage[1], etage4=etage[0])


    except json.JSONDecodeError:
        print("etage.json e")
        return ["false"] * 4
    except Exception as e:
        print(f"{json_file}: {e}")
        return ["false"] * 4


init_csv()

while True:
    load_etage_data()
    time.sleep(3)
