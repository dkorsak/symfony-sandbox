# Symfony2 Sandbox based on bootstrap 3 sonata admin theme


[See theme here](http://almsaeedstudio.com/AdminLTE/) 


## How to install:


1. git clone git@github.com:dkorsak/symfony-sandbox.git
2. rm ./symfony-sandbox/.git -Rf
3. cd symfony-sandbox
4. ant composer
5. cp ./cms/app/config/parameters.yml.dist ./cms/app/config/parameters.yml
6. Configure your project, edit /cms/app/config/parameters.yml file and set database connection, Memcached etc.
7. ant cc
8. ant build.db


$ ant - shows all available ant tasks


