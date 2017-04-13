#!/usr/bin/env bash
#!/bin/sh
set -e

docker build -t openscholar .
docker start -it -p -e TEST_SUITE=$TEST_SUITE 8080:80 openscholar
