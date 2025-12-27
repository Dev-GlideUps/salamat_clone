<?php
/**
 * The manifest of files that are local to specific environment.
 * This file returns a list of environments that the application
 * may be installed under. The returned data must be in the following
 * format:
 *
 * ```php
 * return [
 *     'environment name' => [
 *         'path' => 'directory storing the local files',
 *         'skipFiles'  => [
 *             // list of files that should only copied once and skipped if they already exist
 *         ],
 *         'setWritable' => [
 *             // list of directories that should be set writable
 *         ],
 *         'setExecutable' => [
 *             // list of files that should be set executable
 *         ],
 *         'setCookieValidationKey' => [
 *             // list of config files that need to be inserted with automatically generated cookie validation keys
 *         ],
 *         'createSymlink' => [
 *             // list of symlinks to be created. Keys are symlinks, and values are the targets.
 *         ],
 *     ],
 * ];
 * ```
 */
return [
    'Development' => [
        'path' => 'dev',
        'setWritable' => [
            'console/runtime',
            'admin/runtime',
            'admin/web/assets',
            'clinic/runtime',
            'clinic/web/assets',
            'insurance/runtime',
            'insurance/web/assets',
            'pharmacy/runtime',
            'pharmacy/web/assets',
            'patient/runtime',
            'patient/web/assets',
        ],
        'setExecutable' => [
            'yii',
            'yii_test',
        ],
        'setCookieValidationKey' => [
            'common/config/codeception-local.php',
            'admin/config/main-local.php',
            'clinic/config/main-local.php',
            'insurance/config/main-local.php',
            'pharmacy/config/main-local.php',
            'patient/config/main-local.php',
        ],
    ],
    'Production' => [
        'path' => 'prod',
        'setWritable' => [
            'console/runtime',
            'admin/runtime',
            'admin/web/assets',
            'clinic/runtime',
            'clinic/web/assets',
            'insurance/runtime',
            'insurance/web/assets',
            'pharmacy/runtime',
            'pharmacy/web/assets',
            'patient/runtime',
            'patient/web/assets',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
            'admin/config/main-local.php',
            'clinic/config/main-local.php',
            'insurance/config/main-local.php',
            'pharmacy/config/main-local.php',
            'patient/config/main-local.php',
        ],
    ],
];
