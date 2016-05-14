CREATE TABLE `article_amendment_rel` (
	`id`        INT(10) NOT NULL AUTO_INCREMENT, 
	`article`   INT(10) NOT NULL, 
	`amendment` INT(10) NOT NULL, 
	
	FOREIGN KEY(`article`)   REFERENCES `articles`(`id`)   ON DELETE CASCADE, 
	FOREIGN KEY(`amendment`) REFERENCES `amendments`(`id`) ON DELETE CASCADE, 
	UNIQUE  KEY(`amendment`), 
	PRIMARY KEY(`id`));
	
CREATE INDEX `amendment_by_article` ON `article_amendment_rel`(`article`);
CREATE UNIQUE INDEX `article_by_amendment` ON `article_amendment_rel`(`amendment`);