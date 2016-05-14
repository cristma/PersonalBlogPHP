CREATE TABLE `post_ratings` (
	`id` INT(100) NOT NULL AUTO_INCREMENT, 
	`user` INT(10) NOT NULL, 
	`post` INT(10) NOT NULL, 
	`value` INT(1) NOT NULL DEFAULT 0, 
	
	FOREIGN KEY(`user`) REFERENCES `users`(`id`) ON DELETE CASCADE, 
	FOREIGN KEY(`post`) REFERENCES `posts`(`id`) ON DELETE CASCADE, 
	UNIQUE  KEY(`user`, `post`), 
	PRIMARY KEY(`id`));
	
CREATE INDEX `rating_by_post` ON `post_ratings`(`post`);
CREATE INDEX `rating_by_user` ON `post_ratings`(`user`);