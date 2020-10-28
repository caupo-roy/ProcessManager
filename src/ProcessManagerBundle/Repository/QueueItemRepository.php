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

namespace ProcessManagerBundle\Repository;

use CoreShop\Bundle\ResourceBundle\Pimcore\PimcoreRepository;
use Pimcore\Model\Asset;

class QueueItemRepository extends PimcoreRepository implements QueueItemRepositoryInterface
{
    public function findByType(string $type)
    {
        $list = $this->getList();
        $list->setCondition('type = ?', [$type]);

        return $list->load();
    }
}
