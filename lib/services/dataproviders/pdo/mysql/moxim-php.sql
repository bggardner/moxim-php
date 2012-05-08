CREATE TABLE IF NOT EXISTS `moxim_modules`
( `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`name`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `moxim_relations`
( `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `domain` INT(10) UNSIGNED NOT NULL,
  `name` VARCHAR(32) NOT NULL,
  `range` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`domain`,`name`,`range`),
  KEY (`domain`),
  KEY (`range`),
  FOREIGN KEY (`domain`)
    REFERENCES `moxim_modules` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (`range`)
    REFERENCES `moxim_modules` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `moxim_relationships`
( `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `domain` INT(10) UNSIGNED NOT NULL,
  `relation` INT(10) UNSIGNED NOT NULL,
  `range` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`domain`,`relation`,`range`),
  KEY (`relation`),
  FOREIGN KEY (`relation`)
    REFERENCES `moxim_relations` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `moxim_assignments`
( `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `module` INT(10) UNSIGNED NOT NULL,
  `node` INT(10) UNSIGNED NOT NULL,
  `value` VARCHAR(255),
  PRIMARY KEY (`id`),
  KEY (`module`),
  FOREIGN KEY (`module`)
    REFERENCES `moxim_modules` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;
  
INSERT INTO `moxim_modules` ( `id`, `name`) VALUES (1, 'moxim_modules')
  ON DUPLICATE KEY UPDATE `name` = 'moxim_modules';
INSERT INTO `moxim_modules` ( `id`, `name`) VALUES (2, 'moxim_relations')
  ON DUPLICATE KEY UPDATE `name` = 'moxim_relations';
INSERT INTO `moxim_modules` ( `id`, `name`) VALUES (3, 'moxim_relationships')
  ON DUPLICATE KEY UPDATE `name` = 'moxim_relationships';
INSERT INTO `moxim_modules` ( `id`, `name`) VALUES (4, 'moxim_assignments')
  ON DUPLICATE KEY UPDATE `name` = 'moxim_assignments';
