CREATE DATABASE csapi;
USE csapi;

CREATE TABLE `usuarios` (
 `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
 `nombre` varchar(40) NOT NULL,
 `apellidos` varchar(40) NOT NULL,
 `email` varchar(255) NOT NULL,
 `password` varchar(255) NOT NULL,
 `rol` varchar(10) NOT NULL,
 `confirmado` tinyint(1) DEFAULT 0,
 `token` varchar(255) DEFAULT NULL,
 `token_exp` timestamp NULL DEFAULT NULL,
 PRIMARY KEY (`id`),
 UNIQUE KEY `uq_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `skins` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `nombre` varchar(255) NOT NULL,
 `tipo` varchar(50) NOT NULL,
 `imagen` varchar(255) DEFAULT NULL,
 `desgaste` float NOT NULL,
 `precio` varchar(255) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO skins (nombre, tipo, imagen, desgaste, precio)
VALUES ('Atheris', 'AWP', 'https://community.akamai.steamstatic.com/economy/image/-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpot621FAR17PLfYQJU5cyzhr-GkvP9JrbummpD78A_3-vE9I6t0Afir0JuMWnxIdKRJAZvZF-E_FLsyLruhsS8ucmcz3Vmvj5iuygKH_-NNA/360fx360f', 0.5, '8.00');

-- Ejemplo de INSERT para agregar otro producto
INSERT INTO skins (nombre, tipo, imagen, desgaste, precio)
VALUES ('PrintStream', 'Desert Eagle', 'https://community.akamai.steamstatic.com/economy/image/-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgposr-kLAtl7PDdTjlH7duJhJKCmePnJ6nUl2Zu5cB1g_zMyoD0mlOx5UJpYjj2d9LAdAI5YlqE-Vm_wuy715Xvv5iby3prs3IjtHrVmEez0xhSLrs4cktNb_c/360fx360f', 0.75, '80.50');

-- Otro ejemplo de INSERT para un tercer producto
INSERT INTO skins (nombre, tipo, imagen, desgaste, precio)
VALUES ('Asiimov', 'AK-47', 'https://community.akamai.steamstatic.com/economy/image/-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpot7HxfDhjxszJemkV092lnYmGmOHLPr7Vn35c18lwmO7Eu92milbl-BZsZGiiLNKdJFc8Mg7V_1S_xuzshZK97c_In3pruCJx4X_D30vgyZM--n4/360fx360f', 0.6, '24.99');
