CREATE TABLE `project_ratings` (
	`id` INT(100) NOT NULL AUTO_INCREMENT, 
	`project` INT(10) NOT NULL, 
	`user` INT(10) NOT NULL, 
	`value` INT(1) NOT NULL DEFAULT 0, 
	
	FOREIGN KEY(`project`) REFERENCES `projects`(`id`) ON DELETE CASCADE, 
	FOREIGN KEY(`user`)    REFERENCES `users`(`id`)    ON DELETE CASCADE, 
	PRIMARY KEY(`id`), 
	UNIQUE KEY(`project`, `user`));
	
CREATE INDEX `rating_by_project` ON `project_ratings`(`project`);
CREATE INDEX `rating_by_user`    ON `project_ratings`(`user`);