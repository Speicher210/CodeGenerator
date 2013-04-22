PHP class generator example
===========================
    
Example
-------
```php
   <?php
   
   $cg = new ClassGenerator('myClass', 'MyTests\MyNamespace');
   $cg->setFinal(true);
   $cg->setExtends('c1');
   $cg->setImplements(['i1', 'i2']);
   
   $classDocAnnotation = new BaseTag('baseAnnotation', 'testing');
   $classDoc = new DocCommentGenerator('Short class description.', "Long description.\nOn multiple lines.", [$classDocAnnotation]);
   $cg->setDocumentation($classDoc);
   
   $uses = array();
   $uses[] = new UseGenerator('t1');
   $uses[] = new UseGenerator('t2', ['t2::myfunc as protected myfunc2;', 't2::func as func2;']);
   $cg->setUses($uses);
   
   $constants = array();
   $constants[] = new ClassConstantGenerator('MY_CONST1', null);
   $constant2Doc = new DocCommentGenerator('My second constant.');
   $constant2Doc->addAnnotationTag(new VarTag('string'));
   $constants[] = new ClassConstantGenerator('MY_CONST2', "it's a string", $constant2Doc);
   $cg->setConstants($constants);
   
   $properties = array();
   $properties[] = new PropertyGenerator('publicProperty');
   $properties[] = new PropertyGenerator('protectedProperty', 1, Modifiers::MODIFIER_PROTECTED);
   $properties[] = new PropertyGenerator('privateProperty', 'private', Modifiers::MODIFIER_PRIVATE);
   $propertyStatic = new PropertyGenerator('array', array(1,null,'string'), [Modifiers::MODIFIER_PROTECTED | Modifiers::MODIFIER_STATIC]);
   $propertyStaticDoc = new DocCommentGenerator('Static property.', null, [new VarTag('array')]);
   $propertyStatic->setDocumentation($propertyStaticDoc);
   $properties[] = $propertyStatic;
   $cg->setProperties($properties);
   
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
   
   $method2 = new MethodGenerator('protectedFunc', 'return count($array);');
   $method2->setVisibility(Modifiers::VISIBILITY_PROTECTED);
   $method2->addParameter(new ParameterGenerator('array', array()));
   $method2Doc = new DocCommentGenerator('Protected function.');
   $method2Doc->addAnnotationTag(new ParamTag('array', '$array', 'The array to count.'));
   $method2Doc->addAnnotationTag(new ReturnTag('integer'));
   $method2->setDocumentation($method2Doc);
   $methods[] = $method2;
   
   $method3 = new MethodGenerator('privateStaticFunction');
   $method3->setStatic(true);
   $method3->setVisibility(Modifiers::VISIBILITY_PRIVATE);
   $method3->setFinal(true);
   $method3Doc = new DocCommentGenerator('Private static function.', 'This is my long function.');
   $method3Doc->addAnnotationTag(new ParamTag('\DateTime', '$datetime', 'The date time.'));
   $method3Doc->addAnnotationTag(new ReturnTag('boolean'));
   $method3Doc->addAnnotationTag(new ThrowsTag('\InvalidArgumentException', 'If the date is in the past.'));
   $method3->setDocumentation($method3Doc);
   $method3->addParameter(new ParameterGenerator('datetime', null, '\DateTime'));
   $method3->addBodyLine(new CodeLineGenerator('if ($datetime < new \DateTime(\'now\')) {'));
   $method3->addBodyLine(new CodeLineGenerator('throw new \InvalidArgumentException(\'Date is in the past.\');', 1));
   $method3->addBodyLine(new CodeLineGenerator('}'));
   $method3->addBodyLine(new CodeLineGenerator());
   $method3->addBodyLine(new CodeLineGenerator('return true;'));
   $methods[] = $method3;
   
   $cg->setMethods($methods);
   print $cg;
```

Output
------

```php
   <?php
   
   namespace MyTests\MyNamespace;

   /**
    * Short class description.
    *
    * Long description.
    * On multiple lines.
    *
    * @baseAnnotation testing
    */
   final class myClass extends c1 implements i1, i2 {
   
       use t1;
   
       use t2 {
           t2::myfunc as protected myfunc2;
           t2::func as func2;
       }
   
       const MY_CONST1 = null;
   
       /**
        * My second constant.
        *
        * @var string
        */
       const MY_CONST2 = 'it\'s a string';
   
       public $publicProperty;
   
       protected $protectedProperty = 1;
   
       private $privateProperty = 'private';
   
       /**
        * Static property.
        *
        * @var array
        */
       protected static $array = array(1, null, 'string');
   
       /**
        * Constructor.
        *
        * @param mixed $param1 Parameter 1.
        * @param string $param2 Parameter 2.
        */
       public function __construct($param1, $param2 = null) {
       }
   
       /**
        * Protected function.
        *
        * @param array $array The array to count.
        * @return integer
        */
       protected function protectedFunc(array $array = array()) {
           return count($array);
       }
   
       /**
        * Private static function.
        *
        * This is my long function.
        *
        * @param \DateTime $datetime The date time.
        * @return boolean
        * @throws \InvalidArgumentException If the date is in the past.
        */
       final private static function privateStaticFunction(\DateTime $datetime) {
           if ($datetime < new \DateTime('now')) {
               throw new \InvalidArgumentException('Date is in the past.');
           }
   
           return true;
       }
   
   }
```