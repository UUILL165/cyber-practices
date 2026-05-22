DROP DATABASE IF EXISTS users;

CREATE DATABASE users;

USE users;

CREATE TABLE account (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(20),
    fullname VARCHAR(255) NULL,
    password VARCHAR(255),
    description TEXT NULL
);
