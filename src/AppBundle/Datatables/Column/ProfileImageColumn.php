<?php

namespace AppBundle\Datatables\Column;

use Sg\DatatablesBundle\Datatable\Column\AbstractColumn;
use Sg\DatatablesBundle\Datatable\Helper;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileImageColumn extends AbstractColumn
{
    /**
     * The relative path.
     * Required option.
     *
     * @var string
     */
    protected $relativePath;

    /**
     * The default width of the placeholder.
     * Default:  null
     *
     * @var string
     */
    protected $holderWidth;

    /**
     * The default height of the placeholder.
     * Default: null
     *
     * @var string
     */
    protected $holderHeight;

    /**
     * The default height of the placeholder.
     * Default: 'assets/global/img/avatar.png'
     *
     * @var string
     */
    protected $defaultImage;


    /**
     * Render single field.
     *
     * @param array $row
     *
     * @return $this
     */
    public function renderSingleField(array &$row)
    {
        $path = Helper::getDataPropertyPath($this->data);

        $value = $this->accessor->getValue($row, $path);
        if(!$this->isFileExists($value)) {
            $value = null;
        }
        $content = $this->renderImageTemplate($value, '-image');

        $this->accessor->setValue($row, $path, $content);

        return $this;
    }

    private function isFileExists($value)
    {
        $path = null;

        if(!empty($value)) {
            return file_exists(WEB_PATH . DIRECTORY_SEPARATOR . $this->getRelativePath() . $value);
        }

        return FALSE;
    }

    /**
     * Render toMany.
     *
     * @param array $row
     *
     * @return $this
     */
    public function renderToMany(array &$row)
    {
        // e.g. images[ ].fileName
        //     => $path = [images]
        //     => $value = [fileName]
        $value = null;
        $path = Helper::getDataPropertyPath($this->data, $value);

        $images = $this->accessor->getValue($row, $path);

        if (count($images) > 0) {
            foreach ($images as $key => $image) {
                $currentPath = $path.'['.$key.']'.$value;
                $content = $this->renderImageTemplate($this->accessor->getValue($row, $currentPath), '-gallery-image');
                $this->accessor->setValue($row, $currentPath, $content);
            }
        } else {
            // create an entry for the placeholder image
            $currentPath = $path.'[0]'.$value;
            $content = $this->renderImageTemplate(null, '-gallery-image');
            $this->accessor->setValue($row, $currentPath, $content);
        }

        return $this;
    }

    /**
     * Get the template for the 'renderCellContent' function.
     *
     * @return string
     */
    public function getCellContentTemplate()
    {
        return '@App/_template/ProfileImageColumn/thumb.html.twig';
    }

    /**
     * Config options.
     *
     * @param OptionsResolver $resolver
     *
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(array('relative_path'));

        $resolver->setDefaults(array(
            'filter' => NULL,
            'holder_width' => '',
            'holder_height' => '',
            'default_image' => 'assets/global/img/avatar.png',
        ));

        $resolver->remove('filter');
        $resolver->remove('orderable');
        $resolver->remove('order_data');
        $resolver->remove('order_sequence');

        // the 'searchable' option is removed, but via getter it returns 'false' for the view
        $resolver->remove('searchable');

        $resolver->setAllowedTypes('relative_path', 'string');
        $resolver->setAllowedTypes('default_image', 'string');
        $resolver->setAllowedTypes('holder_width', 'string');
        $resolver->setAllowedTypes('holder_height', 'string');

        return $this;
    }

    /**
     * Render image template.
     *
     * @param string $data
     * @param string $classSuffix
     *
     * @return mixed|string
     */
    private function renderImageTemplate($data, $classSuffix)
    {
        return $this->twig->render(
            $this->getCellContentTemplate(),
            array(
                'data' => $data,
                'image' => $this,
                'image_class' => 'sg-datatables-'.$this->getDatatableName().$classSuffix,
            )
        );
    }

    /**
     * @return string
     */
    public function getRelativePath()
    {
        return $this->relativePath;
    }

    /**
     * @param string $relativePath
     */
    public function setRelativePath($relativePath)
    {
        $this->relativePath = $relativePath;
    }

    /**
     * @return string
     */
    public function getHolderWidth()
    {
        return $this->holderWidth;
    }

    /**
     * @param string $holderWidth
     */
    public function setHolderWidth($holderWidth)
    {
        $this->holderWidth = $holderWidth;
    }

    /**
     * @return string
     */
    public function getHolderHeight()
    {
        return $this->holderHeight;
    }

    /**
     * @param string $holderHeight
     */
    public function setHolderHeight($holderHeight)
    {
        $this->holderHeight = $holderHeight;
    }

    /**
     * @return string
     */
    public function getDefaultImage()
    {
        return $this->defaultImage;
    }

    /**
     * @param string $defaultImage
     */
    public function setDefaultImage($defaultImage)
    {
        $this->defaultImage = $defaultImage;
    }

    public function getOrderable()
    {
        return FALSE;
    }
}
