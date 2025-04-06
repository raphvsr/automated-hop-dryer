
import mysql.connector


def get_csv_data():
    rows = []
    with open("data.csv") as file:
        for i, line in enumerate(file):
            if i == 0:
                continue
            values = line.split(",")
            if len(values) != 7:
                continue
            date, etage4, etage3, etage2, etage1, temps_bruleur, etat_bruleur = values
            status = "true" if etat_bruleur == "true" else "off"
            campaign_id = 1
            rows.append((campaign_id, status, date))


get_csv_data()



# db = mysql.connector.connect(
#     host="localhost:3306",
#     user="root",
#     password="",
#     database="DB_PC"
# )

# cursor = db.cursor()


# db.commit()
# cursor.close()
# db.close()
