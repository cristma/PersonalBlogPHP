CREATE TABLE `article_ratings` (
	`id`      INT(100) NOT NULL AUTO_INCREMENT, 
	`user`    INT(10)  NOT NULL, 
	`article` INT(10)  NOT NULL, 
	`value`   INT(1)   NOT NULL, 
	
	FOREIGN KEY(`article`) REFERENCES `articles`(`id`) ON DELETE CASCADE, 
	FOREIGN KEY(`user`)    REFERENCES `users`(`id`)    ON DELETE CASCADE, 
	PRIMARY KEY(`id`), 
	UNIQUE  KEY(`user`, `article`));
	
CREATE INDEX `rating_by_article` ON `article_ratings`(`article`);
CREATE INDEX `rating_by_user`    ON `article_ratings`(`user`);