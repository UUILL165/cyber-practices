# Web Application Mock Project: SocialNet Project
 
## Full name: Luong The Khiem

## student Id: 1695553
---
### *Setup steps:

-Database Configuration

+Open terminal

+Import the db.sql file to create the users database and the account table:
```
sudo mysql -u root -p < db.sql
```
+open db.php to update the database information to match your own environment.
```
$dbuser = "your_mysql_username"; //Type your mysql username here
$dbpass = "your_mysql_password"; //Type your mysql password here
```
---
-Nginx configuration:

+Nginx server's document root have to point directly to the cloned repository folder.

+Use this command:
```
sudo nano /etc/nginx/sites-available/default
```

+When you're in, find things in the below image:

<img width="753" height="381" alt="Screenshot 2026-05-10 at 19 36 56" src="https://github.com/user-attachments/assets/c39808f7-40c0-45d4-a872-23d2f1be67c4" />

note: remember to change the php version based on yours (fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;), then change "root/var/www/html;" to your cloned repo's path

+Then after updating the nginx configuration, restart nginx.

---
-Running

+When you finish the two steps above, just copy the two below link for processing the application:

Use http://localhost/admin/newuser.php to create account, then the account's information stored in the account table of users database.

Use http://localhost/socialnet/signin.php to log in and process all other features of the app.
