import RPi.GPIO as GPIO
import requests
import sys, os
sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), "../../config")))
from config import API
RELAY_PIN = 17
GPIO.setmode(GPIO.BCM)
GPIO.setup(RELAY_PIN, GPIO.OUT)

def start_drying():
    try:
        GPIO.output(RELAY_PIN, GPIO.HIGH)
        print("Drying started: Burner on")

    except KeyboardInterrupt:
        GPIO.output(RELAY_PIN, GPIO.LOW)
        print("Drying Stopped: Burner Off")
        GPIO.cleanup()

def stop_drying():
    GPIO.output(RELAY_PIN, GPIO.LOW)
    print("Drying Stopped: Burner Off")

def get_status():
    return GPIO.input(RELAY_PIN) == GPIO.HIGH

def seve_status():
    status = get_status()
    requests.post(API + '/get_drying_status', json={'status': status})
