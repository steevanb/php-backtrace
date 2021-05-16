#!/usr/bin/env bash

set -u

function title() {
    local title=${1}
    local titleLength=${#1}

    echo -en "\n\033[46m\033[1;37m    "
    for x in $(seq 1 ${titleLength}); do echo -en " "; done;
    echo -en "\033[0m\n"

    echo -en "\033[46m\033[1;37m  ${title}  \033[0m\n"
    echo -en "\033[46m\033[1;37m    "
    for x in $(seq 1 ${titleLength}); do echo -en " "; done;
    echo -en "\033[0m\n\n"
}
