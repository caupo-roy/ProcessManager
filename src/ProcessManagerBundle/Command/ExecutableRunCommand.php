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
 * @copyright  Copyright (c) 2015-2017 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://github.com/dpfaffenbauer/ProcessManager/blob/master/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace ProcessManagerBundle\Command;

use CoreShop\Component\Registry\ServiceRegistry;

use Pimcore\Console\AbstractCommand;

use ProcessManagerBundle\Model\Executable;
use ProcessManagerBundle\Model\ExecutableInterface;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class ExecutableRunCommand extends AbstractCommand
{
    private $registry;

    /**
     * ExecutableRunCommand constructor.
     * @param ServiceRegistry $registry
     */
    public function __construct(ServiceRegistry $registry)
    {
        $this->registry = $registry;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('process-manager:executable:run')
            ->setDescription('Run a process manager executable.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> runs a predefined Process Manager executable.
EOT
            )
            ->addOption(
                'executable', 'x',
                InputOption::VALUE_REQUIRED,
                'Executable ID'
            );
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Executable $executable */
        $executable = Executable::getById($input->getOption('executable'));
        if (!$executable instanceof ExecutableInterface) {
            throw new \Exception('Executable not found');
        }

        $now =  time();
        $executable->setLastrun($now);
        $executable->save();
        $this->registry->get($executable->getType())->run($executable);
    }
}
