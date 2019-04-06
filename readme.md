# Chat Application
### Prerequisites
* [Composer](https://getcomposer.org/download/ "Download Composer")

-----------------------------------------------------------------------------------

### Installation
This is a guide that explains how to run this app on your local machine. Kindly, follow the following steps:

  1. First step is to install all dependencies needed, use `composer install` command to install all needed dependencies from `composer.json`
  
  2. Use this command `php artisan key:generate` to generate an app key for **Laravel** 

  3. The main database name is `ebtikar-chat`, if you want to change it open `.env` file, and modify it with your DB credentials
 ```sh
// Change those lines
DB_CONNECTION=ebtikar-chat
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mumm_blog
DB_USERNAME=root
DB_PASSWORD=
 ```
 
  4. Setting up your DB connection **and** creating the DB **manually**, run this command `php artisan migrate` to migrate tables.
  
  5. Lastly, you may run `php artisan serve` command. This command will start a development server.

___

### Help

##### Error
`SQLSTATE[42S01]: Base table or view already exists:`
_**Solution**_
Simply run the following commands
``` sh
$ php artisan migrate:fresh
```
