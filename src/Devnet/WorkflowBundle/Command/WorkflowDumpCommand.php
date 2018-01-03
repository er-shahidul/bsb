<?php


namespace Devnet\WorkflowBundle\Command;

use Devnet\WorkflowBundle\Core\WorkflowDefinitionRegistry;
use Devnet\WorkflowBundle\Dumper\GraphvizDumper;
use Devnet\WorkflowBundle\Dumper\StateMachineGraphvizDumper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Workflow\Marking;
use Symfony\Bundle\FrameworkBundle\Command\WorkflowDumpCommand as BaseCommand;
/**
 * @author Grégoire Pineau <lyrixx@lyrixx.info>
 */
class WorkflowDumpCommand extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('basb:workflow:dump')
            ->setDefinition(array(
                new InputArgument('name', InputArgument::REQUIRED, 'A workflow name'),
                new InputArgument('marking', InputArgument::IS_ARRAY, 'A marking (a list of places)'),
            ))
            ->setDescription('Dump a workflow')
            ->setHelp(<<<'EOF'
The <info>%command.name%</info> command dumps the graphical representation of a
workflow in DOT format

    %command.full_name% <workflow name> | dot -Tpng > workflow.png

EOF
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $serviceId = $input->getArgument('name');
        $definition = $container->get(WorkflowDefinitionRegistry::class)->getDefinition($serviceId);
        if ($container->has('workflow.'.$serviceId)) {
            $workflow = $container->get('workflow.'.$serviceId);
            $dumper = new GraphvizDumper($definition);
        } elseif ($container->has('state_machine.'.$serviceId)) {
            $workflow = $container->get('state_machine.'.$serviceId);
            $dumper = new StateMachineGraphvizDumper($definition);
        } else {
            throw new \InvalidArgumentException(sprintf('No service found for "workflow.%1$s" nor "state_machine.%1$s".', $serviceId));
        }

        $marking = new Marking();

        foreach ($input->getArgument('marking') as $place) {
            $marking->mark($place);
        }

        $output->writeln($dumper->dump($workflow->getDefinition(), $marking));
    }
}
