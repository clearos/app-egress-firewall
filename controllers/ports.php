<?php

/**
 * Firewall egress ports controller.
 *
 * @category   apps
 * @package    egress-firewall
 * @subpackage controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011-2015 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/egress_firewall/
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

// Classes
//--------

use \clearos\apps\egress_firewall\Egress as Egress;
use \clearos\apps\network\Network as Network;

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Firewall egress ports controller.
 *
 * @category   apps
 * @package    egress-firewall
 * @subpackage controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011-2015 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/egress_firewall/
 */

class Ports extends ClearOS_Controller
{
    /**
     * Egress port overview.
     *
     * @return view
     */

    function index()
    {
        // Load dependencies
        //------------------

        $this->load->library('egress_firewall/Egress');
        $this->load->library('network/Network');
        $this->lang->load('egress_firewall');

        // Load the view data 
        //------------------- 
        try {
            $data['ports'] = $this->egress->get_exception_ports();
            $data['ranges'] = $this->egress->get_exception_port_ranges();
            $data['network_mode'] = $this->network->get_mode();
            $data['panic'] = $this->egress->is_panic();
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }

        // Load views
        //-----------

        $this->page->view_form('egress_firewall/port/summary', $data, lang('egress_firewall_destination_ports'));
    }

    /**
     * Delete port rule.
     *
     * @param string  $protocol protocol
     * @param integer $port     port
     *
     * @return view
     */

    function delete($protocol, $port)
    {
        $confirm_uri = '/app/egress_firewall/ports/destroy/' . $protocol . '/' . $port;
        $cancel_uri = '/app/egress_firewall';
        $items = array($protocol . ' ' . $port);

        $this->page->view_confirm_delete($confirm_uri, $cancel_uri, $items);
    }

    /**
     * Delete port range rule.
     *
     * @param string  $protocol protocol
     * @param integer $from     from port
     * @param integer $to       to port
     *
     * @return view
     */

    function delete_range($protocol, $from, $to)
    {
        $confirm_uri = '/app/egress_firewall/ports/destroy_range/' . $protocol . '/' . $from . '/' . $to;
        $cancel_uri = '/app/egress_firewall';
        $items = array($protocol . ' ' . $from . ':' . $to);

        $this->page->view_confirm_delete($confirm_uri, $cancel_uri, $items);
    }

    /**
     * Destroys port rule.
     *
     * @param string  $protocol protocol
     * @param integer $port     port
     *
     * @return view
     */

    function destroy($protocol, $port)
    {
        // Load libraries
        //---------------

        $this->load->library('egress_firewall/Egress');

        // Handle form submit
        //-------------------

        try {
            $this->egress->delete_exception_port($protocol, $port);

            $this->page->set_status_deleted();
            redirect('/egress_firewall');
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
    }

    /**
     * Destroys port range rule.
     *
     * @param string  $protocol protocol
     * @param integer $from     from port
     * @param integer $to       to port
     *
     * @return view
     */

    function destroy_range($protocol, $from, $to)
    {
        // Load libraries
        //---------------

        $this->load->library('egress_firewall/Egress');

        // Handle form submit
        //-------------------

        try {
            $this->egress->delete_exception_port_range($protocol, $from, $to);

            $this->page->set_status_deleted();
            redirect('/egress_firewall');
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
    }

    /**
     * Disables port rule.
     *
     * @param string  $protocol protocol
     * @param integer $port     port
     *
     * @return view
     */

    function disable($protocol, $port)
    {
        try {
            $this->load->library('egress_firewall/Egress');

            $this->egress->toggle_enable_exception_port(FALSE, $protocol, $port);

            $this->page->set_status_disabled();
            redirect('/egress_firewall');
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
    }

    /**
     * Disables range rule.
     *
     * @param string  $protocol protocol
     * @param integer $from     from port
     * @param integer $to       to port
     *
     * @return view
     */

    function disable_range($protocol, $from, $to)
    {
        try {
            $this->load->library('egress_firewall/Egress');

            $this->egress->toggle_enable_exception_port_range(FALSE, $protocol, $from, $to);

            $this->page->set_status_disabled();
            redirect('/egress_firewall');
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
    }

    /**
     * Enables port rule.
     *
     * @param string  $protocol protocol
     * @param integer $port     port
     *
     * @return view
     */

    function enable($protocol, $port)
    {
        try {
            $this->load->library('egress_firewall/Egress');

            $this->egress->toggle_enable_exception_port(TRUE, $protocol, $port);

            $this->page->set_status_enabled();
            redirect('/egress_firewall');
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
    }

    /**
     * Enables range rule.
     *
     * @param string  $protocol protocol
     * @param integer $from     from port
     * @param integer $to       to port
     *
     * @return view
     */

    function enable_range($protocol, $from, $to)
    {
        try {
            $this->load->library('egress_firewall/Egress');

            $this->egress->toggle_enable_exception_port_range(TRUE, $protocol, $from, $to);

            $this->page->set_status_enabled();
            redirect('/egress_firewall');
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
    }
}
