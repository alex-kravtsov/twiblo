<?php

abstract class TwibloConfig {

    // Database settings
    const DB_HOST = "localhost";
    const DB_USER = "twiblo";
    const DB_PASSWORD = "ki9fkJ4nGiIk4nF98";
    const DB_NAME = "twiblo";
    const DB_CHARSET = "utf8";
    const DB_PREFIX = "pro6_";

    // Twitter oAuth settings
    const CONSUMER_KEY = "your-consumer-key";
    const CONSUMER_SECRET = "your-consumer-secret";
    const ACCESS_TOKEN = "your-access-token";
    const ACCESS_TOKEN_SECRET = "your-access-token-secret";

    // Common settings
    const INTERNAL_ENCODING = "utf-8";
    const TIMEZONE = "Europe/Moscow";

    // Custom application settings
    const MAX_TWITTER_UPDATE_DELAY = 20;
    const KEEP_LOG_DAYS = 3;

}
