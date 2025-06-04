sudo apt install python3-rpi.gpio
sudo usermod -a -G gpio $USER

## Shutdown

sudo visudo
www-data ALL=NOPASSWD:/sbin/shutdown

## Module RTC

**_ activer i2c _**

sudo raspi-config
Puis :
Interfacing Options
I2C
Choisir Enable
sudo reboot

**_ detect? _**

sudo i2cdetect -y 1

**_ config final _**

sudo nano /boot/config.txt
dtoverlay=i2c-rtc,ds3231
sudo reboot
