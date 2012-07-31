ISIS-OAI-PROVIDER
=================

Isis-oai-provider is a software that provides a ISIS databases in OAI protocol to be harvested.

The application work with PHP 5.3.3 or later.


## Instalation

1. Download the last version from **Downloads** link from github repository (zip or tar gz file) and
extract the file to your server.
Example: Creating a structure and deploy the application under server **/opt/** directory:

```
    $ cd /opt/
    $ mkdir bireme
    $ cd bireme
    $ wget https://github.com/bireme/isis-oai-provider/tarball/master -O isis-oai-provider.tar.gz
    $ tar xvfzp isis-oai-provider.tar.gz
    $ mv bireme-isis-oai-provider-53c0abc/ isis-oai-provider
```

+ Deploy the aplication on the Web Server (ex. Apache Web Server)

    * Option A - Using a specific VirtualHost for the web application

```
    <VirtualHost *:80>
       ServerName isis-oai-provider.local
       DocumentRoot /opt/bireme/isis-oai-provider/htdocs/

       <Directory "/opt/bireme/isis-oai-provider/cgi-bin">
            Options +ExecCGI
            AllowOverride None
            Order deny,allow
       </Directory>

       ScriptAlias /cgi-bin/ /opt/bireme/isis-oai-provider/cgi-bin/
       LogLevel warn
       CustomLog /var/log/apache2/isis-oai-provider.log combined
       ServerSignature On
    </VirtualHost>
```

    * Option B - Using a existing VirtualHost

Assuming you have a website running you need to find the path where is located the following components:

DocumentRoot  --- directory where static files and PHP scripts are located
CGI-BIN       --- directory with execution permission for CGI scripts
ISIS dabases  --- directory on the server where ISIS databases are located

Then move/copy or create symbolic links to the isis-oai-provider directories:

    Ex. Assuming a website with this directory structure:
    /home/sites/
               /site1/
                     htdocs/
                     cgi-bin/
                     bases/

   $ cd /home/sites/site1/
   $ cd htdocs
   $ ln -s /opt/bireme/isis-oai-provider/htdocs/isis-oai-provider/ .
   $ cd ..
   $ cd cgi-bin
   $ ln -s /opt/bireme/isis-oai-provider/cgi-bin/isis-oai-provider/ .



+ Rename configuration files

```
    $ cd htdocs/isis-oai-provider/
    $ mv oai-config-sample.php oai-config.php
    $ mv oai-databases-sample.php oai-databases.php

```

+ To test the application call your browser and inform your server name like: **http://your_server_name/isis-oai-provider/**

  Ex. http://isis-oai-provider.local/isis-oai-provider/


