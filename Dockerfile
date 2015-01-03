#Build a container to run VitaminC. A multi language test runner
FROM ubuntu:latest
MAINTAINER kc merrill <kcmerrill@gmail.com>

RUN apt-get -y update && apt-get -y dist-upgrade
RUN apt-get -y install php5 python

RUN mkdir -p /opt/vitaminc
RUN mkdir -p /code

ADD . /opt/vitaminc

EXPOSE 9999

ENTRYPOINT cd /opt/vitaminc/www && php -S 0.0.0.0:9999
