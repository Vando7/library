import mysql.connector
import random

myconn = mysql.connector.connect(host="localhost", user="root", passwd="123", database="library")
cur = myconn.cursor()

cur.execute("SELECT id FROM user")
result = cur.fetchall()

for x in result:
    print(f"INSERT INTO auth_assignment(user_id, item_name, created_at) VALUES(\'{x[0]}\', \'reader\' ,\'1632924284\');")
        