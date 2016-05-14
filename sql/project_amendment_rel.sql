CREATE TABLE `project_amendment_rel` (
	`id`        INT(10) NOT NULL AUTO_INCREMENT, 
	`project`   INT(10) NOT NULL, 
	`amendment` INT(10) NOT NULL, 
	
	FOREIGN KEY(`project`)   REFERENCES `projects`(`id`)   ON DELETE CASCADE, 
	FOREIGN KEY(`amendment`) REFERENCES `amendments`(`id`) ON DELETE CASCADE, 
	UNIQUE  KEY(`amendment`), 
	PRIMARY KEY(`id`));
	
CREATE INDEX `amendment_by_project` ON `project_amendment_rel`(`project`);
CREATE UNIQUE INDEX `project_by_amendment` ON `project_amendment_rel`(`amendment`);