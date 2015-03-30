<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP;

use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\AnnotationGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\BaseTag;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\TagInterface;
use Wingu\OctopusCore\Reflection\ReflectionDocComment;

/**
 * Class for generating a documentation comment.
 */
class DocCommentGenerator extends PHPGenerator
{

    /**
     * The short description.
     *
     * @var string
     */
    protected $shortDescription;

    /**
     * The long description.
     *
     * @var string
     */
    protected $longDescription;

    /**
     * The annotation generator.
     *
     * @var \Wingu\OctopusCore\CodeGenerator\PHP\Annotation\AnnotationGenerator
     */
    protected $annotationGenerator;

    /**
     * Constructor.
     *
     * @param string $shortDescription The short description.
     * @param string $longDescription The long description.
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\TagInterface[] $annotationTags The annotation tags to add.
     */
    public function __construct($shortDescription = null, $longDescription = null, array $annotationTags = array())
    {
        $this->setShortDescription($shortDescription);
        $this->setLongDescription($longDescription);

        $this->annotationGenerator = new AnnotationGenerator($annotationTags);
    }

    /**
     * Create a new documentation comment from reflection.
     *
     * @param \Wingu\OctopusCore\Reflection\ReflectionDocComment $reflectionDocComment The documentation comment reflection.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator
     */
    public static function fromReflection(ReflectionDocComment $reflectionDocComment)
    {
        $annotations = array();
        foreach ($reflectionDocComment->getAnnotationsCollection()->getAnnotations() as $reflectionTag) {
            $annotations[] = BaseTag::fromReflection($reflectionTag);
        }

        return new static($reflectionDocComment->getShortDescription(), $reflectionDocComment->getLongDescription(), $annotations);
    }

    /**
     * Add an annotation tag.
     *
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\TagInterface $tag The tag to add.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator
     */
    public function addAnnotationTag(TagInterface $tag)
    {
        $this->annotationGenerator->addTag($tag);

        return $this;
    }

    /**
     * Set the short description.
     *
     * @param string $description The short description.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator
     */
    public function setShortDescription($description)
    {
        $description = trim($description);
        if ($description === '') {
            $this->shortDescription = null;
        } else {
            $this->shortDescription = $description;
        }

        return $this;
    }

    /**
     * Get the short description of the comment.
     *
     * @return string
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set the long description.
     *
     * @param string $description The long description.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator
     */
    public function setLongDescription($description)
    {
        $description = trim($description);
        if ($description === '') {
            $this->longDescription = null;
        } else {
            $this->longDescription = $description;
        }

        return $this;
    }

    /**
     * Get the long description of the comment.
     *
     * @return string
     */
    public function getLongDescription()
    {
        return $this->longDescription;
    }

    /**
     * Get the annotation tags.
     *
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\TagInterface[]
     */
    public function getAnnotationTags()
    {
        return $this->annotationGenerator->getTags();
    }

    /**
     * Remove all tags that have a specific name.
     *
     * @param string $tagName The name of the tag.
     */
    public function removeAnnotationTagsByName($tagName)
    {
        $this->annotationGenerator->removeTagsByName($tagName);
    }

    /**
     * Generate the code.
     *
     * @return string
     */
    public function generate()
    {
        $indentation = $this->getIndentation();

        $docLines = array();

        $docLines[] = $indentation . '/**';
        $spaceBeforeAnnotation = false;

        if ($this->shortDescription !== null) {
            $docLines[] = $indentation . ' * ' . $this->shortDescription;
            $spaceBeforeAnnotation = true;
        }

        if ($this->shortDescription !== null && $this->longDescription !== null) {
            $docLines[] = $indentation . ' *';
        }

        if ($this->longDescription !== null) {
            $description = preg_split('/\n|\r/', $this->longDescription, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($description as $desc) {
                $docLines[] = $indentation . ' * ' . trim($desc);
            }

            $spaceBeforeAnnotation = true;
        }

        $this->annotationGenerator->setIndentationLevel($this->getIndentationLevel());
        $this->annotationGenerator->setIndentationString($this->getIndentationString());
        $annotations = $this->annotationGenerator->generate();
        if ($annotations !== '') {
            if ($spaceBeforeAnnotation === true) {
                $docLines[] = $indentation . ' *';
            }

            $docLines[] = $annotations;
        }

        $docLines[] = $indentation . ' */';

        return implode($this->getLineFeed(), $docLines);
    }
}
