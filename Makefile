# Usage
# make - compile and push all the images

.DEFAULT_GOAL := build

build: buildx_install build_amd64 prune
build_dev: buildx_install build_amd64

buildx_install:
	docker run --privileged --rm tonistiigi/binfmt --install all

build_amd64: build_dotnet

build_dotnet:
	docker buildx build -t nikitades/wordscountbot-app:latest -f Dockerfile --platform linux/amd64 --push .

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