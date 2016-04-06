# Build docker build -t openscholar .
# go into docker run -p 9008:80 -it --rm openscholar bash
# docker run -p 2090:80 openscholar -> http://192.168.99.100:2090/openscholar/www/
# docker run -p 2090:80 -it --rm openscholar bash
FROM gizra/drupal-lamp

ADD . /var/www/html/openscholar
WORKDIR /var/www/html/openscholar

EXPOSE 80 3306 22
