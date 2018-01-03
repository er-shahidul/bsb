<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            new FOS\UserBundle\FOSUserBundle(),
            new Xiidea\EasyAuditBundle\XiideaEasyAuditBundle(),
            new Fp\JsFormValidatorBundle\FpJsFormValidatorBundle(),
            new Sg\DatatablesBundle\SgDatatablesBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),

            new AppBundle\AppBundle(),
            new UserBundle\UserBundle(),
            new BudgetBundle\BudgetBundle(),
            new Devnet\PolicyManagerBundle\DevnetPolicyManagerBundle(),
            new Devnet\WorkflowBundle\DevnetWorkflowBundle(),
            new NotificationBundle\NotificationBundle(),
            new AccountBundle\AccountBundle(),
            new PersonnelBundle\PersonnelBundle(),
            new BoardMeetingBundle\BoardMeetingBundle(),
            new WelfareBundle\WelfareBundle(),
            new MovementBundle\MovementBundle(),
            new FuneralBundle\FuneralBundle(),
            new MedicalBundle\MedicalBundle(),
            new LeaveBundle\LeaveBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function boot()
    {
        parent::boot();
        $this->defineMpdfTempDir();
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }

    public function defineMpdfTempDir()
    {
        if (!defined('_MPDF_TTFONTDATAPATH')) {
            define('_MPDF_TTFONTDATAPATH', $this->getCacheDir() . '/');
        }
    }
}
