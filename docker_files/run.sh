#!/usr/bin/env bash

source /root/.bashrc

cd /var/www/html/openscholar

# Install custom domains
echo -e "\n # Add lincoln virtual domain."
service apache2 restart
sh -c "echo 127.0.0.1	lincoln.local >> /etc/hosts"
sh -c "cat openscholar/behat/lincoln-vhost.txt > /etc/apache2/sites-available/lincoln.local.conf"
a2ensite lincoln.local
service apache2 reload

# Install Drupal
echo -e "\n # Install Drupal"
bash docker.install.sh
chmod -R 777 /var/www/html/openscholar/www/sites/default/files/

cd /var/www/html/openscholar

# Index site using Apache solr
echo -e "\n Installing Apache solr integration."
cd www
drush en os_search_solr -y
drush solr-mark-all
drush solr-index
drush vset oembedembedly_api_key $EMBEDLYAPIKEY
cd -

# Install behat
echo -e "\n # Install behat"
cd openscholar/behat
pwd
ls -al
curl -sS https://getcomposer.org/installer | php
php composer.phar install
cp behat.local.yml.travis behat.local.yml

if [ $DOCKER_DEBUG -eq 1 ]; then
  bash
else
  # tests
  echo -e "\n # tests"
  ./bin/behat --tags=$TEST_SUITE


  if [ $? -ne 0 ]; then
    echo "Behat failed"
    exit 1
  fi
fi
