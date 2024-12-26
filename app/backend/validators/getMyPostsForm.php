<?php

namespace backend\validators;

use common\models\User;
use Yii;
use yii\base\Model;

class getMyPostsForm extends Model
{
    public $limit;
    public $offset;

    public function __construct($config = [])
    {
        $this->limit = 12;
        $this->offset = 0;
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['limit', 'offset'], 'integer', 'min' => 0],
        ];
    }



}
