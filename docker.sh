#!/usr/bin/env bash
docker build -t openscholar .
docker run -it -p 8080:80 openscholar
