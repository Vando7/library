Steps to setting up the database

1. Create tables by running:
    create_tables.sql

2. Load data for user, book, genre:
    load_book_dummy.sql
    load_genre_dummy.sql
    load_user_dummy.sql

3. Important: set roles for the users
    generate_role_assignment

    Note: At this point you would want to create an admin account for 
    the app. One way is to sign up a new account in the app. The new 
    account should have ID 1001 (last row of 'user').


4. Load relational data for book_genre:
    book_genre_dummy.sql

4.1 If book_genre_dummy.sql does not exist open
    generate_genre_relations.py
    
    Set the proper values for your DB in "myconn" and run it like this:
    $ python3 generate_genre_relations.py > load_genre_relations.sql

NOTE: as it stands now, the generate_lend_history script inserts
    directly into the database since the generated file is too large to load.
5 Load relational data for lent books:
    load_dummy_lend_history.sql

    Note: Data is generated on 30/09/2021 if you want to generate fresh
        crisp data for your current day run
        generate_lend_history.py

