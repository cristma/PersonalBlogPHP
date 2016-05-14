CREATE TABLE `articles` (
	`id`        INT(10)     NOT NULL AUTO_INCREMENT, 
	`title`     VARCHAR(64) NOT NULL DEFAULT 'Untitled Article', 
	`excerpt`   TEXT        NOT NULL, 
	`content`   TEXT        NOT NULL, 
	`timestamp` TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP, 
	`author`    INT(10)     NOT NULL, 
	`published` INT(1)      NOT NULL DEFAULT 0, 
	
	FOREIGN KEY(`author`) REFERENCES `users`(`id`) ON DELETE CASCADE, 
	PRIMARY KEY(`id`));
	
CREATE INDEX `article_by_author`  ON `articles`(`author`);
CREATE INDEX `article_by_publish` ON `articles`(`published`);