-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 18, 2013 at 04:07 PM
-- Server version: 5.1.68-cll
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `immuta5_site`
--

-- --------------------------------------------------------

--
-- Table structure for table `access`
--

CREATE TABLE IF NOT EXISTS `access` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `weight` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `access`
--

INSERT INTO `access` (`id`, `name`, `weight`) VALUES
(1, 'Banned', 0),
(2, 'Public', 1),
(3, 'Moderator', 2),
(4, 'Administrator', 3),
(5, 'Super User', 4);

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT 'Untitled Article',
  `excerpt` text NOT NULL,
  `content` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `author` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `author` (`author`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `excerpt`, `content`, `timestamp`, `author`) VALUES
(1, 'Acquiring Server Responses Using JSON', '<p>Often times, there are circumstances in which we cannot rely completely on a string that is being echoed back by the server to be pertinent to the information that we processed.&nbsp; We commonly use languages such as XML in order to provide a relevant response mechanism, which more than suffice for the task.&nbsp; The scope of this article is to address this issue using JSON (JavaScript Object Notation) instead of XML.&nbsp; This allows us a quick way of interpreting a server response instead of traversing an XML tree using XPath or some other means of parsing XML.</p>', '<h3><strong style=\\"font-size: 11px;\\">Introduction</strong></h3>\r\n<p>Often times, there are circumstances in which we cannot rely completely on a string that is being echoed back by the server to be pertinent to the information that we processed.&nbsp; We commonly use languages such as XML in order to provide a relevant response mechanism, which more than suffice for the task.&nbsp; The scope of this article is to address this issue using JSON (JavaScript Object Notation) instead of XML.&nbsp; This allows us a quick way of interpreting a server response instead of traversing an XML tree using XPath or some other means of parsing XML.</p>\r\n<h4><strong>What is JSON?</strong></h4>\r\n<p>JSON, if you are not familiar, happens to be the &ldquo;hot trend&rdquo; in the development community lately.&nbsp; It&rsquo;s a good skill to have in your arsenal as a developer, but not necessary.&nbsp; Think of it as a unified transportation mechanism for data transportation that is easily translatable by JavaScript without having to provide the overhead required by XML parsers.&nbsp; So for web technologies, this makes a lot of sense since we will be using JavaScript to communicate with our server asynchronously.</p>\r\n<h4><strong>The Problem</strong></h4>\r\n<p>This problem was presented to me when I decided to assign categories to articles that would be submitted on this website.&nbsp; How could I get the ID of the category back after I had created the category?&nbsp; I knew that in PHP, I could simply call the LastID query against the SQL database in order to acquire that value, but getting that value back to the caller seemed to be the problem I had to overcome.&nbsp; For those that know me probably would have assumed I would have jumped aboard the XML train, but I felt it a good chance to show off some diversity on ability (because some people who don&rsquo;t like to adhere to DTD&rsquo;s or XSD&rsquo;s &ndash; which defined the structure on an XML document.)</p>\r\n<h4><strong>The Design</strong></h4>\r\n<p>First, we must ask ourselves, what exactly do we hope to accomplish with this response?&nbsp; I had a need for a multiple value response, one containing the message from the server (i.e. Category created successfully!) and the object that was created (i.e. the category name, id and description).&nbsp; I knew the name and description were acquired at the point the user typed the information in the input boxes, but I didn&rsquo;t want to break a complete structure for a category object being returned by the server (though only acquiring the ID is sufficient.)&nbsp; I could be presented to multiple responses from the server so a response could come in the form of an array.&nbsp; For simplicity sake, we are going to limit it to one response per query.&nbsp; The query that is being performed will be done via AJAX using the POST method to a PHP script that will in turn echo the response in JSON format.&nbsp; The AJAX calls will be done using JQuery.</p>\r\n<p>The best starting point was to determine the structure of the JSON string to be returned by the PHP script.&nbsp; To do this, I decided to describe it in plain English:</p>\r\n<p><em>I need a response from the server that contains the status, message and if possible the object that was created through the AJAX call.</em></p>\r\n<p>It sounds simple and straight-forward.&nbsp; So we can determine that the encapsulating object for the call is a &ldquo;<em>response&rdquo;.</em>&nbsp; The language schematics for JSON are as follows:</p>\r\n<p><em>object</em> - <strong>{}</strong> | <strong>{</strong>&nbsp;<em>members</em>&nbsp;<strong>}</strong></p>\r\n<p><em>members</em> - <em>pair</em> | <em>pair</em>&nbsp;<strong>,</strong>&nbsp;<em>members</em></p>\r\n<p><em>pair</em> - <em>string</em>&nbsp;<strong>:</strong>&nbsp;<em>value</em></p>\r\n<p><em>value</em> - <em>string</em> |  <em>number</em> | <em>object</em> | <em>array</em> | <strong>true&nbsp;</strong> | <strong>false&nbsp;</strong> | <strong>null</strong></p>\r\n<p>&nbsp;</p>\r\n<p>We can see that our response will be our <em>object.</em>&nbsp; The object is defined as something contained within { } (The terminals for this language rule).&nbsp; But there&rsquo;s more!&nbsp; We have something that describes the object, so we must jump to the <em>{ members }</em> rule.&nbsp; The <em>members</em> portion is considered to be our &ldquo;non-terminal&rdquo;- that is (in short) another rule.&nbsp; Going down the list, we can see that the rule <em>members</em> is a <em>pair </em>(another non-terminal).&nbsp; Since we are going to have multiple <em>members </em>of an object, we can see that we have a recursive rule that satisfies this condition <em>pair, members</em>.&nbsp; The <em>pair</em> is a non-terminal going to the next rule, while <em>members</em> goes back to the non-terminal <em>members</em>.&nbsp; The <em>pair </em>defines a terminal string (which is ambiguously defined as something between &ldquo; and &ldquo;) a semicolon and a value.&nbsp; The <em>value</em> terminal we will use will be <em>string.</em></p>\r\n<p>So our data structure derivation looks like this:</p>\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; { string : string, string : string, string: { string : string, string : string, string : string } }</p>\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; { string : <em>value</em>, string: <em>value</em>, string : { string : <em>value</em>, string : <em>value</em>, string :<em> value</em> } }</p>\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;{ <em>pair</em>, <em>pair</em>, string : { <em>pair</em>, <em>pair</em>, <em>pair</em> } }</p>\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; { <em>pair</em>, <em>pair</em>, string : { <em>pair</em>, <em>pair</em>, <em>members</em> } }</p>\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; { <em>pair</em>, <em>pair</em>, string : { <em>pair</em>, <em>members</em> } }</p>\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; { pair, pair, string : { <em>members</em> } }</p>\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; { <em>pair</em>, <em>pair</em>, string : <em>object</em> }</p>\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; { <em>pair</em>, <em>pair</em>, string : <em>value</em> }</p>\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; { <em>pair</em>,<em> pair</em>, <em>pair</em> }</p>\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; { <em>pair</em>, <em>pair</em>, <em>members</em> }</p>\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; { <em>pair</em>, <em>members</em> }</p>\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; { <em>members</em> }</p>\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <em>object</em></p>\r\n<p>** Italics denote non-terminals, otherwise symbol is a terminal and is required for output.&nbsp; String terminal represents anything encapsulated by quotation marks</p>\r\n<p>This effectively constructs our parse tree for the object.&nbsp; Now it is a matter for filling in what the string values are:&nbsp;</p>\r\n<pre>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; { &ldquo;status&rdquo; : &ldquo;A status message.&rdquo;,<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &ldquo;message&rdquo; : &ldquo;The message that should be returned to the calling script.&rdquo;,<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &ldquo;category&rdquo; : { &ldquo;id&rdquo; : &ldquo;1&rdquo;,<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&ldquo;name&rdquo; : &ldquo;The category name.&rdquo;,<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&ldquo;description&rdquo; : &ldquo;The category description.&rdquo; } }</pre>\r\n<p>So we can see our output coming together rather nicely, but it is safe to say we can see values that will be dynamic in our PHP script.&nbsp; So when we call our PHP script, we will need to fill values in that will be echoed to the caller as the response.</p>\r\n<pre>$myJSONResponse = &lsquo;{&ldquo;status&rdquo; : &ldquo;&rsquo; &nbsp;. $status . &lsquo;&rdquo;, &lsquo; .<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&lsquo;&ldquo;message&rdquo; : &ldquo;&rsquo; . $message . &lsquo;&ldquo;, &lsquo; .<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &lsquo;&ldquo;category&rdquo; : { &ldquo;id&rdquo; : &ldquo;&rsquo; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;. $id . &lsquo;&rdquo;, &lsquo; .<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &lsquo;&ldquo;name&rdquo; : &ldquo;&rsquo; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;. $name . &lsquo;&rdquo;, &lsquo; . &nbsp;&nbsp;<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &lsquo;&ldquo;description&rdquo; : &ldquo;&rsquo; . $description . &lsquo;&rdquo;}}&rsquo;;&nbsp;</pre>\r\n<p>Of course, for those that know PHP, $ is denoting the dynamic values that will be echoed when they contain a value.&nbsp; In this case, if they are NULL (on error), then the string value for the category object is considered to be null and we can use JavaScript logic to protect against this.&nbsp; And as you can see from the language rules, <em>null</em> is a qualified terminal for <em>value</em>.</p>\r\n<p>&nbsp;</p>\r\n<p>When we successfully complete the insert on the server side, we simply echo this string to the stdout for the website.&nbsp;</p>\r\n<pre>echo $myJSONResponse;</pre>\r\n<p>When the AJAX command is called, the response is sent as &ldquo;data&rdquo;.&nbsp; Her is how I set up my AJAX call with JQuery:</p>\r\n<p>&nbsp;</p>\r\n<pre>$(&ldquo;#f_createcategorybutton&rdquo;).click(function() { &nbsp;&nbsp; $.post(&ldquo;ajax.php?content=categories&amp;action=save&rdquo;, {<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; f_categoryname:$(&ldquo;#f_categoryname&rdquo;).val(),<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; f_categorydescription:$(&ldquo;#f_categoryname&rdquo;).val()<br />&nbsp;&nbsp; },</pre>\r\n<pre>&nbsp;&nbsp; function(data, status) {<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; if(status==&rdquo;success&rdquo;) {<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; var response = eval(&ldquo;(&ldquo; + data + &ldquo;)&rdquo;);<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $(&ldquo;#f_createcategorystatus&rdquo;).html(reponse.status + &ldquo;: &ldquo; + reponse.message);&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;</pre>\r\n<pre>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; if(response.category.id != null) {<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;$(&ldquo;#f_blogcategories&rdquo;).append(&lsquo;&lt;option value=&rdquo;&rsquo; + response.category.id + &lsquo;&rdquo;&gt;&rsquo; + response.category.name + &lsquo;&lt;/option&gt;&rsquo;);<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; }<br /> &nbsp;&nbsp;&nbsp;&nbsp; } else {<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; // Script did not execute&hellip; Display some message to user.<br />&nbsp; &nbsp; &nbsp; }<br />&nbsp; &nbsp;});<br />});</pre>\r\n<p>So after I call the POST message to the PHP script, the script is echoing back my JSON text and it is parsed into valid JSON using the eval command.&nbsp; I can then access those objects as if they were normally JavaScript objects.&nbsp; In this case, I am adding the added category to the category selection box and updating a message box indicating the creation of the category was successful.&nbsp; It makes logical sense for a user to add categories as they deem them needed instead of going to a separate page to create them.</p>\r\n<h4>Conclusion</h4>\r\n<p>I hope you find this article a little useful as I described the process to which I determined asynchronous multivalued returns to the caller script is required using JSON, PHP and JQuery.&nbsp; A lot is implied with this article as it is merely an assumption that you are looking for a solution to the problem and not the actual process of creating the category and adding it to the database.&nbsp; That is best left to article describing the actual AJAX calling to the server.</p>', '2013-07-27 09:17:08', 1);

-- --------------------------------------------------------

--
-- Table structure for table `article_category_rel`
--

CREATE TABLE IF NOT EXISTS `article_category_rel` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `article` int(10) NOT NULL,
  `category` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `article` (`article`),
  KEY `article_by_category` (`category`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `article_category_rel`
--

INSERT INTO `article_category_rel` (`id`, `article`, `category`) VALUES
(1, 1, 7),
(2, 1, 9),
(3, 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `article_section_rel`
--

CREATE TABLE IF NOT EXISTS `article_section_rel` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `article` int(10) NOT NULL,
  `section` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `article` (`article`),
  KEY `article_by_section` (`section`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT 'Unnamed Category',
  `description` varchar(255) NOT NULL DEFAULT 'No Description',
  `active` int(1) NOT NULL DEFAULT '1',
  `access` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `access` (`access`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `active`, `access`) VALUES
(1, 'Site News', 'Information that relates directly to the website.', 1, 1),
(2, 'Gesture Recognition', 'Human gesture recognition for more natural human computer interaction.', 1, 1),
(3, 'Java', 'The Java programming language developed by Sun Microsystems, but currently serviced by Oracle.', 1, 1),
(4, 'XML', 'eXtensible Markup Language is a method of data transportation.', 1, 1),
(5, 'Android', 'Android is a Linux based operating system which is maintained by Google.', 1, 1),
(6, 'Bootstrap', 'Twitter Bootstrap CSS is a method of developing a website that can be responsive and unified on a common framework.', 1, 1),
(7, 'JQuery', 'JQuery is a javascript framework that can be used to provide nice asthetics to your website without the overhead of writing the code responsible.', 1, 1),
(8, 'SQL', 'Structured Query Language is a storage mechanism that is used to store data and retrieve it efficiently using a declarative notation. ', 1, 1),
(9, 'JSON', 'Javascript Object Notation - a format used for data transmission that is easily parsable by various languages.', 1, 1),
(10, 'PHP', 'PHP: Hypertext Preprocessor - a language used for server side information processing, most often used with websites.', 1, 1),
(11, 'CSS', '<p>Cascade stylesheets responsible for the design portion of the website.</p>', 1, 1),
(12, 'PSP', '<p>The Personal Software Process is deemed to be the Six Sigma of the software world. &nbsp;it is a benchmarking system that helps an individual and gorup of individuals (TSP) guage the completion time for a project and ensure on time delivery of a produc', 1, 1),
(13, 'Photography', '<p>Digital photography done with a Canon Rebel T3 and an assortment of lens. &nbsp;These photographs may be taken by me or my wife.</p>', 1, 1),
(14, 'MS Access', '<p>Microsoft Access is a form and database application that allows for quick application development that is dependent upon the Access application in order to function. &nbsp;The knowledge base is wide as well as the ability to develop products in a timel', 1, 1),
(15, 'Database', '<p>Databases are used to store large amounts of information that may require retrieval with a specified set of criteria on a regular or irregular basis. &nbsp;This storage medium is optimized for the means to which the data is access and makes it ideal st', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT 'Untitled Post',
  `content` text NOT NULL,
  `author` int(10) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `author` (`author`),
  KEY `posts_by_date` (`date`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `author`, `date`) VALUES
(1, 'Site Improvements', 'Still working to get some of the features enabled on the website.  The layout is essentially done and the widgets are giving me a bit of a headache on this template currently.  Just a matter for fidgeting around with the CSS and then uploading all the images to make the site pop a little more.  For now, at least until I get home from work, I will be updating some of the content on the website.  The first page that will be pushed into completion is the portfolio.  This is so that I can mitigate some of the information from my linkedin page and redirect the crowd here to provide a better explanation of the projects, the development process for completion as well as the documents and whitepaper associate with the projects.  Some of these projects contain photographs of the work in action as well as team photos and screenshots.', 1, '2013-07-07 08:53:20'),
(2, 'Ironing it out!', '\r\nStill working on the theme for this site. I have the backend setup as I want, but when it comes to the template, integrating is not going as expected (does anything ever go as planned?) Regardless, I have put up a simple template until the original template is integrated. I have opted to drop the responsive site features on the template that is to finalize on this site as the menu is causing an issue. Roll over effects in JQuery and changing a graphic do not seem in integrate well either. Of course I work on this when I have free time, which doesn\\’t seem that I have much here lately. I\\’ll keep typing away at some of the projects to move them from my linkedin page to this website. It seems I am getting tons of spam from linkedin on \\“general blanketing\\” of people who are recruiting. My guess is that they are trying to get a large selection of applicants in order to select from them. I am aware of some of the jobs, but not really interested as people seem to think I want an IT job, which is currently the field I work in, AND I AM TRYING TO GET AWAY FROM IT!!!', 1, '2013-07-07 08:53:56'),
(3, 'Bootstrap!', '<p>As I struggle to get used to the design styles of websites these days, I have been having fun with the Twitter Bootstrap CSS library that this site is currently structured upon.  Couple that with WordPress (which I have minimal experience with) you get a slow turn around for the site.  Though the foundation of it is simple to grasp (posting information, categories and such) integrating into the library is not seemingly as straight forward as I had hoped (or at least as I would have done it).</p>\r\n\r\n<p>Though over the years, I have typically programmed the back ends of websites and contracted out the design to a third party (most generally starving artists who do it for a couple hundred bucks), I have wanted to complete a site form the ground up for some time.  This is not to say that I haven’t, as there will be a few posted to my portfolio.  The design “back in my day” was a little less forgiving and % based widths was something we usually laughed at and mocked.  Fast forward to today, the designs are simplistic, minimal but informative.  Trying to find that balance is interesting.  Being the perfectionist that I am, I seem WASTED SPACE everywhere, but I need to come to terms with this not being a bad thing.</p>\r\n\r\n<p>I remember asking for “critique” on bluesfear.com forums and they would tell you how the layout suched because of utilized space.  Small fonts and cramming as much information on a website seemed to be the trend.  Also, “development teams” of individuals seemed to be an achievement, but not so much anymore.  The all-in-one freelancer seems to be the person that most are seeking (or at least looking to employ).  This is why as of earlier this year, I have tossed in my hat for web development.  That’s not to say that I would not find it interesting in the future, but for now I think I am way behind the curve.</p>\r\n\r\n<p>I’ve also gained more of a fondness for desktop and mobile application development.  But I keep cycling back to the topics like “Service Oriented Architecture” which loves to use a web based front end in order to deliver information to the end user (though not explicitly limited to).  I guess we’ll see what future development holds in store for me.</p>', 1, '2013-07-07 08:55:29'),
(4, 'Straying Away From Wordpress', '<p>This last week I decided to custom build my website from the ground up and on the backend.  The reason I decided to do this is because of the restrictions I was facing implementing Wordpress!.  Though I liked some of the abilities for the webapp (like taxonomy) the benefits of custom building my site far outweighed trying to \\"hack\\" Wordpress.</p>\r\n<p>Of course, the site is not fully implemented but it is coming together rather quickly.  I have maintained the usage of Twitter Bootstrap CSS and JQuery 2.0.2.  I now have more flexibility in the tempalte design instead of the template system implemented by a CMS.  I was leaning toward merging back to Joomla! but the overhead involved with it was not necessary for this website.  Besides, I can add features as I see fit.</p>\r\n<p>Right now I am posting information directly to the SQL database instead of an administrator panel, since I have yet to work on that end.  It will be completed after the front content is complete.  The import appears to have not gone as smoothly as I had anticipated, with quotes and all, but regardless, the information is there!</p>', 1, '2013-07-07 09:11:47'),
(5, 'Added Features!', '<p>Added some features to the website. &nbsp;This includes session management to be able to handle user login requests and I have the back end of the blogging software complete. &nbsp;It uses the TinyMCE editor to be able to edit blogs through HTML and make them look very fancy!</p>\r\n<p>In the process of finishing:</p>\r\n<ul>\r\n<li>Project Management</li>\r\n<li>Article Management</li>\r\n<li>User Management</li>\r\n</ul>\r\n<p>(I had to test out a few features of the text editor). &nbsp;More updates to come!</p>', 1, '2013-07-13 11:07:21'),
(6, 'Lessons Learned', '<p>This past week has taught me that being dependent upon certain drivers can be a risky venture.&nbsp; I thought that the webhost, in which this site is currently housed, would not changed a specific driver (MySQLi Native Driver) but it turned out they updated the version of PHP that I was on and built it without the native driver.&nbsp; This left me in a pricarious situation.&nbsp; So I contacted support and asked them why they changed this with their server.&nbsp; Their response:&nbsp;</p>\r\n<p>We don\\''t support debugging programming errors.</p>\r\n<p>As you can imagine, I was pretty frustrated by this and told them exactly what happened, and they cycled back to the \\"working as intended, it\\''s your fault\\" scenario.&nbsp; After 8 hours of yammering with a \\"support tech\\", a server admin finally updated the site to be servered with a version of PHP which included the native driver and put it int he \\"to do\\" list to add that driver to the next build.&nbsp; Of course, I decided to naturally removed the functions that were dependent upon this, but my site was functional again.&nbsp; Now, after 3 days of hammered updates to code, the site is no longer using mysqli::get_result and uses the row-pointer method in order to cycle results.</p>\r\n<p>Fun stuff, lesson learned...</p>', 1, '2013-07-20 14:01:01'),
(7, 'Skinning - Finally!', '<p>Finally working on skinning the website to make it look less like a patchwork of things lumped together. &nbsp;Should make a lot more sense once this is posted. &nbsp;The design still uses twitter bootstrap and scaffolding. &nbsp;I\\''ve also put subtle things in there (such as the golden ratio) and elements of mathematical and engineering influence. &nbsp;I should have this done within the next couple of days, so check back for updates!</p>', 1, '2013-07-21 08:23:28'),
(8, 'Twitter Bootstrap CSS 3 RC 1', '<p><img style=\\"margin: 10px auto; display: block;\\" src=\\"img/blogs/bs-docs-twitter-github.png\\" alt=\\"Twitter Bootstrap CSS\\" width=\\"600\\" height=\\"290\\" /></p>\r\n<p>Twitter Bootstrap CSS has updated to 3 RC1. &nbsp;With its changes appears to be a shift away from the scaffolding to the grid layout system. &nbsp;The key difference is in the usage of the classes (which requires a bit a redesign) but also noticable is the lack of padding between elements. &nbsp;This will probably make a few developers happy as it allows for a seemless graphics merging for those who like to chop websites in Photoshop and can\\''t really get the exact look with scaffolding.&nbsp;<a href=\\"http://twitter.github.io/bootstrap/css/#grid\\" target=\\"_blank\\">(More on the Grid System)</a></p>\r\n<p>There also appears to be an added feature regarding the display among devices. &nbsp;You can hide / show based on the device viewing your website through classes instead of CSS kung fu. &nbsp;Fun stuff!&nbsp;<a href=\\"http://twitter.github.io/bootstrap/css/#responsive-utilities\\" target=\\"_blank\\">(More on Responsive Utilities)</a></p>\r\n<p>The glyph icons have been removed from the downloaded project and shifted to a separate repository. &nbsp;This is to keep the system lightweight and allow for the customization through the use of other icon sets.&nbsp;<a href=\\"http://twitter.github.io/bootstrap/css/#glyphicons\\" target=\\"_blank\\">(More on Glyph Icons)</a></p>', 1, '2013-07-28 07:08:13'),
(9, 'Site Name Change', '<p>The future name of this site will be Imagine-Code.&nbsp; I feel that Immutable Productions is not really relavent to the site\\''s function, as I am no longer developing professionally for the web.&nbsp; Granted, I may do some thigns in the future but it will be more under the current LLC that is a registered business.&nbsp; With taht being said...</p>\r\n<p>The site will keep the same course, but be less personalized and allow for multiuser input.&nbsp; I expect to allow users to create accounts and post information and comments.&nbsp; This is the first portion of a subset of sites that will be building upon a web community.&nbsp; The blog portion will be viewed more as a community news enhancement.&nbsp; Those that are familiar with some previous projects know that I like to develope for web interaction and take significant notice of the way people click information.&nbsp; Look for some changes over the next few weeks.</p>', 1, '2013-07-31 14:30:31'),
(10, 'Similar Direction, Change of Idea', '<p>Instead of converting this site, I\\''ve decided to create an alternative site for some of the information. &nbsp;That site will pull some of hte information form this site and store it in its database, the articles section will be removed from this site and sent over to that site. &nbsp;This is to remove any traffic coming to this site for unintended purpose. &nbsp;I am not looking for large volumes of traffic on Immutable Productions, moreso than leaving it as an outlet for me to vent information and provide a point of reference for abilities.</p>\r\n<p>With that being said, Imagine-Code will have the following features:</p>\r\n<ul>\r\n<li>Visitors to the site will be able to create accounts</li>\r\n<li>Visitors will be able to submit a number of comments pertaining to an article, post, code snippet or project.</li>\r\n<li>Visitors can submit articles for peer review and reference.</li>\r\n<li>Visitors can submit forum posts for user communication.</li>\r\n<li>Visitors can submit code snippets for archive and reference as well as peer review.</li>\r\n<li>Visitors will be able to collaborate on projects and allow users to access that project with specific privileges and allow visitor feedback.</li>\r\n</ul>\r\n<p>Some may say that this has been done before and perhaps that is the case. &nbsp;I also look to implement things in the futures, such as code chains for user collaboration to create a sort of storylined programming sequence to see what the community can produce.</p>\r\n<p>Another site that will come into conception within a few months is a completion of something close to the Image-Fight project concieved a while back. &nbsp;I won\\''t go into the details greatly regarding this as I need to dig up some old code to see what I have available or to simply start form scratch.</p>', 1, '2013-08-01 08:29:29'),
(11, 'Amending Functionality', '<p>I\\''ve decided to add something called \\"amendments\\" to the functionality of the site. &nbsp;Articles, post and project will have \\"amendments\\" added to them to help keep track of a quasi-versioning system. &nbsp;This will allow me to see the updates and will be posted in sequential order (based on date) to the submission. &nbsp;This will also assist in information indexing and I will be able to revert to previous versions (sort of like how WIKI works).</p>\r\n<p>I have also contiplated adding \\"commenting\\" to the website. &nbsp;I\\''ve always been heistant on this since spambots love to exploit this, but I figure that if I can produce my own spam thwarting capabilities, this should disallow the \\"fly-by\\" spam bots who use generalize scripts for things like wordpress and joomla. &nbsp;This site is a custom build, from the ground up, so those exploits would not work. &nbsp;I guess we\\''ll see how it goes.</p>\r\n<p>Cognitively speaking, the site is revolving around the \\"categories\\" idea. &nbsp;Each category has 3 items connected with it - post, article and project. &nbsp;I will be adding another feature to that (pictures) as I prep the architecture for image-fight.com and might even add \\"repository\\" for imagine-code.com.</p>', 1, '2013-08-12 06:25:40'),
(12, 'Database Revamp', '<p>I\\''ve decided to revamp the database backend for this website is order to normalize it with the additions of the rating, commenting and amendment systems that will be rolled out later this week. &nbsp;Instead of weighting access to the site, it has been done more on a discrete level with specific tuned feature security. &nbsp;Examples would include:</p>\r\n<ul>\r\n<li>Add Post</li>\r\n<li>Edit Post</li>\r\n<li>Delete Post</li>\r\n<li>Read Post</li>\r\n</ul>\r\n<p>This format will allow me to assign these privs through a relationship as opposed to a float value column in the&nbsp;<em>users</em> table. &nbsp;It used to be that if a user had a numerical value for security &gt; the base value required for that item, then they could access those features. &nbsp;The base value was set statically in a configuration file. &nbsp;I know this is a bad way to do this, but initial development did not have it intended for community use.</p>\r\n<p>I\\''ve also conitplated getting back to work on some EVE-O stuff. &nbsp;Finding working with Bootstrap fun, but I also have been dabbling in JQuery UI - I have to say that I like it very much. &nbsp;Kicking this down the road and the scoffolding features of the Bootstrap have me wanting to use both, but I still only want to isolate myself to just one. &nbsp;This is why I might push the front end for imagine-code using the JQueryUI instead.</p>\r\n<p>I\\''ll update the next post with a current structure of the API as it is currently implemented.</p>', 1, '2013-08-18 11:46:03');

-- --------------------------------------------------------

--
-- Table structure for table `post_category_rel`
--

CREATE TABLE IF NOT EXISTS `post_category_rel` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `post` int(10) NOT NULL,
  `category` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post` (`post`),
  KEY `post_by_category` (`category`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `post_category_rel`
--

INSERT INTO `post_category_rel` (`id`, `post`, `category`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 4, 6),
(5, 4, 7),
(6, 4, 8),
(7, 4, 1),
(8, 5, 1),
(9, 8, 1),
(10, 8, 6),
(11, 8, 11),
(12, 9, 1),
(13, 10, 1),
(14, 11, 1),
(15, 12, 15),
(16, 12, 10),
(17, 12, 1),
(18, 12, 8);

-- --------------------------------------------------------

--
-- Table structure for table `post_section_rel`
--

CREATE TABLE IF NOT EXISTS `post_section_rel` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `post` int(10) NOT NULL,
  `section` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post` (`post`),
  KEY `post_by_section` (`section`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT 'Unnamed Project',
  `excerpt` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_category_rel`
--

CREATE TABLE IF NOT EXISTS `project_category_rel` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `project` int(10) NOT NULL,
  `category` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project` (`project`),
  KEY `project_by_category` (`category`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `project_category_rel`
--

INSERT INTO `project_category_rel` (`id`, `project`, `category`) VALUES
(9, 3, 15),
(8, 3, 14),
(7, 2, 13);

-- --------------------------------------------------------

--
-- Table structure for table `project_section_rel`
--

CREATE TABLE IF NOT EXISTS `project_section_rel` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `project` int(10) NOT NULL,
  `section` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project` (`project`),
  KEY `project_by_section` (`section`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE IF NOT EXISTS `sections` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT 'Unnamed Section',
  `description` varchar(255) NOT NULL DEFAULT 'No Description',
  `active` int(1) NOT NULL DEFAULT '1',
  `access` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `access` (`access`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT 'Unknown User',
  `username` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0',
  `access` int(2) NOT NULL,
  `rem_addr` varchar(40) NOT NULL DEFAULT 'XXXX',
  PRIMARY KEY (`id`),
  KEY `access` (`access`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `email`, `active`, `access`, `rem_addr`) VALUES
(1, 'Matthew', 'Treston Cal', '8d6db8b7203d647067a7d1b3bbf83f9c', 'mcrist1@cox.net', 1, 5, '70.184.28.118');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
