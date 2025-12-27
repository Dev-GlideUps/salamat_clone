<?php

namespace clinic\components\rbac;

use Yii;
use yii\di\Instance;

class Configs extends \mdm\admin\components\Configs
{
    public $advanced = [
        'admin' => [
            '@common/config/main.php',
            '@common/config/main-local.php',
            '@admin/config/main.php',
            '@admin/config/main-local.php',
        ],
        'clinic' => [
            '@common/config/main.php',
            '@common/config/main-local.php',
            '@clinic/config/main.php',
            '@clinic/config/main-local.php',
        ],
    ];

    /**
     * @var ManagerInterface .
     */
    public $authManager = ['class' => 'clinic\components\rbac\DbManager'];

    private static $_classes = [
        'db' => 'yii\db\Connection',
        'userDb' => 'yii\db\Connection',
        'cache' => 'yii\caching\Cache',
        'authManager' => 'clinic\components\rbac\ManagerInterface',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        foreach (self::$_classes as $key => $class) {
            try {
                $this->{$key} = empty($this->{$key}) ? null : Instance::ensure($this->{$key}, $class);
            } catch (\Exception $exc) {
                $this->{$key} = null;
                Yii::error($exc->getMessage());
            }
        }
    }

    /**
     * @return ManagerInterface
     */
    public static function authManager()
    {
        return static::instance()->authManager;
    }
}
