<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for component 'local_ascend_rewards', language 'en'
 *
 * @package   local_ascend_rewards
 * @copyright 2025 Ascend Rewards
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['admin_dashboard'] = 'Admin Dashboard';
$string['allow_repeat_same_badge'] = 'Allow repeat of same badge in same course';
$string['allow_repeat_same_badge_desc'] = 'If enabled, the same badge may be awarded multiple times to the same user for the same course.';
$string['audit_trail'] = 'Badge Audit Trail';
$string['awardbadges'] = 'Award badges (Ascend Auto Badger)';
$string['badgescreated'] = 'Badges created/ensured successfully.';
$string['coins'] = 'coins';
$string['coins_settings'] = 'Default Coin Rewards';
$string['coins_settings_desc'] = 'These values are used when the plugin is first installed. You can change them anytime.';
$string['createbadges'] = 'Create default badges';
$string['filtercourse'] = 'Filtering by course ID: {$a}';
$string['filternone'] = 'Showing site-wide totals (no course filter).';
$string['leaderboard'] = 'Leaderboard';
$string['managebadges'] = 'Manage Ascend Rewards Badges';
$string['mappingsaved'] = 'Mappings saved.';
$string['nav_rewards'] = 'Ascend Rewards';
$string['noleaderdata'] = 'No leaderboard data yet.';
$string['norecent'] = 'No recent badges.';
$string['pluginname'] = 'Ascend Rewards';
$string['recentbadges'] = 'Recent Badges';
$string['savemappings'] = 'Save mappings';
$string['task_rebuild_badge_cache'] = 'Rebuild badge activity cache';
$string['totalassets'] = 'Your total Ascend Assets: {$a}';




// Privacy API strings.
$string['privacy:metadata:local_ascend_rewards_coins'] = 'Stores user coin and XP transactions for badge awards';
$string['privacy:metadata:userid'] = 'The ID of the user';
$string['privacy:metadata:badgeid'] = 'The ID of the badge awarded';
$string['privacy:metadata:coins'] = 'Number of coins awarded';
$string['privacy:metadata:courseid'] = 'The course where the badge was earned';
$string['privacy:metadata:timecreated'] = 'Time when the record was created';

$string['privacy:metadata:local_ascend_rewards_badgerlog'] = 'Audit log of badge awarding attempts';
$string['privacy:metadata:status'] = 'Status of the badge award (success/failure)';
$string['privacy:metadata:message'] = 'Message describing the badge award';
