<?php

use yii\db\Migration;
use clinic\models\dental\Category;
use clinic\models\dental\Procedure;

/**
 * Class m200728_061401_insert_dental_procedures
 */
class m200728_061401_insert_dental_procedures extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // \Yii::$app->db->createCommand()->batchInsert('{{%dental_category}}', $postModel->attributes(), $rows)->execute();
        $categories = [
            'Cleanings' => [
                'class' => 'clean',
                'procedures' => [
                    'Scaling and polishing',
                    'Deep scaling',
                    'Fluoride treatment',
                ],
            ],
            'Fillings' => [
                'class' => 'filled',
                'procedures' => [
                    'Composite - class I',
                    'Composite - class II',
                    'Composite - class III',
                    'Amalgam - class I',
                    'Amalgam - class II',
                    'Restoration decidous',
                    'Temporary restoration',
                    'Build up',
                    'Post and core',
                ],
            ],
            'Endodontics' => [
                'class' => 'root-canal',
                'procedures' => [
                    'Root canal - retreat anterior',
                    'Root canal - pre-molar',
                    'Root canal - retreat molar',
                    'Root canal - anterior',
                    'Root canal - molar',
                ],
            ],
            'Orthodontics' => [
                'class' => 'braces',
                'procedures' => [
                    'Orthodontics - class I mild',
                    'Orthodontics - class I moderate',
                    'Orthodontics - class I severe',
                    'Orthodontics - class II mild',
                    'Orthodontics - class II moderate',
                    'Orthodontics - class II severe',
                    'Orthodontics - class III mild',
                    'Orthodontics - class III moderate',
                    'Orthodontics - class III severe',
                    'Upper fixed retainer',
                    'Lower fixed retainer',
                    'Hawleys apliane',
                    'Clear aligner upper',
                    'Clear aligner lower',
                    'Clear retainer upper',
                    'Clear retainer lower',
                    'Distalizer',
                    'Expander',
                    'Removable ortho appliance',
                    'Braces follow up',
                ],
            ],
            'Dentures' => [
                'class' => 'artificial',
                'procedures' => [
                    'Maxillary denture',
                    'Mandibular denture',
                    'Partial denture',
                    'Denture repair',
                ],
            ],
            'Crowns & Bridges' => [
                'class' => 'crown',
                'procedures' => [
                    'PFM Bridge pontic',
                    'PFM Crown',
                    'Zircon crown',
                ],
            ],
            'Oral surgeries' => [
                'class' => 'removed',
                'procedures' => [
                    'Extraction - normal',
                    'Extraction - surgical',
                    'Extraction - children',
                ],
            ],
            'Cosmetics' => [
                'class' => 'clean',
                'procedures' => [
                    'Whitening chairside',
                    'Home whitening kit',
                    'Bleaching tray',
                    'USA Veneer',
                    'UAE Veneer',
                ],
            ],
            'Periodontics' => [
                'class' => '',
                'procedures' => [
                    'Temporary restoration',
                ],
            ],
            'Implants' => [
                'class' => 'implant',
                'procedures' => [
                    'Surgical placement of implant anterior',
                    'Surgical placement of implant posterior',
                    'Bone graft',
                ],
            ],
        ];
        foreach ($categories as $item => $procedures) {
            $model = new Category([
                'title' => $item,
                'chart_class' => $procedures['class'],
                'status' => Category::STATUS_ACTIVE,
            ]);
            if ($model->save()) {
                foreach ($procedures['procedures'] as $desc) {
                    $proc = new Procedure([
                        'category_id' => $model->id,
                        'description' => $desc,
                    ]);
                    $proc->save();
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200728_061401_insert_dental_procedures cannot be reverted.\n";
        return false;
    }
}
