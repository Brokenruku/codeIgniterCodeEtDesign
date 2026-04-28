CREATE DATABASE 4064_4078_4284;



CREATE TABLE CATEGORIE(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE plats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    note INT,
    emoji VARCHAR(10),
    temps INT,
    categorie_id INT,
    calorie INT,
    FOREIGN KEY (categorie_id) REFERENCES CATEGORIE(id)
) ENGINE=InnoDB;

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE user_interactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    plat_id INT NOT NULL,
    action ENUM('seen', 'like', 'super') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (plat_id) REFERENCES plats(id),
    UNIQUE KEY unique_interaction (user_id, plat_id, action)
) ENGINE=InnoDB;

CREATE TABLE user_plats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    note INT,
    emoji VARCHAR(10),
    temps INT,
    categorie_id INT,
    calorie INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (categorie_id) REFERENCES CATEGORIE(id)
) ENGINE=InnoDB;