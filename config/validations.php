<?php

/**
 * This file contains validation rules, messages and parameters for
 * all requests within the api that need validation
 */
return [
    'rules' => [
        //Auth Validations

        'vendor-login' => [
            'email' => 'required|email|exists:admins,email',
            'password' => 'required',
        ],

        'make-reservation' => [
            'vendor_id' => 'required|integer|exists:vendors,id',
            'party_size' => 'required|integer',
            'date' => 'required|date',
            // 'start_time' => 'required',
            // 'end_time' => 'required',
            'time' => 'required',
        ],

        'update-reservation' => [
            'id' => 'required|integer|exists:reservations,id',
            'party_size' => 'required|integer',
            'date' => 'required|date',
            // 'start_time' => 'required',
            // 'end_time' => 'required',
            'time' => 'required',
        ],

        'invite-guest' => [
            'reservation_id' => 'required|integer|exists:reservations,id',
        ],

        'create-review' => [
            'type' => 'required|in:vendor',
            'rating' => 'required|integer|between:1,5',
            'vendor_id' => 'exists:vendors,id',
            'content' => 'required|string|min:1|max:1000',
        ],

        'get-reviews' => [
            'vendor_id' => 'required|exists:vendors,id',
        ],

        'free-trial' => [
            'voucher_id' => 'required|exists:vouchers,id',
        ],

        'create-guest' => [
            'vendor_id' => 'required|exists:vendors,id',
            // 'user_id' => 'exists:users,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone' => 'nullable|string|max:255',
            // 'birthday' => 'string|max:255',
            'general_notes' => 'max:1000',
        ],

        'edit-guest' => [
            'id' => 'required|exists:guests,id',
            // 'user_id' => 'exists:users,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone' => 'nullable|string|max:255',
            // 'birthday' => 'string|max:255',
            'general_notes' => 'max:1000',
        ],

        'create-admin' => [
            'vendor_id' => 'required|exists:vendors,id',
            'role_id' => 'required|exists:admin_roles,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'password' => 'required|min:6',
        ],

        'edit-admin' => [
            'id' => 'required|exists:admins,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'password' => 'min:6',
        ],

        'test-guests' => [
            'direction' => ['in:asc,desc', 'nullable'],
            'field' => ['in:id,first_name,last_name,email,phone', 'nullable'],
        ],

        'update-reservation' => [
            'id' => 'required|exists:reservations,id',
        ],

        'vendor-create-reservation' => [
            'vendor_id' => 'required|integer|exists:vendors,id',
            'party_size' => 'required|integer',
            'date' => 'required|date',
            'time' => 'required',
        ],

        'vendor-change-subscription-plan' => [
            'plan_id' => 'required|integer|exists:plans,id',
            'plan_frequency_id' => 'required|integer|exists:plan_frequencies,id',
        ],

        'upload-image' => [
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ],

        'upload-images' => [
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ],
    ],
    'messages' => [
        'user_id.exists' => 'user not found',
    ],
];
