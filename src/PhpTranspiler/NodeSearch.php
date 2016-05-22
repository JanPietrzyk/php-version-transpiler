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
                if($node instanceof  $className) {
                    $found = true;
                    break;
                }

            }

            if(!$found) {
                continue;
            }

            yield $node;
        }
    }

    public function findParent(string $class, Node $fromNode) {
        $this->update();

        do {
            $nodeId = spl_object_hash($fromNode);
            $fromNode = $this->parentChain[$nodeId];
            
            if(null === $fromNode) {
                throw new ParentNotFoundException('Could not find the parent "' . $class . '" for node');
            }

        } while(!$fromNode instanceof $class);

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

    private function update() {
        $allNodes = [];
        $parentChain = [];

        foreach($this->recurse($this->tree) as $parent => $node) {
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
        foreach($nodes as $node) {
            if(is_array($node)) {
                yield from $this->recurse($node, $node);
            } else if($node instanceof Node) {
                yield $parent => $node;

                $subNodeNames = $node->getSubNodeNames();

                foreach ($subNodeNames as $subNodeName) {
                    $subNode = $node->{$subNodeName};

                    if(!is_array($subNode)) {
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
    public function replaceNode($sourceNode, $newNode)
    {
        $this->update();

        $hash = spl_object_hash($sourceNode);

        if(!array_key_exists($hash, $this->parentChain)) {
            throw new ParentNotFoundException('The node supplied has no parents');
        }

        $parent = $this->parentChain[$hash];
        
        foreach ($parent->getSubNodeNames() as $subNodeName) {
            $nodes = &$parent->{$subNodeName};
            
            if(!is_array($nodes) ) {
                if($nodes === $sourceNode) {
                    $parent->{$subNodeName} = $newNode;
                    return;
                }

                continue;
            }

            $foundKey = false;
            foreach ($nodes as $key => $node) {
                if($node === $sourceNode) {
                    $foundKey = $key;
                }
            }
            
            if($foundKey) {
                $nodes[$foundKey] = $newNode;
                return;
            }
        }
        
        throw new NodeNotFoundException('Node not found, replce not possible');
    }
}
