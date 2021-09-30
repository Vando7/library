import mysql.connector
import random
from datetime import date
import datetime

myconn = mysql.connector.connect(host="localhost", user="root", passwd="123", database="library")
cur = myconn.cursor()

cur.execute("SELECT id FROM user")
result = cur.fetchall()

for x in result:
    doprint = random.randint(1,500)

    totalBooksTaken = random.randint(5,500)
    cur.execute(f"SELECT isbn FROM book ORDER BY RAND() LIMIT {totalBooksTaken}")
    takenBooks=cur.fetchall()

    employeeId = random.randint(1,4)


    for y in takenBooks:
        employeeIDBackup = employeeId
        statusChance = random.randint(1,100)
        status = ''

        today = date.today()
        startDate = None
        deadline = None
        returnDate = None

        if statusChance > 5 and statusChance < 50:
            status = 'returned'
            startDateNumber = random.randint(5,5*365)
            startDate = today - datetime.timedelta(days=startDateNumber)
            deadline = startDate + datetime.timedelta(days=30)
            returned = random.randint(2,24)
            returnDate = startDate + datetime.timedelta(days=returned)

        elif statusChance <= 5:
            status = 'reserved'
            employeeId = x[0]
            startDate = today
            deadline = startDate + datetime.timedelta(days=30)
        else:
            status = 'taken'
            startDateNumber = random.randint(1,29)
            startDate = today - datetime.timedelta(days=startDateNumber)
            deadline = startDate + datetime.timedelta(days=30)

        amount = random.randint(1,32)
        
        
        # NOTE: uncomment the following 3 lines to insert into the table DIRECTLY (works)
        # insert = f"INSERT INTO lent_to(book_isbn,user_id,employee_id,amount,date_lent,date_returned,deadline,status) VALUES(\'{y[0]}\',\'{x[0]}\',\'{employeeId}\',\'{amount}\',\'{startDate}\',\'{returnDate}\',\'{deadline}\',\'{status}\');"
        # if(returnDate is None):
        #     insert = f"INSERT INTO lent_to(book_isbn,user_id,employee_id,amount,date_lent,deadline,status) VALUES(\'{y[0]}\',\'{x[0]}\',\'{employeeId}\',\'{amount}\',\'{startDate}\',\'{deadline}\',\'{status}\');"
      
        # NOTE: uncomment these two following lines and pipe the output to an empty sql file. (~42MB file that will make your pc choke)
        #print("INSERT INTO lent_to(book_isbn,user_id,employee_id,amount,date_lent,date_returned,deadline,status)", end = "")
        #print(f" VALUES(\'{y[0]}\',\'{x[0]}\',\'{employeeId}\',\'{amount}\',\'{startDate}\',\'{returnDate}\',\'{deadline}\',\'{status}\');")
        cur.execute(insert)

        employeeId = employeeIDBackup

myconn.commit()

       