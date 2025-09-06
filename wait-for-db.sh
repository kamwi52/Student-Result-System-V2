#!/bin/sh
# wait-for-db.sh

# This script waits for the database to be ready before starting the application.

# The 'exec "$@"' command at the end runs the command that was passed to this script.
# In our Dockerfile, this will be "php artisan migrate --force && php artisan serve..."

# Get the database host and port from the environment variables Railway provides.
# We'll use default values if they aren't set.
DB_HOST=${MYSQLHOST:-db}
DB_PORT=${MYSQLPORT:-3306}

echo "Waiting for database at $DB_HOST:$DB_PORT..."

# We use 'nc' (netcat) to check if the port is open. The loop continues
# until the database is ready to accept connections.
while ! nc -z $DB_HOST $DB_PORT; do
  sleep 1 # wait 1 second before trying again
done

echo "Database is ready. Starting application..."

# Run the main command (e.g., migrations and the web server)
exec "$@"