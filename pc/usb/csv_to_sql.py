
import mysql.connector


def get_csv_data():
    with open("data.csv") as file:
        for i, line in enumerate(file):
            if i == 0:
                continue
            values = line.split(",")
            if len(values) != 8:
                continue
            date, heure, etage4, etage3, etage2, etage1, temps_bruleur, etat_bruleur = values
            print(heure)

get_csv_data()

db = mysql.connector.connect(
    host="localhost:3306",
    user="root",
    password="",
    database="DB_PC"
)

cursor = db.cursor()

cursor.execute("""
    ALTER TABLE burner_status
    MODIFY COLUMN status ENUM('on', 'off') NOT NULL;
""")

db.commit()
cursor.close()
db.close()
