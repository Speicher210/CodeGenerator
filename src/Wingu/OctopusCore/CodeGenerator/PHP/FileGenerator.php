<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP;

use Wingu\OctopusCore\CodeGenerator\FileGeneratorTrait;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\AbstractObject;

/**
 * Class to generate PHP files.
 */
class FileGenerator extends PHPGenerator
{

    use FileGeneratorTrait;
    use DocCommentTrait;
    use NamespaceTrait;
    use GlobalUseTrait;

    /**
     * The required files.
     *
     * @var array
     */
    protected $requiredFiles = array();

    /**
     * Objects added to the file.
     *
     * @var \Wingu\OctopusCore\CodeGenerator\PHP\OOP\AbstractObject[]
     */
    protected $objects = array();

    /**
     * Extra code in the file.
     *
     * @var string
     */
    protected $extraBody;

    /**
     * Constructor.
     *
     * @param string $filename The name of the file.
     */
    public function __construct($filename)
    {
        $this->setFilename($filename);
    }

    /**
     * Set the required files.
     *
     * @param array $requiredFiles The required files.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\FileGenerator
     */
    public function setRequiredFiles(array $requiredFiles)
    {
        $this->requiredFiles = $requiredFiles;
        return $this;
    }

    /**
     * Add required files.
     *
     * @param array $requiredFiles The required files to add.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\FileGenerator
     */
    public function addRequiredFiles(array $requiredFiles = array())
    {
        $this->requiredFiles = array_merge($this->requiredFiles, $requiredFiles);
        return $this;
    }

    /**
     * Add a required file.
     *
     * @param string $requiredFile The required file to add.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\FileGenerator
     */
    public function addRequiredFile($requiredFile)
    {
        $this->requiredFiles[] = $requiredFile;
        return $this;
    }

    /**
     * Get the required files.
     *
     * @return array
     */
    public function getRequiredFiles()
    {
        return $this->requiredFiles;
    }

    /**
     * Set the objects in the file.
     *
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\OOP\AbstractObject[] $objects The objects to add.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\FileGenerator
     */
    public function setObjects(array $objects)
    {
        $this->objects = array();
        return $this->addObjects($objects);
    }

    /**
     * Add objects in the file.
     *
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\OOP\AbstractObject[] $objects The objects to add.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\FileGenerator
     */
    public function addObjects(array $objects)
    {
        foreach ($objects as $object) {
            $this->addObject($object);
        }

        return $this;
    }

    /**
     * Add an object in the file.
     *
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\OOP\AbstractObject $object The object to add.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\FileGenerator
     */
    public function addObject(AbstractObject $object)
    {
        $this->objects[] = $object;
        return $this;
    }

    /**
     * Get the objects in the file.
     *
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\OOP\AbstractObject[]
     */
    public function getObjects()
    {
        return $this->objects;
    }

    /**
     * Set the extra code in the file.
     *
     * @param string $body The body.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\FileGenerator
     */
    public function setExtraBody($body)
    {
        $this->extraBody = $body;
        return $this;
    }

    /**
     * Get the extra code of the file.
     *
     * @return string
     */
    public function getExtraBody()
    {
        return $this->extraBody;
    }

    /**
     * Generate the code.
     *
     * @return string
     */
    public function generate()
    {
        $code = array();

        $body = $this->getExtraBody();
        $bodyHasOpenTag = preg_match('#(?:\s*)<\?php#', $body);
        $code[] = '<?php';
        $code[] = null;

        // Documentation.
        $doc = $this->generateDocumentation();
        if ($doc !== null) {
            $code[] = $doc;
            $code[] = null;
        }

        foreach ($this->objects as $object) {
            foreach ($object->getUses() as $use => $alias) {
                $this->addUse($use, $alias);
            }
        }

        // If there are objects in different namespaces or some objects are in a different namespace compared to the file namespace.
        if ($this->areMultipleNamespacesInFile() === true) {
            $code = array_merge($code, $this->renderObjects(true));

            $code[] = $this->getIndentation() . 'namespace ' . $this->namespace . ' {';
            $code[] = null;

            // Temporarily add one more level of indentation.
            $this->addIndentationLevel(1);

            $code = array_merge($code, $this->renderRequiredFilesLines());

            $code = array_merge($code, $this->renderUsesLines());

            if ($bodyHasOpenTag > 0) {
                $code[] = '?>';
            }

            if ($this->extraBody !== null) {
                $code[] = $this->extraBody;
            }

            // Set back the indentation.
            $this->addIndentationLevel(-1);

            $code[] = $this->getIndentation() . '}';
        } else {
            if ($this->namespace !== null) {
                $code[] = $this->getIndentation() . 'namespace ' . $this->namespace . ';';
                $code[] = null;
            }

            $code = array_merge($code, $this->renderRequiredFilesLines());

            $code = array_merge($code, $this->renderUsesLines());

            $code = array_merge($code, $this->renderObjects(false));

            if ($bodyHasOpenTag > 0) {
                $code[] = '?>';
            }

            if ($this->extraBody !== null) {
                $code[] = $this->extraBody;
            }
        }

        return implode($this->getLineFeed(), $code);
    }

    /**
     * Check if there are multiple namespaces in the file.
     *
     * If an object has no namespace defined it will not be considered as different than the file namespace.
     *
     * @return boolean
     */
    private function areMultipleNamespacesInFile()
    {
        if (count($this->objects) === 0) {
            return false;
        }

        $foundNamespaces = array();
        foreach ($this->objects as $object) {
            $ns = $object->getNamespace();
            if ($ns !== null) {
                $foundNamespaces[$ns] = $ns;
            }
        }

        $foundNamespacesCount = count($foundNamespaces);

        if ($foundNamespacesCount === 0) {
            return false;
        } elseif ($foundNamespacesCount > 1) {
            return true;
        } else {
            return !isset($foundNamespaces[$this->getNamespace()]);
        }
    }

    /**
     * Render the required files.
     *
     * @return array
     */
    private function renderRequiredFilesLines()
    {
        $code = array();
        $indentation = $this->getIndentation();
        foreach ($this->requiredFiles as $requiredFile) {
            $code[] = $indentation . "require_once ('" . $requiredFile . "');";
        }

        if (count($this->requiredFiles) > 0) {
            $code[] = null;
        }

        return $code;
    }

    /**
     * Render all the objects.
     *
     * @param boolean $withNamespace Flag if the objects should also be rendered with the namespace.
     * @return array
     */
    private function renderObjects($withNamespace)
    {
        $code = array();
        foreach ($this->getObjects() as $object) {
            $originalUses = $object->getUses();
            $object->setUses(array());
            if ($withNamespace === false) {
                $originalNamespace = $object->getNamespace();
                $object->setNamespace(null);
                $code[] = $object->generate();
                $object->setNamespace($originalNamespace);
            } else {
                $code[] = $object->generate();
            }

            $object->setUses($originalUses);
            $code[] = null;
        }

        return $code;
    }
}