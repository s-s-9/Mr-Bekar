<?php
	//connect to database
	$servernamedb = 'localhost';
	$usernamedb = 'root';
	$passworddb = '';
	$dbnamedb = 'mr_baker';
	$connection = new mysqli($servernamedb, $usernamedb, $passworddb, $dbnamedb);
	if(!$connection){
		die('Connecting to database failed!');
	}
	
	//create users table 
	$sql = "CREATE TABLE `users` (
			`name` varchar(80) NOT NULL,
			`birthday` date NOT NULL,
			`gender` varchar(10) NOT NULL,
			`email` varchar(80) NOT NULL,
			`password` varchar(80) NOT NULL,
			`role` varchar(10) NOT NULL,
			PRIMARY KEY (`email`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1";
	if($connection->query($sql)===true){
		echo 'users';
	}
	else{
		echo 'error creating users';
	}
	
	//create bekars table
	$sql = "CREATE TABLE `bekars` (
			`email` varchar(80) NOT NULL,
			`resume` varchar(80) NOT NULL DEFAULT 'unavailable',
			`picture` varchar(80) NOT NULL,
			PRIMARY KEY (`email`),
			CONSTRAINT `bekars_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE
			) ENGINE=InnoDB DEFAULT CHARSET=latin1";
	if($connection->query($sql)===true){
		echo 'bekars';
	}
	else{
		echo 'error creating users';
	}
	
	//create companies table 
	$sql = "CREATE TABLE `companies` (
			 `email` varchar(80) NOT NULL,
			 `website` varchar(80) NOT NULL DEFAULT 'unavailable',
			 `logo` varchar(80) NOT NULL DEFAULT 'uploads/defaultcompany.png',
			 `jobsposted` int(11) NOT NULL,
			 `totalsalary` bigint(20) NOT NULL,
			 `interviewcalls` int(11) NOT NULL,
			 PRIMARY KEY (`email`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1";
	if($connection->query($sql)===true){
		echo 'companies';
	}
	else{
		echo 'error creating users';
	}
	
	//create bekarskills table 
	$sql = "CREATE TABLE `bekarskills` (
			 `email` varchar(80) NOT NULL,
			 `dbm` int(80) NOT NULL DEFAULT '0',
			 `gmd` int(80) NOT NULL DEFAULT '0',
			 `mad` int(80) NOT NULL DEFAULT '0',
			 `net` int(11) NOT NULL DEFAULT '0',
			 `oop` int(11) NOT NULL DEFAULT '0',
			 `prs` int(11) NOT NULL DEFAULT '0',
			 `wdb` int(11) NOT NULL DEFAULT '0',
			 `wdf` int(11) NOT NULL DEFAULT '0',
			 PRIMARY KEY (`email`),
			 CONSTRAINT `bekarskills_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE
			) ENGINE=InnoDB DEFAULT CHARSET=latin1";
	if($connection->query($sql)===true){
		echo 'bekarskills';
	}
	else{
		echo 'error creating users';
	}
	
	//create bekarlanguages table
	$sql = "CREATE TABLE `bekarlanguages` (
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
			 `fba` int(80) NOT NULL DEFAULT '0',
			 PRIMARY KEY (`email`),
			 CONSTRAINT `bekarlanguages_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE
			) ENGINE=InnoDB DEFAULT CHARSET=latin1";
	if($connection->query($sql)===true){
		echo 'bekarlanguages';
	}
	else{
		echo 'error creating users';
	}
	
	//create bekarprojects table
	$sql = "CREATE TABLE `bekarprojects` (
			 `email` varchar(80) NOT NULL,
			 `skill` varchar(80) NOT NULL,
			 `name` varchar(80) NOT NULL,
			 `description` text NOT NULL,
			 `projectfile` varchar(80) NOT NULL,
			 PRIMARY KEY (`projectfile`),
			 KEY `email` (`email`),
			 CONSTRAINT `bekarprojects_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE
			) ENGINE=InnoDB DEFAULT CHARSET=latin1";
	if($connection->query($sql)===true){
		echo 'bekarprojects';
	}
	else{
		echo 'error creating users';
	}
	
	//create jobs table
	$sql = "CREATE TABLE `jobs` (
			 `id` int(80) NOT NULL AUTO_INCREMENT,
			 `postedby` varchar(80) NOT NULL,
			 `position` varchar(80) NOT NULL,
			 `type` varchar(80) NOT NULL,
			 `salary` int(20) NOT NULL,
			 `deadline` date NOT NULL,
			 `circular` varchar(80) NOT NULL,
			 PRIMARY KEY (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1";
	if($connection->query($sql)===true){
		echo 'jobs';
	}
	else{
		echo 'error creating users';
	}
	
	//create jobskills table 
	$sql = "CREATE TABLE `jobskills` (
			 `id` int(20) NOT NULL,
			 `dbm` int(80) NOT NULL DEFAULT '0',
			 `gmd` int(80) NOT NULL DEFAULT '0',
			 `mad` int(80) NOT NULL DEFAULT '0',
			 `net` int(11) NOT NULL DEFAULT '0',
			 `oop` int(11) NOT NULL DEFAULT '0',
			 `prs` int(11) NOT NULL DEFAULT '0',
			 `wdb` int(11) NOT NULL DEFAULT '0',
			 `wdf` int(11) NOT NULL DEFAULT '0',
			 PRIMARY KEY (`id`),
			 CONSTRAINT `jobskills_ibfk_1` FOREIGN KEY (`id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE
			) ENGINE=InnoDB DEFAULT CHARSET=latin1";
	if($connection->query($sql)===true){
		echo 'jobskills';
	}
	else{
		echo 'error creating users';
	}
	
	//create joblanguages table
	$sql = "CREATE TABLE `joblanguages` (
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
			 `fba` int(80) NOT NULL DEFAULT '0',
			 PRIMARY KEY (`id`),
			 CONSTRAINT `joblanguages_ibfk_1` FOREIGN KEY (`id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE
			) ENGINE=InnoDB DEFAULT CHARSET=latin1";
	if($connection->query($sql)===true){
		echo 'joblanguages';
	}
	else{
		echo 'error creating users';
	}
	
	//create pendingapplications table
	$sql = "CREATE TABLE `pendingapplications` (
			 `id` int(20) NOT NULL,
			 `email` varchar(80) NOT NULL,
			 UNIQUE KEY `id` (`id`,`email`),
			 KEY `email` (`email`),
			 CONSTRAINT `pendingapplications_ibfk_1` FOREIGN KEY (`id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
			 CONSTRAINT `pendingapplications_ibfk_2` FOREIGN KEY (`email`) REFERENCES `bekars` (`email`) ON DELETE CASCADE
			) ENGINE=InnoDB DEFAULT CHARSET=latin1";
	if($connection->query($sql)===true){
		echo 'pendingapplications';
	}
	else{
		echo 'error creating users';
	}
	
	//create interviewcalls table
	$sql = "CREATE TABLE `interviewcalls` (
			 `id` int(20) NOT NULL,
			 `email` varchar(80) NOT NULL,
			 `interviewdate` date DEFAULT NULL,
			 `interviewtime` varchar(80) DEFAULT NULL,
			 UNIQUE KEY `id` (`id`,`email`),
			 KEY `email` (`email`),
			 CONSTRAINT `interviewcalls_ibfk_1` FOREIGN KEY (`id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
			 CONSTRAINT `interviewcalls_ibfk_2` FOREIGN KEY (`email`) REFERENCES `bekars` (`email`) ON DELETE CASCADE
			) ENGINE=InnoDB DEFAULT CHARSET=latin1
			";
	if($connection->query($sql)===true){
		echo 'interviewcalls';
	}
	else{
		echo 'error creating users';
	}
?>