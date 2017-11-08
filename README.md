# API Le Monde en Sac

http://api.lemondeensac.com/

## Installation

```
cd docker
docker build -t lms-base image/base
docker-compose up -d --build
```

To access inside the container :

```
docker exec -it docker_api-lms_1 /bin/bash
```

Generate jwt keys :

```
openssl genrsa -out var/jwt/private.pem -aes256 4096
openssl rsa -pubout -in var/jwt/private.pem -out var/jwt/public.pem
```
