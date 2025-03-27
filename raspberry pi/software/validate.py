import RPi.GPIO as GPIO
import time

LedPin = 17
BtnPin = 18

Led_status = 1

def setup():
    GPIO.setmode(GPIO.BCM)
    GPIO.setup(LedPin, GPIO.OUT)
    GPIO.setup(BtnPin, GPIO.IN, pull_up_down=GPIO.PUD_UP)
    GPIO.output(LedPin, GPIO.HIGH)

def swLed(ev=None):
    global Led_status
    Led_status = not Led_status
    GPIO.output(LedPin, Led_status)
    if Led_status == 1:
        # valider et ajouter dans la base de donner et dans le ficher csv
        print('test')
def loop():
    GPIO.add_event_detect(BtnPin, GPIO.FALLING, callback=swLed, bouncetime=200)
    while True:
        time.sleep(1)

def destroy():
    GPIO.output(LedPin, GPIO.HIGH)
    GPIO.cleanup()

if __name__ == '__main__':
    setup()
    try:
        loop()
    except KeyboardInterrupt:
        destroy()
