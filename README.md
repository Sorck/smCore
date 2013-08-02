# smCore
This is pre-alpha quality so please don't run it on a production server.

# Installation
You need to use Composer to install the required dependencies.
First download Composer using
``` curl -s http://getcomposer.org/installer | php ```
Then install the dependencies:
``` php composer.phar install ```

Next run ./other/install.php (this produces your SQL query and creates ./settings.php). After this, make sure to fully set up ./settings.php run the generated SQL file.

# Documentation
Just run PHPDocumentor to get hold of the latest documentation. It's not very complete yet so feel free to contribute more documentation to the codebase.

# Miscellaneous

1. It's not set up very well. Sorry about that, I'll clean it up as soon as I remember that I wrote this.

2. I tend to break things. I also change my mind a few times a week and make stupid coding decisions just as often. I usually notice my mistakes sooner or later, though.