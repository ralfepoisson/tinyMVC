<?php
/**
 * Application Configuration
 * 
 * This file contains the configuration for the application making use of
 * the tinyMVC framework.
 * 
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 * @version 0.1
 * @license GPL3
 */

// We will store the configuration inside an object which we will pass to tinyMVC
$configuration = new Object();

/**
 * log_file
 * The location of the application's log files. This can either be a string literal, or it could be dynamic.
 * For example, it could be set to "/var/log/my_apps_name/" . date("Ymd") . ".log", in which case logs would be written
 * to the /var/log/my_apps_name directory, and there would be a separate log for each day, where the file name would be
 * in the format of YYYYMMDD.log
 */
$configuration->log_file = "/var/log/myapp/" . date("Ymd") . ".log";

/**
 * db_engine
 * The database technology to use. The possible values for this are:
 * "mysql" : The MySQL database engine.
 * "mssql" : The Microsoft SQL Server database engine.
 */
$configuration->db_engine = "mysql";

/**
 * db_host
 * This is the IP address or the domain name of the server which is hosting the database. If the same server that this code
 * is running on is also the database server, you could use "localhost".
 */
$configuration->db_host = "localhost";

/**
 * db_user
 * The username with which to authenticate against the database server.
 */
$configuration->db_user = "username";

/**
 * db_pass
 * The password for the db_user, with which to authenticate against database server.
 */
$configuration->db_pass = "password";

/**
 * db_db
 * The name of the database to use hosted in the database server sepcified above (db_host).
 */
$configuration->db_db = "myapp";

/**
 * debug
 * This is a boolean flag used to determine whether or not to output debug information when things go wrong. It is recommended
 * that whilst in development, this is set to true, and for production this is set to false.
 */
$configuration->debug = true;

