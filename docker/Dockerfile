FROM alpine:3.21
RUN mkdir /opt/photosite
RUN mkdir /opt/photosite/uploads
ADD ./scripts/ /opt/photosite/scripts/
ADD ./nginx.conf /etc/nginx/nginx.conf
ADD ./mariadb-server.cnf /etc/my.cnf.d/mariadb-server.cnf
ADD ./php.ini /etc/php83/php.ini
ADD ./binaries/photosite /usr/local/bin/photosite
ADD ./assets/watermarks /opt/photosite/watermarks
ENV PHP_FPM_USER="www"
ENV PHP_FPM_GROUP="www"
ENV PHP_FPM_LISTEN_MODE="0660"
ENV PHP_MEMORY_LIMIT="512M"
ENV PHP_MAX_UPLOAD="256M"
ENV PHP_MAX_FILE_UPLOAD="200"
ENV PHP_MAX_POST="256M"
ENV PHP_DISPLAY_ERRORS="On"
ENV PHP_DISPLAY_STARTUP_ERRORS="On"
ENV PHP_ERROR_REPORTING="E_COMPILE_ERROR\|E_RECOVERABLE_ERROR\|E_ERROR\|E_CORE_ERROR"
ENV PHP_CGI_FIX_PATHINFO=0
RUN echo Installing packages...
RUN apk update
RUN apk add nginx
RUN apk add php83-fpm
RUN apk add php83-mysqli
RUN apk add mariadb
RUN apk add mariadb-client
RUN apk add imagemagick
RUN apk add tiff
RUN apk add libwebp-tools
RUN apk add exiftool 
RUN echo Configuring users... 
RUN mkdir /www 
RUN mkdir /img 
RUN adduser -D -g 'www' www 
RUN echo Configuring permissions... 
RUN chown -R www:www /var/lib/nginx 
RUN chown -R www:www /www 
RUN chown -R www:www /img 
RUN chown -R www:www /usr/local/bin/photosite 
RUN chown -R www:www /opt/photosite 
RUN echo Configuring packages... 
RUN sh /opt/photosite/scripts/php-conf.sh
EXPOSE 80
CMD sh /opt/photosite/scripts/entrypoint.sh
    