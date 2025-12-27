<?php

// use Yii;

$user = Yii::$app->user;


$access = [
    'appointments' => $user->can('View appointments'),
    'patients' => $user->can('View patients'),
    'doctors' => $user->can('View doctors'),
    'medicines' => $user->can('View medicines'),
    'branches' => $user->can('View branches'),
    'diagnoses' => $user->can('View diagnoses'),
    'prescriptions' => $user->can('View prescriptions'),
    'sickLeaves' => $user->can('View sick leaves'),
    'patientAttachments' => $user->can('View patient attachments'),
    'invoices' => $user->can('View invoices'),
    'payments' => $user->can('View payments'),
    'analytics' => $user->can('View analytics'),
    'reports' => $user->can('View reports'),
    'employees' => $user->can('View employees'),
    'users' => $user->can('View users'),
];

$menuItems = [];
$menuItems[] = '<div class="mdc-list-container nav-links">';

$menuItems[] = [
    'label' => Yii::t('general', 'Dashboard'),
    'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/layers.svg')),
    'url' => ['/root/index'],
];

if (count($user->identity->clinics) > 1) {
$menuItems[] = [
    'label' => Yii::t('clinic', 'Switch clinic'),
    'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/settings_1.svg')),
    'url' => ['/user/select-clinic'],
];
}

if ($access['appointments'] || $access['patients'] || $access['doctors'] || $access['medicines'] || $access['branches']) {
$menuItems[] = '<div class="mdc-divider"></div>';
$menuItems[] = '<div class="mdc-list-subtitle">'.Yii::t('clinic', 'Clinic / Hospital').'</div>';
}
if ($access['appointments']) {
$menuItems[] = [
    'label' => Yii::t('clinic', 'Appointments'),
    'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/book_open.svg')),
    'url' => ['/clinic/appointments'],
];
}
if ($access['patients']) {
$menuItems[] = [
    'label' => Yii::t('clinic', 'Patients'),
    'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/group.svg')),
    'url' => ['/clinic/patients'],
];
}
if ($access['doctors']) {
$menuItems[] = [
    'label' => Yii::t('clinic', 'Doctors'),
    'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/shield_user.svg')),
    'url' => ['/clinic/doctors'],
];
}
if ($access['medicines']) {
$menuItems[] = [
    'label' => Yii::t('clinic', 'Medicines'),
    'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/bottle_1.svg')),
    'url' => ['/clinic/medicines'],
];
}


if ($access['branches']) {
$menuItems[] = [
    'label' => Yii::t('clinic', 'Branches'),
    'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/building.svg')),
    'url' => ['/clinic/branches'],
];
}


    $menuItems[] = '<div class="mdc-divider"></div>';
    $menuItems[] = '<div class="mdc-list-subtitle" style="font-weight:bold">' . Yii::t('clinic', 'Consent Form') . '</div>';

    $menuItems[] = [
        'label' => Yii::t('clinic', 'Consent Forms'),
        'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/selected_file.svg')),
        'url' => ['/consent-form/index'],
    ];
    $menuItems[] = [
        'label' => Yii::t('clinic', 'Patient Consents'),
        'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/selected_file.svg')),
        'url' => ['/patient-consent/index'],
    ];


if ($access['diagnoses'] || $access['prescriptions'] || $access['sickLeaves']) {
$menuItems[] = '<div class="mdc-divider"></div>';
$menuItems[] = '<div class="mdc-list-subtitle">'.Yii::t('patient', 'Patients').'</div>';
}
if ($access['diagnoses']) {
$menuItems[] = [
    'label' => Yii::t('patient', 'Doctor notes'),
    'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/clipboard.svg')),
    'url' => ['/patients/doctor-notes'],
];
$menuItems[] = [
    'label' => Yii::t('patient', 'Diagnoses'),
    'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/diagnostics.svg')),
    'url' => ['/patients/diagnoses'],
];
}
if ($access['prescriptions']) {
$menuItems[] = [
    'label' => Yii::t('patient', 'Prescriptions'),
    'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/two_bottles.svg')),
    'url' => ['/patients/prescriptions'],
];
}
if ($access['sickLeaves']) {
$menuItems[] = [
    'label' => Yii::t('patient', 'Sick leaves'),
    'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/file.svg')),
    'url' => ['/patients/sick-leaves'],
];
}
// if ($access['patientAttachments']) {
// $menuItems[] = [
//     'label' => Yii::t('general', 'Attachments'),
//     'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/attachment2.svg')),
//     'url' => ['/patients/attachments'],
// ];
// }

if ($access['invoices'] || $access['payments']) {
$menuItems[] = '<div class="mdc-divider"></div>';
$menuItems[] = '<div class="mdc-list-subtitle">'.Yii::t('finance', 'Finance').'</div>';
}
if ($access['invoices']) {
$menuItems[] = [
    'label' => Yii::t('finance', 'Invoices'),
    'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/selected_file.svg')),
    'url' => ['/finance/invoices'],
];
}
if ($access['payments']) {
$menuItems[] = [
    'label' => Yii::t('finance', 'Payments'),
    'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/credit_card.svg')),
    'url' => ['/finance/payments'],
];
}
if ($access['invoices']) {
$menuItems[] = [
    'label' => Yii::t('insurance', 'Insurance claims'),
    'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/shield_thunder.svg')),
    'url' => ['/finance/insurance/claims'],
];
}

if ($access['analytics'] || $access['reports']) {
$menuItems[] = '<div class="mdc-divider"></div>';
$menuItems[] = '<div class="mdc-list-subtitle">'.Yii::t('general', 'Analytics & reports').'</div>';
}
if ($access['analytics']) {
$menuItems[] = [
    'label' => Yii::t('general', 'Analytics'),
    'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/chart_line_1.svg')),
    'url' => ['/analytics'],
];
}
if ($access['reports']) {
$menuItems[] = [
    'label' => Yii::t('general', 'Reports'),
    'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/chart_bar_1.svg')),
    'url' => ['/reports'],
];
// $menuItems[] = [
//     'label' => Yii::t('general', 'New Reports PDF'),
//     'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/chart_bar_1.svg')),
//     'url' => ['/reports/pdf'],
// ];
}

if ($access['employees']) {
$menuItems[] = '<div class="mdc-divider"></div>';
$menuItems[] = '<div class="mdc-list-subtitle">'.Yii::t('hr', 'Human resources').'</div>';
}
if ($access['employees']) {
$menuItems[] = [
    'label' => Yii::t('he', 'Employees'),
    'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/contact_1.svg')),
    'url' => ['/hr/employees'],
];
}

if ($access['users']) {
$menuItems[] = '<div class="mdc-divider"></div>';
$menuItems[] = '<div class="mdc-list-subtitle">'.Yii::t('user', 'Users & Access control').'</div>';
}
if ($access['users']) {
$menuItems[] = [
    'label' => Yii::t('user', 'Users'),
    'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/address_book_2.svg')),
    'url' => ['/clinic/users'],
];
}

$menuItems[] = '</div>';

return $menuItems;
