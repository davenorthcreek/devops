export $(egrep -v '^#' .env | xargs)
mysql -h $DB_HOST -u $DB_USERNAME -p$DB_PASSWORD $DB_DATABASE
