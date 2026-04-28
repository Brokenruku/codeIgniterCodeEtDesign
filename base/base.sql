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
-- Version sans clés étrangères pour démarrer
CREATE TABLE user_interactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    plat_id INT NOT NULL,
    action ENUM('seen', 'like', 'super') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_plat_id (plat_id),
    UNIQUE KEY unique_interaction (user_id, plat_id, action)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
    INDEX idx_user_id (user_id),
    INDEX idx_categorie_id (categorie_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;