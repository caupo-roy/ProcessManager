<?php
/**
 * Process Manager.
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2020 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://github.com/dpfaffenbauer/ProcessManager/blob/master/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace ProcessManagerBundle;

use CoreShop\Bundle\ResourceBundle\AbstractResourceBundle;
use CoreShop\Bundle\ResourceBundle\ComposerPackageBundleInterface;
use CoreShop\Bundle\ResourceBundle\CoreShopResourceBundle;
use Pimcore\Extension\Bundle\PimcoreBundleInterface;
use Pimcore\Extension\Bundle\Traits\PackageVersionTrait;
use ProcessManagerBundle\DependencyInjection\Compiler\MonologHandlerPass;
use ProcessManagerBundle\DependencyInjection\Compiler\ProcessHandlerFactoryTypeRegistryCompilerPass;
use ProcessManagerBundle\DependencyInjection\Compiler\ProcessReportTypeRegistryCompilerPass;
use ProcessManagerBundle\DependencyInjection\Compiler\ProcessStartupFormRegistryCompilerPass;
use ProcessManagerBundle\DependencyInjection\Compiler\ProcessTypeRegistryCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ProcessManagerBundle extends AbstractResourceBundle implements PimcoreBundleInterface, ComposerPackageBundleInterface
{
    use PackageVersionTrait;

    const STATUS_QUEUED = 'queued';
    const STATUS_STARTING = 'starting';
    const STATUS_RUNNING = 'running';
    const STATUS_STOPPED = 'stopped';
    const STATUS_STOPPING = 'stopping';
    const STATUS_COMPLETED = 'completed';
    const STATUS_COMPLETED_WITH_EXCEPTIONS = 'completed_with_exceptions';
    const STATUS_FAILED = 'failed';

    const ENV_QUEUE_ITEM_ID = 'PIMCORE_PROCESS_MANAGER_BUNDLE_QUEUE_ITEM_ID';

    public function getPackageName()
    {
        return 'dpfaffenbauer/process-manager';
    }

    public function getSupportedDrivers()
    {
        return [
            CoreShopResourceBundle::DRIVER_PIMCORE,
        ];
    }

    public function build(ContainerBuilder $builder)
    {
        parent::build($builder);

        $builder->addCompilerPass(new ProcessTypeRegistryCompilerPass());
        $builder->addCompilerPass(new ProcessReportTypeRegistryCompilerPass());
        $builder->addCompilerPass(new ProcessHandlerFactoryTypeRegistryCompilerPass());
        $builder->addCompilerPass(new MonologHandlerPass());
        $builder->addCompilerPass(new ProcessStartupFormRegistryCompilerPass());
    }

    public function getNiceName()
    {
        return 'Process Manager';
    }

    public function getDescription()
    {
        return 'Process Manager helps you to see statuses for long running Processes';
    }

    protected function getComposerPackageName(): string
    {
        return 'dpfaffenbauer/process-manager';
    }

    public function getInstaller()
    {
        return $this->container->get(Installer::class);
    }

    public function getAdminIframePath()
    {
        return null;
    }

    public function getJsPaths()
    {
        return [];
    }

    public function getCssPaths()
    {
        return [];
    }

    public function getEditmodeJsPaths()
    {
        return [];
    }

    public function getEditmodeCssPaths()
    {
        return [];
    }
}
