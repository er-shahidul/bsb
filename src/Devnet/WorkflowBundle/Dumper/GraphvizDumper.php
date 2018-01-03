<?php


namespace Devnet\WorkflowBundle\Dumper;

use Devnet\WorkflowBundle\Core\WorkflowDefinitionInterface;
use Symfony\Component\Workflow\Dumper\GraphvizDumper as BaseDumper;

/**
 * GraphvizDumper dumps a workflow as a graphviz file.
 *
 * You can convert the generated dot file with the dot utility (http://www.graphviz.org/):
 *
 *   dot -Tpng workflow.dot > workflow.png
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Grégoire Pineau <lyrixx@lyrixx.info>
 */
class GraphvizDumper extends  BaseDumper
{
    /**
     * @var WorkflowDefinitionInterface
     */
    private $def;

    public function __construct(WorkflowDefinitionInterface $definition)
    {
        $this->def = $definition;
    }

    /**
     * @internal
     */
    protected function addPlaces(array $places)
    {
        $code = '';

        foreach ($places as $id => $place) {
            $code .= sprintf("  place_%s [label=\"%s\", shape=circle%s];\n", $this->dotize($id), $this->humanize($id), $this->addAttributes($place['attributes']));
        }

        return $code;
    }

    /**
     * @internal
     */
    protected function addTransitions(array $transitions)
    {
        $code = '';

        foreach ($transitions as $place) {
            $name = $this->def->getTransitionConfig($place['name'], 'label');
            $code .= sprintf("  transition_%s [label=\"%s\", shape=box%s];\n", $this->dotize($place['name']), $name, $this->addAttributes($place['attributes']));
        }

        return $code;
    }

    private function addAttributes(array $attributes)
    {
        $code = array();

        foreach ($attributes as $k => $v) {
            $code[] = sprintf('%s="%s"', $k, $v);
        }

        return $code ? ', '.implode(', ', $code) : '';
    }

    protected function humanize($text)
    {
        return ucfirst(strtolower(trim(preg_replace(array('/([A-Z])/', '/[_\s]+/'), array('_$1', ' '), $text))));
    }
}
