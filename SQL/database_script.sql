-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema ucl_database
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema ucl_database
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `ucl_database` DEFAULT CHARACTER SET latin1 ;
USE `ucl_database` ;

-- -----------------------------------------------------
-- Table `ucl_database`.`privacy_setting_option`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ucl_database`.`privacy_setting_option` (
  `description` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`description`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `ucl_database`.`privacy_setting`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ucl_database`.`privacy_setting` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `description` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `privacy_settings_id_UNIQUE` (`id` ASC),
  INDEX `privacy_setting_option_fk_idx` (`description` ASC),
  CONSTRAINT `privacy_setting_option_fk`
    FOREIGN KEY (`description`)
    REFERENCES `ucl_database`.`privacy_setting_option` (`description`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `ucl_database`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ucl_database`.`user` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `email_address` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `privacy_setting_fk` INT(10) UNSIGNED NULL DEFAULT '2',
  `initial_registration` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `lockout_timer` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `email_address_UNIQUE` (`email_address` ASC),
  INDEX `user_privacy_setting_fk_idx` (`privacy_setting_fk` ASC),
  CONSTRAINT `user_setting_fk`
    FOREIGN KEY (`privacy_setting_fk`)
    REFERENCES `ucl_database`.`privacy_setting` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 30
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `ucl_database`.`photo_album`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ucl_database`.`photo_album` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_owner` INT(10) UNSIGNED NOT NULL,
  `privacy_setting` INT(10) UNSIGNED NOT NULL,
  `name` VARCHAR(45) NULL DEFAULT NULL,
  `description` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `album_privacy_fk_idx` (`privacy_setting` ASC),
  INDEX `album_owner_fk_idx` (`user_owner` ASC),
  CONSTRAINT `album_owner_fk`
    FOREIGN KEY (`user_owner`)
    REFERENCES `ucl_database`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `album_privacy_fk`
    FOREIGN KEY (`privacy_setting`)
    REFERENCES `ucl_database`.`privacy_setting` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 14
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `ucl_database`.`photo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ucl_database`.`photo` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `album_id` INT(10) UNSIGNED NOT NULL,
  `timestamp` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `description` VARCHAR(255) NULL DEFAULT NULL,
  `path` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `photo_collection_fk_idx` (`album_id` ASC),
  CONSTRAINT `photo_collection_fk`
    FOREIGN KEY (`album_id`)
    REFERENCES `ucl_database`.`photo_album` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 22
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `ucl_database`.`annotation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ucl_database`.`annotation` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `photo_id` INT(10) UNSIGNED NOT NULL,
  `user_id` INT(10) UNSIGNED NOT NULL,
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `annotation_photo_fk_idx` (`photo_id` ASC),
  INDEX `annotation_user_fk_idx` (`user_id` ASC),
  CONSTRAINT `annotation_photo_fk`
    FOREIGN KEY (`photo_id`)
    REFERENCES `ucl_database`.`photo` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `annotation_user_fk`
    FOREIGN KEY (`user_id`)
    REFERENCES `ucl_database`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 11
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `ucl_database`.`blog_post`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ucl_database`.`blog_post` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `timestamp` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `blog_owner_id` INT(10) UNSIGNED NOT NULL,
  `content` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `blog_owner_fk_idx` (`blog_owner_id` ASC),
  CONSTRAINT `blog_owner_fk`
    FOREIGN KEY (`blog_owner_id`)
    REFERENCES `ucl_database`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 63
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `ucl_database`.`circle`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ucl_database`.`circle` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `creator` INT(10) UNSIGNED NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `description` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `circle_creator_fk_idx` (`creator` ASC),
  CONSTRAINT `circle_creator_fk`
    FOREIGN KEY (`creator`)
    REFERENCES `ucl_database`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 10
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `ucl_database`.`circle_membership`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ucl_database`.`circle_membership` (
  `circle_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`circle_id`, `user_id`),
  INDEX `circle_member_user_fk_idx` (`user_id` ASC),
  CONSTRAINT `circle_id_fk`
    FOREIGN KEY (`circle_id`)
    REFERENCES `ucl_database`.`circle` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `circle_member_user_fk`
    FOREIGN KEY (`user_id`)
    REFERENCES `ucl_database`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 10
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `ucl_database`.`circle_message`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ucl_database`.`circle_message` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sender_user_id` INT(10) UNSIGNED NOT NULL,
  `receiver_circle_id` INT(10) UNSIGNED NOT NULL,
  `content` VARCHAR(255) NOT NULL,
  `timestamp` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `sender_circle_fk_idx` (`sender_user_id` ASC),
  INDEX `receiver_circle_fk_idx` (`receiver_circle_id` ASC),
  CONSTRAINT `receiver_circle_fk`
    FOREIGN KEY (`receiver_circle_id`)
    REFERENCES `ucl_database`.`circle` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `sender_circle_fk`
    FOREIGN KEY (`sender_user_id`)
    REFERENCES `ucl_database`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 38
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `ucl_database`.`friendship`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ucl_database`.`friendship` (
  `user_1` INT(10) UNSIGNED NOT NULL,
  `user_2` INT(10) UNSIGNED NOT NULL,
  `status` INT(10) UNSIGNED NULL DEFAULT '0',
  `timestamp` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_1`, `user_2`),
  INDEX `user_2_fk_idx` (`user_2` ASC),
  CONSTRAINT `user_1_fk`
    FOREIGN KEY (`user_1`)
    REFERENCES `ucl_database`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `user_2_fk`
    FOREIGN KEY (`user_2`)
    REFERENCES `ucl_database`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `ucl_database`.`login_attempt`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ucl_database`.`login_attempt` (
  `attempt_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ipaddress` VARCHAR(45) NULL DEFAULT NULL,
  `success` INT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`attempt_id`),
  UNIQUE INDEX `attempt_id_UNIQUE` (`attempt_id` ASC),
  INDEX `login_attempt_user_fk_idx` (`user_id` ASC),
  CONSTRAINT `login_attempt_user_fk`
    FOREIGN KEY (`user_id`)
    REFERENCES `ucl_database`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 120
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `ucl_database`.`notification`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ucl_database`.`notification` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type_of_change` INT(2) UNSIGNED NOT NULL,
  `user_id` INT(10) UNSIGNED NOT NULL,
  `changer_id` INT(10) UNSIGNED NOT NULL,
  `change_location` VARCHAR(50) NULL DEFAULT NULL,
  `clicked` INT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `notification_user_fk_idx` (`user_id` ASC),
  INDEX `notification_changer_fk_idx` (`changer_id` ASC),
  CONSTRAINT `notification_changer_fk`
    FOREIGN KEY (`changer_id`)
    REFERENCES `ucl_database`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `notification_user_fk`
    FOREIGN KEY (`user_id`)
    REFERENCES `ucl_database`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `ucl_database`.`photo_comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ucl_database`.`photo_comment` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `photo_id` INT(10) UNSIGNED NOT NULL,
  `content` VARCHAR(255) NOT NULL,
  `timestamp` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `commenter` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `comment_photo_fk_idx` (`photo_id` ASC),
  INDEX `comment_user_fk_idx` (`commenter` ASC),
  CONSTRAINT `comment_photo_fk`
    FOREIGN KEY (`photo_id`)
    REFERENCES `ucl_database`.`photo` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `comment_user_fk`
    FOREIGN KEY (`commenter`)
    REFERENCES `ucl_database`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 10
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `ucl_database`.`session`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ucl_database`.`session` (
  `user_id` INT(10) UNSIGNED NOT NULL,
  `session_key` VARCHAR(255) NOT NULL,
  `timeout` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE INDEX `user_id_UNIQUE` (`user_id` ASC),
  CONSTRAINT `session_user_fk`
    FOREIGN KEY (`user_id`)
    REFERENCES `ucl_database`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
