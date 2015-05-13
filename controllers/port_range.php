<?php

/**
 * Firewall egress port controller.
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
 * Firewall egress port controller.
 *
 * @category   apps
 * @package    egress-firewall
 * @subpackage controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011-2015 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/egress_firewall/
 */

class Port_Range extends ClearOS_Controller
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

        // Set validation rules
        //---------------------

        $this->form_validation->set_policy('range_nickname', 'egress_firewall/Egress', 'validate_name', TRUE);
        $this->form_validation->set_policy('range_protocol', 'egress_firewall/Egress', 'validate_protocol', TRUE);
        $this->form_validation->set_policy('range_from', 'egress_firewall/Egress', 'validate_port', TRUE);
        $this->form_validation->set_policy('range_to', 'egress_firewall/Egress', 'validate_port', TRUE);

        // Handle form submit
        //-------------------

        if ($this->form_validation->run()) {
            try {
                $this->egress->add_exception_port_range(
                    $this->input->post('range_nickname'),
                    $this->input->post('range_protocol'),
                    $this->input->post('range_from'),
                    $this->input->post('range_to')
                );

                $this->page->set_status_added();
                redirect('/egress_firewall');
            } catch (Exception $e) {
                $this->page->set_message(clearos_exception_message($e));
                redirect('/egress_firewall/port_range');
            }
        }

        $data['protocols'] = $this->egress->get_protocols();
        // Only want TCP and UDP
        foreach ($data['protocols'] as $key => $protocol) {
            if ($key != Egress::PROTOCOL_TCP && $key != Egress::PROTOCOL_UDP)
                unset($data['protocols'][$key]);
        }
            
        // Load the views
        //---------------

        $this->page->view_form('egress_firewall/port/port_range', $data, lang('base_add'));
    }
}
