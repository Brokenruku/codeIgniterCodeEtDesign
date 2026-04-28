CREATE DATABASE login;

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE plats (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    note INT,
    emoji VARCHAR(10),
    temps INT,
    categorie_id INT,
    calorie INT,
    FOREIGN KEY (categorie_id) REFERENCES CATEGORIE(id)
);

CREATE TABLE CATEGORIE(
    id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL UNIQUE
);