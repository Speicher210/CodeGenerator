<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\Annotation;

use Wingu\OctopusCore\CodeGenerator\AbstractGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\TagInterface;

/**
 * Documentation annotation generator.
 */
class AnnotationGenerator extends AbstractGenerator
{

    /**
     * Array of tags.
     *
     * @var \Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\TagInterface[]
     */
    protected $tags = array();

    /**
     * Constructor.
     *
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\TagInterface[] $tags The annotations to add.
     */
    public function __construct(array $tags = array())
    {
        $this->addTags($tags);
    }

    /**
     * Set annotations tags.
     *
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\TagInterface[] $tags The annotations tags to set.
     */
    public function setTags(array $tags)
    {
        $this->tags = array();
        $this->addTags($tags);
    }

    /**
     * Add annotations tags.
     *
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\TagInterface[] $tags The annotations tags to add.
     */
    public function addTags(array $tags)
    {
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }
    }

    /**
     * Add an annotation tag.
     *
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\TagInterface $tag The annotation tag to add.
     */
    public function addTag(TagInterface $tag)
    {
        $this->tags[] = $tag;
    }

    /**
     * Get the annotations tags.
     *
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\TagInterface[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Remove all tags that have a specific name.
     *
     * @param string $tagName The name of the tag.
     */
    public function removeTagsByName($tagName)
    {
        $this->tags = array_filter(
            $this->tags,
            function (TagInterface $tag) use ($tagName) {
                return $tag->getTagName() !== $tagName;
            }
        );
    }

    /**
     * Generate the code.
     *
     * @return string
     */
    public function generate()
    {
        $indentation = $this->getIndentation();

        $code = array();
        foreach ($this->tags as $tag) {
            $code[] = $indentation . ' * ' . trim($tag->generate());
        }

        return implode($this->getLineFeed(), $code);
    }
}
