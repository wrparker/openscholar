#!/usr/bin/env bash
#!/bin/sh
set -e

docker build -t openscholar .
docker run -it -p 8080:80 -e TEST_SUITE=restful openscholar bash

# After the docker will finish the command above it will terminate it self.
# In order to keep docker up and running you need to:
# 1. Run docker ps -a -q
# 2. Run docker start HASH
#
#
# Docker will not delete the living containers. Docker containers takes space on
# your HDD. In order to remove them type:
# docker stop $(docker ps -a -q)
# docker rm $(docker ps -a -q)
