#!/usr/bin/env bash
#!/bin/sh
set -e

docker build -t openscholar .
docker run -it -p 8080:80 -e TEST_SUITE=$TEST_SUITE openscholar
