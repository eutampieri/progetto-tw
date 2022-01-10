#!/bin/bash
CONF_FILE="../db_conf.json"
if [ ! -f $CONF_FILE ];
then
	read -p "DB host [localhost]: "
	if [[ $REPLY == "" ]]
	then
		HOST="localhost"
	else
		HOST=$REPLY
	fi
	read -p "DB port [3306]: "
	if [[ $REPLY == "" ]]
	then
		PORT="3306"
	else
		PORT=$REPLY
	fi
	
	
	read -p "DB name [candt_shop]: "
	if [[ $REPLY == "" ]]
	then
		DB_NAME="candt_shop"
	else
		DB_NAME=$REPLY
	fi
	read -p "Does the DB already exist? " -n 1 -r
	echo    # (optional) move to a new line
	if [[ $REPLY =~ ^[Nn]$ ]]
	then
		QUERY="CREATE DATABASE $DB_NAME";
		echo $QUERY | sudo mysql 
	fi
	read -p "Username [candt_shop]: "
	if [[ $REPLY == "" ]]
	then
		DB_USER="candt_shop"
	else
		DB_USER=$REPLY
	fi
	read -p "Does the DB user already exist? " -n 1 -r
	if [[ $REPLY =~ ^[Nn]$ ]]
	then
		DB_PASS=`dd if=/dev/urandom bs=1 count=12 2>/dev/null | base64`
		QUERY="CREATE USER '$DB_USER'@'$HOST' IDENTIFIED BY '$DB_PASS';"
		echo $QUERY | sudo mysql
	else
		echo "TODO"
	fi
	QUERY="GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'$HOST';\nFLUSH PRIVILEGES;"
	echo $QUERY | sudo mysql
	JSON="{\"host\":\"$HOST\",\"port\":$PORT,\"username\":\"$DB_USER\",\"password\":\"$DB_PASS\",\"name\":\"$DB_NAME\"}"
	echo "$JSON" > $CONF_FILE
fi

HOST=`jq -r .host $CONF_FILE`
USER=`jq -r .username $CONF_FILE`
PASS=`jq -r .password $CONF_FILE`
PORT=`jq -r .port $CONF_FILE`
DB_NAME=`jq -r .name $CONF_FILE`
if [ -f .last ]
then
	LAST_MIGRATION=`cat .last`
else
	LAST_MIGRATION=""
fi

for MIGRATION in `ls *.sql`
do
	if [[ $MIGRATION > $LAST_MIGRATION ]]
	then
		cat $MIGRATION | mysql -P $PORT -u $USER -h $HOST --password "$PASS" $DB_NAME
		echo $MIGRATION > ".last"
	fi
done

