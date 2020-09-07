install: down docker-pull docker-build composer-install post-composer-install up wait migrate seeds passport-install fix-permissions
start: down docker-pull composer-install up fix-permissions
stop: down
restart: stop start
up: docker-up
down: docker-down
deploy: docker-pull
check: phplint phpcs
fix: phpcbf

#production
prod-install: down docker-pull up wait migrate seeds fix-permissions
prod-update: docker-pull up wait migrate fix-permissions


#docker
docker-up:
	docker-compose up -d --remove-orphans

docker-down:
	docker-compose down --remove-orphans

docker-build:
	docker-compose build

docker-pull:
	docker-compose pull


#composer
composer-install-unix:
	docker run --rm --interactive --tty --user $$(id -u):$$(id -g) --volume ${PWD}:/app composer install
composer-install-win:
	winpty docker run --rm --interactive --tty --user $$(id -u):$$(id -g) --volume /$$(pwd):/app composer install

composer-install:
ifeq ($(OS), Windows_NT)
	make composer-install-win
else
	make composer-install-unix
endif

post-composer-install-unix:
	docker run --rm --interactive --tty --user $$(id -u):$$(id -g) --volume ${PWD}:/app composer run-script post-root-package-install
	docker run --rm --interactive --tty --user $$(id -u):$$(id -g) --volume ${PWD}:/app composer run-script post-create-project-cmd
	docker run --rm --interactive --tty --user $$(id -u):$$(id -g) --volume ${PWD}:/app composer run-script post-install-cmd
post-composer-install-win:
	winpty docker run --rm --interactive --tty --user $$(id -u):$$(id -g) --volume /$$(pwd):/app composer run-script post-root-package-install
	winpty docker run --rm --interactive --tty --user $$(id -u):$$(id -g) --volume /$$(pwd):/app composer run-script post-create-project-cmd
	winpty docker run --rm --interactive --tty --user $$(id -u):$$(id -g) --volume /$$(pwd):/app composer run-script post-install-cmd

post-composer-install:
ifeq ($(OS), Windows_NT)
	make post-composer-install-win
else
	make post-composer-install-unix
endif

#database
migrate-unix:
	docker-compose exec --user $$(id -u):www-data app php artisan migrate
migrate-win:
	winpty docker-compose exec --user $$(id -u):www-data app php artisan migrate

migrate:
ifeq ($(OS), Windows_NT)
	make migrate-win
else
	make migrate-unix
endif

seeds-unix:
	docker-compose exec --user $$(id -u):www-data app php artisan db:seed
seeds-win:
	winpty docker-compose exec --user $$(id -u):www-data app php artisan db:seed

seeds:
ifeq ($(OS), Windows_NT)
	make seeds-win
else
	make seeds-unix
endif

passport-install-unix:
	docker-compose exec --user $$(id -u):www-data app php artisan passport:install
passport-install-win:
	winpty docker-compose exec --user $$(id -u):www-data app php artisan passport:install

passport-install:
ifeq ($(OS), Windows_NT)
	make passport-install-win
else
	make passport-install-unix
endif


#fix
fix-permissions-unix:
	docker run -v ${PWD}:/app --rm php:7.2-apache chgrp -R www-data /app
	docker run -v ${PWD}:/app --rm php:7.2-apache find /app -type d -exec chmod 0775 {} +
	docker run -v ${PWD}:/app --rm php:7.2-apache find /app -type f -exec chmod 0664 {} +
fix-permissions-win:
	winpty docker run --rm --volume /$$(pwd):/app php:7.2-apache ls -la
	winpty docker run --rm --volume /$$(pwd):/app php:7.2-apache chgrp -R www-data /app
	winpty docker run --rm --volume /$$(pwd):/app php:7.2-apache find /app -type d -exec chmod 0775 {} +
	winpty docker run --rm --volume /$$(pwd):/app php:7.2-apache find /app -type f -exec chmod 0664 {} +

fix-permissions:
ifeq ($(OS), Windows_NT)
	sleep 1
else
	make fix-permissions-unix
endif

#test
test-unix:
	docker-compose exec app php ./vendor/bin/phpunit
test-win:
	winpty docker-compose exec app php ./vendor/bin/phpunit

test:
ifeq ($(OS), Windows_NT)
	make test-win
else
	make test-unix
endif

#lint
phplint-unix:
	docker-compose exec app php ./vendor/bin/phplint
phplint-win:
	winpty docker-compose exec app php ./vendor/bin/phplint

phplint:
ifeq ($(OS), Windows_NT)
	make phplint-win
else
	make phplint-unix
endif

#check
phpcs-unix:
	docker-compose exec app php ./vendor/bin/phpcs -v
phpcs-win:
	winpty docker-compose exec app php ./vendor/bin/phpcs -v

phpcs:
ifeq ($(OS), Windows_NT)
	make phpcs-win
else
	make phpcs-unix
endif

#fix
phpcbf-unix:
	docker-compose exec app php ./vendor/bin/phpcbf
phpcbf-win:
	winpty docker-compose exec app php ./vendor/bin/phpcbf

phpcbf:
ifeq ($(OS), Windows_NT)
	make phpcbf-win
else
	make phpcbf-unix
endif

build-prod-app:
	docker build -t ${IMAGE_APP} -f ./docker/prod/app/Dockerfile .
push-prod-app:
	docker push ${IMAGE_APP}

build-prod-app-fpm:
	docker build -t ${IMAGE_APP_FPM} -f ./docker/prod/app/Dockerfile .
push-prod-app-fpm:
	docker push ${IMAGE_APP_FPM}

build-prod-nginx:
	docker build -t ${IMAGE_GATEWAY} -f ./docker/prod/nginx/Dockerfile .
push-prod-nginx:
	docker push ${IMAGE_GATEWAY}


prod-deploy:
	docker stack deploy --with-registry-auth --compose-file=docker-compose.prod.yml laravel
prod-kill:
	docker stack rm laravel

wait:
	sleep 60