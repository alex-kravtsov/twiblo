#!/bin/bash

if php main.php --task=channels.show_statistics "$@" ; then exit 0; fi

exit 1
