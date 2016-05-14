CREATE TABLE `article_category_rel` (
	`id` INT(10) NOT NULL AUTO_INCREMENT, 
	`article` INT(10) NOT NULL, 
	`category` INT(10) NOT NULL, 
	
	FOREIGN KEY(`article`) REFERENCES `articles`(`id`) ON DELETE CASCADE, 
	FOREIGN KEY(`category`) REFERENCES `categories`(`id`) ON DELETE CASCADE, 
	PRIMARY KEY(`id`));
	
CREATE INDEX `article_by_category` ON `article_category_rel`(`category`);
CREATE INDEX `category_by_article` ON `article_category_rel`(`article`);