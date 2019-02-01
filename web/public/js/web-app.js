/**
 * This file contains functions for use when running app as a web app. All functions should also have a desktop
 * equivalent in electron app webview-preload.js
 */

/**
 * Determines whether running as a desktop app
 * @returns {boolean}
 */
function isDesktopApp() {
    return false;
}

/**
 * Go back a page
 */
function goBack() {
    window.history.back();
}
