PHP interface generator example
===============================

Example
-------

```php
   <?php
   $ig = new InterfaceGenerator('myInterface', 'MyTests\MyNamespace');
   $ig->setExtends(['i1', 'i2']);
   
   $interfaceDocAnnotation = new BaseTag('baseAnnotation', 'testing');
   $classDoc = new DocCommentGenerator('Short interface description.', "Long description.\nOn multiple lines.", [$interfaceDocAnnotation]);
   $ig->setDocumentation($classDoc);
   
   $constants = array();
   $constants[] = new ClassConstantGenerator('MY_CONST1', null);
   $constant2Doc = new DocCommentGenerator('My second constant.');
   $constant2Doc->addAnnotationTag(new VarTag('string'));
   $constants[] = new ClassConstantGenerator('MY_CONST2', "it's a string", $constant2Doc);
   $ig->setConstants($constants);
   
   $methods = array();
   $method1 = new MethodGenerator('__construct');
   $method1Doc = new DocCommentGenerator('Constructor.');
   $method1Doc->addAnnotationTag(new ParamTag('mixed', '$param1', 'Parameter 1.'));
   $method1Doc->addAnnotationTag(new ParamTag('string', '$param2', 'Parameter 2.'));
   $method1->setDocumentation($method1Doc);
   $method1->addParameter(new ParameterGenerator('param1'));
   $method1Param2 = new ParameterGenerator('param2');
   $method1Param2->setDefaultValue(null);
   $method1->addParameter($method1Param2);
   $methods[] = $method1;
   
   $method2 = new MethodGenerator('publicFunc', 'return count($array);');
   $method2->addParameter(new ParameterGenerator('array', array()));
   $method2Doc = new DocCommentGenerator('Protected function.');
   $method2Doc->addAnnotationTag(new ParamTag('array', '$array', 'The array to count.'));
   $method2Doc->addAnnotationTag(new ReturnTag('integer'));
   $method2->setDocumentation($method2Doc);
   $methods[] = $method2;
   
   $method3 = new MethodGenerator('publicStaticFunction');
   $method3->setStatic(true);
   $method3Doc = new DocCommentGenerator('Private static function.', 'This is my long function.');
   $method3Doc->addAnnotationTag(new ParamTag('\DateTime', '$datetime', 'The date time.'));
   $method3Doc->addAnnotationTag(new ReturnTag('boolean'));
   $method3Doc->addAnnotationTag(new ThrowsTag('\InvalidArgumentException', 'If the date is in the past.'));
   $method3->setDocumentation($method3Doc);
   $method3->addParameter(new ParameterGenerator('datetime', null, '\DateTime'));
   $methods[] = $method3;
   
   $ig->setMethods($methods);
   
   print $ig;
```

Output
------

```php
   <?php
   
   namespace MyTests\MyNamespace;
   
   /**
    * Short interface description.
    *
    * Long description.
    * On multiple lines.
    *
    * @baseAnnotation testing
    */
   interface myInterface extends i1, i2 {
   
       const MY_CONST1 = null;
   
       /**
        * My second constant.
        *
        * @var string
        */
       const MY_CONST2 = 'it\'s a string';
   
       /**
        * Constructor.
        *
        * @param mixed $param1 Parameter 1.
        * @param string $param2 Parameter 2.
        */
       public function __construct($param1, $param2 = null);
   
       /**
        * Protected function.
        *
        * @param array $array The array to count.
        * @return integer
        */
       public function publicFunc(array $array = array());
   
       /**
        * Private static function.
        *
        * This is my long function.
        *
        * @param \DateTime $datetime The date time.
        * @return boolean
        * @throws \InvalidArgumentException If the date is in the past.
        */
       public static function publicStaticFunction(\DateTime $datetime);
   
   }
```