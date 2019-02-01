FROM php:5.6-apache

# Setup /var/www as work directory and add all code to it
WORKDIR /var/www
COPY . /var/www

#RUN \
#    apt-get update && \
#    apt-get install -y \
#        build-essential \
#        python3 \
#        python3-dev \
#        python3-setuptools \
#        python3-numpy \
#        python3-scipy \
#        python3-pip \
#        libatlas-dev \
#        libatlas3gf-base \
#        python3-pandas \
#        python3-nltk \
#        python3-matplotlib \
#        python3-mpld3 \
#        python3-jinja2 && \
#    python3 -m pip install \
#        PyMySQL \
#        scikit-learn && \
#    python3 -c "import nltk; nltk.download('stopwords', download_dir='/usr/share/nltk_data'); nltk.download('punkt', download_dir='/usr/share/nltk_data')"

# Enable PHP extensions
RUN docker-php-ext-install mysqli

 # Install needed php extensions: ldap
RUN \
    apt-get update && \
    apt-get install libldap2-dev -y && \
    rm -rf /var/lib/apt/lists/* && \
    docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ && \
    docker-php-ext-install ldap

 # Install needed php extensions: zip
RUN \
    apt-get update && \
    apt-get install zlib1g-dev libpng-dev -y && \
    docker-php-ext-install zip gd

#RUN \
#    apt-get update && \
#    apt-get install ruby-full -y && \
#    gem install sass --no-user-install

# Apache and PHP configuration
RUN a2enmod rewrite
ADD docker/apache/vhost.conf /etc/apache2/sites-enabled/000-default.conf
ADD docker/php/php.ini /usr/local/etc/php/php.ini

# Install dependencies
RUN apt-get update && \
    apt-get install zip unzip wget -y

# Install composer globally
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/bin/composer

# Install PhpUnit globally
#curl --location --output /usr/local/bin/phpunit https://phar.phpunit.de/phpunit-5.7.9.phar
#chmod +x /usr/local/bin/phpunit
RUN wget https://phar.phpunit.de/phpunit-5.7.9.phar -O phpunit.phar && \
    chmod +x phpunit.phar && \
    mv phpunit.phar /usr/local/bin/phpunit

# Make uploads directory writable by the web server
RUN chown -R www-data:www-data /var/www/uploads

# Install composer dependencies
RUN composer install -o



#######################################################################################################################

