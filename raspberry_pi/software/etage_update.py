#               file etage_update.py              
# ================================================
#         Original Author: Raphael Vasseur        
# ================================================

# COMMIT HISTORY:
# ============================================================
# 2025-05-20 - feat: Add Git activity journal generator for university project - Raphael Vasseur
#   raspberry_pi/software/etage_update.py | 19 ++++++++++---------
#   1 file changed, 10 insertions(+), 9 deletions(-)
#
# 2025-04-10 - edit database_pc et csv_to_sql - Raphael Vasseur
#   raspberry_pi/software/etage_update.py | 1 +
#   1 file changed, 1 insertion(+)
#
# 2025-04-02 - changed folder name removed the space - fateh kabbani
#   1 file changed, 0 insertions(+), 0 deletions(-)
#
# 2025-04-01 - Update etage status management: initialize CSV and JSON, refactor validation logic, and implement etage update functionality - Raphael Vasseur
#   raspberry pi/software/etage_update.py | 12 ++++++++++++
#   1 file changed, 12 insertions(+)
#
# ============================================================

import json

# utilisé par raspberry_pi\software\validate.py
def update_etage():
    with open("etage.json", "r") as f:
        data = json.load(f)

    for key, value in data.items():
        if value is False:
            data[key] = True
            break

    with open("etage.json", "w") as f:
        json.dump(data, f, indent=4)
