import RPi.GPIO as GPIO
import time

RELAY_PIN = 17 

GPIO.setmode(GPIO.BCM)
GPIO.setup(RELAY_PIN, GPIO.OUT)

def start_drying():
    try:
        GPIO.output(RELAY_PIN, GPIO.HIGH)
        print("Séchage démarré : Brûleur allumé")
        
    except KeyboardInterrupt:
        GPIO.output(RELAY_PIN, GPIO.LOW)
        print("Séchage arrêté : Brûleur éteint")
        GPIO.cleanup()

def stop_drying():
    GPIO.output(RELAY_PIN, GPIO.LOW)
    print("Séchage arrêté : Brûleur éteint")
    GPIO.cleanup()