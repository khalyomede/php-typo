FROM ubuntu:22.04

ENV DEBIAN_FRONTEND noninteractive

# Ubuntu dependencies
RUN apt-get update
RUN apt-get upgrade --yes
RUN apt-get install --yes software-properties-common zip git curl
RUN add-apt-repository ppa:ondrej/php
RUN apt-get update
RUN apt-get upgrade --yes
# PHP
RUN apt-get install --yes php8.2 php8.2-zip php8.2-curl php8.2-dom php8.2-intl
# Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer
