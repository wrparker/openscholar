#!/bin/bash

if [ "${TEST_SUITE}" == "harvard" ]; then
  drush en harvard_courses -y && drush cc all
fi

if [ "${TEST_SUITE}" == "solr" ]; then
  # Download and install Apache solr.
  wget https://archive.apache.org/dist/lucene/solr/3.6.2/apache-solr-3.6.2.zip
  unzip -o apache-solr-3.6.2.zip
  cd apache-solr-3.6.2/example/solr/conf

  # Copy the solr config files from the apache solr module.
  yes | cp /home/travis/build/openscholar/openscholar/www/profiles/openscholar/modules/contrib/apachesolr/solr-conf/solr-3.x/* .

  # Copy the modified solrconfig.xml file to commit records every 20 seconds so
  # items will show up in search sooner.
  yes | cp /home/travis/build/openscholar/openscholar/www/profiles/openscholar/behat/solr/solrconfig.xml .
  cd ../../
  java -jar start.jar &
  sleep 10
  cd /home/travis/build/openscholar/openscholar

  # Index site using Apache solr.
  drush en os_search_solr -y
  drush solr-mark-all
  drush solr-index

  # Trying to execute the internal solr commit.
  sleep 10
  wget http://localhost:8983/solr/update?commit=true
fi

#if [ "${TEST_SUITE}" == "features_second" ]; then
  # Run selenium.
  sh -e /etc/init.d/xvfb start
  export DISPLAY=:99.0
#  wget http://selenium-release.storage.googleapis.com/2.49/selenium-server-standalone-2.49.0.jar
#  nohup bash -c "java -jar selenium-server-standalone-2.49.0.jar -p 4444 2>&1 &"
  wget http://selenium-release.storage.googleapis.com/2.47/selenium-server-standalone-2.47.0.jar
  nohup bash -c "java -jar selenium-server-standalone-2.47.0.jar -p 4444 2>&1 &"
  sleep 5
#fi
