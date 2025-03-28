import time
import requests
import csv

api = "http://127.0.0.1/raspberry pi/web/backend/php/api/"

with open('data.csv', 'w', newline='') as file:
    writer = csv.writer(file)
    field = ["date", "heure", "etage4", "etage3", "etage2", "etage1", "temps_bruleur", "etat_bruleur"]
    writer.writerow(field)

def write_data(date="", heure="", etage4="", etage3="", etage2="", etage1="", temps_bruleur="", etat_bruleur=""):
    with open('data.csv', 'a', newline='') as file:
        writer = csv.writer(file)
        writer.writerow([date, heure, etage4, etage3, etage2, etage1, temps_bruleur, etat_bruleur])

def date_hour():
    resp = requests.post(f"{api}start_drying.php")
    data = resp.json()
    timestamp = data.get("timestamp", "N/A N/A")
    return timestamp.split()

def drying_status(date, heure):
    resp = requests.get(f"{api}get_temperatures.php")
    data = resp.json()

    max_temp, max_sensor = 0, "None"

    temp = data.get(x, 0)
    exceed = "DEPASSEMENT" if temp > 60 else ""
    write_data(date, heure, "", x, temp, exceed, "")

    if temp > max_temp:
        max_temp, max_sensor = temp, x

    print(f"Max Temp: {max_temp}°C | Sensor: {max_sensor}")



while True:
    date, heure = date_hour()
    sensors(date, heure)
    time.sleep(300)
