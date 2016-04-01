#Build a container to run VitaminC. A multi language test runner
FROM kcmerrill/base
MAINTAINER kc merrill <kcmerrill@gmail.com>

RUN apt-get -y update && apt-get install -y python
ADD . /var/www/
