FROM ubuntu:16.04

RUN apt update
RUN apt-get --assume-yes install gcc
COPY server /server
WORKDIR /server
RUN gcc server.c -o server
EXPOSE 5432
CMD ["./server"]
