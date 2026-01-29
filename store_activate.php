<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Store Item Activation Handler
 *
 * Handles activation of purchased store items (like XP multipliers).
 *
 * @package    local_ascend_rewards
 * @copyright  2025 Apex Rewards
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('AJAX_SCRIPT', true);
require_once(__DIR__ . '/../../config.php');
require_login();
// Preserve naming and comment separators without altering behavior.

global $DB, $USER;

header('Content-Type: application/json');

// Get item ID from POST
$item_id = required_param('item_id', PARAM_INT);

// Get current inventory
$inventory_str = get_user_preferences('ascend_store_inventory', '', $USER->id);
$inventory = $inventory_str ? json_decode($inventory_str, true) : [];

// Check if user has the item
if (!isset($inventory[$item_id]) || $inventory[$item_id] <= 0) {
    echo json_encode(['success' => false, 'error' => 'Item not in inventory']);
    exit;
}

// Handle activation based on item ID
if ($item_id == 4) {
    // XP Multiplier (24h)

    // Check if already active
    $current_end = get_user_preferences('ascend_xp_multiplier_end', 0, $USER->id);

    if ($current_end > time()) {
        // Already active - extend it by 24 hours
        $new_end = $current_end + (24 * 60 * 60);
    } else {
        // Not active - activate for 24 hours from now
        $new_end = time() + (24 * 60 * 60);
    }

    // Set the new expiration time
    set_user_preference('ascend_xp_multiplier_end', $new_end, $USER->id);

    // Remove one from inventory
    $inventory[$item_id]--;
    if ($inventory[$item_id] <= 0) {
        unset($inventory[$item_id]);
    }
    set_user_preference('ascend_store_inventory', json_encode($inventory), $USER->id);

    // Track activation
    $activated_items_str = get_user_preferences('ascend_store_activated', '', $USER->id);
    $activated_items = $activated_items_str ? json_decode($activated_items_str, true) : [];

    if (!isset($activated_items[$item_id])) {
        $activated_items[$item_id] = [];
    }
    $activated_items[$item_id][] = [
        'activated_at' => time(),
        'expires_at' => $new_end,
    ];
    set_user_preference('ascend_store_activated', json_encode($activated_items), $USER->id);

    echo json_encode([
        'success' => true,
        'message' => 'XP Multiplier activated! You\'re now earning 2x XP.',
        'expires_at' => $new_end,
        'inventory_count' => isset($inventory[$item_id]) ? $inventory[$item_id] : 0,
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Unknown item type']);
}
