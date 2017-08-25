##### Requirements (Read "developed with")
Docker 17.06.1-ce
Docker-compose 1.15.0

##### How to deploy
`docker-compose up --force-recreate --build -d`
`docker-compose exec postgres psql -d cogcrate -U cogcrate -f /application/deploy/dbScripts/schema.sql`

##### How to run tests
`docker-compose exec php-fpm /application/vendor/bin/phpunit`
