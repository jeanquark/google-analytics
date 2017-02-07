# Retrieve Google Analytics data in Laravel via AJAX call to the Google API

For step by step instructions, visit my [blog post](http://www.jmkleger.com/post/retrieve-analytics-data-in-laravel-via-ajax-call-to-the-google-api).
No database required!


## Installation

Clone the repo
```
git clone https://github.com/jeanquark/google_analytics.git
```

Move to the newly created folder and install all dependencies:
```
cd google_analytics
composer install
```

Rename .env.example
```
mv .env.example .env
```

Generate the application key 
```
php artisan key:generate
```

Finally, add a new folder named .google containing your personal service account file. To obtain this file, follow the instructions (Step 1) on [this](https://developers.google.com/analytics/devguides/reporting/core/v3/quickstart/service-php) page.

Visit the newly created app on your favorite browser.

## Features
1. Retrieve a selection of Google Analytics dimensions and metrics and display them in a table.
2. Well, that's it ;-)

## Screenshots

Homepage:
![homepage](https://github.com/jeanquark/google-analytics/raw/master/public/homepage.png "Homepage")

Button:
![analytics](https://github.com/jeanquark/google-analytics/raw/master/public/analytics.png "analytics")

Table:
![table](https://github.com/jeanquark/google-analytics/raw/master/public/table.png "table")

## Licence

Please refer to the [Laravel licence](https://opensource.org/licenses/MIT)