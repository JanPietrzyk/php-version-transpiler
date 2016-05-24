<?php


namespace JanPiet\PhpTranspiler;

use PhpParser\Node;

class NodeSearch
{
    /**
     * @var \PhpParser\Node[]
     */
    private $tree;

    /**
     * @var Node[]
     */
    private $allNodes;

    /**
     * @var Node[]
     */
    private $parentChain;

    /**
     * NodeSearch constructor.
     * @param Node[] $nodes
     */
    public function __construct(array $nodes)
    {
        $this->tree = $nodes;
    }

    /**
     * @param \string[] ...$classNames
     * @return \Traversable
     */
    public function eachType(string ...$classNames): \Traversable
    {
        $this->update();

        foreach ($this->allNodes as $node) {
            $found = false;
            foreach ($classNames as $className) {
                if ($node instanceof  $className) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                continue;
            }

            yield $node;
        }
    }

    public function findParent(string $class, Node $fromNode)
    {
        $this->update();

        do {
            $nodeId = spl_object_hash($fromNode);
            $fromNode = $this->parentChain[$nodeId];
            
            if (null === $fromNode) {
                throw new ParentNotFoundException('Could not find the parent "' . $class . '" for node');
            }
        } while (!$fromNode instanceof $class);

        return $fromNode;
    }

    /**
     * @param Node $newClass
     */
    public function appendToRoot(Node $newClass)
    {
        $this->tree[] = $newClass;
    }

    /**
     * @return \PhpParser\Node[]
     */
    public function getTree()
    {
        return $this->tree;
    }

    private function update()
    {
        $allNodes = [];
        $parentChain = [];

        foreach ($this->recurse($this->tree) as $parent => $node) {
            $nodeId = spl_object_hash($node);
            $allNodes[$nodeId] = $node;
            $parentChain[$nodeId] = $parent;
        }

        $this->allNodes = $allNodes;
        $this->parentChain = $parentChain;
    }

    /**
     * @param Node[] $nodes
     * @return \Traversable
     */
    private function recurse(array $nodes, Node $parent = null): \Traversable
    {
        foreach ($nodes as $node) {
            if (is_array($node)) {
                yield from $this->recurse($node, $node);
            } elseif ($node instanceof Node) {
                yield $parent => $node;

                $subNodeNames = $node->getSubNodeNames();

                foreach ($subNodeNames as $subNodeName) {
                    $subNode = $node->{$subNodeName};

                    if (!is_array($subNode)) {
                        $subNode = [$subNode];
                    }

                    yield from $this->recurse($subNode, $node);
                }
            }
        }
    }

    /**
     * @param $sourceNode
     * @param $newNode
     */
    public function replaceNode(Node $sourceNode, Node $newNode)
    {
        $this->update();

        $hash = spl_object_hash($sourceNode);

        $parents = $this->determineReplaceParents($hash);

        foreach ($parents as $key => $parent) {
            if ($parent === $sourceNode) {
                $parent[$key] = $newNode;
                return;
            }

            if($this->checkReplaceSubNodes($parent, $sourceNode, $newNode)) {
                return;
            }
        }
        
        throw new NodeNotFoundException('Node not found, replace not possible');
    }

    /**
     * @param Node $parent
     * @param Node $sourceNode
     * @param Node $newNode
     * @return bool
     */
    private function checkReplaceSubNodes(Node $parent, Node $sourceNode, Node $newNode): bool
    {
        foreach ($parent->getSubNodeNames() as $subNodeName) {
            $nodes = &$parent->{$subNodeName};

            if (!is_array($nodes)) {
                if ($nodes === $sourceNode) {
                    $parent->{$subNodeName} = $newNode;
                    return true;
                }

                continue;
            }

            $foundKey = false;
            foreach ($nodes as $key => $node) {
                if ($node === $sourceNode) {
                    $foundKey = $key;
                }
            }

            if (false !== $foundKey) {
                $nodes[$foundKey] = $newNode;
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $hash
     * @return \PhpParser\Node[]
     */
    private function determineReplaceParents(string $hash): array
    {
        if (array_key_exists($hash, $this->parentChain)) {
            $parents = [$this->parentChain[$hash]];
            return $parents;
        }

        $parents = $this->tree;
        return $parents;
    }
}
