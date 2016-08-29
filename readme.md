# QueueApp Used frameworks :<br><br>

This application uses PHP framework Laravel 5.3 and CSS framework Bootstrap 3.3.7

# Application Description :

We have a council who would like to use an application to queue people at the reception
desk. The receptionist should be able to select the service (example provided below) and
take the customer details depending on the customer type:<br><br>

• Citizen: title, first name and last name fields should be displayed<br>
• Organisation: organisation name field should be visible<br>
• Anonymous: no input fields should be visible<br>

# How to run :

Download the QueueApp to your web server.

A) You can run it from your browser with :<br>
http://localhost/queue/

B) If you use vhosts then create vhost :
```
<VirtualHost *:80>
   DocumentRoot "PATH_TO_SOURCE_CODE/csreport/public"
   ServerName csreport.local

   # This should be omitted in the production environment
   SetEnv APPLICATION_ENV development                    
   <Directory "PATH_TO_SOURCE_CODE/csreport/public">
       Options Indexes MultiViews FollowSymLinks
       AllowOverride All
       Order allow,deny
       Allow from all
   </Directory>
</VirtualHost>
```
Add this line to hosts file (if you use Windows you can find the file here "C:\Windows\System32\drivers\etc\hosts") :<br>
127.0.0.1	csreport.local

Afterwars restart your web server and then you can run it in browser with :<br>
csreport.local
