docker run --name www -p 80:80 -v /Users/shengeih/Documents/htdocs/fern:/data/www/default -e "NGINX_GENERATE_DEFAULT_VHOST=true" -d shengeih/nginx-php7:latest

docker run --name phpmyadmin -p 8080:80 -v /Users/shengeih/Documents/htdocs/phpmyadmin:/data/www/default -e "NGINX_GENERATE_DEFAULT_VHOST=true" -d shengeih/nginx-php7:latest

docker run --name mysql -p 3306:3306 -v /Users/shengeih/mysql:/var/lib/mysql -e MYSQL_ROOT_PASSWORD=123456 -d theredlabs/mysql-osx
