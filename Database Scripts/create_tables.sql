DROP TABLE book_genre, lent_to, user, book, genre;

CREATE OR REPLACE TABLE user (
    id INT(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(64) NOT NULL,
    last_name VARCHAR(64) NOT NULL,
    country VARCHAR(64) NOT NULL,
    city VARCHAR(64) NOT NULL,
    street VARCHAR(64) NOT NULL,
    phone VARCHAR(20) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('reader','librarian','admin') NOT NULL,
    note TINYTEXT DEFAULT NULL,
    register_date DATE DEFAULT NOW() NOT NULL,
    suspended_status ENUM('no','yes'),
    suspended_date DATETIME DEFAULT NULL,
    suspended_reason TINYTEXT DEFAULT NULL,
    INDEX(first_name),
    INDEX(last_name)
)ENGINE=InnoDB;

CREATE OR REPLACE TABLE book(
    isbn VARCHAR(13) NOT NULL UNIQUE PRIMARY KEY,
    pictures JSON DEFAULT NULL,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    published DATE NOT NULL,
    description TEXT NOT NULL,
    total_count SMALLINT UNSIGNED NOT NULL,
    available_count SMALLINT UNSIGNED NOT NULL DEFAULT total_count,
    INDEX (title),
    INDEX (author),
    INDEX (published),
    CHECK(JSON_VALID(pictures))
)ENGINE=InnoDB;

CREATE OR REPLACE TABLE genre(
	id SMALLINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
	name VARCHAR(25) NOT NULL
)ENGINE=InnoDB;

CREATE OR REPLACE TABLE book_genre(
	book_isbn VARCHAR(13) NOT NULL,
	genre_id SMALLINT UNSIGNED NOT NULL,
	FOREIGN KEY (book_isbn) REFERENCES book(isbn)
		ON UPDATE CASCADE ON DELETE RESTRICT,
	FOREIGN KEY (genre_id) REFERENCES genre (id)
		ON UPDATE CASCADE ON DELETE RESTRICT
)ENGINE=InnoDB;

CREATE OR REPLACE TABLE lent_to(
	book_isbn VARCHAR(13) NOT NULL,
	user_id INT(11) UNSIGNED NOT NULL,
	employee_id INT(11) UNSIGNED NOT NULL,
	amount SMALLINT UNSIGNED NOT NULL,
	date_lent DATETIME NOT NULL DEFAULT NOW(),
	date_returned DATETIME DEFAULT NULL,
	deadline DATETIME NOT NULL,
	status ENUM('taken','reserved','returned') NOT NULL,
	INDEX(status),
	FOREIGN KEY (book_isbn) REFERENCES book(isbn)
		ON UPDATE CASCADE ON DELETE RESTRICT,
	FOREIGN KEY (user_id) REFERENCES user(id)
		ON UPDATE CASCADE ON DELETE RESTRICT,
	FOREIGN KEY (employee_id) REFERENCES user(id)
		ON UPDATE CASCADE ON DELETE RESTRICT
)ENGINE=InnoDB;

