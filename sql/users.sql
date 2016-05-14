CREATE TABLE `users` (
	`id`       INT(10)     NOT NULL AUTO_INCREMENT, 
	`username` VARCHAR(64) NOT NULL, 
	`password` VARCHAR(64) NOT NULL, 
	`active`   INT(1)      NOT NULL DEFAULT 0, 
	`rem_addr` VARCHAR(64) NOT NULL DEFAULT '', 
	
	UNIQUE  KEY(`username`), 
	PRIMARY KEY(`id`));
	
/* Indexed values for quicker searching... */
CREATE UNIQUE INDEX `users_by_id` ON `users`(`id`);
CREATE UNIQUE INDEX `users_by_username` ON `users`(`username`);