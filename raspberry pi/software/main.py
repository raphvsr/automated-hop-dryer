import time
import requests
import csv
import json

api = "http://127.0.0.1/raspberry pi/web/backend/php/api/"


with open('data.csv', 'w', newline='') as file:
    writer = csv.writer(file)
    field = ["date", "heure", "etage4", "etage3", "etage2", "etage1", "temps_bruleur", "etat_bruleur"]
    writer.writerow(field)

def write_data(date="", heure="", etage4="1", etage3="", etage2="", etage1="", temps_bruleur="", etat_bruleur=""):
    with open('data.csv', 'a', newline='') as file:
        writer = csv.writer(file)
        writer.writerow([date, heure, etage4, etage3, etage2, etage1, temps_bruleur, etat_bruleur])

def drying_status(etage4, etage3, etage2, etage1):
    resp = requests.post(f"{api}get_drying_status.php")
    data = resp.json()
    date, heure = date_hour()

    write_data(date=date, heure=heure, etat_bruleur=data.get("message"))

def date_hour():
    import time
    current_time = time.strftime("%Y-%m-%d %H:%M:%S")
    date, heure = current_time.split()
    return date, heure

def validate():
    etage = ["false","false","false","false"]
    with open("etage.json", "r") as file:
        etages = json.load(file)
        for value in etages:
            is_charged = etages.get(value)
            if is_charged == True:
                return value




while True:
    drying_status()
    validate()
    time.sleep(3)


# TEMP :
# data = resp.json()
# max_temp, max_sensor = 0, "None"
# temp = data.get(x, 0)
# exceed = "DEPASSEMENT" if temp > 60 else ""
# write_data(date, heure, "", x, temp, exceed, "")

# if temp > max_temp:
#     max_temp, max_sensor = temp, x
