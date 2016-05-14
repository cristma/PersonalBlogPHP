CREATE TABLE `user_access_rel` (
	`id`     INT(10) NOT NULL AUTO_INCREMENT, 
	`user`   INT(10) NOT NULL, 
	`access` INT(10) NOT NULL, 
	
	FOREIGN KEY(`user`)   REFERENCES `users`(`id`)  ON DELETE CASCADE, 
	FOREIGN KEY(`access`) REFERENCES `access`(`id`) ON DELETE CASCADE, 
	PRIMARY KEY(`id`));
	
CREATE INDEX `access_by_user` ON `user_access_rel`(`user`);
CREATE INDEX `user_by_access` ON `user_access_rel`(`access`);