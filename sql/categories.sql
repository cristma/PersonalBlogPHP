CREATE TABLE `categories` (
	`id`          INT(10)     NOT NULL AUTO_INCREMENT, 
	`name`        VARCHAR(64) NOT NULL DEFAULT 'Unnamed Category', 
	`description` TEXT        NOT NULL, 

	PRIMARY KEY(`id`), 
	UNIQUE  KEY(`name`));