<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace PrestaShop\PrestaShop\Adapter\Backup;

use PrestaShop\PrestaShop\Adapter\Entity\PrestaShopBackup;
use PrestaShop\PrestaShop\Core\Backup\BackupInterface;
use PrestaShop\PrestaShop\Core\Backup\Exception\BackupException;
use PrestaShop\PrestaShop\Core\Backup\Exception\DirectoryIsNotWritableException;
use PrestaShop\PrestaShop\Core\Backup\Manager\BackupCreatorInterface;

/**
 * Class DatabaseBackupCreator is responsible for creating database backups.
 *
 * @internal
 */
final class DatabaseBackupCreator implements BackupCreatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createBackup(): BackupInterface
    {
        ini_set('max_execution_time', '0');

        if (!is_writable(PrestaShopBackup::getBackupPath())) {
            throw new DirectoryIsNotWritableException('To create backup, its directory must be writable');
        }

        $legacyBackup = new PrestaShopBackup();
        if (!$legacyBackup->add()) {
            throw new BackupException('Failed to create backup');
        }

        $backupFilePathParts = explode(DIRECTORY_SEPARATOR, $legacyBackup->id);

        return new Backup(end($backupFilePathParts));
    }
}
