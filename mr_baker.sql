-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 19, 2017 at 07:53 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mr_baker`
--

-- --------------------------------------------------------

--
-- Table structure for table `bekarlanguages`
--

CREATE TABLE `bekarlanguages` (
  `email` varchar(80) NOT NULL,
  `cor` int(80) NOT NULL DEFAULT '0',
  `cpp` int(80) NOT NULL DEFAULT '0',
  `jav` int(80) NOT NULL DEFAULT '0',
  `pyt` int(80) NOT NULL DEFAULT '0',
  `htm` int(80) NOT NULL DEFAULT '0',
  `css` int(80) NOT NULL DEFAULT '0',
  `php` int(80) NOT NULL DEFAULT '0',
  `msq` int(80) NOT NULL DEFAULT '0',
  `csh` int(80) NOT NULL DEFAULT '0',
  `rub` int(11) NOT NULL DEFAULT '0',
  `per` int(11) NOT NULL DEFAULT '0',
  `njs` int(80) NOT NULL DEFAULT '0',
  `dot` int(80) NOT NULL DEFAULT '0',
  `mon` int(80) NOT NULL DEFAULT '0',
  `vba` int(80) NOT NULL DEFAULT '0',
  `fba` int(80) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bekarlanguages`
--

INSERT INTO `bekarlanguages` (`email`, `cor`, `cpp`, `jav`, `pyt`, `htm`, `css`, `php`, `msq`, `csh`, `rub`, `per`, `njs`, `dot`, `mon`, `vba`, `fba`) VALUES
('jabiribnekamal@gmail.com', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
('keysersoze@gmail.com', 0, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0),
('sayemshuvo19@gmail.com', 0, 0, 0, 0, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0),
('shadmansoumik9@gmail.com', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `bekarprojects`
--

CREATE TABLE `bekarprojects` (
  `email` varchar(80) NOT NULL,
  `skill` varchar(80) NOT NULL,
  `name` varchar(80) NOT NULL,
  `description` text NOT NULL,
  `projectfile` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bekarprojects`
--

INSERT INTO `bekarprojects` (`email`, `skill`, `name`, `description`, `projectfile`) VALUES
('sayemshuvo19@gmail.com', 'wdf', 'Mr. Baker', 'A website for pathetic bekars to find a job.', 'uploads/59939d64dbfad8.04748840project.rar');

-- --------------------------------------------------------

--
-- Table structure for table `bekars`
--

CREATE TABLE `bekars` (
  `email` varchar(80) NOT NULL,
  `resume` varchar(80) NOT NULL DEFAULT 'unavailable',
  `picture` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bekars`
--

INSERT INTO `bekars` (`email`, `resume`, `picture`) VALUES
('jabiribnekamal@gmail.com', 'unavailable', 'uploads/defaultbekar.png'),
('keysersoze@gmail.com', 'uploads/598af18bb84d16.04313681resume.pdf', 'uploads/598af1c7ebadc9.26173070profilepic.jpg'),
('sayemshuvo19@gmail.com', 'unavailable', 'uploads/5993592ca56e42.91943874profilepic.jpg'),
('shadmansoumik9@gmail.com', 'unavailable', 'uploads/defaultbekar.png');

-- --------------------------------------------------------

--
-- Table structure for table `bekarskills`
--

CREATE TABLE `bekarskills` (
  `email` varchar(80) NOT NULL,
  `dbm` int(80) NOT NULL DEFAULT '0',
  `gmd` int(80) NOT NULL DEFAULT '0',
  `mad` int(80) NOT NULL DEFAULT '0',
  `net` int(11) NOT NULL DEFAULT '0',
  `oop` int(11) NOT NULL DEFAULT '0',
  `prs` int(11) NOT NULL DEFAULT '0',
  `wdb` int(11) NOT NULL DEFAULT '0',
  `wdf` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bekarskills`
--

INSERT INTO `bekarskills` (`email`, `dbm`, `gmd`, `mad`, `net`, `oop`, `prs`, `wdb`, `wdf`) VALUES
('jabiribnekamal@gmail.com', 1, 0, 0, 0, 0, 0, 0, 0),
('keysersoze@gmail.com', 0, 1, 1, 0, 1, 0, 0, 0),
('sayemshuvo19@gmail.com', 0, 0, 0, 0, 0, 0, 1, 1),
('shadmansoumik9@gmail.com', 0, 0, 0, 0, 0, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `email` varchar(80) NOT NULL,
  `website` varchar(80) NOT NULL DEFAULT 'unavailable',
  `logo` varchar(80) NOT NULL DEFAULT 'uploads/defaultcompany.png',
  `jobsposted` int(11) NOT NULL,
  `totalsalary` bigint(20) NOT NULL,
  `interviewcalls` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`email`, `website`, `logo`, `jobsposted`, `totalsalary`, `interviewcalls`) VALUES
('guru@gmail.com', 'unavailable', 'uploads/defaultcompany.png', 3, 90000, 2),
('samiamuntaha@gmail.com', 'https://www.facebook.com', 'uploads/599d62c960f346.24142601logo.jpg', 7, 216000, 4);

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `email` varchar(80) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `email`, `message`) VALUES
(2, 's@g.c', 'isuck!'),
(3, 'shadmansoumik9@gmail.com', 'ss9 jo o o o oss!');

-- --------------------------------------------------------

--
-- Table structure for table `interviewcalls`
--

CREATE TABLE `interviewcalls` (
  `id` int(20) NOT NULL,
  `email` varchar(80) NOT NULL,
  `interviewdate` date DEFAULT NULL,
  `interviewtime` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `interviewcalls`
--

INSERT INTO `interviewcalls` (`id`, `email`, `interviewdate`, `interviewtime`) VALUES
(38, 'sayemshuvo19@gmail.com', '2017-08-31', '12:34'),
(38, 'shadmansoumik9@gmail.com', '2017-08-30', '12:34'),
(44, 'keysersoze@gmail.com', '2017-08-30', '00:34'),
(45, 'keysersoze@gmail.com', '2017-08-30', '12:34'),
(46, 'sayemshuvo19@gmail.com', '2017-08-30', '04:56');

-- --------------------------------------------------------

--
-- Table structure for table `joblanguages`
--

CREATE TABLE `joblanguages` (
  `id` int(20) NOT NULL,
  `cor` int(80) NOT NULL DEFAULT '0',
  `cpp` int(80) NOT NULL DEFAULT '0',
  `jav` int(80) NOT NULL DEFAULT '0',
  `pyt` int(80) NOT NULL DEFAULT '0',
  `htm` int(80) NOT NULL DEFAULT '0',
  `css` int(80) NOT NULL DEFAULT '0',
  `php` int(80) NOT NULL DEFAULT '0',
  `msq` int(80) NOT NULL DEFAULT '0',
  `csh` int(80) NOT NULL DEFAULT '0',
  `rub` int(11) NOT NULL DEFAULT '0',
  `per` int(11) NOT NULL DEFAULT '0',
  `njs` int(80) NOT NULL DEFAULT '0',
  `dot` int(80) NOT NULL DEFAULT '0',
  `mon` int(80) NOT NULL DEFAULT '0',
  `vba` int(80) NOT NULL DEFAULT '0',
  `fba` int(80) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `joblanguages`
--

INSERT INTO `joblanguages` (`id`, `cor`, `cpp`, `jav`, `pyt`, `htm`, `css`, `php`, `msq`, `csh`, `rub`, `per`, `njs`, `dot`, `mon`, `vba`, `fba`) VALUES
(36, 0, 0, 0, 0, 1, 1, 1, 1, 0, 0, 0, 1, 0, 0, 0, 0),
(38, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(43, 0, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(44, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(45, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(46, 0, 0, 0, 0, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(80) NOT NULL,
  `postedby` varchar(80) NOT NULL,
  `position` varchar(80) NOT NULL,
  `type` varchar(80) NOT NULL,
  `salary` int(20) NOT NULL,
  `deadline` date NOT NULL,
  `circular` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `postedby`, `position`, `type`, `salary`, `deadline`, `circular`) VALUES
(36, 'samiamuntaha@gmail.com', 'Web Developer', 'Permanent', 35000, '2017-08-31', 'uploads/599e12ea801b12.51138253circular.docx'),
(38, 'samiamuntaha@gmail.com', 'Data Entry', 'Part Time', 6000, '2017-08-31', 'uploads/599f07d18b1625.55331327circular.docx'),
(43, 'guru@gmail.com', 'Software Developer', 'Permanent', 40000, '2017-08-31', 'uploads/59a3dcc4d23823.86483598circular.docx'),
(44, 'samiamuntaha@gmail.com', 'Testing Job ', 'Permanent', 15000, '2017-09-15', 'uploads/59a4c73e228456.40698111circular.docx'),
(45, 'samiamuntaha@gmail.com', 'Job Poster', 'Part Time', 20000, '2017-09-27', 'uploads/59a4c76a239ea9.33978109circular.docx'),
(46, 'guru@gmail.com', 'Notun Chakri', 'Permanent', 20000, '2017-09-14', 'uploads/59a59487730dc8.94409653circular.docx');

-- --------------------------------------------------------

--
-- Table structure for table `jobskills`
--

CREATE TABLE `jobskills` (
  `id` int(20) NOT NULL,
  `dbm` int(80) NOT NULL DEFAULT '0',
  `gmd` int(80) NOT NULL DEFAULT '0',
  `mad` int(80) NOT NULL DEFAULT '0',
  `net` int(11) NOT NULL DEFAULT '0',
  `oop` int(11) NOT NULL DEFAULT '0',
  `prs` int(11) NOT NULL DEFAULT '0',
  `wdb` int(11) NOT NULL DEFAULT '0',
  `wdf` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jobskills`
--

INSERT INTO `jobskills` (`id`, `dbm`, `gmd`, `mad`, `net`, `oop`, `prs`, `wdb`, `wdf`) VALUES
(36, 1, 0, 0, 0, 0, 0, 1, 1),
(38, 0, 0, 0, 0, 0, 0, 0, 0),
(43, 0, 0, 0, 0, 1, 1, 0, 0),
(44, 1, 0, 0, 0, 0, 0, 0, 0),
(45, 0, 0, 0, 0, 0, 1, 0, 0),
(46, 0, 0, 0, 0, 0, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pendingapplications`
--

CREATE TABLE `pendingapplications` (
  `id` int(20) NOT NULL,
  `email` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `skillstolanguages`
--

CREATE TABLE `skillstolanguages` (
  `skill` varchar(80) NOT NULL,
  `language1` varchar(80) NOT NULL DEFAULT 'unavailable',
  `language2` varchar(80) NOT NULL DEFAULT 'unavailable',
  `language3` varchar(80) NOT NULL DEFAULT 'unavailable',
  `language4` varchar(80) NOT NULL DEFAULT 'unavailable',
  `language5` varchar(80) NOT NULL DEFAULT 'unavailable'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `skillstolanguages`
--

INSERT INTO `skillstolanguages` (`skill`, `language1`, `language2`, `language3`, `language4`, `language5`) VALUES
('Database Management', 'MySql', 'Oracle', 'MongoDB', 'SQLite', 'Firebase'),
('Game Development', 'C++', 'Python', 'Java', 'C#', 'Swift'),
('Mobile App Development', 'C#', 'Javascript', 'Java', 'HTML5', 'Ruby'),
('Networking', 'C++', 'C#', 'Objective-C', 'Java', 'Python'),
('Object Oriented Programming', 'C++', 'PHP', 'Python', 'Java', 'Visual Basic'),
('Problem Solving', 'C', 'C++', 'Python', 'Java', 'C#'),
('Web Development (Back End)', 'PHP', 'Node JS', 'Python', 'Perl', 'Java'),
('Web Development (Front End)', 'html5', 'css3', 'javascript', 'Ruby', '.NET');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `name` varchar(80) NOT NULL,
  `birthday` date NOT NULL,
  `gender` varchar(10) NOT NULL,
  `email` varchar(80) NOT NULL,
  `password` varchar(80) NOT NULL,
  `role` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`name`, `birthday`, `gender`, `email`, `password`, `role`) VALUES
('Guru Turjo', '0000-00-00', '0', 'guru@gmail.com', 'guruguru', 'company'),
('Jabir Ibne Buira', '1989-02-01', 'male', 'jabiribnekamal@gmail.com', '12345', 'baker'),
('Keyser Soze', '2017-08-09', 'male', 'keysersoze@gmail.com', 'keysersoze', 'baker'),
('Samia Muntaha', '0000-00-00', '0', 'samiamuntaha@gmail.com', '13579', 'company'),
('Sayem Shuvo', '1993-01-19', 'male', 'sayemshuvo19@gmail.com', '12345', 'baker'),
('Shadman Soumik', '1993-05-22', 'male', 'shadmansoumik9@gmail.com', '02468', 'baker');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bekarlanguages`
--
ALTER TABLE `bekarlanguages`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `bekarprojects`
--
ALTER TABLE `bekarprojects`
  ADD PRIMARY KEY (`projectfile`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `bekars`
--
ALTER TABLE `bekars`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `bekarskills`
--
ALTER TABLE `bekarskills`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `interviewcalls`
--
ALTER TABLE `interviewcalls`
  ADD UNIQUE KEY `id` (`id`,`email`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `joblanguages`
--
ALTER TABLE `joblanguages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobskills`
--
ALTER TABLE `jobskills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pendingapplications`
--
ALTER TABLE `pendingapplications`
  ADD UNIQUE KEY `id` (`id`,`email`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `skillstolanguages`
--
ALTER TABLE `skillstolanguages`
  ADD PRIMARY KEY (`skill`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(80) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `bekarlanguages`
--
ALTER TABLE `bekarlanguages`
  ADD CONSTRAINT `bekarlanguages_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE;

--
-- Constraints for table `bekarprojects`
--
ALTER TABLE `bekarprojects`
  ADD CONSTRAINT `bekarprojects_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE;

--
-- Constraints for table `bekars`
--
ALTER TABLE `bekars`
  ADD CONSTRAINT `bekars_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE;

--
-- Constraints for table `bekarskills`
--
ALTER TABLE `bekarskills`
  ADD CONSTRAINT `bekarskills_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE;

--
-- Constraints for table `interviewcalls`
--
ALTER TABLE `interviewcalls`
  ADD CONSTRAINT `interviewcalls_ibfk_1` FOREIGN KEY (`id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `interviewcalls_ibfk_2` FOREIGN KEY (`email`) REFERENCES `bekars` (`email`) ON DELETE CASCADE;

--
-- Constraints for table `joblanguages`
--
ALTER TABLE `joblanguages`
  ADD CONSTRAINT `joblanguages_ibfk_1` FOREIGN KEY (`id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jobskills`
--
ALTER TABLE `jobskills`
  ADD CONSTRAINT `jobskills_ibfk_1` FOREIGN KEY (`id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pendingapplications`
--
ALTER TABLE `pendingapplications`
  ADD CONSTRAINT `pendingapplications_ibfk_1` FOREIGN KEY (`id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pendingapplications_ibfk_2` FOREIGN KEY (`email`) REFERENCES `bekars` (`email`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
