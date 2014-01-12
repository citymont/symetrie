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

## Dependencies
1. Twig
2. ToroPHP

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

