# Symfony2 Sandbox Grund, Bower and Sass Edition

## How to install:

1. git clone git@github.com:dkorsak/symfony-sandbox.git
2. rm ./symfony-sandbox/.git -Rf
3. cd symfony-sandbox
4. cp ./cms/app/config/parameters.yml.dist ./cms/app/config/parameters.yml
5. ant composer
6. Configure your project, edit /cms/app/config/parameters.yml file and set database connection, APC, Memcached etc.
7. ant cc
8. ant build.db

$ ant - shows all available ant tasks



# GRUNT

This highlights some real-word usage of:

1. Require.js
1. Compass
1. Uglify
1. Grunt

See the **Installation** section or the **What to Look for** section that
explains what you should expect to see and where.

### Installation

1. Install the Composer vendors (download Composer first from http://getcomposer.org)

    ```
    php composer.phar install
    ```

    Follow the instructions at the end to make sure that you have the parameters.yml
    file setup.

1. Make sure you DB is present and populated!

    ```
    php app/console doctrine:database:create
    php app/console doctrine:schema:create
    php app/console doctrine:fixtures:load
    ```

1. Make sure you have node and npm installed and setup. If you do, the following
2 commands should work.

    ```
    node -v
    npm -v
    ```

    If these don't work, ya know, install them!

1. Use npm to install bower, compass and grunt-cli

    ```
    sudo npm install -g bower
    sudo npm install -g compass
    sudo npm install -g grunt-cli
    ```

1. Download the bower dependencies:

    ```
    bower install
    ```

    This should give you a populated `web/assets/vendor` directory.

1. Download the local node dependencies:

    ```
    npm install
    ```

    This should give you a `node_modules` directory.

1. Use grunt to initially compile the SASS files

    ```
    grunt
    ```

    Later, when you're actually developing, you'll use grunt to watch for file
    changes and automatically re-compile:

    ```
    grunt watch
    ```

1. Start up a web server and view it:

    ```
    php app/console server:run
    ```

    Then go to:

    ```
    http://localhost:8000
    ```

### What to Look for

Once you have the app running, if you login as `admin:admin`, you'll see
the following JavaScript items:

1. a little edit button on the homepage for each event which allows inline editing.
1. When adding a new event, you'll see that the form is AJAX-submitted.
1. When adding a new event, if you click the map, its border changes colors.

All of these are driven by JavaScript included by Require.js. See the `::base.html.twig`
file as well as the `EventBundle::_requirejs.html.twig` file and notes.
Or, just watch the darn presentation :p.

Compass is also used - the SASS files are located at `web/assets/sass` and
compiled to `web/assets/css`.