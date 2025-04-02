import psutil
import usb.core
import usb.util
import os

def find_usb_path():
    for partition in psutil.disk_partitions():
        if 'removable' in partition.opts:
            return partition.mountpoint
    return None


def find_csv(usb_path):
    for root, drive, _ in os.walk(usb_path):
        if "csv" in drive:
            csv_path = os.path.join(root, "csv")
            for root, _, files in os.walk(usb_path):
                if files.endswith(".csv"):
                    return os.path.join(csv_path, files)
                else:
                    print("csv file not found")
        else:
            print("csv folder no")


usb_path = find_usb_path()
find_csv(usb_path)

# @app.route('/import_csv', methods=['POST'])
# def import_csv():
#     """Handles the button click to import CSV from USB."""
#     usb_drive = find_usb_path()
#     if not usb_drive:
#         return jsonify({"error": "No USB detected!"}), 400

#     csv_file = find_csv(usb_drive)
#     if not csv_file:
#         return jsonify({"error": "No CSV file found on USB!"}), 400

#     update_database(csv_file)
#     return jsonify({"message": "Database updated successfully!"})
