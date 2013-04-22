Same namespaces example
-----------------------
```php
   <?php
   
   $doc = new DocCommentGenerator('Shortfile description', "Long file description.\nOn multiple lines.");
   $doc->addAnnotationTag(new BaseTag('author', 'Wingu'));
   
   $requiredFiles = ['r1.php', 'r2.php'];
   $uses = ['use1', ['use2', 'use2_alias']];
   $body = "<?php\n\n\$var = 1;";
   
   $objects = array();
   $objects[] = new ClassGenerator('class_1');
   $objects[] = new interfaceGenerator('interface_1', 'myNS\subNS2');
   
   $fg = new FileGenerator('test.php');
   $fg->setDocumentation($doc);
   $fg->setNamespace('myNS\subNS2');
   $fg->setRequiredFiles($requiredFiles);
   $fg->setUses($uses);
   $fg->setExtraBody($body);
   $fg->setObjects($objects);
   
   print $fg->generate();
```

Same namespaces output
----------------------
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
   
   namespace myNS\subNS2;
   
   require_once ('r1.php');
   require_once ('r2.php');
   
   use use1;
   use use2 as use2_alias;
   
   class class_1 {
   
   }
   
   interface interface_1 {
   
   }
   
   ?>
   <?php
   
   $var = 1;
```