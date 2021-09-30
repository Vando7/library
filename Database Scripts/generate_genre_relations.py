import mysql.connector
import random

myconn = mysql.connector.connect(host="localhost", user="root", passwd="123", database="library")
cur = myconn.cursor()

cur.execute("SELECT isbn FROM book")
result = cur.fetchall()

for x in result:
    genreCount = random.randint(1,4)
    genreIDList = []

    for y in range(genreCount):
        genreID = random.randint(1,14)

        while genreID in genreIDList:
            genreID = random.randint(1,14)
        
        genreIDList.append(genreID)

    for y in genreIDList:
        insert = f"INSERT INTO book_genre(book_isbn,genre_id) VALUES(\'{str(x[0])}\',\'{y}\');"
        print(insert)
        

        