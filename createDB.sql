SET NAMES utf8mb4;

DROP TABLE IF EXISTS `fertilizers`;
DROP TABLE IF EXISTS `plants`;
DROP TABLE IF EXISTS `supervisors`;
DROP TABLE IF EXISTS `laborants`;
DROP TABLE IF EXISTS `surfaces`;
DROP TABLE IF EXISTS `areas`;
DROP TABLE IF EXISTS `experiments`;
DROP TABLE IF EXISTS `results`;
DROP TABLE IF EXISTS `samples`;

CREATE TABLE fertilizers (
    fertilizer_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    fertilizer_name VARCHAR(50) NOT NULL UNIQUE
)
CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

INSERT INTO fertilizers(fertilizer_name) VALUES ("Gnojówka");
INSERT INTO fertilizers(fertilizer_name) VALUES ("Azot");
INSERT INTO fertilizers(fertilizer_name) VALUES ("Kompost");
INSERT INTO fertilizers(fertilizer_name) VALUES ("Witaminy");
INSERT INTO fertilizers(fertilizer_name) VALUES ("Sztuczna mieszanka");

CREATE TABLE plants (
    plant_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    plant_name VARCHAR(50) NOT NULL UNIQUE
)
CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

INSERT INTO plants(plant_name) VALUES ("Róża");
INSERT INTO plants(plant_name) VALUES ("Tulipan");
INSERT INTO plants(plant_name) VALUES ("Marchew");
INSERT INTO plants(plant_name) VALUES ("Burak");
INSERT INTO plants(plant_name) VALUES ("Groszek");
INSERT INTO plants(plant_name) VALUES ("Malina");
INSERT INTO plants(plant_name) VALUES ("Truskawka");

CREATE TABLE supervisors (
    supervisor_id  INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    supervisor_name VARCHAR(40) NOT NULL,
    login VARCHAR(40) NOT NULL UNIQUE,
    passwd VARCHAR(200) NOT NULL
)
CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

INSERT INTO supervisors(supervisor_name, login, passwd) VALUES ("Mirek","Mir123",PASSWORD("mirhaslo"));
INSERT INTO supervisors(supervisor_name, login, passwd) VALUES ("Stefan","Stefci0",PASSWORD("stehaslo"));
INSERT INTO supervisors(supervisor_name, login, passwd) VALUES ("Franek","Frank99",PASSWORD("frahaslo"));

CREATE TABLE laborants (
    laborant_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    laborant_name VARCHAR(40) NOT NULL,
    login VARCHAR(40) NOT NULL UNIQUE,
    passwd VARCHAR(200) NOT NULL
)
CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

INSERT INTO laborants(laborant_name, login, passwd) VALUES ("Dawid","Dawid",PASSWORD("dawhaslo"));
INSERT INTO laborants(laborant_name, login, passwd) VALUES ("Józef","Jozin",PASSWORD("jozhaslo"));
INSERT INTO laborants(laborant_name, login, passwd) VALUES ("Paweł","Pawel",PASSWORD("pawhaslo"));
INSERT INTO laborants(laborant_name, login, passwd) VALUES ("Aleksander","Olek",PASSWORD("alehaslo"));
INSERT INTO laborants(laborant_name, login, passwd) VALUES ("Sławomir","Slawcio",PASSWORD("slahaslo"));
INSERT INTO laborants(laborant_name, login, passwd) VALUES ("Konrad","Konrad",PASSWORD("konhaslo"));
INSERT INTO laborants(laborant_name, login, passwd) VALUES ("Jakub","Kuba",PASSWORD("jakhaslo"));
INSERT INTO laborants(laborant_name, login, passwd) VALUES ("Piotr","Piter",PASSWORD("piohaslo"));
INSERT INTO laborants(laborant_name, login, passwd) VALUES ("Maciej","Maciek",PASSWORD("machaslo"));
INSERT INTO laborants(laborant_name, login, passwd) VALUES ("Kacper","Kacper",PASSWORD("kachaslo"));

CREATE TABLE surfaces(
    surface_id  INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    surface_name VARCHAR(100) NOT NULL,
    size INT NOT NULL
)
CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

INSERT INTO surfaces(surface_name, size) VALUES ("Pole Mirka", 50);
INSERT INTO surfaces(surface_name, size) VALUES ("Pole Stefana", 75);
INSERT INTO surfaces(surface_name, size) VALUES ("Pole Franka", 60);

CREATE TABLE areas(
    area_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    surface_id INT,
    area_name VARCHAR(100) NOT NULL,
    size INT NOT NULL
)
CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

INSERT INTO areas(surface_id, area_name, size) VALUES (1, "Pole Mirka, obszar 1", 10);
INSERT INTO areas(surface_id, area_name, size) VALUES (1, "Pole Mirka, obszar 2", 15);
INSERT INTO areas(surface_id, area_name, size) VALUES (1, "Pole Mirka, obszar 3", 5);
INSERT INTO areas(surface_id, area_name, size) VALUES (1, "Pole Mirka, obszar 4", 20);
INSERT INTO areas(surface_id, area_name, size) VALUES (2, "Pole Stefana, obszar 1", 12);
INSERT INTO areas(surface_id, area_name, size) VALUES (2, "Pole Stefana, obszar 2", 9);
INSERT INTO areas(surface_id, area_name, size) VALUES (2, "Pole Stefana, obszar 3", 11);
INSERT INTO areas(surface_id, area_name, size) VALUES (2, "Pole Stefana, obszar 4", 8);
INSERT INTO areas(surface_id, area_name, size) VALUES (2, "Pole Stefana, obszar 5", 16);
INSERT INTO areas(surface_id, area_name, size) VALUES (2, "Pole Stefana, obszar 6", 19);
INSERT INTO areas(surface_id, area_name, size) VALUES (3, "Pole Franka, obszar 1", 40);
INSERT INTO areas(surface_id, area_name, size) VALUES (3, "Pole Franka, obszar 2", 20);


-- resut <=> done experiment 
CREATE TABLE results (
    result_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    experiment_name VARCHAR(100) NOT NULL,
    area_id INT NOT NULL,
    plant_id INT NOT NULL,
    fertilizer_id INT NOT NULL,
    plants_quantity INT NOT NULL,
    quantity_ratio DECIMAL(5,2) NOT NULL,
    average_size DECIMAL(5,2) NOT NULL,
    plants_quality DECIMAL(3,2) NOT NULL,
    finish_date TIMESTAMP
)
CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

INSERT INTO results (experiment_name, area_id, plant_id, fertilizer_id, plants_quantity,
    quantity_ratio, average_size, plants_quality) VALUES ("Doświadczenie 001",1,1,1,119,119/10,15.42,7.66);
INSERT INTO results (experiment_name, area_id, plant_id, fertilizer_id, plants_quantity,
    quantity_ratio, average_size, plants_quality) VALUES ("Doświadczenie 002",3,5,4,218,218/5,47.17,5.28);
INSERT INTO results (experiment_name, area_id, plant_id, fertilizer_id, plants_quantity,
    quantity_ratio, average_size, plants_quality) VALUES ("Doświadczenie 003",2,2,2,157,157/15,39.50,6.46);
INSERT INTO results (experiment_name, area_id, plant_id, fertilizer_id, plants_quantity,
    quantity_ratio, average_size, plants_quality) VALUES ("Doświadczenie 004",3,2,1,74,74/15,15.45,5.01);
INSERT INTO results (experiment_name, area_id, plant_id, fertilizer_id, plants_quantity,
    quantity_ratio, average_size, plants_quality) VALUES ("Doświadczenie 005",9,7,5,132,132/16,28.22,9.38);

CREATE TABLE experiments (
    experiment_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    experiment_name VARCHAR(100) NOT NULL,
    area_id INT NOT NULL,
    plant_id INT NOT NULL,
    fertilizer_id INT NOT NULL,
    is_done BOOL,
    create_date TIMESTAMP
)
CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

INSERT INTO experiments (experiment_name, area_id, plant_id, fertilizer_id, is_done)
    VALUES ("Doswiadczenie 1", 1, 1, 1, false);
INSERT INTO experiments (experiment_name, area_id, plant_id, fertilizer_id, is_done)
    VALUES ("Doswiadczenie 2", 2, 1, 2, false);
INSERT INTO experiments (experiment_name, area_id, plant_id, fertilizer_id, is_done)
    VALUES ("Doswiadczenie 3", 3, 1, 3, false);
INSERT INTO experiments (experiment_name, area_id, plant_id, fertilizer_id, is_done)
    VALUES ("Doswiadczenie 4", 4, 1, 4, false);
INSERT INTO experiments (experiment_name, area_id, plant_id, fertilizer_id, is_done)
    VALUES ("Doswiadczenie 5", 5, 1, 5, false);
INSERT INTO experiments (experiment_name, area_id, plant_id, fertilizer_id, is_done)
    VALUES ("Doswiadczenie 6", 6, 2, 1, false);
INSERT INTO experiments (experiment_name, area_id, plant_id, fertilizer_id, is_done)
    VALUES ("Doswiadczenie 7", 7, 2, 2, false);
INSERT INTO experiments (experiment_name, area_id, plant_id, fertilizer_id, is_done)
    VALUES ("Doswiadczenie 8", 8, 2, 3, false);
INSERT INTO experiments (experiment_name, area_id, plant_id, fertilizer_id, is_done)
    VALUES ("Doswiadczenie 9", 9, 2, 4, false);
INSERT INTO experiments (experiment_name, area_id, plant_id, fertilizer_id, is_done)
    VALUES ("Doswiadczenie 10", 10, 2, 5, false);


CREATE TABLE samples (
    sample_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    experiment_id INT NOT NULL,
    laborant_id INT NOT NULL,
    sample_name VARCHAR(100) NOT NULL,
    quantity INT NOT NULL,
    size DECIMAL(4,2) NOT NULL,
    quality INT NOT NULL,
    create_date TIMESTAMP
)
CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

INSERT INTO samples (experiment_id, laborant_id, sample_name, quantity,
    size, quality) VALUES(1, 1, "probka 1.1", 3, 9.50, 8);
INSERT INTO samples (experiment_id, laborant_id, sample_name, quantity,
    size, quality) VALUES(1, 2, "probka 1.2", 1, 9.27, 5);
INSERT INTO samples (experiment_id, laborant_id, sample_name, quantity,
    size, quality) VALUES(2, 5, "probka 2.1", 1, 7.98, 6);
INSERT INTO samples (experiment_id, laborant_id, sample_name, quantity,
    size, quality) VALUES(2, 5, "probka 2.2", 2, 20.63, 1);
INSERT INTO samples (experiment_id, laborant_id, sample_name, quantity,
    size, quality) VALUES(2, 4, "probka 2.3", 5, 6.45, 5);
INSERT INTO samples (experiment_id, laborant_id, sample_name, quantity,
    size, quality) VALUES(3, 1, "probka 3.1", 1, 13.57, 9);
INSERT INTO samples (experiment_id, laborant_id, sample_name, quantity,
    size, quality) VALUES(3, 8, "probka 3.2", 6, 2.74, 2);
INSERT INTO samples (experiment_id, laborant_id, sample_name, quantity,
    size, quality) VALUES(3, 7, "probka 3.3", 3, 1.52, 10);
INSERT INTO samples (experiment_id, laborant_id, sample_name, quantity,
    size, quality) VALUES(3, 10, "probka 3.4", 1, 16.61, 4);
INSERT INTO samples (experiment_id, laborant_id, sample_name, quantity,
    size, quality) VALUES(3, 2, "probka 3.5", 1, 8.70, 8);
INSERT INTO samples (experiment_id, laborant_id, sample_name, quantity,
    size, quality) VALUES(3, 1, "probka 3.6", 5, 9.34, 1);
INSERT INTO samples (experiment_id, laborant_id, sample_name, quantity,
    size, quality) VALUES(3, 6, "probka 3.7", 2, 15.43, 2);
INSERT INTO samples (experiment_id, laborant_id, sample_name, quantity,
    size, quality) VALUES(4, 5, "probka 4.1", 3, 6.42, 10);
INSERT INTO samples (experiment_id, laborant_id, sample_name, quantity,
    size, quality) VALUES(5, 1, "probka 5.1", 3, 19.24, 2);
INSERT INTO samples (experiment_id, laborant_id, sample_name, quantity,
    size, quality) VALUES(6, 6, "probka 6.1", 4, 16.22, 3);
INSERT INTO samples (experiment_id, laborant_id, sample_name, quantity,
    size, quality) VALUES(6, 10, "probka 6.2", 5, 11.83, 4);
INSERT INTO samples (experiment_id, laborant_id, sample_name, quantity,
    size, quality) VALUES(7, 2, "probka 7.1", 2, 7.80, 8);
INSERT INTO samples (experiment_id, laborant_id, sample_name, quantity,
    size, quality) VALUES(7, 3, "probka 7.2", 1, 13.82, 5);
INSERT INTO samples (experiment_id, laborant_id, sample_name, quantity,
    size, quality) VALUES(7, 4, "probka 7.3", 1, 5.94, 7);
INSERT INTO samples (experiment_id, laborant_id, sample_name, quantity,
    size, quality) VALUES(7, 5, "probka 7.4", 7, 11.10, 8);
INSERT INTO samples (experiment_id, laborant_id, sample_name, quantity,
    size, quality) VALUES(7, 6, "probka 7.5", 2, 2.01, 6);
