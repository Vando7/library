<?php

namespace app\rbac;

use Yii;
use yii\rbac\Rule;

/**
 * Checks if user group matches
 */
class UserGroupRule extends Rule
{
    public $name = 'userGroup';

    public function execute($user, $item, $params)
    {
        if (!Yii::$app->user->isGuest) {
            $group = Yii::$app->user->identity->role;
            if ($item->name === 'admin') {
                return $group == 'admin';
            } elseif ($item->name === 'librarian') {
                return $group == 'librarian';
            }
            elseif ($item->name === 'reader') {
                return $group == 'reader';
            }
        }
        return false;
    }
}