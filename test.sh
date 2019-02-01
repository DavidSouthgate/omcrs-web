#!/bin/bash

configExists=true

# If config file does not exist create it
if [ ! -e "web/src/config.php" ] ; then
    echo "<?php" >> "web/src/config.php"
    echo "include(\"sample.config.php\");" >> "web/src/config.php"
    configExists=false
fi

# Remove the container
docker-compose rm -f

# Remove volumes
docker volume prune --force

# Rebuild containers
docker-compose build

# Run docker compose in test config
docker-compose -f docker-compose.yml -f docker-compose.phpunit.yml up --abort-on-container-exit

# Stop the container and output the logs of the test
#docker-compose stop
#docker-compose logs web

# Gets exit code from docker
exitCode=$(docker-compose ps -q | xargs docker inspect -f '{{ .Name }} exited with status {{ .State.ExitCode }}' | grep /omcrsweb_web_1 | grep "status [0-9]*" -oh | grep "[0-9]*" -oh)

# If the config did not exist, delete it now
if [ "$configExists" = false ] ; then
    rm "web/src/config.php"
fi

# Exists script with success/error exit code
exit $exitCode