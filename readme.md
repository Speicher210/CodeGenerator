Wingu Code Generator Component
==============================

Overview
--------

The Code Ggenerator Component allows you to generate arbitrary code using an object oriented interface.
Current implementation is limited to *PHP* but can easily be extended to generate code for other languages or even configuration files.

With the PHP generator you can like functions, classes and traits.
You can also generate the documentation of code and even the annotations atached to the documentation code.

Usage
-----
In the most typical use case, you will simply instantiate a code generator class or use the ``fromReflection()`` method to load all the parts from.
To dump the code you can either print out the generator instance or call the ``generate()`` method.

```php
   // Example how to generate a function using the API.
   $code = new FunctionGenerator('myTestFunction');
   
   $functionDoc = new DocCommentGenerator('Short function description.', "Long description.\nOn multiple lines.");
   $functionDoc->addAnnotationTag(new ParamTag('array', '$array', 'The array to sum up.'));
   $functionDoc->addAnnotationTag(new ParamTag('float', '$times', 'The multiplier.'));
   $functionDoc->addAnnotationTag(new ReturnTag('float'));
   $code->setDocumentation($functionDoc);
   
   $code->addParameter(new ParameterGenerator('array', null, 'array'));
   $code->addParameter(new ParameterGenerator('times', 1));
   $code->addBodyLine(new CodeLineGenerator('return array_sum($array) * $times;'));
   
   print $code;
```

This will generate something similar to:

```php
   <?php
   
   /**
    * Short function description.
    *
    * Long description.
    * On multiple lines.
    *
    * @param array $array The array to sum up.
    * @param float $times The multiplier.
    * @return float
    */
   function myTestFunction(array $array, $times = 1) {
       return array_sum($array) * $times;
   }
```

Detailed documentation about some generators:
Please refer to the API documentation for a complete list of code generators and their capabilities.
See the following examples for a quick reference.

[File generator](/docs/php/filegenerator.md)

[Class generator](/docs/php/oop/generate-class.md)

[Interface generator](/docs/php/oop/generate-interface.md)

[Trait generator](/docs/php/oop/generate-trait.md)
