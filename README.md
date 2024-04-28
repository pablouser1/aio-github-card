# All-in-one Github Card
Dynamic cards for your Github README for multiple services

## Supported services
* Trakt (https://trakt.tv)
* Backloggd (https://backloggd.com)

## Usage
You can interactively generate your Markdown [here](https://gh-cards.pabloferreiro.es)

## Self-hosting

### Installation
Clone the repository and fetch the requiered external packages with:
```bash
composer install
```

Then you can run it locally using for example the PHP Development Server with:
```bash
php -S localhost:8000 api/index.php
```
### Config
You need to create a new application on Trakt [here](https://trakt.tv/oauth/applications/new), then copy the CLIENT_ID to .env
### .env
Move the .env.example file to .env and modify it.

### Apache
You don't have to do anything more

### Nginx
Add the following to your config (you can modify the aio-card part if you have or not a subdir):
```
location /aio-card {
  return 302 $scheme://$host/aio-card/;
}

location /aio-card/ {
    try_files $uri $uri/ /aio-card/api/index.php?$query_string;
}

location /aio-card/.env {
  deny all;
  return 404;
}
```

## TODO
* Scrolling text
* Better designs
* Better themes system

## Credits
* [Backloggd](https://backloggd.com)
* [Trakt](https://trakt.tv)
* [TheMovieDB](https://themoviedb.org)
