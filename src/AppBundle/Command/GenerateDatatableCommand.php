<?php

namespace AppBundle\Command;

use Sensio\Bundle\GeneratorBundle\Command\GenerateDoctrineCommand;
use Sg\DatatablesBundle\Command\CreateDatatableCommand;
use Sg\DatatablesBundle\Command\Fields;
use Sg\DatatablesBundle\Generator\DatatableGenerator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Sensio\Bundle\GeneratorBundle\Command\Validators;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use RuntimeException;
use Exception;

/**
 * Class GenerateDatatableCommand
 *
 * @package Sg\DatatablesBundle\Command
 */
class GenerateDatatableCommand extends CreateDatatableCommand
{
    /**
     * @var DatatableGenerator
     */
    private $generator;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:datatable:generate')
            ->setDescription('Generates a new Datatable based on a Doctrine entity.')
            ->addArgument('entity', InputArgument::REQUIRED, 'The entity class name (shortcut notation).')
            ->addOption('fields', 'f', InputOption::VALUE_OPTIONAL, 'The fields to create columns in the new Datatable.')
            ->addOption('overwrite', 'o', InputOption::VALUE_NONE, 'Allow to overwrite an existing Datatable.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // get entity argument
        $entity = Validators::validateEntityName($input->getArgument('entity'));
        list($bundle, $entity) = $this->parseShortcutNotation($entity);

        // get entity's metadata
        $entityClass = $this->getContainer()->get('doctrine')->getAliasNamespace($bundle)."\\".$entity;
        $metadata = $this->getEntityMetadata($entityClass);

        // get fields option
        $fieldsOption = $input->getOption('fields');
        null === $fieldsOption ? $fields = $this->getFieldsFromMetadata2($metadata[0]) : $fields = $this->parseFields2($fieldsOption);

        // get overwrite option
        $overwrite = $input->getOption('overwrite');

        // get the entity's primary key
        $id = $this->getPrimaryKey($entityClass);

        // get the bundle in which to create the Datatable
        $bundle = $this->getContainer()->get('kernel')->getBundle($bundle);
        $bundleName = $bundle->getName();

        /** @var DatatableGenerator $generator */
        $generator = $this->getGenerator($bundle);
        $generator->generate($bundle, $entity, $fields, $id[0], $overwrite);

        $parts = explode("\\", $entity);
        $className = array_pop($parts) . 'Datatable';

        $output->writeln(
            '
<comment>Example Controller for List Page</comment>
/** @var DatatableInterface|Response $datatable */
$datatable = $this->prepareDatatable('.$className.'::class, $request->isXmlHttpRequest(), function ($qb) {
    /** @var QueryBuilder $qb */
    /** Default Filter */
    //$qb->andWhere("budget.office = :office");
    //$qb->setParameter(\'office\', $this->getUser()->getOffice());
});

if ($request->isXmlHttpRequest()) {
    return $datatable;
}

return $this->render(\'--views-path-here--\', array(
    \'datatable\' => $datatable,
));

<comment>Example Twig for List Page</comment>
{{ sg_datatable_render_html(datatable) }}
{{ sg_datatable_render_js(datatable) }}'
);

        $output->writeln(
            sprintf(
                'The new Datatable %s.php has been created under %s.',
                $generator->getClassName(),
                $generator->getClassPath()
            )
        );
    }

    /**
     * Get Id from metadata.
     *
     * @param array $metadata
     *
     * @return mixed
     * @throws Exception
     */
    private function getIdentifierFromMetadata2(array $metadata)
    {
        if (count($metadata[0]->identifier) > 1) {
            throw new Exception('CreateDatatableCommand::getIdentifierFromMetadata(): The Datatable generator does not support entities with multiple primary keys.');
        }

        return $metadata[0]->identifier;
    }

    /**
     * Parse fields.
     *
     * @param string $input
     *
     * @return array
     */
    private static function parseFields2($input)
    {
        $fields = array();

        foreach (explode(' ', $input) as $value) {
            $elements = explode(':', $value);

            $row = array();
            $row['property'] = $elements[0];
            $row['column_type'] = 'Column::class';
            $row['data'] = null;
            $row['title'] = ucwords(str_replace('.', ' ', $elements[0]));

            if (isset($elements[1])) {
                switch ($elements[1]) {
                    case 'datetime':
                        $row['column_type'] = 'DateTimeColumn::class';
                        break;
                    case 'boolean':
                        $row['column_type'] = 'BooleanColumn::class';
                        break;
                }
            }

            $fields[] = $row;
        }

        return $fields;
    }

    /**
     * Returns an array of fields. Fields can be both column fields and
     * association fields.
     *
     * @param ClassMetadataInfo $metadata
     *
     * @return array $fields
     */
    private function getFieldsFromMetadata2(ClassMetadataInfo $metadata)
    {
        $fields = array();

        foreach ($metadata->fieldMappings as $field) {
            $row = array();
            $row['property'] = $field['fieldName'];

            switch ($field['type']) {
                case 'datetime':
                    $row['column_type'] = 'DateTimeColumn::class';
                    break;
                case 'boolean':
                    $row['column_type'] = 'BooleanColumn::class';
                    break;
                default:
                    $row['column_type'] = 'Column::class';
            }

            $row['title'] = ucwords($field['fieldName']);
            $row['data'] = null;
            $fields[] = $row;
        }

        foreach ($metadata->associationMappings as $relation) {
            $targetClass = $relation['targetEntity'];
            $targetMetadata = $this->getEntityMetadata($targetClass);

            foreach ($targetMetadata[0]->fieldMappings as $field) {
                $row = array();
                $row['property'] = $relation['fieldName'].'.'.$field['fieldName'];
                $row['column_type'] = 'Column::class';
                $row['title'] = ucwords(str_replace('.', ' ', $row['property']));
                if ($relation['type'] === ClassMetadataInfo::ONE_TO_MANY || $relation['type'] === ClassMetadataInfo::MANY_TO_MANY) {
                    $row['data'] = $relation['fieldName'].'[, ].'.$field['fieldName'];
                } else {
                    $row['data'] = null;
                }
                $fields[] = $row;
            }
        }

        return $fields;
    }

    protected function getSkeletonDirs(BundleInterface $bundle = null)
    {
        $skeletonDirs = array();

        if (isset($bundle) && is_dir($dir = $bundle->getPath().'/Resources/SgDatatablesBundle/views/Skeleton')) {
            $skeletonDirs[] = $dir;
        }

        if (is_dir($dir = $this->getContainer()->get('kernel')->getRootdir().'/Resources/SgDatatablesBundle/views/Skeleton')) {
            $skeletonDirs[] = $dir;
        }

        return $skeletonDirs;
    }

    private function getPrimaryKey($entityClass)
    {
        return $this->getContainer()->get('doctrine.orm.default_entity_manager')->getClassMetadata($entityClass)->getSingleIdentifierFieldName();
    }
}
