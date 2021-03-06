<?php

/**
 * Firewall egress port controller.
 *
 * @category   apps
 * @package    egress-firewall
 * @subpackage controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
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
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/egress_firewall/
 */

class Port extends ClearOS_Controller
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

        $this->form_validation->set_policy('port_nickname', 'egress_firewall/Egress', 'validate_name', TRUE);
        $this->form_validation->set_policy('port_protocol', 'egress_firewall/Egress', 'validate_protocol', TRUE);
        $this->form_validation->set_policy('port', 'egress_firewall/Egress', 'validate_port', TRUE);

        // Handle form submit
        //-------------------

        if ($this->form_validation->run()) {
            try {
                $this->egress->add_exception_port(
                    $this->input->post('port_nickname'),
                    $this->input->post('port_protocol'),
                    $this->input->post('port')
                );

                $this->page->set_status_added();
                redirect('/egress_firewall');
            } catch (Exception $e) {
                $this->page->set_message(clearos_exception_message($e));
                redirect('/egress_firewall/port');
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

        $this->page->view_form('egress_firewall/port/port', $data, lang('base_add'));
    }
}
