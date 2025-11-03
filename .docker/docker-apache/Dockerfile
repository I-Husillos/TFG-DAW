FROM debian:12-slim


RUN apt-get update && apt-get install -y --no-install-recommends \
    apache2 \
    apache2-utils \
    libapache2-mod-fcgid \
    procps \
    curl \
    ca-certificates \
    vim \
    && rm -rf /var/lib/apt/lists/*


RUN a2enmod proxy proxy_fcgi rewrite headers mime setenvif expires

COPY ./.docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN ln -sf /proc/self/fd/1 /var/log/apache2/access.log \
    && ln -sf /proc/self/fd/1 /var/log/apache2/error.log

RUN mkdir -p /var/www/html 

WORKDIR /var/www/html

EXPOSE 80

CMD ["apache2ctl", "-D", "FOREGROUND"]