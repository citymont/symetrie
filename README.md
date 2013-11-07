Symétrie
========

soon ... Symétrie is a simple tool to build website with no sql database and in-place editing

## Installation

```
$ composer install
$ php app/commands/install.php
```

## Dependencies
1. Twig
2. ToroPHP

## Directories

```

app/
    commands/
        page.php    - add document to a collection
        parser.php  - write template file from model

    data/
        (modelname)
            /*.json - json file for a document (timestamp history)

    model/          - file like *.editable.html as the model

    src/
        admin/      - actions handlers for admin
        actions.php - rendering functions related to actions
        app.php     - app functions
        cache.php   - functions related to static cache
        (model).actions.php - action handler for a model

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

