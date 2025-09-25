<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Role-Based Access Control Helper
 * Provides helper functions for checking permissions in views
 */

if (!function_exists('can_user')) {
    /**
     * Check if current user has specific permission
     */
    function can_user($permission) {
        $CI =& get_instance();
        if (method_exists($CI, 'has_permission')) {
            return $CI->has_permission($permission);
        }
        return false;
    }
}

if (!function_exists('user_role')) {
    /**
     * Get current user's role
     */
    function user_role() {
        $CI =& get_instance();
        return $CI->session->userdata('user_role');
    }
}

if (!function_exists('is_super_admin')) {
    /**
     * Check if current user is super admin
     */
    function is_super_admin() {
        return strtolower(user_role()) === 'super admin';
    }
}

if (!function_exists('is_admin')) {
    /**
     * Check if current user is admin or super admin
     */
    function is_admin() {
        $role = strtolower(user_role());
        return in_array($role, ['admin', 'super admin']);
    }
}

if (!function_exists('is_viewer')) {
    /**
     * Check if current user is viewer
     */
    function is_viewer() {
        return strtolower(user_role()) === 'viewer';
    }
}

if (!function_exists('show_if_can')) {
    /**
     * Show content only if user has permission
     */
    function show_if_can($permission, $content) {
        return can_user($permission) ? $content : '';
    }
}
