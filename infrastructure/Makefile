up:
	docker-compose up -d
build:
	docker-compose build --no-cache --force-rm
install-recommend-packages:
	docker-compose exec -T app composer require --dev josegonzalez/dotenv:dev-master
	docker-compose exec -T app composer require --dev roave/security-advisories:dev-master
	docker-compose exec -T app composer require --dev phpunit/phpunit:^9.0
init:
	docker-compose up -d --build
	docker-compose exec -T app composer install
	docker-compose exec -T app cp .env.example .env
vendor-to-local:
	rm -rf ../src/vendor/*
	docker cp ffxiv-scraping-app-1:/app/vendor ../src
remake:
	@make destroy
	@make init
stop:
	docker-compose stop
down:
	docker-compose down
restart:
	@make down
	@make up
destroy:
	docker-compose down --rmi all --volumes
	docker image rm composer:latest
destroy-volumes:
	docker-compose down --volumes
ps:
	docker-compose ps
logs:
	docker-compose logs
logs-watch:
	docker-compose logs --follow
web:
	docker-compose exec web ash
app:
	docker-compose exec app bash
migrate:
	docker-compose exec app /composer/vendor/bin/phalcon-migrations run
info:
	docker-compose exec -T app /composer/vendor/bin/phalcon info
fresh:
seed:
rollback-test:
test:
optimize:
optimize-clear:
	docker-compose exec app rm -rf ./cache/*
cache:
	docker-compose exec app composer dump-autoload -o
	@make optimize
cache-clear:
	@make optimize-clear
development-tools-app:
	docker-compose exec app apt update
	docker-compose exec app apt install -y vim
npm:
	@make npm-install
npm-install:
	docker-compose exec web npm install
npm-dev:
	docker-compose exec web npm run dev
npm-watch:
	docker-compose exec web npm run watch
npm-watch-poll:
	docker-compose exec web npm run watch-poll
npm-hot:
	docker-compose exec web npm run hot
yarn:
	docker-compose exec web yarn
yarn-install:
	@make yarn
yarn-dev:
	docker-compose exec web yarn dev
yarn-watch:
	docker-compose exec web yarn watch
yarn-watch-poll:
	docker-compose exec web yarn watch-poll
yarn-hot:
	docker-compose exec web yarn hot
db:
	docker-compose exec db bash
sql:
	docker-compose exec db bash -c 'mysql -u $$MYSQL_USER -p$$MYSQL_PASSWORD $$MYSQL_DATABASE'
redis:
	docker-compose exec redis redis-cli
