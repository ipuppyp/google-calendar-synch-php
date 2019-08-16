CREATE TABLE EVENTS (
	ID MEDIUMINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	CALENDARORIGICALUID VARCHAR(20) NOT NULL UNIQUE,	
	CALENDARSTART DATETIME NOT NULL,
    CALENDARLOCATION VARCHAR(80) NOT NULL,
    LOCATION VARCHAR(80),
    DESCRIPTION VARCHAR(200),
    CHANGED BOOLEAN DEFAULT TRUE,
    VISIBLE BOOLEAN DEFAULT FALSE,
    CREATED TIMESTAMP NOT NULL,
    UPDATED TIMESTAMP NOT NULL,
    FACEBOOKLINK VARCHAR(200),
    TICKETPURCHASELINK VARCHAR(200)
);