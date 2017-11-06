docker run --name www -p 80:80 -v /Users/shengeih/Documents/htdocs/fern:/data/www/default -e "NGINX_GENERATE_DEFAULT_VHOST=true" -d shengeih/nginx-php7:latest
