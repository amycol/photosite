initdb() {
    while !(mariadb-admin ping) 
    do
        sleep 0.5
        echo "Waiting for MariaDB ..."
    done
    echo "Starting MariaDB initialisation..."
    mariadb-install-db --user=mysql --basedir=/usr --datadir=/var/lib/mysql &&\
    if mariadb -u root < /opt/photosite/scripts/checkdb.sql | tail -1 | grep FALSE; then
        echo Database does not exist!
        echo Creating database...
        mariadb -u root < /opt/photosite/scripts/initialdb.sql
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
(mariadbd-safe &) &&\
initdb &&\
echo Initialisation complete! &&\
tail -f /dev/null

<<comment
OUTPUT="Can\'t connect"
while [[ $OUTPUT == *"Can\'t connect"* ]]
do
    OUTPUT=$(sh /opt/photosite/scripts/dbinit.sh)
done
comment