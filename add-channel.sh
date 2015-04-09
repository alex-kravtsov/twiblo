#!/bin/bash

# Parameters:
# --rss_feed_url=RSS_FEED_URL

if php main.php --task=channel.add "$@" ; then exit 0; fi

exit 1;
