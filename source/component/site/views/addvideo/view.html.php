<?php
/**
 * @version 0.9 $Id: view.html.php 507 2008-01-03 15:48:34Z schlu $
 * @package Joomla
 * @subpackage jomtube
 * @copyright (C) 2008 - 2010 Tran Le Manh
 * @license GNU/GPL, see LICENSE.php
 * EventList is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.

 * jomtube is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with EventList; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * View class for the jomtube Video screen
 *
 * @package Joomla
 * @subpackage EventList
 * @since 0.9
 */
class jomtubeViewAddvideo extends JView {

    function display($tpl = null)
    {
        global $mainframe;

        //check permission
        jomtube::checkPermission("acl_addvideo");

        $c = jomtube_configs::get_instance();
        $this->assignRef('c'      	, $c);

        parent::display($tpl);
    }
}
?>