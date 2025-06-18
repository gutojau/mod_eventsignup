<?php
/**
 * Version details for the eventsignup module.
 *
 * @package   mod_eventsignup
 * @copyright 2024 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->component = 'mod_eventsignup'; // Full name of the plugin (used for diagnostics).
$plugin->version   = 2024061800;        // The current plugin version (Date: YYYYMMDDXX).
$plugin->requires  = 2022112800;        // Requires this Moodle version (Moodle 4.1).
$plugin->maturity  = MATURITY_ALPHA;    // Development status.
$plugin->release   = '1.0.0 (Alpha)';

// The 'questionnaire' plugin is a dependency, as we are forking its structure.
// This is not strictly necessary if we copy all the required code,
// but it's good practice to acknowledge the origin.
// For a full fork, you might remove this dependency later once all code is self-contained.
$plugin->dependencies = [
    'mod_questionnaire' => 2022111500 // Optional: specify dependency version.
];

