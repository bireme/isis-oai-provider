ISIS-OAI-PROVIDER
=================

Isis-oai-provider is a software that provides a ISIS databases in OAI protocol to be harvested.

The application work with PHP 5.3.3 or later.


## Instalation

1. Download the last version from **Downloads** link from github repository (zip or tar gz file) and
extract the file to your server.
Example: Creating a structure and deploy the application under /opt/ directory:

```
    $ cd /opt/
    $ mkdir bireme
    $ cd bireme
    $ wget https://github.com/bireme/isis-oai-provider/tarball/master -O isis-oai-provider.tar.gz
    $ tar xvfzp isis-oai-provider.tar.gz
    $ mv bireme-isis-oai-provider-53c0abc/ isis-oai-provider
```

1. Create a VirtualHost for the web application (alternatively you can copy directories for a existing VirtualHost)

```
    <VirtualHost *:80>
       ServerName isis-oai-provider.local
       DocumentRoot /opt/isis-oai-provider/htdocs/
       <Directory "/home/projects/isis-oai-provider/htdocs">
        Options +FollowSymLinks
            AllowOverride FileInfo
            Options Indexes ExecCGI MultiViews FollowSymLinks
            MultiviewsMatch Handlers
            Order allow,deny
            Allow from all
       </Directory>
       <Directory "/opt/projects/isis-oai-provider/cgi-bin">
           Options +ExecCGI
       AllowOverride None
       Options FollowSymLinks MultiViews
       Order deny,allow
       </Directory>
       ScriptAlias /cgi-bin/ /opt/isis-oai-provider/cgi-bin/
       LogLevel warn
       CustomLog /var/log/apache2/isis-oai-provider.log combined
       ServerSignature On
    </VirtualHost>
```
