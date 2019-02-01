#!/bin/bash
php src/waitForDatabase.php
phpunit --bootstrap src/autoload.php tests