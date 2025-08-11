##################
# Docker compose
##################

dc_build:
	docker build -t php-fpm -f ./docker/php-fpm/Dockerfile .
	docker build -t nginx -f ./docker/nginx/Dockerfile ./docker/nginx
	docker build -t postgres -f ./docker/postgres/Dockerfile ./docker/postgres

dc_start:
	cd ./docker && docker-compose start

dc_stop:
	cd ./docker && docker-compose stop

dc_up:
	cd ./docker && docker-compose up -d --remove-orphans

dc_ps:
	cd ./docker && docker-compose ps

dc_logs:
	cd ./docker && docker-compose logs -f

dc_down:
	cd ./docker && docker-compose down -v --rmi=all --remove-orphans


##################
# App
##################

app_bash:
	cd ./docker && docker-compose exec -u www-data php-fpm bash