PRAGMA foreign_keys = ON;
-- -----------------------------------------------------
-- Table `Usuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Especie` ;

CREATE TABLE IF NOT EXISTS `Especie` (
  `id` INTEGER NOT NULL,
  `nome` CHAR(45) NULL,
  PRIMARY KEY (`id`)
);

-- Constants
INSERT INTO `Especie` (`nome`) VALUES ('cachorro'), ('gato'), ('outro');

-- -----------------------------------------------------
-- Table `Usuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Usuario` ;

CREATE TABLE IF NOT EXISTS `Usuario` (
  `id` INTEGER NOT NULL,
  `nome` CHAR(90) NOT NULL,
  `email` CHAR(90) NULL,
  `senha` CHAR(90) NOT NULL,
  `localizacao` CHAR(45) NULL,
  PRIMARY KEY (`id`)
);

-- Exemplo
-- INSERT INTO `Usuario` (`nome`,`email`,`senha`,`localizacao`) VALUES ('usuario1', 'usuario1@example.com', 'senha1', '-20.341164,-40.313314');

-- Constants
INSERT INTO `Usuario` (`nome`,`email`,`senha`,`localizacao`) VALUES ('usuario1', 'usuario1@gmail.com', 'senha1', '-20.341164,-40.313314');

-- -----------------------------------------------------
-- Table `Animal`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Animal` ;

CREATE TABLE IF NOT EXISTS `Animal` (
  `id` INTEGER NOT NULL,
  `nome` CHAR(45) NULL,
  `especie_id` INTEGER NULL,
  `usuario_id` INTEGER NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY(`especie_id`) REFERENCES `Especie`(`id`) ON DELETE CASCADE,
  FOREIGN KEY(`usuario_id`) REFERENCES `Usuario`(`id`) ON DELETE CASCADE
);

-- Exemplo
-- INSERT INTO `Animal` (`nome`,`especie_id`,`usuario_id`) VALUES ('pet1', 1, 1);

-- -----------------------------------------------------
-- Table `AnimalPerdido`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Perdido` ;

CREATE TABLE IF NOT EXISTS `Perdido` (
  `animal_id` INTEGER NOT NULL,
  `ultimaLocalizacao` CHAR(45) NULL,
  `observacao` CHAR(512) NULL,
  PRIMARY KEY (`animal_id`),
  FOREIGN KEY(`animal_id`) REFERENCES `Animal`(`id`) ON DELETE CASCADE
);

-- Exemplo
-- INSERT INTO `Perdido` (`animal_id`,`ultimaLocalizacao`,`observacao`) VALUES (1, '-20.341164,-40.313314', 'Pet foi perdido durante passeio');

-- -----------------------------------------------------
-- Table `Adocao`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Adocao` ;

CREATE TABLE IF NOT EXISTS `Adocao` (
  `animal_id` INTEGER NOT NULL,
  `observacao` CHAR(512) NULL,
  PRIMARY KEY (`animal_id`),
  FOREIGN KEY(`animal_id`) REFERENCES `Animal`(`id`) ON DELETE CASCADE
);

-- Exemplo
-- INSERT INTO `Adocao` (`animal_id`,`observacao`) VALUES (1, 'Pet foi abandonado');

