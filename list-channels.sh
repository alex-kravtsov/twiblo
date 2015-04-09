#!/bin/bash

if php main.php --task=channels.show_list "$@" ; then exit 0; fi

exit 1
