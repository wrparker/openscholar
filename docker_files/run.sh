#!/usr/bin/env bash

# Increasing the memory limitation.
mv docker_files/php.ini /usr/local/etc/php

# Start MySQL.
echo -e "\n # Start MySQL."
service mysql start

# Configure apache2.
echo -e "\n # Configure apache2."
cp docker_files/default.apache2.conf /etc/apache2/apache2.conf
service apache2 restart

# Install Bower.
echo -e "\n # Install Bower."
npm install -g bower
echo '{ "allow_root": true }' > /root/.bowerrc

# Install drush.
echo -e "\n # Install drush."
export PATH="$HOME/.composer/vendor/bin:$PATH"
composer global require drush/drush:7.*

cd /var/www/html/openscholar
mkdir ~/.drush/
cp docker_files/aliases.drushrc.php ~/.drush/aliases.drushrc.php
source /root/.bashrc

# Install Drupal
echo -e "\n # Install Drupal"
bash docker.install.sh

# Install Firefox (iceweasel).
echo -e "\n${BGCYAN}[RUN] Install firefox. ${RESTORE}"
apt-get update
apt-get -qq -y install iceweasel > /dev/null

# Install headless GUI for browser.'Xvfb is a display server that performs
# graphical operations in memory'.
echo -e "\n [RUN] Installing XVFB (headless GUI for Firefox).\n"
apt-get install xvfb -y
apt-get install openjdk-7-jre-headless -y
Xvfb :99 -ac &
export DISPLAY=:99
sleep 5

# Install Selenium.
echo -e  "\n${BGCYAN}[RUN] Install Selenium. ${RESTORE}"
wget http://selenium-release.storage.googleapis.com/2.53/selenium-server-standalone-2.53.0.jar
java -jar selenium-server-standalone-2.53.0.jar > /dev/null 2>&1 &
sleep 10

# Install behat
#echo -e "\n # Install behat"
#cd behat
#curl -sS https://getcomposer.org/installer | php
#php composer.phar install
#cp behat.local.yml.travis behat.local.yml

# Run tests
#echo -e "\n # Run tests"
#./bin/behat --tags=~@wip
#
#if [ $? -ne 0 ]; then
#  echo "Behat failed"
#  exit 1
#fi
