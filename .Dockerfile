FROM tomsik68/xampp
MAINTAINER alexdimthesisauth <alexdim.thesis.auth@gmail.com>
COPY . /www
RUN chmod -R 757 /www
COPY . /usr/share/

EXPOSE 8000