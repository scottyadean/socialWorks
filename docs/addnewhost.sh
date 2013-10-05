#!/bin/sh

echo "<VirtualHost *:80>
   DocumentRoot \"/home/scotty/Sites/newsite\"
   ServerName yii.local

   # This should be omitted in the production environment
   SetEnv APPLICATION_ENV development

   <Directory \"/home/scotty/Sites/newsite\">
       Options Indexes MultiViews FollowSymLinks
       AllowOverride All
       Order allow,deny
       Allow from all
   </Directory>
</VirtualHost>"

sudo gedit /etc/apache2/sites-enabled/000-default


read -p " > Edit hosts y/n: " aws

if test "$aws" = "y"

then
     echo "Host File Edit"
     sudo gedit /etc/hosts
else
     echo "Skip Host Edit."	
fi


read -p " > Restart Apache y/n: " aws2

if test "$aws2" = "y"
then
     echo "Apache Restarting..."
     sudo /etc/init.d/apache2 restart
else
     echo "Skip Apache Restart."	
fi


echo "done"

