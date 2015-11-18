# OOSproject

## Setup

### Setup project:
- clone this project under /compsci/webdocs/ccid/web_docs
- chmod 755 web_docs
- chmod 755 OOSproject folder and oos folder
- chmod 644 files inside oos folder
- chmod 777 for dummy files (sensor.jpg, audio.wav, temp.csv)
- open: http://consort.cs.ualberta.ca/<~ccid>/OOSproject/oos/login.html

### Setup database: 
- run setup.sql
- run sql_insert.sql

### Setup connection:
- change the username and password under PHPConnectionDB.php
  $conn = oci_connect('username', 'password');
- PLEASE DO NOT PUSH YOUR FILE WITH YOUR USERNAME AND PASSWORD!
  everytime you want to push your project, please change it back to:
  $conn = oci_connect('username', 'password');

## Contributions
- The PHP Group, http://php.net/manual/en
- ircmaxell, http://stackoverflow.com/questions/3056287/oracle-blob-as-img-src-in-php-page

## License
OOSproject is licensed under GPLv3
