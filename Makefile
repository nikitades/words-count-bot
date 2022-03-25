# Usage
# make - compile and push all the images

.DEFAULT_GOAL := build

build: build_amd64 prune
build_dev: build_amd64

build_amd64: build_dotnet

build_dotnet:
	docker build -t nikitades/wordscountbot-app:latest -f Dockerfile .
	docker push nikitades/wordscountbot-app

prune:
	docker system prune -af

down:
	docker-compose -f docker-compose.yml down

up:
	docker-compose -f docker-compose.yml up -d

rebuild_and_restart: down build_dev up

restart: down up

logs:
	docker-compose -f docker-compose.yml logs -f