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

After, this command will create the view controller and convert the model to Twig format: 

```
$ php app/commands/parser.php <modelname>
```

Now, you must add the route to **[app/config/conf.php](https://github.com/citymont/symetrie/blob/master/app/config/conf.php)**


## How to create a new page ?

To create a page, go to your admin and just click on **ADD PAGE**. Select a template and insert the page name (url slug).


## What are slices ?

Slices are small structured templates that you can use on every pages. Each slices can be administered.

There is a Twig template and a JSON schema for each slide.


Example : 
> Schema : app/model/slices/<shemaname>.json
> 
> Template : app/views/slices/<shemaname>.html.twig


## How to use slices ?

Each slice combinate a JSON schema and a Twig template.

#### Schema
Schema is based on JSON-editor https://github.com/jdorn/json-editor/ .
You can create one like this :

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
And save it here : **app/model/slices/<shemaname>.json**

#### Template

Create a Twig template like this : 

```
<h1>{{gender|raw}} {{prenom|raw}}</h1>
```

And save it here : **app/views/slices/<shemaname>.html.twig**

#### Include slice on your view-model file :

Example : 

```
<div class="slice" data-schema="<SCHEMANAME>" data-source="<DATA-SOURCE>" data-tpl="<DATA-TPL> ">
	{% include '<DATA-TPL> .html.twig' with <DATA-SOURCE> %}
</div>


<SCHEMANAME> : Schema name ( eg : person )
<DATA-SOURCE> : JSON filename with slice data ( eg : michel )
<DATA-TPL> : Template name ( eg : slices/test )

```

#### And update your Action Handler

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

