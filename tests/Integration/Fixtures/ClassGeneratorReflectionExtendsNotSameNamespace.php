<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures {

    use Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures\Test\MyExtension;

	class ClassGeneratorReflectionExtendsNotSameNamespace extends MyExtension {

    }
}

namespace Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures\Test {
    class MyExtension {}
}