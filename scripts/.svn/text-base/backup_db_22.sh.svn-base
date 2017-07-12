#!/bin/bash
Now=$(date +"%Y-%m-%d_%H:%M:%S")
#File1=/backup/rs_esm_soho_$Now.sql.gz
File2=/backup/rs_esmlog_soho_$Now.sql.gz
dbuser=root
dbpasswd=rs160718@fp
dbhost=127.0.0.1
sockPath=/var/lib/mysql/mysql.sock
#mysqldump -h$dbhost --set-gtid-purged=off --socket=$sockPath -u$dbuser -p$dbpasswd rs_esm_soho | gzip >$File1
mysqldump -h$dbhost --set-gtid-purged=off --socket=$sockPath -u$dbuser -p$dbpasswd rs_esmlog_soho1 | gzip >$File2