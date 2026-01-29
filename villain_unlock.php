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
 * Villain unlock handler for Ascend Rewards.
 *
 * Processes villain unlocks using tokens or coins.
 *
 * @package   local_ascend_rewards
 * @copyright 2025 Ascend Rewards
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_login();
require_once(__DIR__ . '/classes/performance_cache.php');
// Preserve legacy naming while we stabilize submission checks.

header('Content-Type: application/json');

$villain_id = required_param('villain_id', PARAM_INT);
$unlock_type = required_param('unlock_type', PARAM_TEXT); // 'token' or 'coin'

try {
    // Validate user is logged in
    if (!$USER->id) {
        throw new Exception('User not logged in');
    }

    // Validate unlock type
    if (!in_array($unlock_type, ['token', 'coin'])) {
        throw new Exception('Invalid unlock type');
    }

    // DEMO VERSION: Villain catalog with only Dryad (elf's companion) and Mole (imp's companion)
    $villain_catalog = [
        // Level 1: Emberveil Forest
        300 => ['name' => 'Dryad', 'pet_id' => 100, 'avatar' => 'elf.png', 'level' => 1, 'price' => 500],
        302 => ['name' => 'Mole', 'pet_id' => 102, 'avatar' => 'imp.png', 'level' => 1, 'price' => 500],
    ];

    if (!isset($villain_catalog[$villain_id])) {
        throw new Exception('Invalid villain ID');
    }

    $villain_data = $villain_catalog[$villain_id];

    // Check if villain is already owned
    $existing = $DB->get_record('local_ascend_avatar_unlocks', [
        'userid' => $USER->id,
        'villain_id' => $villain_id,
    ]);

    if ($existing) {
        throw new Exception('Villain already unlocked');
    }

    // Check if user has unlocked the required pet
    $pet_unlocked = $DB->get_record_sql(
        "SELECT * FROM {local_ascend_avatar_unlocks} 
         WHERE userid = :uid AND pet_id = :petid AND villain_id IS NULL",
        ['uid' => $USER->id, 'petid' => $villain_data['pet_id']]
    );

    if (!$pet_unlocked) {
        throw new Exception('Pet not adopted. You must adopt the matching pet first.');
    }

    $new_balance = null;

    // Start transaction
    $transaction = $DB->start_delegated_transaction();

    try {
        if ($unlock_type === 'token') {
            // Token unlock
            $token_record = $DB->get_record('local_ascend_level_tokens', ['userid' => $USER->id]);
            if (!$token_record) {
                throw new Exception('No token record found');
            }

            $tokens_available = $token_record->tokens_available - $token_record->tokens_used;
            if ($tokens_available <= 0) {
                throw new Exception('No tokens available');
            }

            // Increment tokens_used
            $token_record->tokens_used++;
            $token_record->timemodified = time();
            $DB->update_record('local_ascend_level_tokens', $token_record);
        } else {
            // Coin unlock
            $price = $villain_data['price'];

            // Get user's total coins
            $coins_records = $DB->get_records('local_ascend_rewards_coins', ['userid' => $USER->id]);
            $total_coins = 0;
            foreach ($coins_records as $record) {
                $total_coins += $record->coins;
            }

            if ($total_coins < $price) {
                throw new Exception('Insufficient coins. Need ' . $price . ', have ' . $total_coins);
            }

            // Deduct coins by inserting a negative record (preserves XP/level)
            $deduction_record = new stdClass();
            $deduction_record->userid = $USER->id;
            $deduction_record->coins = -$price;
            $deduction_record->badgeid = 0; // 0 indicates spending, not badge earning
            $deduction_record->timecreated = time();
            $DB->insert_record('local_ascend_rewards_coins', $deduction_record);

            // Clear performance caches
            \local_ascend_rewards\performance_cache::clear_user_cache($USER->id);
            \local_ascend_rewards\performance_cache::clear_leaderboard_cache();

            $new_balance = $total_coins - $price;
        }

        // Insert villain unlock record
        $unlock_record = new stdClass();
        $unlock_record->userid = $USER->id;
        $unlock_record->avatar_name = $villain_data['avatar'];
        $unlock_record->avatar_level = $villain_data['level'];
        $unlock_record->pet_id = $villain_data['pet_id'];
        $unlock_record->villain_id = $villain_id;
        $unlock_record->unlock_type = $unlock_type;
        $unlock_record->timecreated = time();

        $DB->insert_record('local_ascend_avatar_unlocks', $unlock_record);

        // Commit transaction
        $transaction->allow_commit();

        $response = [
            'success' => true,
            'message' => 'Villain unlocked successfully!',
        ];

        if ($unlock_type === 'coin') {
            $response['new_balance'] = $new_balance;
        }

        echo json_encode($response);
    } catch (Exception $e) {
        $transaction->rollback($e);
        throw $e;
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
    ]);
}
