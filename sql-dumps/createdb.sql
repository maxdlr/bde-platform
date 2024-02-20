set FOREIGN_KEY_CHECKS=0;

DROP DATABASE IF EXISTS bde_platform;
CREATE DATABASE IF NOT EXISTS bde_platform;

USE bde_platform;
DROP TABLE IF EXISTS event;
CREATE TABLE event
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

DROP TABLE IF EXISTS user;
CREATE TABLE user
(
    id         int          NOT NULL AUTO_INCREMENT,
    firstname  varchar(255) not null,
    lastname   varchar(255) not null,
    email      varchar(255) not null UNIQUE,
    password   varchar(255) not null,
    roles      varchar(255) not null,
    isVerified boolean      not null,
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
    CONSTRAINT interested_event FOREIGN KEY (event_id) REFERENCES event (id),
    CONSTRAINT interested_user FOREIGN KEY (user_id) REFERENCES user (id)
);
DROP TABLE IF EXISTS participant;
CREATE TABLE participant
(
    id       int not null auto_increment,
    event_id int not null,
    user_id  int not null,
    PRIMARY KEY (id),
    CONSTRAINT participant_event FOREIGN KEY (event_id) REFERENCES event (id),
    CONSTRAINT participant_user FOREIGN KEY (user_id) REFERENCES user (id)

);

INSERT INTO user (firstname, lastname, email, password, roles, isVerified, signedUpOn)
VALUES
    ('John', 'Doe', 'john.doe@example.com', 'password', 'admin', 1, NOW()),
    ('Jane', 'Smith', 'jane.smith@example.com', 'password', 'BDE Members', 1, NOW()),
    ('Michael', 'Johnson', 'michael.johnson@example.com', 'password', 'students', 1, NOW()),
    ('Emily', 'Brown', 'emily.brown@example.com', 'password', 'visitor', 1, NOW()),
    ('William', 'Taylor', 'william.taylor@example.com', 'password', 'admin', 1, NOW()),
    ('Olivia', 'Martinez', 'olivia.martinez@example.com', 'password', 'BDE Members', 1, NOW()),
    ('James', 'Anderson', 'james.anderson@example.com', 'password', 'students', 1, NOW()),
    ('Sophia', 'Thomas', 'sophia.thomas@example.com', 'password', 'visitor', 1, NOW()),
    ('Benjamin', 'Hernandez', 'benjamin.hernandez@example.com', 'password', 'admin', 1, NOW()),
    ('Mia', 'Wilson', 'mia.wilson@example.com', 'password', 'BDE Members', 1, NOW()),
    ('Ethan', 'Moore', 'ethan.moore@example.com', 'password', 'students', 1, NOW()),
    ('Ava', 'Garcia', 'ava.garcia@example.com', 'password', 'visitor', 1, NOW()),
    ('Alexander', 'Lopez', 'alexander.lopez@example.com', 'password', 'admin', 1, NOW()),
    ('Isabella', 'Perez', 'isabella.perez@example.com', 'password', 'BDE Members', 1, NOW()),
    ('Jacob', 'Lee', 'jacob.lee@example.com', 'password', 'students', 1, NOW()),
    ('Sophia', 'Rodriguez', 'sophia.rodriguez@example.com', 'password', 'visitor', 1, NOW()),
    ('Michael', 'Gonzalez', 'michael.gonzalez@example.com', 'password', 'admin', 1, NOW()),
    ('Emma', 'Harris', 'emma.harris@example.com', 'password', 'BDE Members', 1, NOW()),
    ('Elijah', 'Clark', 'elijah.clark@example.com', 'password', 'students', 1, NOW()),
    ('Avery', 'Lewis', 'avery.lewis@example.com', 'password', 'visitor', 1, NOW()),
    ('Logan', 'Allen', 'logan.allen@example.com', 'password', 'admin', 1, NOW()),
    ('Abigail', 'Young', 'abigail.young@example.com', 'password', 'BDE Members', 1, NOW()),
    ('Daniel', 'Wright', 'daniel.wright@example.com', 'password', 'students', 1, NOW()),
    ('Harper', 'King', 'harper.king@example.com', 'password', 'visitor', 1, NOW()),
    ('Mason', 'Scott', 'mason.scott@example.com', 'password', 'admin', 1, NOW()),
    ('Evelyn', 'Green', 'evelyn.green@example.com', 'password', 'BDE Members', 1, NOW()),
    ('William', 'Baker', 'william.baker@example.com', 'password', 'students', 1, NOW()),
    ('Sofia', 'Adams', 'sofia.adams@example.com', 'password', 'visitor', 1, NOW()),
    ('Lucas', 'Nelson', 'lucas.nelson@example.com', 'password', 'admin', 1, NOW()
);
-- Insérer des événements avec des données aléatoires
INSERT INTO event (name, description, startDate, endDate, tag, capacity, owner_id)
VALUES
    ('Fête de lancement du projet', 'Célébration du lancement du nouveau projet de l\'entreprise', '2024-03-01 18:00:00', '2024-03-01 22:00:00', 'Lancement', 50, 29),
    ('Séminaire sur le leadership', 'Conférence sur le développement du leadership dans le monde des affaires', '2024-03-10 09:00:00', '2024-03-10 17:00:00', 'Leadership', 100, 9),
    ('Tournoi de football inter-entreprises', 'Compétition de football entre différentes entreprises de la région', '2024-03-15 14:00:00', '2024-03-15 18:00:00', 'Sport', 80, 1),
    ('Soirée cinéma en plein air', 'Projection de films en plein air pour toute la famille', '2024-03-20 19:00:00', '2024-03-20 23:00:00', 'Cinéma', 120, 5),
    ('Conférence sur l\'innovation technologique', 'Présentation des dernières innovations technologiques et leur impact', '2024-03-25 10:00:00', '2024-03-25 16:00:00', 'Innovation', 150, 9),
    ('Atelier de développement personnel', 'Atelier interactif sur le développement personnel et professionnel', '2024-03-30 13:00:00', '2024-03-30 17:00:00', 'Développement personnel', 50, 13),
    ('Exposition d\'art local', 'Exposition d\'œuvres d\'art réalisées par des artistes locaux', '2024-04-05 11:00:00', '2024-04-05 19:00:00', 'Art', 70, 17),
    ('Journée porte ouverte', 'Visite guidée des installations de l\'entreprise et présentation des activités', '2024-04-10 09:00:00', '2024-04-10 15:00:00', 'Portes ouvertes', 200, 21),
    ('Séance de yoga en plein air', 'Session de yoga relaxante en plein air pour tous les niveaux', '2024-04-15 08:00:00', '2024-04-15 10:00:00', 'Bien-être', 40, 25),
    ('Soirée karaoké', 'Soirée karaoké avec des chansons populaires et beaucoup de plaisir', '2024-04-20 20:00:00', '2024-04-21 00:00:00', 'Divertissement', 80, 29);

