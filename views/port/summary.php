<?php

/**
 * Egress firewall summary view.
 *
 * @category   apps
 * @package    egress-firewall
 * @subpackage views
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
// Load dependencies
///////////////////////////////////////////////////////////////////////////////

use \clearos\apps\firewall\Firewall as Firewall;
use \clearos\apps\network\Network as Network;

$this->lang->load('egress_firewall');
$this->lang->load('firewall');

///////////////////////////////////////////////////////////////////////////////
// Warnings
///////////////////////////////////////////////////////////////////////////////

if ($panic)
    $this->load->view('firewall/panic');

if ($network_mode == Network::MODE_TRUSTED_STANDALONE)
    $this->load->view('network/firewall_verify');

///////////////////////////////////////////////////////////////////////////////
// Headers
///////////////////////////////////////////////////////////////////////////////

$headers = array(
    lang('firewall_nickname'),
    lang('firewall_service'),
    lang('firewall_protocol'),
    lang('firewall_port')
);

///////////////////////////////////////////////////////////////////////////////
// Anchors 
///////////////////////////////////////////////////////////////////////////////

$anchors = anchor_multi(
    array (
        'egress_firewall/service' => lang('base_add_by') . ': ' . lang('firewall_service'),
        'egress_firewall/port' => lang('base_add_by') . ': ' . lang('firewall_port'),
        'egress_firewall/port_range' => lang('base_add_by') . ': ' . lang('firewall_port_range')
    ),
    lang('base_add')
);

///////////////////////////////////////////////////////////////////////////////
// Ports
///////////////////////////////////////////////////////////////////////////////

foreach ($ports as $rule) {
    $key = $rule['protocol'] . '/' . $rule['port'];
    $state = ($rule['enabled']) ? 'disable' : 'enable';
    $state_anchor = 'anchor_' . $state;

    $item['title'] = $rule['name'];
    $item['current_state'] = (bool)$rule['enabled'];
    $item['action'] = '/app/egress_firewall/ports/delete/' . $key;
    $item['anchors'] = button_set(
        array(
            $state_anchor('/app/egress_firewall/ports/' . $state . '/' . $key, 'high'),
            anchor_delete('/app/egress_firewall/ports/delete/' . $key, 'low')
        )
    );
    $item['details'] = array(
        $rule['name'],
        $rule['service'],
        $rule['protocol'],
        $rule['port'],
    );

    $items[] = $item;
}

///////////////////////////////////////////////////////////////////////////////
// Port ranges
///////////////////////////////////////////////////////////////////////////////

foreach ($ranges as $rule) {
    $key = $rule['protocol'] . '/' . $rule['from'] . '/' . $rule['to'];
    $state = ($rule['enabled']) ? 'disable' : 'enable';
    $state_anchor = 'anchor_' . $state;

    $item['title'] = $rule['name'];
    $item['current_state'] = (bool)$rule['enabled'];
    $item['action'] = '/app/egress_firewall/ports/delete_range/' . $key;
    $item['anchors'] = button_set(
        array(
            $state_anchor('/app/egress_firewall/ports/' . $state . '_range/' . $key, 'high'),
            anchor_delete('/app/egress_firewall/ports/delete_range/' . $key, 'low')
        )
    );
    $item['details'] = array(
        $rule['name'],
        $rule['service'],
        $rule['protocol'],
        $rule['from'] . ':' . $rule['to'],
    );

    $items[] = $item;
}

sort($items);

///////////////////////////////////////////////////////////////////////////////
// Summary table
///////////////////////////////////////////////////////////////////////////////

$options = array (
    'default_rows' => 25,
    'sort-default-col' => 1,
    'row-enable-disable' => TRUE
);

echo summary_table(
    lang('egress_firewall_destination_ports'),
    $anchors,
    $headers,
    $items,
    $options
);
