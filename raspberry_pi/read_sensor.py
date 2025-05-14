import os
import glob
import time
import datetime
import json
from raspberry_pi.web.backend.python.drying_control import stop_drying
import RPi.GPIO as GPIO


#these tow lines mount the device:
os.system('modprobe w1-gpio')
os.system('modprobe w1-therm')

class DS18B20:
    def __init__(self):
        self.base_dir = r'/sys/bus/w1/devices/28*'
        self.sensor_path = []
        self.sensor_name = []
        self.temps = []
        self.log = []

    def find_sensors(self):
        self.sensor_path = glob.glob(self.base_dir)
        self.sensor_name = [path.split('/')[-1] for path in self.sensor_path]

    def strip_string(self, temp_str):
        i = temp_str.index('t=')
        if i != -1:
            t = temp_str[i+2:]
            temp_c = float(t)/1000.0
        return temp_c

    def read_temp(self):
        MAX_TEMP = 60

        tstamp = datetime.datetime.now()
        for sensor, path in zip(self.sensor_name, self.sensor_path):
            # open sensor file and read data
            with open(path + '/w1_slave','r') as f:
                valid, temp = f.readlines()
                
            # check validity of data
            if 'YES' in valid:
                self.log.append((tstamp, sensor) + self.strip_string(temp))

                try:
                    with open("/skl-project/raspberry_pi/web/config/config-drying.json", "r") as f:
                        config = json.load(f)
                        max_temperature = config.get("max-temperature", MAX_TEMP)
                except Exception as e:
                    print(f"Error loading config: {e}")
                    max_temperature = MAX_TEMP

                for t, n, c, f in self.log:
                    print(f'Sensor: {n}  C={c:,.3f}  DateTime: {t}')

                    if c > max_temperature:
                        GPIO.setup(4, GPIO.OUT)

                        try:
                            GPIO.output(4, GPIO.HIGH)

                            time.sleep(300)
                            GPIO.output(4, GPIO.LOW)

                        finally:
                            # clean GPIO settings
                            GPIO.cleanup()

                        stop_drying()
                        time.sleep(1)
                        break
            else:
                time.sleep(0.2)

    def clear_log(self):
        s.log.clear()

s = DS18B20()
s.find_sensors()

while True:
    s.read_temp()
    s.print_temps()
    s.clear_log()
