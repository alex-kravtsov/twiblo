#!/bin/bash

if php main.php --task=log.show_statistics "$@" ; then exit 0; fi

exit 1
