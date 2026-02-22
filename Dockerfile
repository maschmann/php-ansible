ARG PHP_VERSION=8.4
FROM php:${PHP_VERSION}-cli

WORKDIR /app

ENV ANSIBLE_VERSION 2.9.17

# composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# python, pipx & ansible
RUN apt-get update \
    && apt-get install -y gcc python3 git zip 7zip unzip pipx \
    && apt-get clean all; \
    pipx install --upgrade pip; \
    pipx install ansible-core; \
    pipx install ansible;

# keep container running
CMD [ "bash",  "-c",  "echo 'running'; tail -f /dev/null" ]