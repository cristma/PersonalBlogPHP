CREATE TABLE `post_comment_rel` (
	`id`      INT(10) NOT NULL AUTO_INCREMENT, 
	`post`    INT(10) NOT NULL, 
	`comment` INT(10) NOT NULL, 
	
	FOREIGN KEY(`post`)    REFERENCES `posts`(`id`)    ON DELETE CASCADE, 
	FOREIGN KEY(`comment`) REFERENCES `comments`(`id`) ON DELETE CASCADE, 
	UNIQUE  KEY(`comment`), 
	PRIMARY KEY(`id`));
	
CREATE INDEX `comment_by_post` ON `post_comment_rel`(`post`);
CREATE UNIQUE INDEX `post_by_comment` ON `post_comment_rel`(`comment`);