<?php

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

//Dashboard
Breadcrumbs::for('dashboard', function ($trail) {
    $trail->push(trans('dashboard.title'), url('/dashboard'));
});

//***************************************************************
// Dashboard > Admins
Breadcrumbs::for('admins', function ($trail) {
    $trail->parent('dashboard');
    $trail->push(trans('admin.title_list'), route('admin.list'));
});

// Dashboard > Admins > Create
Breadcrumbs::for('admins-create', function ($trail) {
    $trail->parent('admins');
    $trail->push(trans('admin.title_create'), route('admin.create'));
});

// Dashboard > Admins > Edit
Breadcrumbs::for('admins-edit', function ($trail, $id) {
    $trail->parent('admins');
    $trail->push(trans('admin.title_edit'), route('admin.edit', $id));
});

// Dashboard > Admins > Profile
Breadcrumbs::for('admins-profile', function ($trail, $id) {
    $trail->parent('admins');
    $trail->push(trans('admin.title_profile'), route('admin.show', $id));
});

//***************************************************************
// Dashboard > Patients
Breadcrumbs::for('patients', function ($trail) {
    $trail->parent('dashboard');
    $trail->push(trans('patient.title_list'), route('patient.list'));
});

// Dashboard > Patients > Create
Breadcrumbs::for('patients-create', function ($trail) {
    $trail->parent('patients');
    $trail->push(trans('patient.title_create'), route('patient.create'));
});

// Dashboard > Patients > Edit
Breadcrumbs::for('patients-edit', function ($trail, $id) {
    $trail->parent('patients');
    $trail->push(trans('patient.title_edit'), route('patient.edit', $id));
});

// Dashboard > Patients > Profile
Breadcrumbs::for('patients-profile', function ($trail, $id) {
    $trail->parent('patients');
    $trail->push(trans('patient.title_profile'), route('patient.show', $id));
});

//***************************************************************
// Dashboard > Announcements > Create
Breadcrumbs::for('announcements-create', function ($trail) {
    $trail->parent('dashboard');
    $trail->push(trans('announcement.title_create'), route('announcement.create'));
});
