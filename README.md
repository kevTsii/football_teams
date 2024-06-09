# football_clubs
Interview test task for Internet Projects Ltd

# Installation (after git clone)
1- Change the database parameters in the .env as you wish <br>
2- composer install <br>
3- php bin/console doctrine:database:create <br>
4- php bin/console make:migration -n <br>
5- php bin/console doctrine:migrations:migrate --all-or-nothing --no-interaction <br>
6- php bin/console app:import-countries <br>
7- npm install <br>
8- npm run build <br>
9- launch the server <br>
&emsp;&emsp;a- if you have Symfony CLI : symfony server:start <br>
&emsp;&emsp;b- if you don't : php -S 127.0.0.1:8000 -t public

# enjoy :)
