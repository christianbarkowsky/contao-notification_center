<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  terminal42 gmbh 2013
 * @license    LGPL
 */

namespace NotificationCenter;

class tl_form extends \Backend
{
    /**
     * Get notification choices
     * @param   \DataContainer
     * @return  array
     */
    public function getNotificationChoices(\DataContainer $dc)
    {
        $arrChoices = array();
        $objNotifications = \Database::getInstance()->execute("SELECT id,title FROM tl_nc_notification WHERE type='core_form' ORDER BY title");

        while ($objNotifications->next()) {
            $arrChoices[$objNotifications->id] = $objNotifications->title;
        }

        return $arrChoices;
    }

    /**
     * Send the form notification
     * @param array
     * @param array
     */
    public function sendFormNotification($arrData, $arrForm)
    {
        if (!$arrForm['nc_notification']) {
            return;
        }

        // Prepare tokens
        foreach ($arrData as $k => $v) {
            $arrData['form_' . $k] = $v;
            unset($arrData[$k]);
        }

        // Administrator e-mail
        $arrData['admin_email'] = $GLOBALS['TL_ADMIN_EMAIL'];

        $objNotification = Model\Notification::findByPk($arrForm['nc_notification']);

        if ($objNotification !== null) {
            $objNotification->send($arrData);
        }
    }
}