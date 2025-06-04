#                  file config.py                 
# ================================================
#        Original Author: Romain Provencel        
# ================================================

# COMMIT HISTORY:
# ============================================================
# 2025-05-13 - Refactor drying control methods for consistency; update drying time in configuration files to align with new defaults. - fateh kabbani
#   raspberry_pi/web/config/config.py | 4 +++-
#   1 file changed, 3 insertions(+), 1 deletion(-)
#
# 2025-04-09 - Refactor drying control logic to load drying time from configuration; update start_drying.php to remove debug output and enhance drying_control.py to manage drying duration with a timer. Update config.py to define a default drying time. - Romain Provencel
#   raspberry_pi/web/config/config.py | 3 ++-
#   1 file changed, 2 insertions(+), 1 deletion(-)
#
# 2025-04-02 - changed folder name removed the space - fateh kabbani
#   1 file changed, 0 insertions(+), 0 deletions(-)
#
# 2025-03-28 - . - Romain Provencel
#   raspberry pi/web/config/config.py | 1 +
#   1 file changed, 1 insertion(+)
#
# 2025-03-28 - . - Romain Provencel
#   raspberry pi/web/config/config.py | 3 +--
#   1 file changed, 1 insertion(+), 2 deletions(-)
#
# 2025-03-28 - Refactor drying control to import API configuration and update GPIO handling - fateh kabbani
#   raspberry pi/web/config/config.py | 3 ++-
#   1 file changed, 2 insertions(+), 1 deletion(-)
#
# 2025-03-20 - move the file to raspberry pi - fateh kabbani
#   1 file changed, 0 insertions(+), 0 deletions(-)
#
# 2025-03-14 - Add GPIO control for drying process - Romain Provencel
#   1 file changed, 0 insertions(+), 0 deletions(-)
#
# 2025-03-13 - Add drying configuration and control classes with initial database setup + structure - Romain Provencel
#   web/config/gpio_config.py | 1 +
#   1 file changed, 1 insertion(+)
#
# ============================================================

API = 'http://localhost:80/skl-project/raspberry%20pi/web/backend/api'
RELAY_PIN = 17
DEFAULT_DRYING_TIME = 540


