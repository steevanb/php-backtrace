#!/usr/bin/env sh

set -eu

if [ -z "${PHP_SHORT_VERSION:-}" ]; then
    echo "Variable PHP_SHORT_VERSION should be defined."
    exit 1
fi

if [ $(which docker || false) ]; then
    readonly DOCKER_IMAGE_NAME="steevanb/php-backtrace-ci:php${PHP_SHORT_VERSION}"

    if [ -z "${ROOT_DIR:-}" ]; then
        echo "Variable ROOT_DIR should be defined."
        exit 1
    fi

    docker \
        run \
            --rm \
            -i \
            --volume "${ROOT_DIR}":/app \
            "${DOCKER_IMAGE_NAME}" \
            "bin/ci/phpunitPhp${PHP_SHORT_VERSION}"
else
    # Install steevanb/php-backtrace from current source code
    composer global update > /dev/null 2>&1

    phpunit tests
fi
