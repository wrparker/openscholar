FROM gizra/drupal-lamp

ADD . /var/www/html/openscholar
WORKDIR /var/www/html/openscholar

# Increasing the memory limitation.
RUN mv docker_files/php.ini /usr/local/etc/php

# Start MySQL.
RUN echo -e "\n # Start MySQL."
RUN service mysql start

# Configure apache2.
RUN echo -e "\n # Configure apache2."
RUN cp docker_files/default.apache2.conf /etc/apache2/apache2.conf
RUN service apache2 restart

# Install Bower.
RUN echo -e "\n # Install Bower."
RUN npm install -g bower
RUN echo '{ "allow_root": true }' > /root/.bowerrc

# Install drush.
RUN echo -e "\n # Install drush."
RUN export PATH="$HOME/.composer/vendor/bin:$PATH"
RUN composer global require drush/drush:7.*

RUN mkdir ~/.drush/
RUN cp docker_files/aliases.drushrc.php ~/.drush/aliases.drushrc.php

# Download and install Apache solr.
RUN echo -e "\n # Install apache solr."
RUN wget https://archive.apache.org/dist/lucene/solr/3.6.2/apache-solr-3.6.2.zip
RUN unzip -oq apache-solr-3.6.2.zip
RUN cd apache-solr-3.6.2/example/solr/conf

# Copy the solr config files from the apache solr module
RUN yes | cp /var/www/html/openscholar/www/profiles/openscholar/modules/contrib/apachesolr/solr-conf/solr-3.x/* .

# Copy the modified solrconfig.xml file to commit records every 20 seconds so items will show up in search sooner
RUN yes | cp /var/www/html/openscholar/www/profiles/openscholar/behat/solr/solrconfig.xml .
RUN cd ../../
RUN java -jar start.jar &
RUN sleep 10

# Install Firefox (iceweasel).
RUN echo -e "\n${BGCYAN}[RUN] Install firefox. ${RESTORE}"
RUN apt-get update
RUN apt-get -qq -y install iceweasel > /dev/null

# Install headless GUI for browser.'Xvfb is a display server that performs
# graphical operations in memory'.
RUN echo -e "\n [RUN] Installing XVFB (headless GUI for Firefox).\n"
RUN apt-get install xvfb -y
RUN apt-get install openjdk-7-jre-headless -y
RUN Xvfb :99 -ac &
RUN export DISPLAY=:99
RUN sleep 5

# Install Selenium.
RUN echo -e  "\n${BGCYAN}[RUN] Install Selenium. ${RESTORE}"
RUN wget http://selenium-release.storage.googleapis.com/2.53/selenium-server-standalone-2.53.0.jar
RUN java -jar selenium-server-standalone-2.53.0.jar > /dev/null 2>&1 &
RUN sleep 10

# Add a bash script to finalize all
RUN chmod +x /var/www/html/openscholar/docker_files/run.sh
ENTRYPOINT ["/var/www/html/openscholar/docker_files/run.sh"]

EXPOSE 80 3306 22 8983
