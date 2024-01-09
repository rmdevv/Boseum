USE michelonr;

DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Artworks;
DROP TABLE IF EXISTS Labels;
DROP TABLE IF EXISTS ArtworkLabels;
DROP TABLE IF EXISTS ArtworkDetails;
DROP TABLE IF EXISTS Artshows;
DROP TABLE IF EXISTS ArtshowArtworks;
DROP TABLE IF EXISTS ArtshowPrenotations;

CREATE TABLE Users (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    username VARCHAR(30) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(30) NOT NULL,
    lastname VARCHAR(30) NOT NULL,
    isAmm BOOLEAN NOT NULL,
    image VARCHAR(100) UNIQUE,
    birth_date DATE,
    birth_place VARCHAR(30),
    biography VARCHAR(1000),
    experience VARCHAR(1000)
);

CREATE TABLE Artworks (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    title VARCHAR(50) NOT NULL,
    main_image VARCHAR(100) NOT NULL UNIQUE,
    description VARCHAR(1000) NOT NULL,
    height FLOAT,
    width FLOAT,
    length FLOAT,
    start_date DATE,
    end_date DATE,
    upload_time TIMESTAMP NOT NULL,
    id_artist INT NOT NULL REFERENCES Users(id)
);

CREATE TABLE Labels (
    label VARCHAR(20) PRIMARY KEY NOT NULL
);

CREATE TABLE ArtworkLabels (
    id_artwork INT NOT NULL REFERENCES Artwork(id),
    label VARCHAR(20) NOT NULL REFERENCES Labels(label),
    PRIMARY KEY (id_artwork, label)
);

CREATE TABLE ArtworkDetails (
    id_artwork INT NOT NULL REFERENCES Artwork(id),
    image VARCHAR(100) NOT NULL UNIQUE,
    PRIMARY KEY (id_artwork, image)
);

CREATE TABLE Artshows (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    title VARCHAR(30) NOT NULL,
    description VARCHAR(300) NOT NULL,
    image VARCHAR(100) UNIQUE NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL
);

CREATE TABLE ArtshowPrenotations (
    id_artshow INT NOT NULL REFERENCES Artshow(id),
    id_artist INT NOT NULL REFERENCES Users(id),
    PRIMARY KEY (id_artshow, id_artist)
);

INSERT INTO Users(username, password, isAmm) VALUES
('admin', '$2b$12$9sm6jSUZr5up9OBU3GsTOewCMm4NUj5xK1EFwviI76xxAAcmTm.Je', True);

INSERT INTO Labels VALUES
    ('dipinto'),
    ('digitale'),
    ('realismo'),
    ('astrazione'),
    ('minimalismo'),
    ('sketch'),
    ('scultura'),
    ('marmo'),
    ('bronzo'),
    ('oggetto'),
    ('arte contemporanea'),
    ('architettura'),
    ('paesaggio'),
    ('natura'),
    ('ritratto'),
    ('movimento'),
    ('bianco e nero'),
    ('sfumature'),
    ('mare'),
    ('notte'),
    ('inverno');
