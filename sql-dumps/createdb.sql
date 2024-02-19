CREATE DATABASE IF NOT EXISTS bde_platform;

USE bde_platform;

CREATE TABLE IF NOT EXISTS event
(
    id          int          NOT NULL auto_increment,
    name        varchar(255) NOT NULL,
    description varchar(255) NOT NULL,
    startDate   dateTime     NOT NULL,
    endDate     datetime     NOT NULL,
    tag         varchar(255) NOT NULL,
    capacity    int          NOT NULL,
    owner_id    int          NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT event_owner FOREIGN KEY (owner_id) REFERENCES user (id)
);

CREATE TABLE IF NOT EXISTS user
(
    id         int          NOT NULL AUTO_INCREMENT,
    firstname  varchar(255) not null,
    lastname   varchar(255) not null,
    email      varchar(255) not null,
    password   varchar(255) not null,
    roles      varchar(255) not null,
    isVerified boolean      not null,
    signedUpOn dateTime     not null,

    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS interested
(
    id       int not null auto_increment,
    event_id int not null,
    user_id  int not null,
    PRIMARY KEY (id),
    CONSTRAINT interested_event FOREIGN KEY (event_id) REFERENCES event (id),
    CONSTRAINT interested_user FOREIGN KEY (user_id) REFERENCES user (id)
);

CREATE TABLE IF NOT EXISTS participant
(
    id       int not null auto_increment,
    event_id int not null,
    user_id  int not null,
    PRIMARY KEY (id),
    CONSTRAINT participant_event FOREIGN KEY (event_id) REFERENCES event (id),
    CONSTRAINT participant_user FOREIGN KEY (user_id) REFERENCES user (id)

);
