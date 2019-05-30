<?php

// Home
Breadcrumbs::register('home', function($breadcrumbs)
{
    $breadcrumbs->push('Home', route('home'));
});

// Dashboard
Breadcrumbs::register('dashboard', function($breadcrumbs)
{
    $breadcrumbs->push('Dashboard', route('dashboard'));
});

// Home > Registration OAuth Selection
Breadcrumbs::register('register-front', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Registration', route('register-front'));
});

// Home > Registration OAuth Selection > Checking for existing account
Breadcrumbs::register('register', function($breadcrumbs)
{
    $breadcrumbs->parent('register-front');
    $breadcrumbs->push('Checking for existing account', route('register'));
});

// Home > Registration OAuth Selection > Checking for existing account > New User - Confirmation of Existing Account > New User - Creating New Account
Breadcrumbs::register('register-profile', function($breadcrumbs)
{
    $breadcrumbs->parent('register');
    $breadcrumbs->push('Creating New Account', route('register-profile'));
});

// Home > Registration OAuth Selection > Checking for existing account > New User - Confirmation of Existing Account > New User - Creating New Account > New User - Creating New Account (Addresses)
Breadcrumbs::register('register-address', function($breadcrumbs)
{
    $breadcrumbs->parent('register-profile');
    $breadcrumbs->push('Creating New Account (Addresses)', route('register-address'));
});

// Home > Registration OAuth Selection > Checking for existing account > New User - Confirmation of Existing Account > New User - Creating New Account > New User - Creating New Account (Addresses) > Complete
Breadcrumbs::register('register-email-confirm', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Account registration complete', route('register-email-confirm'));
});

// Home > Login
Breadcrumbs::register('login', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Login', route('login'));
});

// Home > Inquiries
Breadcrumbs::register('inquiries', function($breadcrumbs)
{
    if(Auth::user())
        $breadcrumbs->parent('dashboard');
    else
        $breadcrumbs->parent('home');
    $breadcrumbs->push('Submit an Inquiry', route('inquiries.create'));
});

// Dashboard > Quotes
Breadcrumbs::register('quotes', function($breadcrumbs)
{
    if(Auth::user())
        $breadcrumbs->parent('dashboard');
    else
        $breadcrumbs->parent('home');
    $breadcrumbs->push('Quotes', route('quotes.request'));
});

// Dashboard > Quotes > Forms
Breadcrumbs::register('quotes.form', function($breadcrumbs, $form)
{
    if(Auth::user())
        $breadcrumbs->parent('dashboard');
    else
        $breadcrumbs->parent('home');
    $breadcrumbs->push($form->title, route('quotes.form', $form->policy_id, $form->form_type_id, $form->group));
});

// Dashboard > Quotes > Covers
Breadcrumbs::register('quotes.review', function($breadcrumbs, $form)
{
    if(Auth::user())
        $breadcrumbs->parent('dashboard');
    else
        $breadcrumbs->parent('home');
    $breadcrumbs->push($form->title, route('quotes.review', $form->policy_id, $form->form_type_id));
});

// Dashboard > Register Adviser
// Home > Inquiries
Breadcrumbs::register('admin-register', function($breadcrumbs)
{
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Create an Adviser account', route('admin-register'));
});