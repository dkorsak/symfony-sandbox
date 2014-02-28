# Symfony2 Sandbox Grund, Bower and Sass Edition usage

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
