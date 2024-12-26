<?php

namespace backend\validators;

use common\models\User;
use Yii;
use yii\base\Model;

class createPostForm extends Model
{
    public $text;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text'], 'required'],
        ];
    }

}
