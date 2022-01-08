# Trakt Github Card
Dynamic cards for your Github README using Trakt.tv API

## Usage
You can interactively generate your Markdown [here](https://trakt-github-card.herokuapp.com)

or you can manually generate your request. More info on the next section

## Api
The endpoint that generates the svg is in /api, you have to send the following query params:

### Modes (required)
The available modes are:
* stats: Show total viewed movies and shows of a user
* watch: Get currently watching item, if there are none, return latest seen one.

More modes will be added in the future

### Username (required)
The profile you want to get the data from

### Theme
The available themes are:
* default
* dark

More themes will be adeded in the future

## Self-hosting

### Installation
Clone the repository and fetch the requiered external packages with:
```bash
composer install
```

Then you can run it using for example the PHP Development Server with:
```bash
php -S localhost:8080
```
### Config
You need to create a new application on Trakt [here](https://trakt.tv/oauth/applications/new), then copy the CLIENT_ID to .env
### .env
Move the .env.example file to .env and modify it.

### Apache
You don't have to do anything more

### Nginx
Add the following to your config (you can modify the trakt-card part if you have or not a subdir):
```
location /trakt-card {
    return 302 $scheme://$host/trakt-card/;
}

location /trakt-card/ {
    try_files $uri $uri/ /trakt-card/index.php?$query_string;
}

location /trakt-card/.env {
    deny all;
    return 404;
}
```

## TODO
* Improve markdown generator on /
