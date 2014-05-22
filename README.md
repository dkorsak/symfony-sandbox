# Symfony2 Sandbox based on the old bootstrap 2 theme Grund, Bower and Sass Edition


## How to install:

1. git clone git@github.com:dkorsak/symfony-sandbox.git
1. rm ./symfony-sandbox/.git -Rf
1. cd symfony-sandbox
1. cp ./cms/app/config/parameters.yml.dist ./cms/app/config/parameters.yml
1. ant composer
1. Configure your project, edit /cms/app/config/parameters.yml file and set database connection, APC, Memcached etc.
1. Install grunt and bower see https://github.com/dkorsak/symfony-sandbox/blob/grunt/cms/README.md
1. ant build


$ ant - shows all available ant tasks
