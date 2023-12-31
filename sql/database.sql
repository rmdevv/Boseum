USE michelonr;

DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Artwork;
DROP TABLE IF EXISTS ArtworkLabels;
DROP TABLE IF EXISTS Images;
DROP TABLE IF EXISTS ArtworkDetails;
DROP TABLE IF EXISTS Artshow;
DROP TABLE IF EXISTS ArtshowArtworks;
DROP TABLE IF EXISTS ArtshowPrenotations;

CREATE TABLE Users (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    username VARCHAR(30) NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(30) NOT NULL,
    surname VARCHAR(30) NOT NULL,
    isAmm BOOLEAN NOT NULL,
    image INT REFERENCES Images(id),
    birth_date DATE NOT NULL,
    birth_place VARCHAR(30) NOT NULL,
    biography VARCHAR(1000),
    experience VARCHAR(1000)
);

CREATE TABLE Artwork (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    title VARCHAR(30) NOT NULL,
    main_image INT NOT NULL REFERENCES Images(id),
    description VARCHAR(1000),
    height FLOAT,
    width FLOAT,
    depth FLOAT,
    start_date DATE,
    end_date DATE,
    upload_time TIMESTAMP NOT NULL,
    id_artist INT REFERENCES Users(id)
);

CREATE TABLE ArtworkLabels (
    id_artwork INT NOT NULL REFERENCES Artwork(id),
    label VARCHAR(20) NOT NULL REFERENCES Labels(label),
    PRIMARY KEY (id_artwork, label)
);

CREATE TABLE Labels (
    label VARCHAR(20) PRIMARY KEY NOT NULL
);

CREATE TABLE Images (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    source VARCHAR(300) NOT NULL
);

CREATE TABLE ArtworkDetails (
    id_artwork INT NOT NULL REFERENCES Artwork(id),
    image INT NOT NULL REFERENCES Images(id),
    PRIMARY KEY (id_artwork, image)
);

CREATE TABLE Artshow (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    title VARCHAR(30) NOT NULL,
    description VARCHAR(300) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL
);

CREATE TABLE ArtshowArtworks (
    id_artshow INT NOT NULL REFERENCES Artshow(id),
    id_artwork INT NOT NULL REFERENCES Artwork(id),
    PRIMARY KEY (id_artshow, id_artwork)
);

CREATE TABLE ArtshowPrenotations (
    id_artshow INT NOT NULL REFERENCES Artshow(id),
    id_artist INT NOT NULL REFERENCES Users(id),
    PRIMARY KEY (id_artshow, id_artist)
);

INSERT INTO Labels VALUES
    ('dipinto'),
    ('digitale'),
    ('realismo'),
    ('astrazione'),
    ('minimalismo'),
    ('schizzo'),
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

