#!/bin/bash

# Parameters:
# --channel_id=CHANNEL_ID

if php main.php --task=channel.activate "$@" ; then exit 0; fi

exit 1
