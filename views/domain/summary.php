<?php

/**
 * Egress firewall by domain summary view.
 *
 * @category   ClearOS
 * @package    Egress_Firewall
 * @subpackage Views
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

$this->lang->load('egress_firewall');
$this->lang->load('firewall');

///////////////////////////////////////////////////////////////////////////////
// Headers
///////////////////////////////////////////////////////////////////////////////

$headers = array(
    lang('firewall_nickname'),
    lang('firewall_host'),
);

///////////////////////////////////////////////////////////////////////////////
// Anchors 
///////////////////////////////////////////////////////////////////////////////

$anchors = array(anchor_add('/app/egress_firewall/domain/add'));

///////////////////////////////////////////////////////////////////////////////
// Items
///////////////////////////////////////////////////////////////////////////////

foreach ($hosts as $rule) {
    $key = $rule['host'];
    $state = ($rule['enabled']) ? 'disable' : 'enable';
    $state_anchor = 'anchor_' . $state;

    $item['title'] = $rule['name'];
    $item['action'] = '/app/egress_firewall/domain/delete/' . $key;
    $item['anchors'] = button_set(
        array(
            $state_anchor('/app/egress_firewall/domain/' . $state . '/' . $key, 'high'),
            anchor_delete('/app/egress_firewall/domain/delete/' . $key, 'low')
        )
    );
    $item['details'] = array(
        $rule['name'],
        $rule['host'],
    );

    $items[] = $item;
}

sort($items);

///////////////////////////////////////////////////////////////////////////////
// Summary table
///////////////////////////////////////////////////////////////////////////////

$options['default_rows'] = 25;

echo summary_table(
    lang('egress_firewall_destination_domains'),
    $anchors,
    $headers,
    $items,
    $options
);
