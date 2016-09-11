DBGov
======

DBGov is a tool that allows you to mass create MySQL users and databases based of a csv-file with names in it.

The users are grouped to classes (1 csv-file per class) and you can download the generated usernames and passwords as a pdf file.


Get Started
-----------

#### Requirements

To run this application on your machine, you need at least:

* >= PHP 5.5
* >= Phalcon 3.0
* Apache Web Server with `mod_rewrite enabled`, and `AllowOverride Options` (or `All`) in your `httpd.conf` or or Nginx Web Server
* Latest Phalcon Framework extension installed/enabled
* MySQL >= 5.1.5

Then you'll need to create the database and initialize schema (included):

    echo 'CREATE DATABASE vokuro' | mysql -u root
    cat schemas/vokuro.sql | mysql -u root vokuro

Installing Dependencies via Composer
------------------------------------
Vökuró's dependencies must be installed using Composer. Install composer in a common location or in your project:

```bash
curl -s http://getcomposer.org/installer | php
```

Run the composer installer:

```bash
cd vokuro
php composer.phar install
```

The program uses FPDF for pdf creation, you have to add it manually to the vendor folder. 
Get it here: http://www.fpdf.org/ and place fpdf.php and fonts folder into /vendor/fpdf. You also have to add the following to the fpdf.php file:

````bash
namespace Vokuro\fpdf;
````
Also make sure you convert the classname in fpdf.php to lower case (class fpdf{...))! 

-------
License
-------
Vökuró is open-sourced software licensed under the New BSD License. //to be edited
