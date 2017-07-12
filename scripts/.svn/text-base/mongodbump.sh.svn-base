 #!/bin/bash
 filename="`date +%Y%m%d%H%M%S`"
 printf "************开始数据库备份************\n" 
 /usr/local/bin/mongodb/bin/mongodump -h localhost:50000 -d admin  -o ./$filename
 /usr/local/bin/mongodb/bin/mongodump -h localhost:50000 -d config -o ./$filename
 /usr/local/bin/mongodb/bin/mongodump -h localhost:50000 -d rec_log -o ./$filename
 /usr/local/bin/mongodb/bin/mongodump -h localhost:50000 -d rec_manager -o ./$filename
 /usr/local/bin/mongodb/bin/mongodump -h localhost:50000 -d test -o ./$filename
 printf '备份文件保存目录为:%s\n' $PWD/$filename
 printf '************数据库备份完成！！！************\n'
