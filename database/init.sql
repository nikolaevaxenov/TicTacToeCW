CREATE DATABASE IF NOT EXISTS tictactoeDB;
CREATE USER IF NOT EXISTS 'user'@'%' IDENTIFIED BY 'password';
GRANT SELECT,UPDATE,INSERT ON tictactoeDB.* TO 'user'@'%';
FLUSH PRIVILEGES;

use tictactoeDB;
CREATE TABLE IF NOT EXISTS users (
  ID INT NOT NULL AUTO_INCREMENT,
  login VARCHAR(20) NOT NULL,
  password VARCHAR(40) NOT NULL,
  score INT,
  apikey VARCHAR(34),
  PRIMARY KEY (ID)
);
