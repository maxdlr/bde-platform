set FOREIGN_KEY_CHECKS = 0;

DROP DATABASE IF EXISTS bde_platform;
CREATE DATABASE IF NOT EXISTS bde_platform;

USE bde_platform;
DROP TABLE IF EXISTS event;
CREATE TABLE event
(
    id          int           NOT NULL auto_increment,
    name        varchar(255)  NOT NULL,
    description varchar(2000) NOT NULL,
    startDate   dateTime      NOT NULL,
    endDate     datetime      NOT NULL,
    tag         varchar(255)  NOT NULL,
    capacity    int           NOT NULL,
    fileName    varchar(100),
    fileSize    double,
    owner_id    int           NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT event_owner FOREIGN KEY (owner_id) REFERENCES user (id) on delete cascade
);

DROP TABLE IF EXISTS user;
CREATE TABLE user
(
    id         int          NOT NULL AUTO_INCREMENT,
    firstname  varchar(255) not null,
    lastname   varchar(255) not null,
    email      varchar(255) not null UNIQUE,
    password   varchar(255) not null,
    roles      varchar(255) not null,
    isVerified boolean,
    signedUpOn dateTime     not null,

    PRIMARY KEY (id)
);
DROP TABLE IF EXISTS interested;
CREATE TABLE interested
(
    id       int not null auto_increment,
    event_id int not null,
    user_id  int not null,
    PRIMARY KEY (id),
    CONSTRAINT interested_event FOREIGN KEY (event_id) REFERENCES event (id) on delete cascade,
    CONSTRAINT interested_user FOREIGN KEY (user_id) REFERENCES user (id) on delete cascade
);
DROP TABLE IF EXISTS participant;
CREATE TABLE participant
(
    id       int not null auto_increment,
    event_id int not null,
    user_id  int not null,
    PRIMARY KEY (id),
    CONSTRAINT participant_event FOREIGN KEY (event_id) REFERENCES event (id) on delete cascade,
    CONSTRAINT participant_user FOREIGN KEY (user_id) REFERENCES user (id) on delete cascade

);

