# OOS PROJECT
An Ocean Observation system (OOS) is a computer information system that stores and processes different types of data for an ocean observatory.

# INSTALLATION GUIDE

### Setup project:
1. clone this project under /compsci/webdocs/ccid/web_docs
2. open command line, and type:
```sh
chmod 755 web_docs
chmod 755 oos.sh
oos.sh
```
or
```sh
chmod 755 web_docs
chmod 755 oos.sh
./oos.sh
```

### Setup database:
1. open command line, and type:
```sh
sqlplus
username
password
setup.sql
sql_insert.sql
```

### Setup connection:
change the username and password under PHPConnectionDB.php
$conn = oci_connect('username', 'password');

# USER MANUAL

## login module
### login
1. enter your username and password.
2. user with "administrator" privileges will be sent to "administrator" page.
3. user with "data curator" privileges will be sent to "data curator" page.
4. user with "scientist" privileges will be sent to "scientist" page.
### modify account
1. click on "Modify Account".
2. enter the username, and click "search".
3. enter the data that you want to modify, and click "modify".

## administrator module


# CONTRIBUTIONS
- The PHP Group, http://php.net/manual/en
- ircmaxell, http://stackoverflow.com/questions/3056287/oracle-blob-as-img-src-in-php-page

# LICENSE
OOSproject is licensed under GPLv3
