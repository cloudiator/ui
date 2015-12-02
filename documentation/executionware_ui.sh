sudo apt-get update -y
sudo apt-get install -y php5-common libapache2-mod-php5 php5-cli apache2 php5-curl

cd /var/www/html
sudo git clone ssh://gitolite@tuleap.ow2.org/paasage/executionware_ui.git

cat << EOF >> /etc/apache2/sites-available/000-default.conf
<Directory /var/www/executionware_ui>
    Options Indexes FollowSymLinks MultiViews
    AllowOverride all
    Order allow,deny
    allow from all
</Directory>
EOF

sudo a2enmod rewrite
sudo service apache2 start