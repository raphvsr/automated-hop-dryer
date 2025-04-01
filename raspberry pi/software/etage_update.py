import json

with open("etage.json", "r") as f:
    data = json.load(f)

for key, value in data.items():
    if value is False:
        data[key] = True
        break

with open("etage.json", "w") as f:
    json.dump(data, f, indent=4)
