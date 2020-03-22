FROM ubuntu:16.04

LABEL maintainer="Chris Fidao"

RUN useradd -ms /bin/bash -u 1337 vessel
WORKDIR /var/www/html

ENV GOSU_VERSION 1.7
RUN set -x \
    && apt-get update && apt-get install -y --no-install-recommends ca-certificates wget && rm -rf /var/lib/apt/lists/* \
    && wget -O /usr/local/bin/gosu "https://github.com/tianon/gosu/releases/download/$GOSU_VERSION/gosu-$(dpkg --print-architecture)" \
    && wget -O /usr/local/bin/gosu.asc "https://github.com/tianon/gosu/releases/download/$GOSU_VERSION/gosu-$(dpkg --print-architecture).asc" \
    && export GNUPGHOME="$(mktemp -d)" \
    && gpg --keyserver ha.pool.sks-keyservers.net --recv-keys B42F6819007F00F88E364FD4036A9C25BF357DD4 \
    && gpg --batch --verify /usr/local/bin/gosu.asc /usr/local/bin/gosu \
    && rm -r "$GNUPGHOME" /usr/local/bin/gosu.asc \
    && chmod +x /usr/local/bin/gosu \
    && gosu nobody true \
    && apt-get purge -y --auto-remove ca-certificates wget

RUN echo "deb http://ppa.launchpad.net/ondrej/php/ubuntu xenial main" > /etc/apt/sources.list.d/ppa_ondrej_php.list \
    && echo "deb http://ppa.launchpad.net/nginx/development/ubuntu xenial main" > /etc/apt/sources.list.d/ppa_nginx_mainline.list \
    && apt-key adv --keyserver keyserver.ubuntu.com --recv-keys E5267A6C \
    && apt-key adv --keyserver keyserver.ubuntu.com --recv-keys C300EE8C \
    && apt-get update \
    && apt-get install -y sudo curl wget vim htop zip unzip git supervisor sqlite3 apt-utils apt-transport-https \
    && apt-get install -y nginx php7.2-fpm php7.2-cli php7.2-gd php7.2-mysql \
       php7.2-pgsql php7.2-imap php-memcached php7.2-mbstring php7.2-xml php7.2-curl php7.2-http \
       php7.2-imagick php7.2-zip php7.2-bcmath php7.2-sqlite3 php7.2-intl php-xdebug php7.2-gmp \
    && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && mkdir /run/php \
    && echo "daemon off;" >> /etc/nginx/nginx.conf

RUN wget https://packages.erlang-solutions.com/erlang-solutions_1.0_all.deb \
        		&& dpkg -i erlang-solutions_1.0_all.deb \
        		&& echo "deb https://dl.bintray.com/rabbitmq/debian xenial main" | tee /etc/apt/sources.list.d/bintray.rabbitmq.list \
        		&& wget -O- https://dl.bintray.com/rabbitmq/Keys/rabbitmq-release-signing-key.asc | apt-key add - \
        		&& wget -O- https://www.rabbitmq.com/rabbitmq-release-signing-key.asc | apt-key add - \
        		&& apt-get update \
        		&& apt-get install -y rabbitmq-server

RUN echo "172.17.0.2 local local.container" >> /etc/hosts; \
    		export HOSTNAME="local.container"; \
    		echo "local.container" > /etc/hostname; \
    		mv /bin/hostname /bin/hostname.bkp; \
        echo "echo local.container" > /bin/hostname; \
        chmod +x /bin/hostname; \
    		hostname; \
    		systemctl enable rabbitmq-server; \
    		rabbitmq-plugins enable rabbitmq_management; \
    		chown -R rabbitmq.rabbitmq /var/lib/rabbitmq/; \
    		/etc/init.d/rabbitmq-server start; \
    		rabbitmqctl add_user nicemqadm nicemq2018; \
    		rabbitmqctl set_user_tags nicemqadm administrator; \
    		rabbitmqctl set_permissions -p / nicemqadm ".*" ".*" ".*"; \
    		/etc/init.d/rabbitmq-server stop; \
    		chown -R rabbitmq.rabbitmq /var/lib/rabbitmq/; \
    		mv /bin/hostname.bkp /bin/hostname

RUN apt-get -y autoremove; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

COPY h5bp /etc/nginx/h5bp
COPY default /etc/nginx/sites-available/default
COPY php-fpm.conf /etc/php/7.2/fpm/php-fpm.conf
COPY xdebug.ini /etc/php/7.2/mods-available/xdebug.ini

EXPOSE 80
EXPOSE 15672

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY start-container /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container

ENTRYPOINT ["start-container"]
