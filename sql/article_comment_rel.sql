CREATE TABLE `article_comment_rel` (
	`id`      INT(10) NOT NULL AUTO_INCREMENT, 
	`article` INT(10) NOT NULL, 
	`comment` INT(10) NOT NULL, 
	
	FOREIGN KEY(`article`) REFERENCES `articles`(`id`) ON DELETE CASCADE, 
	FOREIGN KEY(`comment`) REFERENCES `comments`(`id`) ON DELETE CASCADE, 
	UNIQUE  KEY(`comment`), 
	PRIMARY KEY(`id`));
	
CREATE INDEX `comment_by_article` ON `article_comment_rel`(`article`);
CREATE UNIQUE INDEX `article_by_comment` ON `article_comment_rel`(`comment`);