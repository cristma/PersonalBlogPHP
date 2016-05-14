/******************************************************************
 * SAMPLE DATA
 ******************************************************************/
/* Access Rights */
insert into `access` (`name`) values ('Create Post');                /* 1  */
insert into `access` (`name`) values ('Delete Post');                /* 2  */
insert into `access` (`name`) values ('Modify Post');                /* 3  */
insert into `access` (`name`) values ('Read Published Post');        /* 4  */
insert into `access` (`name`) values ('Create Article');             /* 5  */
insert into `access` (`name`) values ('Delete Article');             /* 6  */
insert into `access` (`name`) values ('Modify Article');             /* 7  */
insert into `access` (`name`) values ('Read Published Article');     /* 8  */
insert into `access` (`name`) values ('Read Unpublished Article');   /* 9  */
insert into `access` (`name`) values ('Create Project');             /* 10 */
insert into `access` (`name`) values ('Delete Project');             /* 11 */
insert into `access` (`name`) values ('Modify Project');             /* 12 */
insert into `access` (`name`) values ('Read Published Project');     /* 13 */
insert into `access` (`name`) values ('Read Unpublished Project');   /* 14 */
insert into `access` (`name`) values ('Create Comment');             /* 15 */
insert into `access` (`name`) values ('Delete Comment');             /* 16 */
insert into `access` (`name`) values ('Modify Comment');             /* 17 */
insert into `access` (`name`) values ('Read Comment');               /* 18 */
insert into `access` (`name`) values ('Assign Privs');               /* 19 */
insert into `access` (`name`) values ('Read Logs');                  /* 20 */

/* Users */
insert into `users` (`username`, `password`, `active`) values ('admin',    md5('simulation'), 1);
insert into `users` (`username`, `password`, `active`) values ('mod',      md5('simulation'), 1);
insert into `users` (`username`, `password`, `active`) values ('user',     md5('simulation'), 1);
insert into `users` (`username`, `password`, `active`) values ('banned',   md5('simulation'), 1);
insert into `users` (`username`, `password`, `active`) values ('inactive', md5('simulation'), 0);

/* Profiles */
insert into `profiles` (`fname`, `lname`, `email`, `user`) values ('Admin', 'User', 'mcrist1@cox.net', 1);
insert into `profiles` (`fname`, `lname`, `email`, `user`) values ('Mod', 'User', 'none1@none.com', 2);
insert into `profiles` (`fname`, `lname`, `email`, `user`) values ('Standard', 'User', 'none2@none.com', 3);
insert into `profiles` (`fname`, `lname`, `email`, `user`) values ('Banned', 'User', 'none3@none.com', 4);
insert into `profiles` (`fname`, `lname`, `email`, `user`) values ('Inactive', 'User', 'none4@none.com', 5);

/* User-Access Relations */
/* Administrator Template */
insert into `user_access_rel` (`user`, `access`) values (1, 1);
insert into `user_access_rel` (`user`, `access`) values (1, 2);
insert into `user_access_rel` (`user`, `access`) values (1, 3);
insert into `user_access_rel` (`user`, `access`) values (1, 4);
insert into `user_access_rel` (`user`, `access`) values (1, 5);
insert into `user_access_rel` (`user`, `access`) values (1, 6);
insert into `user_access_rel` (`user`, `access`) values (1, 7);
insert into `user_access_rel` (`user`, `access`) values (1, 8);
insert into `user_access_rel` (`user`, `access`) values (1, 9);
insert into `user_access_rel` (`user`, `access`) values (1, 10);
insert into `user_access_rel` (`user`, `access`) values (1, 11);
insert into `user_access_rel` (`user`, `access`) values (1, 12);
insert into `user_access_rel` (`user`, `access`) values (1, 13);
insert into `user_access_rel` (`user`, `access`) values (1, 14);
insert into `user_access_rel` (`user`, `access`) values (1, 15);
insert into `user_access_rel` (`user`, `access`) values (1, 16);
insert into `user_access_rel` (`user`, `access`) values (1, 17);
insert into `user_access_rel` (`user`, `access`) values (1, 18);
insert into `user_access_rel` (`user`, `access`) values (1, 19);
insert into `user_access_rel` (`user`, `access`) values (1, 20);

/* Moderator Template */
insert into `user_access_rel` (`user`, `access`) values (2, 1);
insert into `user_access_rel` (`user`, `access`) values (2, 2);
insert into `user_access_rel` (`user`, `access`) values (2, 3);
insert into `user_access_rel` (`user`, `access`) values (2, 4);
insert into `user_access_rel` (`user`, `access`) values (2, 5);
insert into `user_access_rel` (`user`, `access`) values (2, 6);
insert into `user_access_rel` (`user`, `access`) values (2, 7);
insert into `user_access_rel` (`user`, `access`) values (2, 8);
insert into `user_access_rel` (`user`, `access`) values (2, 10);
insert into `user_access_rel` (`user`, `access`) values (2, 11);
insert into `user_access_rel` (`user`, `access`) values (2, 12);
insert into `user_access_rel` (`user`, `access`) values (2, 13);
insert into `user_access_rel` (`user`, `access`) values (2, 15);
insert into `user_access_rel` (`user`, `access`) values (2, 16);
insert into `user_access_rel` (`user`, `access`) values (2, 17);
insert into `user_access_rel` (`user`, `access`) values (2, 18);

/* User Template */
insert into `user_access_rel` (`user`, `access`) values (3, 4);
insert into `user_access_rel` (`user`, `access`) values (3, 8);
insert into `user_access_rel` (`user`, `access`) values (3, 13);
insert into `user_access_rel` (`user`, `access`) values (3, 15);
insert into `user_access_rel` (`user`, `access`) values (3, 18);

/* Banned Template */
insert into `user_access_rel` (`user`, `access`) values (3, 4);
insert into `user_access_rel` (`user`, `access`) values (3, 8);
insert into `user_access_rel` (`user`, `access`) values (3, 13);
insert into `user_access_rel` (`user`, `access`) values (3, 18);

/* Inactive User (User Template) */
insert into `user_access_rel` (`user`, `access`) values (3, 4);
insert into `user_access_rel` (`user`, `access`) values (3, 8);
insert into `user_access_rel` (`user`, `access`) values (3, 13);
insert into `user_access_rel` (`user`, `access`) values (3, 15);
insert into `user_access_rel` (`user`, `access`) values (3, 18);


insert into `categories` (`name`, `description`) values ('Category 1', 'A description of category 1.');
insert into `categories` (`name`, `description`) values ('Category 2', 'A description of category 2.');
insert into `categories` (`name`, `description`) values ('Category 3', 'A description of category 3.');
insert into `categories` (`name`, `description`) values ('Category 4', 'A description of category 4.');
insert into `categories` (`name`, `description`) values ('Category 5', 'A description of category 5.');

insert into `posts` (`title`, `content`, `author`) values ('Test Post 1', 'This is the content of the test post 1.', 1);
insert into `posts` (`title`, `content`, `author`) values ('Test Post 2', 'This is the content of the test post 2.', 1);
insert into `posts` (`title`, `content`, `author`) values ('Test Post 3', 'This is the content of the test post 3.', 2);
insert into `posts` (`title`, `content`, `author`) values ('Test Post 4', 'This is the content of the test post 4.', 3);
insert into `posts` (`title`, `content`, `author`) values ('Test Post 5', 'This is the content of the test post 5.', 2);

insert into `articles` (`title`, `content`, `author`, `excerpt`, `published`) values ('Test Article 1', 'This is the content of the test article 1.', 2, 'This is the excerpt description for article 1.', 1);
insert into `articles` (`title`, `content`, `author`, `excerpt`, `published`) values ('Test Article 2', 'This is the content of the test article 2.', 1, 'This is the excerpt description for article 2.', 1);
insert into `articles` (`title`, `content`, `author`, `excerpt`, `published`) values ('Test Article 3', 'This is the content of the test article 3.', 1, 'This is the excerpt description for article 3.', 1);
insert into `articles` (`title`, `content`, `author`, `excerpt`, `published`) values ('Test Article 4', 'This is the content of the test article 4.', 2, 'This is the excerpt description for article 4.', 1);
insert into `articles` (`title`, `content`, `author`, `excerpt`, `published`) values ('Test Article 5', 'This is the content of the test article 5.', 1, 'This is the excerpt description for article 5.', 0);

insert into `projects` (`name`, `description`, `excerpt`, `published`) values ('Test Project 1', 'This is the description of the test project 1.', 'This is the excerpt description for project 1.', 1);
insert into `projects` (`name`, `description`, `excerpt`, `published`) values ('Test Project 2', 'This is the description of the test project 2.', 'This is the excerpt description for project 2.', 1);
insert into `projects` (`name`, `description`, `excerpt`, `published`) values ('Test Project 3', 'This is the description of the test project 3.', 'This is the excerpt description for project 3.', 1);
insert into `projects` (`name`, `description`, `excerpt`, `published`) values ('Test Project 4', 'This is the description of the test project 4.', 'This is the excerpt description for project 4.', 0);
insert into `projects` (`name`, `description`, `excerpt`, `published`) values ('Test Project 5', 'This is the description of the test project 5.', 'This is the excerpt description for project 5.', 1);

insert into `project_category_rel` (`project`, `category`) values (1, 1);
insert into `project_category_rel` (`project`, `category`) values (1, 2);
insert into `project_category_rel` (`project`, `category`) values (1, 3);
insert into `project_category_rel` (`project`, `category`) values (2, 3);
insert into `project_category_rel` (`project`, `category`) values (2, 5);
insert into `project_category_rel` (`project`, `category`) values (3, 1);
insert into `project_category_rel` (`project`, `category`) values (4, 2);
insert into `project_category_rel` (`project`, `category`) values (4, 3);
insert into `project_category_rel` (`project`, `category`) values (5, 5);
insert into `project_category_rel` (`project`, `category`) values (5, 3);

insert into `post_category_rel` (`post`, `category`) values (1, 1);
insert into `post_category_rel` (`post`, `category`) values (1, 2);
insert into `post_category_rel` (`post`, `category`) values (1, 3);
insert into `post_category_rel` (`post`, `category`) values (2, 3);
insert into `post_category_rel` (`post`, `category`) values (2, 5);
insert into `post_category_rel` (`post`, `category`) values (3, 1);
insert into `post_category_rel` (`post`, `category`) values (4, 2);
insert into `post_category_rel` (`post`, `category`) values (4, 3);
insert into `post_category_rel` (`post`, `category`) values (5, 5);
insert into `post_category_rel` (`post`, `category`) values (5, 3);

insert into `article_category_rel` (`article`, `category`) values (1, 1);
insert into `article_category_rel` (`article`, `category`) values (1, 2);
insert into `article_category_rel` (`article`, `category`) values (1, 3);
insert into `article_category_rel` (`article`, `category`) values (2, 3);
insert into `article_category_rel` (`article`, `category`) values (2, 5);
insert into `article_category_rel` (`article`, `category`) values (3, 1);
insert into `article_category_rel` (`article`, `category`) values (4, 2);
insert into `article_category_rel` (`article`, `category`) values (4, 3);
insert into `article_category_rel` (`article`, `category`) values (5, 5);
insert into `article_category_rel` (`article`, `category`) values (5, 3);

insert into `amendments` (`content`, `author`) values ('Amendment 1',  1);
insert into `amendments` (`content`, `author`) values ('Amendment 2',  2);
insert into `amendments` (`content`, `author`) values ('Amendment 3',  1);
insert into `amendments` (`content`, `author`) values ('Amendment 4',  1);
insert into `amendments` (`content`, `author`) values ('Amendment 5',  2);
insert into `amendments` (`content`, `author`) values ('Amendment 6',  1);
insert into `amendments` (`content`, `author`) values ('Amendment 7',  1);
insert into `amendments` (`content`, `author`) values ('Amendment 8',  1);
insert into `amendments` (`content`, `author`) values ('Amendment 9',  2);
insert into `amendments` (`content`, `author`) values ('Amendment 10', 1);

insert into `comments` (`content`, `author`) values ('Comment 1',  1);
insert into `comments` (`content`, `author`) values ('Comment 2',  2);
insert into `comments` (`content`, `author`) values ('Comment 3',  1);
insert into `comments` (`content`, `author`) values ('Comment 4',  3);
insert into `comments` (`content`, `author`) values ('Comment 5',  2);
insert into `comments` (`content`, `author`) values ('Comment 6',  3);
insert into `comments` (`content`, `author`) values ('Comment 7',  1);
insert into `comments` (`content`, `author`) values ('Comment 8',  3);
insert into `comments` (`content`, `author`) values ('Comment 9',  2);
insert into `comments` (`content`, `author`) values ('Comment 10', 3);

insert into `article_comment_rel` (`article`, `comment`) values (1, 10);
insert into `article_comment_rel` (`article`, `comment`) values (2, 9);
insert into `article_comment_rel` (`article`, `comment`) values (3, 8);
insert into `article_comment_rel` (`article`, `comment`) values (4, 7);
insert into `article_comment_rel` (`article`, `comment`) values (5, 6);
insert into `article_comment_rel` (`article`, `comment`) values (1, 5);
insert into `article_comment_rel` (`article`, `comment`) values (2, 4);
insert into `article_comment_rel` (`article`, `comment`) values (3, 3);
insert into `article_comment_rel` (`article`, `comment`) values (4, 2);
insert into `article_comment_rel` (`article`, `comment`) values (1, 1);

insert into `project_comment_rel` (`project`, `comment`) values (1, 10);
insert into `project_comment_rel` (`project`, `comment`) values (2, 9);
insert into `project_comment_rel` (`project`, `comment`) values (3, 8);
insert into `project_comment_rel` (`project`, `comment`) values (4, 7);
insert into `project_comment_rel` (`project`, `comment`) values (5, 6);
insert into `project_comment_rel` (`project`, `comment`) values (1, 5);
insert into `project_comment_rel` (`project`, `comment`) values (2, 4);
insert into `project_comment_rel` (`project`, `comment`) values (3, 3);
insert into `project_comment_rel` (`project`, `comment`) values (4, 2);
insert into `project_comment_rel` (`project`, `comment`) values (1, 1);

insert into `post_comment_rel` (`post`, `comment`) values (1, 10);
insert into `post_comment_rel` (`post`, `comment`) values (2, 9);
insert into `post_comment_rel` (`post`, `comment`) values (3, 8);
insert into `post_comment_rel` (`post`, `comment`) values (4, 7);
insert into `post_comment_rel` (`post`, `comment`) values (5, 6);
insert into `post_comment_rel` (`post`, `comment`) values (1, 5);
insert into `post_comment_rel` (`post`, `comment`) values (2, 4);
insert into `post_comment_rel` (`post`, `comment`) values (3, 3);
insert into `post_comment_rel` (`post`, `comment`) values (4, 2);
insert into `post_comment_rel` (`post`, `comment`) values (1, 1);

insert into `article_amendment_rel` (`article`, `amendment`) values (1, 10);
insert into `article_amendment_rel` (`article`, `amendment`) values (2, 9);
insert into `article_amendment_rel` (`article`, `amendment`) values (3, 8);
insert into `article_amendment_rel` (`article`, `amendment`) values (4, 7);
insert into `article_amendment_rel` (`article`, `amendment`) values (5, 6);
insert into `article_amendment_rel` (`article`, `amendment`) values (1, 5);
insert into `article_amendment_rel` (`article`, `amendment`) values (2, 4);
insert into `article_amendment_rel` (`article`, `amendment`) values (3, 3);
insert into `article_amendment_rel` (`article`, `amendment`) values (4, 2);
insert into `article_amendment_rel` (`article`, `amendment`) values (1, 1);

insert into `project_amendment_rel` (`project`, `amendment`) values (1, 10);
insert into `project_amendment_rel` (`project`, `amendment`) values (2, 9);
insert into `project_amendment_rel` (`project`, `amendment`) values (3, 8);
insert into `project_amendment_rel` (`project`, `amendment`) values (4, 7);
insert into `project_amendment_rel` (`project`, `amendment`) values (5, 6);
insert into `project_amendment_rel` (`project`, `amendment`) values (1, 5);
insert into `project_amendment_rel` (`project`, `amendment`) values (2, 4);
insert into `project_amendment_rel` (`project`, `amendment`) values (3, 3);
insert into `project_amendment_rel` (`project`, `amendment`) values (4, 2);
insert into `project_amendment_rel` (`project`, `amendment`) values (1, 1);

insert into `post_amendment_rel` (`post`, `amendment`) values (1, 10);
insert into `post_amendment_rel` (`post`, `amendment`) values (2, 9);
insert into `post_amendment_rel` (`post`, `amendment`) values (3, 8);
insert into `post_amendment_rel` (`post`, `amendment`) values (4, 7);
insert into `post_amendment_rel` (`post`, `amendment`) values (5, 6);
insert into `post_amendment_rel` (`post`, `amendment`) values (1, 5);
insert into `post_amendment_rel` (`post`, `amendment`) values (2, 4);
insert into `post_amendment_rel` (`post`, `amendment`) values (3, 3);
insert into `post_amendment_rel` (`post`, `amendment`) values (4, 2);
insert into `post_amendment_rel` (`post`, `amendment`) values (1, 1);

insert into `article_ratings` (`user`, `article`, `value`) values (1, 1, 5);
insert into `article_ratings` (`user`, `article`, `value`) values (1, 2, 4);
insert into `article_ratings` (`user`, `article`, `value`) values (1, 3, 3);
insert into `article_ratings` (`user`, `article`, `value`) values (1, 4, 2);
insert into `article_ratings` (`user`, `article`, `value`) values (1, 5, 1);
insert into `article_ratings` (`user`, `article`, `value`) values (2, 1, 4);
insert into `article_ratings` (`user`, `article`, `value`) values (2, 2, 3);
insert into `article_ratings` (`user`, `article`, `value`) values (2, 3, 2);
insert into `article_ratings` (`user`, `article`, `value`) values (3, 4, 1);
insert into `article_ratings` (`user`, `article`, `value`) values (3, 5, 3);
insert into `article_ratings` (`user`, `article`, `value`) values (3, 1, 2);

insert into `post_ratings` (`user`, `post`, `value`) values (1, 1, 5);
insert into `post_ratings` (`user`, `post`, `value`) values (1, 2, 4);
insert into `post_ratings` (`user`, `post`, `value`) values (1, 3, 3);
insert into `post_ratings` (`user`, `post`, `value`) values (1, 4, 2);
insert into `post_ratings` (`user`, `post`, `value`) values (1, 5, 1);
insert into `post_ratings` (`user`, `post`, `value`) values (2, 1, 4);
insert into `post_ratings` (`user`, `post`, `value`) values (2, 2, 3);
insert into `post_ratings` (`user`, `post`, `value`) values (2, 3, 2);
insert into `post_ratings` (`user`, `post`, `value`) values (3, 4, 1);
insert into `post_ratings` (`user`, `post`, `value`) values (3, 5, 3);
insert into `post_ratings` (`user`, `post`, `value`) values (3, 1, 2);

insert into `project_ratings` (`user`, `project`, `value`) values (1, 1, 5);
insert into `project_ratings` (`user`, `project`, `value`) values (1, 2, 4);
insert into `project_ratings` (`user`, `project`, `value`) values (1, 3, 3);
insert into `project_ratings` (`user`, `project`, `value`) values (1, 4, 2);
insert into `project_ratings` (`user`, `project`, `value`) values (1, 5, 1);
insert into `project_ratings` (`user`, `project`, `value`) values (2, 1, 4);
insert into `project_ratings` (`user`, `project`, `value`) values (2, 2, 3);
insert into `project_ratings` (`user`, `project`, `value`) values (2, 3, 2);
insert into `project_ratings` (`user`, `project`, `value`) values (3, 4, 1);
insert into `project_ratings` (`user`, `project`, `value`) values (3, 5, 3);
insert into `project_ratings` (`user`, `project`, `value`) values (3, 1, 2);