CREATE TABLE `comments` (
	`id`      INT(10) NOT NULL AUTO_INCREMENT, 
	`content` TEXT    NOT NULL, 
	`author`  INT(10) NOT NULL,  

	FOREIGN KEY(`author`) REFERENCES `users`(`id`) ON DELETE CASCADE, 
	PRIMARY KEY(`id`));
	
CREATE INDEX `comments_by_author` ON `comments`(`author`);