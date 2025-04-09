import RPi.GPIO as GPIO
import requests
import sys, os
import json
import time
from threading import Timer

sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), "../../config")))
from config import API, RELAY_PIN, DEFAULT_DRYING_TIME

GPIO.setmode(GPIO.BCM)
GPIO.setup(RELAY_PIN, GPIO.OUT)

CONFIG_PATH = "/skl-project/raspberry_pi/web/config/config-drying.json"

def load_drying_config():
    try:
        with open(CONFIG_PATH) as f:
            config = json.load(f)
            return config.get("drying-time", DEFAULT_DRYING_TIME)
    except Exception as e:
        print(f"Error loading config: {e}")
        return DEFAULT_DRYING_TIME

def start_drying():
    try:
        GPIO.output(RELAY_PIN, GPIO.HIGH)
        print("Drying started: Burner on")
        sys.stdout.flush()
        save_status()
        
        drying_time_minutes = load_drying_config()
        
        drying_time_seconds = drying_time_minutes * 60
        stop_timer = Timer(drying_time_seconds, stop_drying)
        stop_timer.start()
        
    except KeyboardInterrupt:
        GPIO.output(RELAY_PIN, GPIO.LOW)
        print("Drying Stopped: Burner Off")
        sys.stdout.flush()
        save_status()
        GPIO.cleanup()

def stop_drying():
    GPIO.output(RELAY_PIN, GPIO.LOW)
    print("Drying Stopped: Burner Off")
    save_status()

def get_status():
    return GPIO.input(RELAY_PIN) == GPIO.HIGH

def save_status():
    status = get_status()
    requests.post(API + '/get_drying_status', json={'status': status})
