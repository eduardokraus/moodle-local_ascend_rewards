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
 * Hook callbacks for navigation.
 *
 * @package   local_ascend_rewards
 * @copyright 2026 Ascend Rewards
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_ascend_rewards\hook_callbacks;

// phpcs:disable moodle.Files.MoodleInternal.MoodleInternalNotNeeded
defined('MOODLE_INTERNAL') || die();

/**
 * Navigation hook callbacks.
 */
class navigation_callbacks {
    /**
     * Add Ascend Rewards to the primary navigation.
     *
     * @param \core\hook\navigation\primary_extend $hook Hook payload.
     * @return void
     */
    public static function primary_extend(\core\hook\navigation\primary_extend $hook): void {
        if (!isloggedin() || isguestuser()) {
            return;
        }

        $primary = $hook->get_primaryview();
        $key = 'local_ascend_rewards';

        if ($primary->find($key, \navigation_node::TYPE_CUSTOM)) {
            return;
        }

        $node = $primary->add(
            get_string('pluginname', 'local_ascend_rewards'),
            new \moodle_url('/local/ascend_rewards/index.php'),
            \navigation_node::TYPE_CUSTOM,
            null,
            $key
        );

        if ($node) {
            $node->showinflatnavigation = true;
        }
    }
}
