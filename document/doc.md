OOS PROJECT
-----------------
An Ocean Observation system (OOS) is a computer information system that stores and processes different types of data for an ocean observatory.


----------


INSTALLATION GUIDE
---------------------------

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


----------


USER MANUAL
-------------------

> Navigate this page to:
> : [1. login module](#login-module)
> : [2. sensor and user management module](#sensor-and-user-management-module)
> : [3. subscribe module](#subscribe-module)
> : [4. uploading module](#uploading-module) 
> : [5. search module](#search-module) 
> : [6. data analysis](#data-analysis-module)

### Login module

#### login
1. enter your username and password, and click <kbd>login</kbd>.
2. user with "administrator" privileges will be sent to "administrator" page.
3. user with "data curator" privileges will be sent to "data curator" page.
4. user with "scientist" privileges will be sent to "scientist" page.

#### modify account
1. click on "Modify Account".
2. enter the username, and click <kbd>search</kbd>.
3. enter the data that you want to modify, and click <kbd>modify</kbd>.

### Sensor and user management module

####  add sensor
1. enter the location of the sensor.
2. choose the type of the sensor (audio, image, or scalar).
3. enter the description of the sensor.
4. click <kbd>submit</kbd> to add the new sensor with the information that has been specified.

#### delete sensor
1. select the sensor id from the dropdown list.
2. click <kbd>delete</kbd> to remove the sensor from the database.

#### add person
1. enter the person's first name.
2. enter the person's last name.
3. enter the person's address.
4. enter the person's email.
5. enter the person's phone number.
6. click <kbd>submit</kbd> to add the new person with the information that has been specified.

#### add user
1. select the person id from the dropdown list.
2. enter the user's username.
3. enter the user's password.
4. choose the role of the user.
5. click <kbd>submit</kbd> to add the new user with the information that has been specified.

#### modify person
1. select person id from dropdown list, and click "search".
2. enter the new information to the field that you wish to change.
3. click <kbd>edit</kbd> to update the existing person information.

#### modify user
1. select username from dropdown list, and click "search".
2. enter the new information to the field that you wish to change.
3. click <kbd>edit</kbd> to update the existing person information.

#### delete person
1. select the person id from the dropdown list.
2. click <kbd>delete</kbd> to remove the person from the database.

#### delete user
1. select the username from the dropdown list.
2. click <kbd>delete</kbd> to remove the user from the database.

### Subscribe module

#### subscribe sensor
1. enter the sensor id.
2. click <kbd>subscribe</kbd> to add the sensor with this sensor id to your subscription.

#### unsubscribe sensor
1. enter the sensor id.
2. click <kbd>unsubscribe</kbd> to remove the sensor with this sensor id from your subscription.

### Uploading module

#### upload audio
1. select the sensor id from the dropdown list.
2. enter the date of the audio created (with format DD/MM/YYYY HH:MM:SS).
3. enter the length of the audio.
4. enter the description of the audio.
5. choose the audio file (.wav) from the local storage.
6. click <kbd>submit</kbd> to upload the audio data to the database.

#### upload image
1. select the sensor id from the dropdown list.
2. enter the date of the image created (with format DD/MM/YYYY HH:MM:SS).
3. enter the description of the image.
4. choose the audio file (.jpg) from the local storage.
5. click <kbd>submit</kbd> to upload the image data to the database.

#### upload scalar data
1. choose the file that contains scalar data (.csv) from the local storage.
2. click <kbd>submit</kbd> to upload the scalar data to the database.

### Search module

#### search data
1. enter the keyword. and/or 
2. the sensor type and/or location, and
3. a time period
4. click <kbd>search</kbd> to search the data that you have subscribed.

#### download data
1. click "download" to download the data from database.

### Data analysis module


----------


CONTRIBUTIONS
---------------------

- The PHP Group, http://php.net/manual/en
- ircmaxell, http://stackoverflow.com/questions/3056287/oracle-blob-as-img-src-in-php-page


----------


LICENSE
----------
OOSproject is licensed under GPLv3
