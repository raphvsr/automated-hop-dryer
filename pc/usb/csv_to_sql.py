import mysql.connector
from datetime import datetime
import tkinter as tk
from tkinter.filedialog import askopenfilename





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

    if not all_values:
        print("Aucune donnée valide dans le CSV.")
        return "red", "Aucune donnée valide dans le CSV."

    db = mysql.connector.connect(
        host="localhost",
        port=3306,
        user="root",
        password="",
        database="test"
    )

    cursor = db.cursor()

    try:
        for values in all_values:
            date, etage4, etage3, etage2, etage1, temps_bruleur, etat_bruleur, variety_name = values

            #__variete__
            cursor.execute("SELECT id FROM varieties WHERE name = %s", (variety_name,))
            variety = cursor.fetchone()

            if not variety:
                cursor.execute("INSERT INTO varieties (name) VALUES (%s)", (variety_name,))
                db.commit()
                variety_id = cursor.lastrowid
            else:
                variety_id = variety[0]
            #_________


            # Trouver la campagne correspondante
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
                    VALUES (1, %s)
                """, (date,))
                db.commit()
                campaign_id = cursor.lastrowid
            else:
                campaign_id = campaign[0]

            print(f"Campaign ID: {campaign_id}")

            # Insérer les états des étages
            for etage_number, etage_state in enumerate([etage1, etage2, etage3, etage4], start=1):
                etage_state = etage_state.strip().lower() == "true"
                if etage_state:
                    cursor.execute("""
                        INSERT INTO drying_etages (campaign_id, etage_number, start_time, end_time)
                        VALUES (%s, %s, %s, NULL)
                    """, (campaign_id, etage_number, date))

            # Statut du brûleur
            if etat_bruleur.strip():
                etat_bruleur = etat_bruleur.strip().lower()
                status = 'on' if etat_bruleur == "true" or etat_bruleur == "on" else 'off'
                cursor.execute("""
                    INSERT INTO burner_status (campaign_id, etage_number, status, changed_at)
                    VALUES (%s, 1, %s, %s)
                """, (campaign_id, status, date))
    except Exception as e:
        return "red", e


    db.commit()

    cursor.close()
    db.close()

    return "green", "Base de données mise à jour"


def get_csv_file():
    filename = askopenfilename()
    color, message = update_database(filename)
    success_label.config(text=message, fg=color)



root = tk.Tk()

root.title("Importer CSV")
root.geometry("400x200")

button = tk.Button(
    root,
    text="Importer Un Fichier CSV",
    command=get_csv_file,
    font=("Helvetica", 16),
    width=20,
    height=2,
    bg="#4CAF50",
    fg="white",
    activebackground="#45a049",
    relief="flat",

)


button.pack(expand=True)
success_label = tk.Label(root, text="", font=("Helvetica", 12), fg="black")
success_label.pack(side="bottom", pady=10)

root.mainloop()
