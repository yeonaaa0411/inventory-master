#!/bin/bash

# Start the Flask application in the background
python3 /var/www/html/predictive_analytics.py &

# Start Apache in the foreground
apache2ctl -D FOREGROUND
