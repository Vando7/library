#!/bin/bash

mysql --user=root --password=123 --database=library --execute="UPDATE  	book, lent_to SET 	book.available_count = book.available_count + 1 WHERE  	lent_to.book_isbn = book.isbn and lent_to.status = 'reserved'; DELETE FROM lent_to WHERE status = 'reserved';";