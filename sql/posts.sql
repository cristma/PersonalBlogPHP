CREATE TABLE `posts` (
	`id`      INT(10)      NOT NULL AUTO_INCREMENT, 
	`title`   VARCHAR(100) NOT NULL DEFAULT 'Untitled Post', 
	`content` TEXT         NOT NULL, 
	`author`  INT(10)      NOT NULL, 
	`date`    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP, 
	
	FOREIGN KEY(`author`) REFERENCES `users`(`id`) ON DELETE CASCADE, 
	PRIMARY KEY(`id`));
	
CREATE INDEX `posts_by_author` ON `posts`(`author`);
CREATE INDEX `posts_by_date`   ON `posts`(`date`);