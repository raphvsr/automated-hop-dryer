import time
import requests
import csv

api = "http://127.0.0.1/raspberry pi/web/backend/php/api/"

def sensors():
    resp = requests.get(f"{api}get_temperatures.php")
    data = resp.json()
    sensors = ["sensor1", "sensor2", "sensor3", "sensor4", "sensor5", "sensor6"]

    with open('data.csv', 'w', newline='') as file:
        writer = csv.writer(file)
        field = ["sensor", "temperature", "exceed"]
        writer.writerow(field)

        for x in sensors:
            temp = data.get(x)
            exceed = "DEPASSEMENT" if temp > 60 else ""
            writer.writerow([x, temp, exceed])

def date_hour():
    resp = requests.post(f"{api}start_drying.php")
    data = resp.json()
    print(data)

    with open('data.csv', 'a', newline='') as file:
        writer = csv.writer(file)
        writer.writerow(["date"])
        writer.writerow([data.get("timestamp", "N/A")])  # Save timestamp in CSV

date_hour()

while(True):
    time.sleep(2)

    sensors()
