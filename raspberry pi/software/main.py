
import requests

resp = requests.post("http://127.0.0.1/raspberry pi/web/backend/php/api/shutdown.php")

print(resp.text)
