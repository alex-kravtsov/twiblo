#!/bin/bash

TWIBLO_ROOT="/home/alex/workspace/twiblo/project";

if php $TWIBLO_ROOT/main.php --task=twitter.update "$@" ; then exit 0; fi

exit 1
