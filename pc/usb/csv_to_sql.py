import mysql.connector
from datetime import datetime
import tkinter as tk
from tkinter.filedialog import askopenfilename

NOM_UTILISATEUR_BD = "root"
MOT_DE_PASSE_BD = ""


#            +-----------------------------+
#            |       get_csv_file()        |
#            |  Ouvre et prévisualise le   |
#            |   contenu d’un fichier CSV  |
#            +-------------+---------------+
#                          |
#                          v
#            +-----------------------------+
#            |     get_csv_data(fichier)   |
#            |  - Lit le fichier ligne par |
#            |    ligne                    |
#            |  - Nettoie et trie par date |
#            +-------------+---------------+
#                          |
#                          v
#            +-----------------------------+
#            |  Affiche un résumé lisible  |
#            |  dans la zone centrale      |
#            +-----------------------------+
#                          |
#                          |
#          [Après clic sur "Valider Importation"]
#                          |
#                          v
#     +----------------------------------------+
#     |        update_database(fichier)        |
#     |   Insère les données dans la base      |
#     +-------------------+--------------------+
#                         |
#      +------------------+------------------+
#      |                                     |
#      v                                     v
# +---------------------+         +---------------------------+
# | get_csv_data()      |         | Connexion à la base MySQL |
# +---------------------+         +---------------------------+
#             |
#             v
#   +-----------------------------+
#   | Boucle sur chaque ligne     |
#   | du fichier CSV              |
#   |                             |
#   | +------------------------+  |
#   | | Vérifie/Ajoute variété |  |
#   | +------------------------+  |
#   | | Trouve/Ajoute campagne |  |
#   | +------------------------+  |
#   | | Insère les étages      |  |
#   | +------------------------+  |
#   | | Insère l’état brûleur  |  |
#   | +------------------------+  |
#   +-------------+---------------+
#                 |
#                 v
#       +----------------------------+
#       | Commit + fermeture DB      |
#       +----------------------------+
#                 |
#                 v
#     +-----------------------------------------+
#     | Affiche résumé + message réussite/erreur|
#     +-----------------------------------------+


# Variable globale pour garder le fichier sélectionné
selected_filename = None

def get_csv_data(filename):
    rows = []
    with open(filename) as file:
        for i, line in enumerate(file):
            if i == 0:
                continue
            values = line.strip().split(",")
            if len(values) != 8:
                continue
            rows.append(values)
    rows.sort(key=lambda x: datetime.strptime(x[0], "%Y-%m-%d %H:%M:%S"))
    return rows

def update_database(filename):
    all_values = get_csv_data(filename)
    resume = ""

    if not all_values:
        return "red", "Aucune donnée valide dans le CSV.", ""

    db = mysql.connector.connect(
        host="localhost",
        port=3306,
        user=NOM_UTILISATEUR_BD,
        password=MOT_DE_PASSE_BD,
        database="test"
    )

    cursor = db.cursor()

    try:
        for values in all_values:
            date, etage4, etage3, etage2, etage1, temps_bruleur, etat_bruleur, variety_name = values
            resume += f"\n{date} - Variété: {variety_name}"

            cursor.execute("SELECT id FROM varieties WHERE name = %s", (variety_name,))
            variety = cursor.fetchone()

            if not variety:
                cursor.execute("INSERT INTO varieties (name) VALUES (%s)", (variety_name,))
                db.commit()
                variety_id = cursor.lastrowid
                resume += " | Nouvelle variété"
            else:
                variety_id = variety[0]

            cursor.execute("""
                SELECT id FROM drying_campaigns
                WHERE start_time <= %s
                ORDER BY start_time DESC
                LIMIT 1
            """, (date,))
            campaign = cursor.fetchone()

            if not campaign:
                cursor.execute("""
                    INSERT INTO drying_campaigns (variety_id, start_time)
                    VALUES (%s, %s)
                """, (variety_id, date))
                db.commit()
                campaign_id = cursor.lastrowid
                resume += " | Nouvelle campagne"
            else:
                campaign_id = campaign[0]

            for etage_number, etage_state in enumerate([etage1, etage2, etage3, etage4], start=1):
                etage_state = etage_state.strip().lower() == "true"
                if etage_state:
                    cursor.execute("""
                        INSERT INTO drying_etages (campaign_id, etage_number, start_time, end_time)
                        VALUES (%s, %s, %s, NULL)
                    """, (campaign_id, etage_number, date))
                    resume += f" | Etage {etage_number}"

            if etat_bruleur.strip():
                etat_bruleur = etat_bruleur.strip().lower()
                status = 'on' if etat_bruleur == "true" or etat_bruleur == "on" else 'off'
                cursor.execute("""
                    INSERT INTO burner_status (campaign_id, etage_number, status, changed_at)
                    VALUES (%s, 1, %s, %s)
                """, (campaign_id, status, date))
                resume += f" | Brûleur: {status.upper()}"
    except Exception as e:
        return "red", str(e), ""

    db.commit()
    cursor.close()
    db.close()

    return "green", "Base de données mise à jour", resume

# Chargement du fichier uniquement (prévisualisation)
def get_csv_file():
    global selected_filename
    selected_filename = askopenfilename()
    if not selected_filename:
        return

    all_values = get_csv_data(selected_filename)

    if not all_values:
        resume_text.delete("1.0", tk.END)
        resume_text.insert(tk.END, "Aucune donnée valide dans le fichier.")
        success_label.config(text="Fichier invalide.", fg="red")
        return

    resume = ""
    for values in all_values:
        date, etage4, etage3, etage2, etage1, temps_bruleur, etat_bruleur, variety_name = values
        resume += f"\n{date} | Variété: {variety_name}"
        for i, etage in enumerate([etage1, etage2, etage3, etage4], start=1):
            if etage.strip().lower() == "true":
                resume += f" | Etage {i}"
        if etat_bruleur.strip():
            status = 'ON' if etat_bruleur.strip().lower() in ("true", "on") else "OFF"
            resume += f" | Brûleur: {status}"

    resume_text.delete("1.0", tk.END)
    resume_text.insert(tk.END, resume.strip())
    success_label.config(text="Prévisualisation terminée.", fg="orange")

# Validation de l'importation
def validate_import():
    global selected_filename
    if not selected_filename:
        success_label.config(text="Aucun fichier.", fg="red")
        return

    color, message, resume = update_database(selected_filename)
    success_label.config(text=message, fg=color)
    resume_text.delete("1.0", tk.END)
    resume_text.insert(tk.END, resume if resume else "Aucun changement détecté.")

# Annuler l'importation
def cancel_import():
    global selected_filename
    selected_filename = None
    resume_text.delete("1.0", tk.END)
    success_label.config(text="Importation annulée.", fg="black")

# Interface graphique
root = tk.Tk()
root.title("Importer CSV")
root.geometry("900x600")

# Cadre pour résumé avec scrollbar
resume_frame = tk.Frame(root)
resume_frame.pack(expand=True, fill="both", padx=20, pady=10)

resume_text = tk.Text(resume_frame, wrap="word", font=("Helvetica", 12))
resume_text.pack(side="left", fill="both", expand=True)

scrollbar = tk.Scrollbar(resume_frame, command=resume_text.yview)
scrollbar.pack(side="right", fill="y")
resume_text.config(yscrollcommand=scrollbar.set)

# Boite en bas pour les boutons
bottom_frame = tk.Frame(root)
bottom_frame.pack(side="bottom", pady=20)

# bouton importer fichier
button_import = tk.Button(
    bottom_frame,
    text="Importer Fichier CSV",
    command=get_csv_file,
    font=("Helvetica", 16),
    width=18,
    height=2,
    bg="#2196F3",
    fg="white",
    activebackground="#1976D2",
    relief="flat",
)
button_import.pack(side="left", padx=5)

# valider importation
button_valide = tk.Button(
    bottom_frame,
    text="Valider Importation",
    command=validate_import,
    font=("Helvetica", 16),
    width=18,
    height=2,
    bg="#4CAF50",
    fg="white",
    activebackground="#45a049",
    relief="flat",
)
button_valide.pack(side="left", padx=5)

# annuler importation
button_annule = tk.Button(
    bottom_frame,
    text="Annuler",
    command=cancel_import,
    font=("Helvetica", 16),
    width=15,
    height=2,
    bg="#de3030",
    fg="white",
    activebackground="#B71C1C",
    relief="flat",
)
button_annule.pack(side="left", padx=5)

# message de succès ou erreur
success_label = tk.Label(bottom_frame, text="", font=("Helvetica", 12), fg="black")
success_label.pack(side="bottom", pady=(10, 0))

# Lancer l'interface
root.mainloop()
