#!/bin/bash

echo "" > tests/logs/check_docker.log

if [ -x "$(command -v docker)" ]; then
    echo "Docker is installed" > tests/logs/check_docker.log
fi