FROM alpine:3.21
RUN mkdir /opt/photosite
ADD ./scripts/ /opt/photosite/scripts/
ADD ./nginx.conf /etc/nginx/nginx.conf
ADD ./mariadb-server.cnf /etc/my.cnf.d/mariadb-server.cnf
ADD ./binaries/photosite /usr/local/bin/photosite
ENV PHP_FPM_USER="www"
ENV PHP_FPM_GROUP="www"
ENV PHP_FPM_LISTEN_MODE="0660"
ENV PHP_MEMORY_LIMIT="512M"
ENV PHP_MAX_UPLOAD="50M"
ENV PHP_MAX_FILE_UPLOAD="200"
ENV PHP_MAX_POST="100M"
ENV PHP_DISPLAY_ERRORS="On"
ENV PHP_DISPLAY_STARTUP_ERRORS="On"
ENV PHP_ERROR_REPORTING="E_COMPILE_ERROR\|E_RECOVERABLE_ERROR\|E_ERROR\|E_CORE_ERROR"
ENV PHP_CGI_FIX_PATHINFO=0
RUN echo Installing packages... &&\
    apk update &&\
    apk add nginx php83-fpm php83-mysqli mariadb mariadb-client imagemagick libwebp-tools &&\
    echo Configuring users... &&\
    mkdir /www &&\
    adduser -D -g 'www' www &&\
    echo Configuring permissions... &&\
    chown -R www:www /var/lib/nginx &&\
    chown -R www:www /www &&\
    chown -R www:www /usr/local/bin/photosite &&\
    echo Configuring packages... &&\
    sh /opt/photosite/scripts/php-conf.sh
EXPOSE 80
CMD sh /opt/photosite/scripts/entrypoint.sh
    