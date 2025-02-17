waitdb() {
    while !(mariadb-admin ping) 
    do
        sleep 0.5
        echo "Waiting for MariaDB ..."
    done
    if mariadb-admin ping; then
        echo MariaDB is running!
    else
        echo "An error has occured! MariaDB has likely crashed."
    fi
}

installdb() {
    echo "Starting database installation..."
    mariadb-install-db --user=mysql --basedir=/usr --datadir=/var/lib/mysql &&\
    echo "Database installation successful!"
}

initdb() {
    echo "Starting database initialisation..."
    if mariadb -u root < /opt/photosite/scripts/checkdb.sql | tail -1 | grep FALSE; then
        echo Database does not exist!
        echo Creating database...
        mariadb -u root < /opt/photosite/scripts/initialdb.sql
        mariadb -u root < /opt/photosite/scripts/users.sql
        if mariadb -u root < /opt/photosite/scripts/checkdb.sql | tail -1 | grep FALSE; then
            echo Database creation failed! Halting...
            halt
        else
            echo Database creation successful!
        fi
    else
        echo Database exists! Skipping creation...
    fi
}


nginx &&\
php-fpm83 &&\
installdb &&\
(mariadbd-safe &) &&\
waitdb &&\
initdb &&\
echo Initialisation complete! &&\
tail -f /dev/null