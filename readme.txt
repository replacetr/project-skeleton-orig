1.  run this comm>composer install
2.  run this comm>edit .env files to your database 

- DATABASE_URL=mysql://{db_root}:{db_password}@{localhost_db_path}:{port_of_db}/{database+name}

3. run this comm>php bin/console make migration
4.  
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
5 go to bin/DataFixture/UserFixtures.php note that user is create using this class
    run>php bin/console doctrine:fixture:load

6 Walla!