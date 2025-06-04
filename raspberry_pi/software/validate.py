#                 file validate.py                
# ================================================
#          Original Author: fateh kabbani         
# ================================================

# COMMIT HISTORY:
# ============================================================
# 2025-05-23 - Refactor sensor reading logic in read_sensor.py, including improved logging and temperature handling. Update GPIO pin configuration in validate.py. Change API endpoint in users.js for user updates. Enhance error handling and logging in varieties-create.php and login-process.php, including session and request logging. Modify register-process.php to ensure all fields are validated. Add info.php for PHP configuration display. Create log files for login attempts to aid in debugging. - fateh kabbani
#   raspberry_pi/software/validate.py | 4 ++--
#   1 file changed, 2 insertions(+), 2 deletions(-)
#
# 2025-05-20 - fix: Remove unnecessary blank line in validate.py - Raphael Vasseur
#   raspberry_pi/software/validate.py | 1 -
#   1 file changed, 1 deletion(-)
#
# 2025-04-02 - changed folder name removed the space - fateh kabbani
#   1 file changed, 0 insertions(+), 0 deletions(-)
#
# 2025-04-01 - Update etage status management: initialize CSV and JSON, refactor validation logic, and implement etage update functionality - Raphael Vasseur
#   raspberry pi/software/validate.py | 6 +++---
#   1 file changed, 3 insertions(+), 3 deletions(-)
#
# 2025-03-31 - Add validation logic and JSON configuration for drying status - Raphael Vasseur
#   raspberry pi/software/validate.py | 6 ++++--
#   1 file changed, 4 insertions(+), 2 deletions(-)
#
# 2025-03-27 - . - fateh kabbani
#   raspberry pi/software/validate.py | 6 ++----
#   1 file changed, 2 insertions(+), 4 deletions(-)
#
# 2025-03-20 - Add GPIO control script for LED and button interaction with event detection - fateh kabbani
#   raspberry pi/software/validate.py | 38 ++++++++++++++++++++++++++++++++++++++
#   1 file changed, 38 insertions(+)
#
# ============================================================

import RPi.GPIO as GPIO
import time
from etage_update import update_etage


LedPin = 6
BtnPin = 7


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
        update_etage()
        Led_status = 0

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
