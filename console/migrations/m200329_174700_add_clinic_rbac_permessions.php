<?php

use yii\db\Migration;

/**
 * Class m200329_174700_add_clinic_rbac_permessions
 */
class m200329_174700_add_clinic_rbac_permessions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $authManager = new \clinic\components\rbac\DbManager();

        $roles = new \stdClass();
        $roles->admin = $authManager->createRole('Admin');
        $roles->doctor = $authManager->createRole('Doctor');
        $roles->medicalStaff = $authManager->createRole('Medical staff');
        $roles->reception = $authManager->createRole('Reception');

        $authManager->add($roles->admin);
        $authManager->add($roles->doctor);
        $authManager->add($roles->medicalStaff);
        $authManager->add($roles->reception);

        $permissions = [
            // users
            'Manage users' => [
                'description' => 'Manage users (full access)',
                'sort' => 1,
                'roles' => [
                    'admin',
                ],
                'parents' => [
                ],
            ],
            'Control users' => [
                'description' => 'Control users access',
                'sort' => 2,
                'roles' => [
                ],
                'parents' => [
                    'Manage users',
                ],
            ],
            'View users' => [
                'description' => 'View users information',
                'sort' => 3,
                'roles' => [
                    'doctor',
                ],
                'parents' => [
                    'Manage users',
                    'Control users',
                ],
            ],
            // appointments
            'Manage appointments' => [
                'description' => 'Manage appointments (full access)',
                'sort' => 4,
                'roles' => [
                    'admin',
                    'reception',
                ],
                'parents' => [
                ],
            ],
            'Create appointments' => [
                'description' => 'Create appointments',
                'sort' => 5,
                'roles' => [
                    'medicalStaff',
                ],
                'parents' => [
                    'Manage appointments',
                ],
            ],
            'Update appointments' => [
                'description' => 'Update appointments',
                'sort' => 6,
                'roles' => [
                    'medicalStaff',
                ],
                'parents' => [
                    'Manage appointments',
                ],
            ],
            'View appointments' => [
                'description' => 'View appointments information',
                'sort' => 7,
                'roles' => [
                ],
                'parents' => [
                    'Manage appointments',
                    'Create appointments',
                    'Update appointments',
                ],
            ],
            // patients
            'Manage patients' => [
                'description' => 'Manage patients (full access)',
                'sort' => 8,
                'roles' => [
                    'admin',
                    'doctor',
                ],
                'parents' => [
                ],
            ],
            'Add patients' => [
                'description' => 'Add new patients',
                'sort' => 9,
                'roles' => [
                    'medicalStaff',
                    'reception',
                ],
                'parents' => [
                    'Manage patients',
                ],
            ],
            'Update patients' => [
                'description' => 'Update patients information',
                'sort' => 10,
                'roles' => [
                    'medicalStaff',
                ],
                'parents' => [
                    'Manage patients',
                ],
            ],
            'View patients' => [
                'description' => 'View patients information',
                'sort' => 11,
                'roles' => [
                ],
                'parents' => [
                    'Manage patients',
                    'Add patients',
                    'Update patients',
                ],
            ],
            // doctors
            'Manage doctors' => [
                'description' => 'Manage doctors (full access)',
                'sort' => 12,
                'roles' => [
                    'admin',
                ],
                'parents' => [
                ],
            ],
            'Update doctors' => [
                'description' => 'Update doctors information',
                'sort' => 13,
                'roles' => [
                    'doctor',
                    'reception',
                ],
                'parents' => [
                    'Manage doctors',
                ],
            ],
            'View doctors' => [
                'description' => 'View doctors information',
                'sort' => 14,
                'roles' => [
                    'medicalStaff',
                ],
                'parents' => [
                    'Manage doctors',
                    'Update doctors',
                ],
            ],
            // medicines
            'Manage medicines' => [
                'description' => 'Manage medicines (full access)',
                'sort' => 15,
                'roles' => [
                    'admin',
                    'medicalStaff',
                ],
                'parents' => [
                ],
            ],
            'Create medicines' => [
                'description' => 'Add new medicines',
                'sort' => 16,
                'roles' => [
                ],
                'parents' => [
                    'Manage medicines',
                ],
            ],
            'Update medicines' => [
                'description' => 'Update medicines',
                'sort' => 17,
                'roles' => [
                ],
                'parents' => [
                    'Manage medicines',
                ],
            ],
            'Delete medicines' => [
                'description' => 'Delete medicines',
                'sort' => 18,
                'roles' => [
                ],
                'parents' => [
                    'Manage medicines',
                ],
            ],
            'View medicines' => [
                'description' => 'View medicines',
                'sort' => 19,
                'roles' => [
                    'doctor',
                ],
                'parents' => [
                    'Manage medicines',
                    'Create medicines',
                    'Update medicines',
                    'Delete medicines',
                ],
            ],
            // branches
            'Manage branches' => [
                'description' => 'Manage branches (full access)',
                'sort' => 20,
                'roles' => [
                    'admin',
                    'reception',
                ],
                'parents' => [
                ],
            ],
            'Update branches' => [
                'description' => 'Update branches information',
                'sort' => 21,
                'roles' => [
                ],
                'parents' => [
                    'Manage branches',
                ],
            ],
            'View branches' => [
                'description' => 'View branches',
                'sort' => 22,
                'roles' => [
                    'doctor',
                ],
                'parents' => [
                    'Manage branches',
                    'Update branches',
                ],
            ],
            // diagnoses
            'View diagnoses' => [
                'description' => 'View diagnoses',
                'sort' => 23,
                'roles' => [
                    'admin',
                    'medicalStaff',
                ],
                'parents' => [
                ],
            ],
            // prescriptions
            'View prescriptions' => [
                'description' => 'View prescriptions',
                'sort' => 24,
                'roles' => [
                    'admin',
                    'medicalStaff',
                ],
                'parents' => [
                ],
            ],
            // sickLeaves
            'View sick leaves' => [
                'description' => 'View sickLeaves',
                'sort' => 25,
                'roles' => [
                    'admin',
                    'medicalStaff',
                    'reception',
                ],
                'parents' => [
                ],
            ],
            // invoices
            'Manage invoices' => [
                'description' => 'Manage invoices (full access)',
                'sort' => 26,
                'roles' => [
                    'admin',
                    'reception',
                ],
                'parents' => [
                ],
            ],
            'Create invoices' => [
                'description' => 'Create new invoices',
                'sort' => 27,
                'roles' => [
                ],
                'parents' => [
                    'Manage invoices',
                ],
            ],
            'View invoices' => [
                'description' => 'View invoices',
                'sort' => 28,
                'roles' => [
                    'doctor',
                ],
                'parents' => [
                    'Manage invoices',
                    'Create invoices',
                ],
            ],
            // payments
            'Manage payments' => [
                'description' => 'Manage payments (full access)',
                'sort' => 29,
                'roles' => [
                    'admin',
                    'reception',
                ],
                'parents' => [
                ],
            ],
            'Create payments' => [
                'description' => 'Create new payments',
                'sort' => 30,
                'roles' => [
                ],
                'parents' => [
                    'Manage payments',
                ],
            ],
            'View payments' => [
                'description' => 'View payments',
                'sort' => 31,
                'roles' => [
                    'doctor',
                ],
                'parents' => [
                    'Manage payments',
                    'Create payments',
                ],
            ],
        ];

        foreach ($permissions as $permission => $data) {
            $item = $authManager->createPermission($permission);
            $item->description = $data['description'];
            $item->sort = $data['sort'];
            $authManager->add($item);

            foreach ($data['roles'] as $role) {
                $authManager->addChild($roles->$role, $item);
            }

            foreach ($data['parents'] as $parent) {
                $authManager->addChild(new \yii\rbac\Permission([
                    'name' => $parent,
                    'type' => \yii\rbac\Item::TYPE_PERMISSION,
                ]), $item);
            }
        }

        $authManager->addChild($roles->doctor, $roles->medicalStaff);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200329_174700_add_clinic_rbac_permessions cannot be reverted.\n";
        return false;
    }
}
