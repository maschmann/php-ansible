FROM php:8.3-cli

ENV ANSIBLE_VERSION 2.9.17

# composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/bin/composer \
    && chmod +x /usr/bin/composer

# python, pipx & ansible
RUN apt-get update \
    && apt-get install -y gcc python3 git zip 7zip unzip pipx \
    && apt-get clean all; \
    pipx install --upgrade pip; \
    pipx install "ansible==${ANSIBLE_VERSION}"; \
    pipx install ansible;

# keep container running
CMD [ "bash",  "-c",  "echo 'running'; tail -f /dev/null" ]