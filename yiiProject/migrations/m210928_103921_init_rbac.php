<?php 

use yii\db\Migration;

/**
 * Class m210928_103921_init_rbac
 */
class m210928_103921_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $rule = new \app\rbac\UserGroupRule;
        $auth->add($rule);

        // Reader permissions.
        $reader = $auth->createRole('reader');
        $reader->description='Can view books, own history and edit own account.';
        $auth->add($reader);

        // Librarian permissions.
        $addBook = $auth->CreatePermission('addBook');
        $addBook->description = 'Add a book';
        $auth->add($addBook);

        $editBook = $auth->CreatePermission('editBook');
        $editBook->description = 'Edit book';
        $auth->add($editBook);

        $removeBook = $auth->createPermission('removeBook');
        $removeBook->description = 'Remove book';
        $auth->add($removeBook);

        $editUserSuspended = $auth->createPermission('editUserSuspended');
        $editUserSuspended->description = 'Can set the suspended status of a user';
        $auth->add($editUserSuspended);

        $giveTakeBook = $auth->createPermission('giveTakeBook');
        $giveTakeBook->description = 'Can give to and take books from readers';
        $auth->add($giveTakeBook);
        
        $librarian = $auth->createRole('librarian');
        $librarian->description = 'Can add,remove and edit books. Can View History. Can set user suspended status.';
        $auth->add($librarian);
        $auth->addChild($librarian, $addBook);
        $auth->addChild($librarian, $editBook);
        $auth->addChild($librarian, $removeBook);
        $auth->addChild($librarian, $editUserSuspended);
        $auth->addChild($librarian, $giveTakeBook);

        $editUserInfo = $auth->createPermission('editUserInfo');
        $editUserInfo->description='Edit user information and role';
        $auth->add($editUserInfo);

        $admin = $auth->createRole('admin');
        $admin->description='Can do everything a librarian can. Can edit user information and role.';
        $auth->add($admin);
        $auth->addChild($admin, $editUserInfo);
        $auth->addChild($admin, $librarian);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210927_140040_init_rbac cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210927_140040_init_rbac cannot be reverted.\n";

        return false;
    }
    */
}
