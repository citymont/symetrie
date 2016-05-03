# WIKI

Summary:
- How to create a new template ?
- How to create a new page ?
- What is slices ?
- How to use slices ?


## How to create a new template page ?

On Symétrie, model and view are in the same file. 
To create a template, you can start with a file like that :

**[app/model/<modelname>.indexeditable.html](https://github.com/citymont/symetrie/blob/master/app/model/index.editable.html)**

After, this command will create the view controller and convert the model to Twig: 

```
$ php app/commands/parser.php <modelname>
```

Now, you must add the route to **[app/conf.php](https://github.com/citymont/symetrie/blob/master/app/conf.php)**


## How to create a new page ?

To create a page, go to your admin and just click on **ADD PAGE**. Select a template and insert the page name.


## What is slices ?

Slices are small structured templates that you can use on every pages. Each slices can be administered.

There are a template and a schema for each slide.


Example : 
> Schema : app/data/slices/schema/<shemaname>.json
> 
> Template : app/views/slices/<shemaname>.html.twig


## How to use slices ?

Each slices are a combinaison of a shema and a template

#### Shema
Schema are based on JSON-editor https://github.com/jdorn/json-editor/ you can use like that :

```
{
  "type": "object",
  "title": "Person",
  "properties": {
    "gender": {
      "type": "string",
      "enum": [
        "Mr",
        "Mme",
        "Mll"]
    },
    "prenom": {
      "type": "string"
    },
    "date_naissance": {
      "type": "integer",
        "enum": [
          1995,1996,1997,1998,1999,
          2000,2001,2002,2003,2004,
          2005,2006,2007,2008,2009,
          2010,2011,2012,2013,2014
          ],
        "default": 2008
      }
  }
}
```
Save your schema here : **app/data/slices/schema/<shemaname>.json**

#### Template

Like the other, slides templates are based on Twig.
Save your template here : **app/views/slices/<shemaname>.html.twig**

Example : 

```
<h1>{{gender|raw}} {{prenom|raw}}</h1>
```

## Include slice on your view-model file :

Example : 

```
<div class="slice" data-schema="<SCHEMANAME>" data-source="<DATA-SOURCE>" data-tpl="<DATA-TPL> ">
	{% include '<DATA-TPL> .html.twig' with <DATA-SOURCE> %}
</div>


<SCHEMANAME> : Schema name ( eg : person )
<DATA-SOURCE> : JSON filename with slice data ( eg : michel )
<DATA-TPL> : Template name ( eg : slices/test )

```

## And update your Action Handler

Inside the get() function, you must load all data included into the slides.

```
function get($name = null, $b = null) {

	$appActions = new Actions(); 
        
  $dataloader = new TwigData(); 
 	$dataSlice1 = $dataloader->getData('slices/person','person');
  $dataSlice2 = $dataloader->getData('slices/person2','person2');
  $arrayData = array_merge($dataSlice1,$dataSlice2);

	if( defined('CACHE_FLAG') ) { 
		    	
    	$twig = $appActions->Twigloader();
                
      $appActions->renderViewExtended($twig, $this->modelName,$this->docId,$arrayData);
               
	}


	if( defined('ADMIN') ) { 
					
		  $appActions->Admin($this->modelName,$arrayData); 
		
	}
      
}
```

Enjoy !

