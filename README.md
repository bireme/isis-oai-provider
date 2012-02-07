ISIS-OAI-PROVIDER
=================

Como instalar
-------------

Assumindo que há uma instalação com o document_root configurado no diretório **htdocs**, e com os diretórios **bases** e **cgi-bin** no mesmo nível, extraia o pacote isis-oai-provider de acordo com a estrutura citada e siga os seguintes passos:

### Bases

### cgi-bin

chmod 755

### Htdocs

Ao extrair, renomeie os arquivos **oai-config-sample.php** e **oai-databases-sample.php** para **oai-config.php** e **oai-databases.php**, respectivamente.

Feito isso, vamos as configurações de cada arquivo:

* oai-config.php
** ENVIRONMENT
*** **DIRECTORY**: é o nome do diretório em que o isis-oai-provider está instalado. Recomendamos deixar a opção default.
*** **DATABASE_PATH**: caminho do diretório em que se encontram as bases de dados que serão disponibilizadas pelo isis-oai-provider
*** **CGI-BIN_DIRECTORY**: diretório em que o cgi-bin deste virtualhost foi configurado. Geralmente o conteúdo default é o mais utilizado.

