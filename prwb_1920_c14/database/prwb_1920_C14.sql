git-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Mar 14 Avril 2020 à 13:02
-- Version du serveur :  5.7.11
-- Version de PHP :  5.6.18


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


DROP DATABASE IF EXISTS `prwb_1920_c14`;
CREATE DATABASE IF NOT EXISTS `prwb_1920_c14` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `prwb_1920_c14`;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `prwb_1920_c14`
--

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `CommentId` int(11) NOT NULL,
  `UserId` int(11) NOT NULL,
  `PostId` int(11) NOT NULL,
  `Body` text NOT NULL,
  `Timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `comment`
--

INSERT INTO `comment` (`CommentId`, `UserId`, `PostId`, `Body`, `Timestamp`) VALUES
(20, 3, 8, 'C1', '2019-12-10 13:54:18'),
(21, 3, 8, 'C2', '2019-12-10 13:54:23'),
(22, 3, 13, 'C3', '2019-12-10 13:54:34'),
(23, 4, 8, 'C3', '2020-02-04 19:00:21'),
(24, 4, 11, 'C4', '2020-02-04 19:00:26'),
(25, 4, 4, 'For anything more complex than [datapipe](https://angular.io/api/common/DatePipe) you should use moment.js', '2020-02-04 19:05:07'),
(26, 2, 4, 'Have you tried square brackets? `d [days so far in] LLLL `', '2020-02-07 14:19:15'),
(29, 4, 13, 'C6', '2020-02-08 18:20:09'),
(35, 1, 5, 'test', '2020-03-28 14:02:41'),
(37, 1, 7, 'test', '2020-03-28 14:06:59'),
(38, 1, 7, 'test2', '2020-03-28 14:09:49'),
(43, 1, 5, 'comment', '2020-03-28 14:14:23'),
(44, 1, 6, 'commentaire', '2020-03-28 14:17:12'),
(47, 1, 4, 'test', '2020-03-28 18:00:41'),
(48, 1, 4, 'test1', '2020-03-28 18:03:23'),
(49, 1, 4, 'essai', '2020-03-28 18:03:35'),
(50, 1, 4, 'essai', '2020-03-28 18:04:53'),
(51, 1, 5, 'hello', '2020-03-28 18:06:08'),
(52, 1, 7, 'hello', '2020-03-28 18:06:44'),
(53, 1, 7, 'essai1', '2020-03-28 18:10:23'),
(54, 1, 7, 'essai', '2020-03-28 18:13:08'),
(57, 1, 8, 'test2', '2020-03-31 17:20:19'),
(58, 1, 28, 'essai1', '2020-03-31 21:35:07'),
(59, 1, 29, 'essai1', '2020-03-31 21:35:25'),
(60, 1, 10, 'commentaire1', '2020-04-01 09:12:51');

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

CREATE TABLE `post` (
  `PostId` int(11) NOT NULL,
  `AuthorId` int(11) NOT NULL,
  `Title` varchar(256) DEFAULT NULL,
  `Body` text NOT NULL,
  `Timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `AcceptedAnswerId` int(11) DEFAULT NULL,
  `ParentId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `post`
--

INSERT INTO `post` (`PostId`, `AuthorId`, `Title`, `Body`, `Timestamp`, `AcceptedAnswerId`, `ParentId`) VALUES
(1, 1, 'What does \'initialization\' exactly mean?', 'My csapp book says that if global and static variables are initialized, than they are contained in .data section in ELF relocatable object file.\r\n\r\nSo my question is that if some `foo.c` code contains \r\n​```\r\nint a;\r\nint main()\r\n{\r\n    a = 3;\r\n}`\r\n​```\r\nand `example.c` contains,\r\n​```\r\nint b = 3;\r\nint main()\r\n{\r\n...\r\n}\r\n​```\r\nis it only `b` that considered to be initialized? In other words, does initialization mean declaration and definition in same line?', '2019-11-02 08:30:00', NULL, NULL),
(2, 2, '', 'It means exactly what it says. Initialized static storage duration objects will have their init values set before the main function is called. Not initialized will be zeroed. The second part of the statement is actually implementation dependant,  and implementation has the full freedom of the way it will be archived. \r\n\r\nWhen you declare the variable without the keyword `extern`  you always define it as well', '2019-11-02 08:31:00', NULL, 1),
(3, 3, '', 'Both are considered initialized\r\n------------------------------------\r\n\r\n\r\nThey get [zero initialized][1] or constant initalized (in short: if the right hand side is a compile time constant expression).\r\n\r\n> If permitted, Constant initialization takes place first (see Constant\r\n> initialization for the list of those situations). In practice,\r\n> constant initialization is usually performed at compile time, and\r\n> pre-calculated object representations are stored as part of the\r\n> program image. If the compiler doesn\'t do that, it still has to\r\n> guarantee that this initialization happens before any dynamic\r\n> initialization.\r\n> \r\n> For all other non-local static and thread-local variables, Zero\r\n> initialization takes place. In practice, variables that are going to\r\n> be zero-initialized are placed in the .bss segment of the program\r\n> image, which occupies no space on disk, and is zeroed out by the OS\r\n> when loading the program.\r\n\r\nTo sum up, if the implementation cannot constant initialize it, then it must first zero initialize and then initialize it before any dynamic initialization happends.\r\n\r\n\r\n  [1]: https://en.cppreference.com/w/cpp/language/zero_initialization\r\n\r\n', '2019-11-02 08:32:00', NULL, 1),
(4, 1, 'How do I escape characters in an Angular date pipe?', 'I have an Angular date variable `today` that I\'m using the [date pipe][1] on, like so:\r\n\r\n    {{today | date:\'LLLL d\'}}\r\n\r\n> February 13\r\n\r\nHowever, I would like to make it appear like this:\r\n\r\n> 13 days so far in February\r\n\r\nWhen I try a naive approach to this, I get this result:\r\n\r\n    {{today | date:\'d days so far in LLLL\'}}\r\n\r\n> 13 13PM201818 18o fPMr in February\r\n\r\nThis is because, for instance `d` refers to the day.\r\n\r\nHow can I escape these characters in an Angular date pipe? I tried `\\d` and such, but the result did not change with the added backslashes.\r\n  [1]: https://angular.io/api/common/DatePipe', '2019-11-02 08:33:00', 7, NULL),
(5, 1, '', 'How about this:\r\n\r\n    {{today | date:\'d \\\'days so far in\\\' LLLL\'}}\r\n\r\nAnything inside single quotes is ignored. Just don\'t forget to escape them.', '2019-11-02 08:34:00', NULL, 4),
(6, 3, '', 'Then only other alternative to stringing multiple pipes together as suggested by RichMcCluskey would be to create a custom pipe that calls through to momentjs format with the passed in date. Then you could use the same syntax including escape sequence that momentjs supports.\r\n\r\nSomething like this could work, it is not an exhaustive solution in that it does not deal with localization at all and there is no error handling code or tests.\r\n\r\n	import { Inject, Pipe, PipeTransform } from \'@angular/core\';\r\n\r\n	@Pipe({ name: \'momentDate\', pure: true })\r\n	export class MomentDatePipe implements PipeTransform {\r\n\r\n		transform(value: any, pattern: string): string {\r\n			if (!value)\r\n				return \'\';\r\n			return moment(value).format(pattern);\r\n		}\r\n	}\r\n\r\nAnd then the calling code:\r\n\r\n    {{today | momentDate:\'d [days so far in] LLLL\'}}\r\n\r\nFor all the format specifiers see the [documentation for format][1]. \r\n\r\nKeep in mind you do have to import `momentjs` either as an import statement, have it imported in your cli config file, or reference the library from the root HTML page (like index.html).\r\n\r\n\r\n\r\n  [1]: http://momentjs.com/docs/#/displaying/format/', '2019-12-10 13:50:27', NULL, 4),
(7, 2, '', 'As far as I know this is not possible with the Angular date pipe at the time of this answer. One alternative is to use multiple date pipes like so:\r\n\r\n    {{today | date:\'d\'}} days so far in {{today | date:\'LLLL\'}}\r\n\r\nEDIT:\r\n\r\nAfter posting this I tried @Gh0sT \'s solution and it worked, so I guess there is a way to use one date pipe.', '2019-11-02 08:36:00', NULL, 4),
(8, 5, 'Q1', 'Q1', '2019-12-02 08:00:00', NULL, NULL),
(9, 1, '', 'R1', '2019-12-02 08:05:00', NULL, 8),
(10, 2, '', 'R2', '2019-12-02 08:03:00', NULL, 8),
(11, 3, '', 'R3', '2019-12-02 08:04:00', NULL, 8),
(12, 4, 'Q2', 'Q2', '2019-12-02 09:00:00', NULL, NULL),
(13, 5, '', 'R4', '2019-12-02 09:01:00', NULL, 12),
(14, 1, 'Q3', 'Q3', '2019-12-02 10:00:00', NULL, NULL),
(15, 3, '', 'R5', '2019-12-02 10:02:00', NULL, 14),
(16, 3, '', 'R6', '2019-12-02 10:02:00', NULL, 14),
(17, 2, 'Q4', 'Q4', '2019-12-02 11:00:00', NULL, NULL),
(18, 3, '', 'R7', '2019-12-02 10:02:00', NULL, 17),
(19, 4, 'Q5', 'Q5', '2019-12-02 11:00:00', NULL, NULL),
(20, 3, '', 'R8', '2019-12-02 10:02:00', NULL, 19),
(22, 4, 'Should \'Comparable\' be a \'Functional interface\'?', 'The definition of a functional interface is "A functional interface is an interface that has just one abstract method (aside from the methods of Object ), and thus represents a single function contract."\r\n\r\nAccording to this definition, the `Comparable<T>` is definitely a functional interface.\r\n\r\nThe definition of a lambda expression is "A lambda expression is like a method: it provides a list of formal parameters and a body - an expression or block - expressed in terms of those parameters."\r\n\r\nEvaluation of a lambda expression produces an instance of a functional interface.\r\n\r\nThus, the purpose of the lambda expression is to be able to create an instance of the functional interface, by implementing the single function of the functional interface. ie. to allow the creation of an instance with the single function.\r\n\r\nLet us look at Comparable<T>, is this interface designed for use as a single function? ie. was it designed for the creation of instances with this single function only?\r\n\r\nThe documentation of Comparable<T> starts with "This interface imposes a total ordering on the objects of each class that implements it. This ordering is referred to as the class\'s natural ordering, and the class\'s compareTo method is referred to as its natural comparison method."\r\n\r\nThe above sentence makes it clear that the Comparable<T> is not designed to be used as a single function, but is always meant to be implemented by a class, which has natural ordering for its instances, by adding this single function.\r\n\r\nWhich would mean that it is not designed to be created by using a lambda expression?\r\n\r\nThe point is that we would not have any object which is just Comparable only, it is meant to be implemented and thus used as an additional function for a class.\r\n\r\nSo, is there a way in the Java language, by which creation of a lambda expression for Comparable<T> is prevented? Can the designer of an interface decide that this interface is meant to be implemented by a class and not meant to be created as an instance with this single method by use of a lambda expression?\r\n\r\nSimply because an interface happens to have a single abstract method, it should not be considered as a functional interface.\r\n\r\nMaybe, if Java provides an annotation like NotFunctional, it can be checked by the compiler that this interface is not used for the creation of a lambda expression, eg.\r\n\r\n```java\r\n@NotFunctional\r\npublic interface Comparable<T> { public int compareTo(T t); }\r\n	```\r\n	', '2020-01-17 22:25:28', NULL, NULL),
(24, 4, 'Q6', '', '2020-02-08 17:24:16', NULL, NULL),
(27, 4, NULL, 'R9', '2020-02-08 18:18:15', NULL, 8),
(28, 5, 'Converting timestamp to time ago in PHP e.g 1 day ago, 2 days ago...', 'I am trying to convert a timestamp of the format `2009-09-12 20:57:19` and turn it into something like `3 minutes ago` with PHP.\r\n\r\nI found a useful script to do this, but I think it\'s looking for a different format to be used as the time variable. The script I\'m wanting to modify to work with this format is:\r\n\r\n    function _ago($tm,$rcs = 0) {\r\n        $cur_tm = time(); \r\n        $dif = $cur_tm-$tm;\r\n        $pds = array(\'second\',\'minute\',\'hour\',\'day\',\'week\',\'month\',\'year\',\'decade\');\r\n        $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);\r\n\r\n        for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);\r\n            $no = floor($no);\r\n            if($no <> 1)\r\n                $pds[$v] .=\'s\';\r\n            $x = sprintf("%d %s ",$no,$pds[$v]);\r\n            if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0))\r\n                $x .= time_ago($_tm);\r\n            return $x;\r\n        }\r\n\r\nI think on those first few lines the script is trying to do something that looks like this (different date format math):\r\n\r\n    $dif = 1252809479 - 2009-09-12 20:57:19;\r\n\r\nHow would I go about converting my timestamp into that (unix?) format?', '2020-02-08 18:56:07', 29, NULL),
(29, 2, '', '###Use example :\r\n\r\n    echo time_elapsed_string(\'2013-05-01 00:22:35\');\r\n    echo time_elapsed_string(\'@1367367755\'); # timestamp input\r\n    echo time_elapsed_string(\'2013-05-01 00:22:35\', true);\r\n\r\nInput can be any [supported date and time format][1].\r\n\r\n###Output :\r\n\r\n    4 months ago\r\n    4 months, 2 weeks, 3 days, 1 hour, 49 minutes, 15 seconds ago\r\n\r\n###Function :\r\n\r\n    function time_elapsed_string($datetime, $full = false) {\r\n    	$now = new DateTime;\r\n    	$ago = new DateTime($datetime);\r\n    	$diff = $now->diff($ago);\r\n		\r\n		$diff->w = floor($diff->d / 7);\r\n		$diff->d -= $diff->w * 7;\r\n    	\r\n    	$string = array(\r\n    		\'y\' => \'year\',\r\n    		\'m\' => \'month\',\r\n    		\'w\' => \'week\',\r\n    		\'d\' => \'day\',\r\n    		\'h\' => \'hour\',\r\n    		\'i\' => \'minute\',\r\n    		\'s\' => \'second\',\r\n    	);\r\n    	foreach ($string as $k => &$v) {\r\n    		if ($diff->$k) {\r\n    			$v = $diff->$k . \' \' . $v . ($diff->$k > 1 ? \'s\' : \'\');\r\n    		} else {\r\n				unset($string[$k]);\r\n			}\r\n    	}\r\n\r\n		if (!$full) $string = array_slice($string, 0, 1);\r\n    	return $string ? implode(\', \', $string) . \' ago\' : \'just now\';\r\n    }\r\n\r\n\r\n[1]: http://www.php.net/manual/en/datetime.formats.php\r\n\r\n', '2020-02-08 18:57:10', NULL, 28),
(30, 1, 'Q7', 'Q7', '2020-02-08 19:02:59', NULL, NULL),
(33, 1, 'MySQL output not formatted when executed through php', 'I\'m trying to get formatted output from mysql as it normally shows when executed from a shell. This is discussed here and here, but it\'s not working for me.\r\n\r\nWhen I run this in my shell for instance:\r\n\r\nmysql -e &quot;select language_id, name, image from `language`;&quot; my_database', '2020-03-31 22:00:35', NULL, NULL),
(36, 1, 'MySQL output not formatted when executed through php', 'I\'m trying to get formatted output from mysql as it normally shows when executed from a shell. This is discussed here and here, but it\'s not working for me.\r\n\r\nWhen I run this in my shell for instance:\r\n\r\nmysql -e &quot;select language_id, name, image from `language`;&quot; my_database', '2020-04-01 13:56:43', NULL, NULL),
(37, 1, 'question', 'cpor', '2020-04-01 14:02:40', NULL, NULL),
(38, 1, 'question', 'cpor', '2020-04-01 14:02:40', NULL, NULL),
(39, 1, 'question', 'cpor', '2020-04-01 14:06:06', NULL, NULL),
(40, 1, 'question', 'corps de la question', '2020-04-01 14:09:23', NULL, NULL),
(41, 1, 'question', 'corps de la question', '2020-04-01 14:10:43', NULL, NULL),
(42, 1, 'glk', 'question', '2020-04-01 14:14:43', NULL, NULL),
(43, 1, 'glk', 'question', '2020-04-01 14:22:03', NULL, NULL),
(44, 1, 'glk', 'question', '2020-04-01 14:22:53', NULL, NULL),
(45, 1, 'eassai', 'question', '2020-04-01 14:23:43', NULL, NULL),
(46, 1, 'eassai', 'question', '2020-04-01 14:24:47', NULL, NULL),
(47, 1, 'eassai', 'question', '2020-04-01 14:25:28', NULL, NULL),
(48, 1, 'touriste', 'hello je suis une touriste', '2020-04-01 14:27:58', NULL, NULL),
(49, 1, 'Jquery validation not working for second row in table', 'I am adding the rows dynamically. Code is inside JQuery steps. Jquery validation is working on first row in table but not on second row in table. I meant, when I click next without typing anything, it shows the tab as red color. But this is not happening for second row in table.\r\n\r\nAm I missing anything?', '2020-04-01 16:02:01', NULL, NULL),
(50, 1, 'Routing Problem with custom language code on asp.net core', 'I am attempting to add a custom language code before the controller using asp.net core 3.1, and the language code is not a standard cultural code on C#.', '2020-04-01 16:41:22', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `posttag`
--

CREATE TABLE `posttag` (
  `PostId` int(11) NOT NULL,
  `TagId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `posttag`
--

INSERT INTO `posttag` (`PostId`, `TagId`) VALUES
(8, 8),
(8, 9),
(12, 9),
(30, 9),
(49, 9),
(1, 10),
(28, 11),
(44, 11),
(4, 12),
(4, 13),
(28, 13),
(22, 14),
(49, 14),
(48, 20),
(47, 26),
(48, 26),
(50, 26),
(45, 27),
(46, 27),
(49, 27);

-- --------------------------------------------------------

--
-- Structure de la table `tag`
--

CREATE TABLE `tag` (
  `TagId` int(11) NOT NULL,
  `TagName` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `tag`
--

INSERT INTO `tag` (`TagId`, `TagName`) VALUES
(22, 'Ajax'),
(12, 'Angular'),
(10, 'C#'),
(26, 'GIT'),
(20, 'HTML'),
(14, 'Java'),
(17, 'JavaSript'),
(27, 'mysql'),
(11, 'PHP'),
(8, 'Tag1'),
(9, 'Tag2'),
(16, 'Tag3'),
(13, 'Time');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `UserId` int(11) NOT NULL,
  `UserName` varchar(128) NOT NULL,
  `Password` varchar(256) NOT NULL,
  `FullName` varchar(256) NOT NULL,
  `Email` varchar(128) NOT NULL,
  `Role` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`UserId`, `UserName`, `Password`, `FullName`, `Email`, `Role`) VALUES
(1, 'ben', '56ce92d1de4f05017cf03d6cd514d6d1', 'Benoît Penelle', 'ben@test.com', 'admin'),
(2, 'bruno', '56ce92d1de4f05017cf03d6cd514d6d1', 'Bruno Lacroix', 'bruno@test.com', 'user'),
(3, 'admin', '56ce92d1de4f05017cf03d6cd514d6d1', 'Administrator', 'admin@test.com', 'admin'),
(4, 'boris', '56ce92d1de4f05017cf03d6cd514d6d1', 'Boris Verhaegen', 'boris@test.com', 'admin'),
(5, 'alain', '56ce92d1de4f05017cf03d6cd514d6d1', 'Alain Silovy', 'alain@test.com', 'user'),
(6, 'nino', '56ce92d1de4f05017cf03d6cd514d6d1', 'Ingrid Martine', 'nino@gmail.com', 'user');

-- --------------------------------------------------------

--
-- Structure de la table `vote`
--

CREATE TABLE `vote` (
  `UserId` int(11) NOT NULL,
  `PostId` int(11) NOT NULL,
  `UpDown` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `vote`
--

INSERT INTO `vote` (`UserId`, `PostId`, `UpDown`) VALUES
(1, 5, 1),
(1, 8, -1),
(1, 11, 1),
(1, 12, 1),
(1, 13, -1),
(1, 18, 1),
(1, 28, 1),
(2, 1, -1),
(2, 3, 1),
(2, 9, -1),
(2, 11, 1),
(2, 12, 1),
(2, 14, -1),
(2, 15, -1),
(3, 1, -1),
(3, 2, -1),
(3, 5, -1),
(3, 12, 1),
(4, 7, 1),
(4, 8, -1),
(4, 9, 1),
(4, 11, 1),
(4, 16, -1),
(4, 29, 1),
(5, 1, 1),
(5, 5, 1),
(5, 29, 1);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`CommentId`),
  ADD KEY `UserId` (`UserId`),
  ADD KEY `PostId` (`PostId`);

--
-- Index pour la table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`PostId`),
  ADD KEY `Author` (`AuthorId`),
  ADD KEY `ParentId` (`ParentId`),
  ADD KEY `AcceptedAnswerId` (`AcceptedAnswerId`);

--
-- Index pour la table `posttag`
--
ALTER TABLE `posttag`
  ADD PRIMARY KEY (`PostId`,`TagId`),
  ADD KEY `TagId` (`TagId`);

--
-- Index pour la table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`TagId`),
  ADD UNIQUE KEY `TagName` (`TagName`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserId`),
  ADD UNIQUE KEY `UserName` (`UserName`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Index pour la table `vote`
--
ALTER TABLE `vote`
  ADD PRIMARY KEY (`UserId`,`PostId`),
  ADD KEY `PostId` (`PostId`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `CommentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `PostId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT pour la table `tag`
--
ALTER TABLE `tag`
  MODIFY `TagId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `UserId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `user` (`UserId`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`PostId`) REFERENCES `post` (`PostId`);

--
-- Contraintes pour la table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`AuthorId`) REFERENCES `user` (`UserId`),
  ADD CONSTRAINT `post_ibfk_2` FOREIGN KEY (`ParentId`) REFERENCES `post` (`PostId`),
  ADD CONSTRAINT `post_ibfk_3` FOREIGN KEY (`AcceptedAnswerId`) REFERENCES `post` (`PostId`);

--
-- Contraintes pour la table `posttag`
--
ALTER TABLE `posttag`
  ADD CONSTRAINT `posttag_ibfk_1` FOREIGN KEY (`PostId`) REFERENCES `post` (`PostId`),
  ADD CONSTRAINT `posttag_ibfk_2` FOREIGN KEY (`TagId`) REFERENCES `tag` (`TagId`);

--
-- Contraintes pour la table `vote`
--
ALTER TABLE `vote`
  ADD CONSTRAINT `vote_ibfk_1` FOREIGN KEY (`PostId`) REFERENCES `post` (`PostId`),
  ADD CONSTRAINT `vote_ibfk_2` FOREIGN KEY (`UserId`) REFERENCES `user` (`UserId`);


--
-- Métadonnées
--
USE `phpmyadmin`;

--
-- Métadonnées pour comment
--

--
-- Métadonnées pour post
--

--
-- Métadonnées pour posttag
--

--
-- Métadonnées pour tag
--

--
-- Métadonnées pour user
--

--
-- Métadonnées pour vote
--

--
-- Contenu de la table `pma__table_uiprefs`
--

INSERT INTO `pma__table_uiprefs` (`username`, `db_name`, `table_name`, `prefs`, `last_update`) VALUES
('root', 'prwb_1920_c14', 'vote', '{"sorted_col":"`vote`.`UpDown`  ASC"}', '2020-03-23 17:15:41');

--
-- Métadonnées pour prwb_1920_c14
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
