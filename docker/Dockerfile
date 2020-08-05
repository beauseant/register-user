FROM php:apache

RUN set -x \
    && apt-get update \
    && apt-get install -y libldap2-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ \
    && docker-php-ext-install ldap \
    && apt-get purge -y --auto-remove libldap2-dev

COPY 000-default.conf /etc/apache2/sites-enabled
RUN mkdir /etc/apache2/ssl/
COPY ca* /etc/apache2/ssl/

RUN a2enmod ssl
RUN a2enmod rewrite
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN service apache2 restart

#GD Llibrary:

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd  \
    && docker-php-ext-install -j$(nproc) gd




#COPY web /var/www/html
#COPY data_php /usr/local/etc/php/
#RUN apt-get install php7.0-ldap

EXPOSE 443 80


#Para ejectuarlo:
#sudo podman build -t myphp_gd_https .
#sudo podman run -it -v /home/breakthoven/Mis_Documentos/Empresa_Trabajillos/RepositorioGit/register-user/:/var/www/html -p 80:80 myphp_gd_https