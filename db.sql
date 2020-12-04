CREATE SCHEMA IF NOT EXISTS `calendar_db` DEFAULT CHARACTER SET UTF8MB4 ;
USE `calendar_db` ;

-- -----------------------------------------------------
-- Table `calendar_db`.`locations`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `calendar_db`.`locations` (
  `cityid` INT NOT NULL,
  `cityname` VARCHAR(60) NOT NULL,
  `state` VARCHAR(60) NULL DEFAULT NULL,
  `country` VARCHAR(60) NULL DEFAULT NULL,
  PRIMARY KEY (`cityid`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = UTF8MB4;


-- -----------------------------------------------------
-- Table `calendar_db`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `calendar_db`.`users` (
  `userid` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `password` VARCHAR(256) NOT NULL,
  `location` INT NULL DEFAULT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC) VISIBLE,
  INDEX `location_idx` (`location` ASC) VISIBLE,
  CONSTRAINT `location`
    FOREIGN KEY (`location`)
    REFERENCES `calendar_db`.`locations` (`cityid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = UTF8MB4
COMMENT = 'Table for all registered users';


-- -----------------------------------------------------
-- Table `calendar_db`.`diaries`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `calendar_db`.`diaries` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `submitted_time` DATETIME NOT NULL,
  `edit_time` DATETIME NULL DEFAULT NULL,
  `content` VARCHAR(2000) NULL DEFAULT NULL,
  `visibility` TINYINT NOT NULL,
  `userid` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `d_user_idx` (`userid` ASC) VISIBLE,
  CONSTRAINT `d_user`
    FOREIGN KEY (`userid`)
    REFERENCES `calendar_db`.`users` (`userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = UTF8MB4;


-- -----------------------------------------------------
-- Table `calendar_db`.`events`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `calendar_db`.`events` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `location` VARCHAR(256) NULL DEFAULT NULL,
  `start_time` DATETIME NOT NULL,
  `end_time` DATETIME NOT NULL,
  `notes` VARCHAR(1000) NULL DEFAULT NULL,
  `userid` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `userid_idx` (`userid` ASC) VISIBLE,
  CONSTRAINT `userid`
    FOREIGN KEY (`userid`)
    REFERENCES `calendar_db`.`users` (`userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = UTF8MB4
COMMENT = 'timed events';


-- -----------------------------------------------------
-- Table `calendar_db`.`reminders`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `calendar_db`.`reminders` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `deadline` DATE NULL DEFAULT NULL,
  `notes` VARCHAR(1000) NULL DEFAULT NULL,
  `userid` INT NOT NULL,
  `finished` TINYINT NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `userid_idx` (`userid` ASC) VISIBLE,
  CONSTRAINT `rm_userid`
    FOREIGN KEY (`userid`)
    REFERENCES `calendar_db`.`users` (`userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = UTF8MB4
COMMENT = 'all to do items';