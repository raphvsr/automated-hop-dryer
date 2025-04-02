import os
import psutil

def find_usb():
    for partition in psutil.disk_partitions():
        if 'removable' in partition.opts:
            return partition.device
    return None

def find_csv(usb_path):
    for root, _, files in os.walk(usb_path):
        for file in files:
            if file.endswith(".csv"):
                return os.path.join(root, file)
    return None

@app.route('/import_csv', methods=['POST'])
def import_csv():
    """Handles the button click to import CSV from USB."""
    usb_drive = find_usb()
    if not usb_drive:
        return jsonify({"error": "No USB detected!"}), 400

    csv_file = find_csv(usb_drive)
    if not csv_file:
        return jsonify({"error": "No CSV file found on USB!"}), 400

    update_database(csv_file)
    return jsonify({"message": "Database updated successfully!"})

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
