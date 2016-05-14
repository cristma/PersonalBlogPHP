/* Clean up the table structures */
drop table if exists `project_comment_rel`;
drop table if exists `project_amendment_rel`;
drop table if exists `project_category_rel`;
drop table if exists `project_ratings`;

drop table if exists `article_comment_rel`;
drop table if exists `article_amendment_rel`;
drop table if exists `article_category_rel`;
drop table if exists `article_ratings`;

drop table if exists `post_comment_rel`;
drop table if exists `post_amendment_rel`;
drop table if exists `post_category_rel`;
drop table if exists `post_ratings`;

drop table if exists `posts`;
drop table if exists `articles`;
drop table if exists `projects`;

drop table if exists `comments`;
drop table if exists `amendments`;
drop table if exists `categories`;

drop table if exists `profiles`;
drop table if exists `user_access_rel`;
drop table if exists `users`;
drop table if exists `access`;

/* Create the tables from the source. */
source C:/inetpub/wwwroot/immutable/sql/access.sql;
source C:/inetpub/wwwroot/immutable/sql/users.sql;
source C:/inetpub/wwwroot/immutable/sql/user_access_rel.sql;

source C:/inetpub/wwwroot/immutable/sql/profiles.sql;
source C:/inetpub/wwwroot/immutable/sql/categories.sql;
source C:/inetpub/wwwroot/immutable/sql/amendments.sql;
source C:/inetpub/wwwroot/immutable/sql/comments.sql;
source C:/inetpub/wwwroot/immutable/sql/projects.sql;
source C:/inetpub/wwwroot/immutable/sql/articles.sql;
source C:/inetpub/wwwroot/immutable/sql/posts.sql;

source C:/inetpub/wwwroot/immutable/sql/post_ratings.sql;
source C:/inetpub/wwwroot/immutable/sql/post_category_rel.sql;
source C:/inetpub/wwwroot/immutable/sql/post_amendment_rel.sql;
source C:/inetpub/wwwroot/immutable/sql/post_comment_rel.sql;

source C:/inetpub/wwwroot/immutable/sql/article_ratings.sql;
source C:/inetpub/wwwroot/immutable/sql/article_category_rel.sql;
source C:/inetpub/wwwroot/immutable/sql/article_amendment_rel.sql;
source C:/inetpub/wwwroot/immutable/sql/article_comment_rel.sql;

source C:/inetpub/wwwroot/immutable/sql/project_ratings.sql;
source C:/inetpub/wwwroot/immutable/sql/project_category_rel.sql;
source C:/inetpub/wwwroot/immutable/sql/project_amendment_rel.sql;
source C:/inetpub/wwwroot/immutable/sql/project_comment_rel.sql;

source C:/inetpub/wwwroot/immutable/sql/sample_data.sql