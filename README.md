# Symfony2 Sandbox

## How to install:

Included SonataAdminBundle

1. git clone git@github.com:dkorsak/symfony-sandbox.git
2. rm ./symfony-sandbox/.git -Rf
3. cd symfony-sandbox
4. cp ./cms/app/config/parameters-template.ini ./cms/app/config/parameters.ini
5. ant composer
6. Configure your project, edit /cms/app/config/parameters.ini file and set database connection, APC, Memcached etc.
7. ant cc
8. ant build.db


$ ant - shows all available ant tasks
