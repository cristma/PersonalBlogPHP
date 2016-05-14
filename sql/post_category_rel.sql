CREATE TABLE `post_category_rel` (
	`id`       INT(10) NOT NULL AUTO_INCREMENT, 
	`post`     INT(10) NOT NULL, 
	`category` INT(10) NOT NULL, 
	
	FOREIGN KEY(`post`)     REFERENCES `posts`(`id`)      ON DELETE CASCADE, 
	FOREIGN KEY(`category`) REFERENCES `categories`(`id`) ON DELETE CASCADE, 
	PRIMARY KEY(`id`));
	
CREATE INDEX `post_by_category` ON `post_category_rel`(`category`);
CREATE INDEX `category_by_post` ON `post_category_rel`(`post`);