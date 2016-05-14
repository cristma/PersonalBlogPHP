CREATE TABLE `post_amendment_rel` (
	`id`        INT(10) NOT NULL AUTO_INCREMENT, 
	`post`      INT(10) NOT NULL, 
	`amendment` INT(10) NOT NULL, 
	
	FOREIGN KEY(`post`)      REFERENCES `posts`(`id`)      ON DELETE CASCADE, 
	FOREIGN KEY(`amendment`) REFERENCES `amendments`(`id`) ON DELETE CASCADE, 
	UNIQUE  KEY(`amendment`), 
	PRIMARY KEY(`id`));
	
CREATE INDEX `amendment_by_post` ON `post_amendment_rel`(`post`);
CREATE UNIQUE INDEX `post_by_amendment` ON `post_amendment_rel`(`amendment`);