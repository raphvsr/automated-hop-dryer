
import mysql.connector


def get_csv_data():
    rows = []
    with open("data.csv") as file:
        for i, line in enumerate(file):
            if i == 0:
                continue
            values = line.strip().split(",")
            if len(values) != 7:
                continue
            return values


def update_database():
    values = get_csv_data()

    if values:
        date, etage4, etage3, etage2, etage1, temps_bruleur, etat_bruleur = values




    db = mysql.connector.connect(
        host="localhost", port=3306,
        user="root",
        password="",
        database="test"
    )



    cursor = db.cursor()

    cursor.execute("""
    SELECT id FROM drying_campaigns WHERE start_time <= %s ORDER BY start_time DESC LIMIT 1
    """, (date,))
    campaign_id = cursor.fetchone()

    # Si aucune campagne n'est trouvée, créer une nouvelle campagne
    if not campaign_id:
        cursor.execute("""
        INSERT INTO drying_campaigns (variety_id, start_time)
        VALUES (1, %s)  # Change `1` to the actual variety_id
        """, (date,))
        db.commit()
        campaign_id = cursor.lastrowid  # Récupérer l'ID de la nouvelle campagne
    else:
        campaign_id = campaign_id[0]

    print(f"Campaign ID: {campaign_id}")

    # Logique de mise à jour de l'état des étages
    # Mise à jour de l'état des étages (par exemple, "étage1" => "etage_number 1")
    for etage_number, etage_state in enumerate([etage1, etage2, etage3, etage4], start=1):
        etage_state = etage_state.strip().lower()
        etage_state = True if etage_state == "true" else False

        # Insertion ou mise à jour dans la table drying_etages
        query = """
        INSERT INTO drying_etages (campaign_id, etage_number, start_time, end_time)
        VALUES (%s, %s, %s, NULL)
        """
        cursor.execute(query, (campaign_id, etage_number, date))

    # Mise à jour du statut du brûleur
    etat_bruleur = etat_bruleur.strip().lower()
    etat_bruleur = 'on' if etat_bruleur == "true" else 'off'

    query = """
    INSERT INTO burner_status (campaign_id, etage_number, status, changed_at)
    VALUES (%s, 1, %s, %s)  # Change `1` to the actual etage_number if necessary
    """
    cursor.execute(query, (campaign_id, etat_bruleur, date))

    db.commit()
    print("Données mises à jour avec succès.")

    cursor.close()
    db.close()


update_database()
