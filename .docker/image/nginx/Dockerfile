FROM debian:bullseye

# Set package manager mode to run without any user prompts
ARG DEBIAN_FRONTEND=noninteractive

# Get latest packages
RUN apt-get update

# Install system packages
ARG apt_get_install="apt-get install -y --no-install-recommends"
RUN $apt_get_install apt-utils
RUN $apt_get_install curl
RUN $apt_get_install wget
RUN $apt_get_install git
RUN $apt_get_install vim
RUN $apt_get_install nano
RUN $apt_get_install zip
RUN $apt_get_install unzip
RUN $apt_get_install ca-certificates
RUN $apt_get_install wait-for-it

# Install Nginx
ARG apt_get_install="apt-get install -y --no-install-recommends --ignore-missing --fix-missing"
RUN $apt_get_install nginx

# Configure service
RUN rm -rfv /etc/nginx/sites-enabled
RUN rm -rfv /etc/nginx/sites-available

COPY config/nginx.conf /etc/nginx/nginx.conf
COPY config/sites-available /etc/nginx/sites-available

RUN mkdir /etc/nginx/sites-enabled
RUN ln -sfv /etc/nginx/sites-available/app.conf /etc/nginx/sites-enabled/app.conf

RUN usermod -u 1000 www-data

# Configure miscellaneous settings
RUN ln -snf /usr/share/zoneinfo/Europe/Prague /etc/localtime
RUN echo 'alias ll="ls -alk"' >> ~/.bashrc

# Expose ports to Docker network
EXPOSE 80
EXPOSE 443
