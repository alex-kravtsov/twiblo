#!/bin/bash

TWIBLO_ROOT="/home/alex/workspace/twiblo/project";

if php $TWIBLO_ROOT/main.php --task=log.clean_outdated "$@" ; then exit 0; fi

exit 1
