Symétrie
========

Symétrie is a simple and accessible tool to build website with in-place editing and no sql database.

http://symetrie.moderntree.net

## Installation

```
$ composer install
$ php app/commands/install.php
$ php app/commands/parser.php index (init the first model)
```
Make sure that the /app/data and /app/storage folders and all its subfolders are writable

## Start

Login to edit page :  /public/admin.php/admin/login

Edit default page :  /public/admin.php/

Show default page :  /public/index.php/

## Dependencies
1. Twig
2. ToroPHP (PHP >= 5.3)
3. PHP >= 5.5.0 (for Password Hashing API)

## Extras
1. [Vagrant Box Symétrie](https://github.com/citymont/symetrie-vagrant)
2. [Frontpack for Symétrie (Grunt, bootstrap)](https://github.com/citymont/symetrie-bootstrap)


## Licence

MIT

## Directories

```

app/
    
    actions/        - controllers/action handler for a model
    
    commands/
        page.php    - add document to a collection
        parser.php  - write template file from model
        cache.php   - clear cache
        generatepwd.php    - generate admin password

    data/
        (modelname)
            /*.json - json file for a document (timestamp history)

    lib/            - app functions
    
    model/          - file like *.editable.html as the model

    storage/
        cache/      - static cache
        views/      - twig cache

    views/
        main/       - template files

public/
    css/            - css files
    js/             - js files
    index.php       - load public app 
    admin.php       - load editor app 
```

