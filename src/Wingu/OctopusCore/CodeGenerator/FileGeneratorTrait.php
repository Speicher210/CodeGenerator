<?php

namespace Wingu\OctopusCore\CodeGenerator;

use Wingu\OctopusCore\CodeGenerator\Exceptions\RuntimeException;

/**
 * Trait class for file generation.
 */
trait FileGeneratorTrait {

    /**
     * The name of the file.
     *
     * @var string
     */
    protected $filename;

    /**
     * Set the file name.
     *
     * @param string $filename The file name.
     * @return \Wingu\OctopusCore\CodeGenerator\AbstractFileGenerator
     */
    public function setFilename($filename) {
        $this->filename = $filename;
        return $this;
    }

    /**
     * Get the file name.
     *
     * @return string
     */
    public function getFilename() {
        return $this->filename;
    }

    /**
     * Write the code to the file.
     *
     * @param string $directory The directory where to put the file. If NULL then only the filename is used.
     * @return \Wingu\OctopusCore\CodeGenerator\AbstractFileGenerator
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\RuntimeException If the file is not writable.
     */
    public function write($directory = null) {
        $file = $this->getFilename();

        if ($directory === null) {
            $directory = dirname($file);
            $file = basename($file);
        }

        $file = $directory . DIRECTORY_SEPARATOR . $file;
        if (is_writable($directory) !== true) {
            throw new RuntimeException('The file "' . $file . '" is not writable.');
        }

        file_put_contents($file, $this->generate());
        return $this;
    }
}