CREATE DATABASES bde_platform IF NOT EXISTS;

USE bde_platform;

CREATE TABLE event IF NOT EXISTS (
	id int NOT NULL auto_increment,
	name varchar(255) NOT NULL,
	description varchar(255) NOT NULL,
	startDate dateTime NOT NULL,
	endDate datetime NOT NULL,
	tag varchar(255) NOT NULL,
	capacity int NOT NULL,
	owner_id int NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (owner_id) REFERENCES user(id)
);

CREATE TABLE user IF NOT EXISTS (
	id int NOT NULL AUTO_INCREMENT,
	firstname varchar(255) not null,
	lastname varchar(255) not null,
	email varchar(255) not null, 
	password varchar(255) not null, 
	roles varchar(255) not null,
	isVerified boolean not null,
	signedUpOn dateTime not null,

	PRIMARY KEY (id)
);

CREATE TABLE interested IF NOT EXISTS (
	id int not null auto_increment,
	event_id int not null,
	user_id int not null,
	PRIMARY KEY (id),
	FOREIGN KEY (event_id) REFERENCES event(id),
	FOREGIN KEY (user_id) REFERENCES user(id)
);

CREATE TABLE participant IF NOT EXISTS (
	id int not null auto_increment,
	event_id int not null, 
	user_id int not null,
	PRIMARY KEY (id),
	FOREIGN KEY (event_id) REFERENCES event(id),
	FOREGIN KEY (user_id) REFERENCES user(id)

);
