Different namespaces example
----------------------------

```php
   <?php
   
   $doc = new DocCommentGenerator('Shortfile description', "Long file description.\nOn multiple lines.");
   $doc->addAnnotationTag(new BaseTag('author', 'Wingu'));

   $requiredFiles = ['r1.php', 'r2.php'];
   $uses = ['use1', ['use2', 'use2_alias']];
   $body = '$var = 1;';
   
   $objects = array();
   $objects[] = new ClassGenerator('class_1', 'myObjects\classes');
   $objects[] = new ClassGenerator('class_2', 'myObjects\classes');
   $objects[] = new interfaceGenerator('interface_1', 'myObjects\interfaces');

   $fg = new FileGenerator('test.php');
   $fg->setDocumentation($doc);
   $fg->setNamespace('\myObjects');
   $fg->setRequiredFiles($requiredFiles);
   $fg->setUses($uses);
   $fg->setExtraBody($body);
   $fg->setObjects($objects);
   
   print $fg->generate();
```

Different namespaces output
---------------------------
```php
   <?php

   /**
    * Shortfile description
    *
    * Long file description.
    * On multiple lines.
    *
    * @author Wingu
    */
   
   namespace myObjects\classes {
   
       class class_1 {
   
       }
   }
   
   namespace myObjects\classes {
   
       class class_2 {
   
       }
   }
   
   namespace myObjects\interfaces {
   
       interface interface_1 {
   
       }
   }
   
   namespace myObjects {
   
       require_once ('r1.php');
       require_once ('r2.php');
   
       use use1;
       use use2 as use2_alias;
   
   $var = 1;
   }
```