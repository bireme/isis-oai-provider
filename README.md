ISIS-OAI-PROVIDER
=================

Isis-oai-provider is a software that provides a ISIS databases in OAI protocol to be harvested.

The application work with PHP 5.3.3 or later.


## Instalation

* Download the last version from **Downloads** link from github repository (zip or tar gz file) and
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

* Create a VirtualHost for the web application (alternatively you can copy directories for a existing VirtualHost)

```
    <VirtualHost *:80>
       ServerName isis-oai-provider.local
       DocumentRoot /opt/bireme/isis-oai-provider/htdocs/

       <Directory "/opt/bireme/isis-oai-provider/htdocs">
            Options +FollowSymLinks
            AllowOverride FileInfo
            Options Indexes ExecCGI MultiViews FollowSymLinks
            MultiviewsMatch Handlers
            Order allow,deny
            Allow from all
       </Directory>

       <Directory "/opt/bireme/isis-oai-provider/cgi-bin">
            Options +ExecCGI
            AllowOverride None
            Options FollowSymLinks MultiViews
            Order deny,allow
       </Directory>

       ScriptAlias /cgi-bin/ /opt/bireme/isis-oai-provider/cgi-bin/
       LogLevel warn
       CustomLog /var/log/apache2/isis-oai-provider.log combined
       ServerSignature On
    </VirtualHost>
```

* Rename configuration files

```
    $ cd htdocs/isis-oai-provider/
    $ mv oai-config-sample.php oai-config.php
    $ mv oai-databases-sample.php oai-databases.php

```
