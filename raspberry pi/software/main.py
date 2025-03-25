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
            if temp > 60:
                exceed = "DEPASSEMENT"
            else:
                exceed = ""
            writer.writerow([x, temp, exceed])

def date_hour():
    resp = requests.get(f"{api}start_drying.php")
    data = resp.json()
    print(data)


while(True):
    sensors()
    time.sleep(2)
