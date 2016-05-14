CREATE TABLE `amendments` (
	`id`      INT(10)   NOT NULL AUTO_INCREMENT, 
	`content` TEXT      NOT NULL, 
	`author`  INT(10)   NOT NULL, 
	`date`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 

	FOREIGN KEY(`author`) REFERENCES `users`(`id`) ON DELETE CASCADE, 
	PRIMARY KEY(`id`));

CREATE INDEX `amendments_by_author` ON `amendments`(`author`);
CREATE INDEX `amendments_by_date`   ON `amendments`(`date`);