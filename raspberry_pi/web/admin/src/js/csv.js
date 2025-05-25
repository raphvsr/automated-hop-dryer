//                   file csv.js                  
// ===============================================
//          Original Author: fateh kabbani        
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-29 - Refactor admin dashboard and user management: move files to admin directory and update CSS/JS - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-28 - Add USB device management and dashboard functionality - fateh kabbani
//   raspberry pi/web/src/js/csv.js | 39 +++++++++++++++++++++++++++++++++++++++
//   1 file changed, 39 insertions(+)
//
// ============================================================

 function updateUsbStatus() {
  fetch('backend/check_usb.php')
    .then(response => response.json())
    .then(data => {
      const usbStatus = document.getElementById('usbStatus');
      if (data.devices.length > 0) {
        usbStatus.innerHTML = `
                        <ul>
                            ${data.devices.map(device => `
                                <li>${device}</li>
                            `).join('')}
                        </ul>
                    `;
      } else {
        usbStatus.innerHTML = "Aucun périphérique USB détecté";
      }
    });
}

function exportToUsb(filename) {
  fetch('backend/export_to_usb.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ filename: filename })
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('Fichier exporté avec succès vers USB');
      } else {
        alert('Erreur lors de l\'export: ' + data.message);
      }
    });
}

updateUsbStatus();
setInterval(updateUsbStatus, 5000);
