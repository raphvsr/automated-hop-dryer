
import mysql.connector

db = mysql.connector.connect(
    host="localhost:3306",
    user="root",
    password="",
    database="DB_PC"
)


cursor = db.cursor()

cursor.execute("""
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    age INT
)
""")
