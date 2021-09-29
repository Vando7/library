<?php

use yii\db\Migration;


/**
 * Class m210929_065928_init_rbac
 */
class m210929_065928_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // Reader permissions.
        $viewBook = $auth->createPermission('viewBook');
        $viewBook->description = 'Can access a list of books and view basic book pages.';
        $auth->add($viewBook);

        $viewOwnHistory = $auth->createPermission('viewOwnHistory');
        $viewOwnHistory->description = 'Can view own hostory of books taken';
        $auth->add($viewOwnHistory);

        $viewOwnProfile = $auth->createPermission('viewOwnProfile');
        $viewOwnProfile->description = 'Can acces a simplified view of their profile.';
        $auth->add($viewOwnProfile);

        $editOwnProfile = $auth->createPermission('editOwnProfile');
        $editOwnProfile->description = 'Can acces a simplified form to edit own profile.';
        $auth->add($editOwnProfile);
        
        $reader = $auth->createRole('reader');
        $reader->description='Can view books, own history and edit own account.';
        $auth->add($reader);
        $auth->addChild($reader,$viewBook);
        $auth->addChild($reader,$viewOwnHistory);
        $auth->addChild($reader,$viewOwnProfile);
        $auth->addChild($reader,$editOwnProfile);

        // Librarian permissions.
        $manageBook = $auth->createPermission('manageBook');
        $manageBook->description = 'Can view book History. Can give and take books. Can manage books and genres: add, remove, edit.';
        $auth->add($manageBook);

        $viewAllHistory = $auth->createPermission('viewAllHistory');
        $viewAllHistory->description = 'Can view history of all books given and returned';
        $auth->add($viewAllHistory);

        $viewAllProfiles = $auth->createPermission('viewAllProfilesLibrary');
        $viewAllProfiles->description = 'Can view all user profiles';
        $auth->add($viewAllProfiles);

        $suspendOrNote = $auth->createPermission('suspendOrNote');
        $suspendOrNote->description = 'Can suspend users or leave a note on their profiles.';
        $auth->add($suspendOrNote);
        
        $librarian = $auth->createRole('librarian');
        $librarian->description = 'Can add,remove and edit books. Can View History. Can set user suspended status.';
        $auth->add($librarian);
        $auth->addChild($librarian, $reader);
        $auth->addChild($librarian, $manageBook);
        $auth->addChild($librarian, $suspendOrNote);
        $auth->addChild($librarian, $viewAllProfiles);
        $auth->addChild($librarian, $viewAllHistory);

        
        // Admin Permissions
        $manageUsers = $auth->createPermission('manageUsers');
        $manageUsers->description='Edit user information and role';
        $auth->add($manageUsers);

        $admin = $auth->createRole('admin');
        $admin->description='Can do everything a librarian can. Can manage all user accounts.';
        $auth->add($admin);
        $auth->addChild($admin, $manageUsers);
        $auth->addChild($admin, $librarian);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210929_065928_init_rbac cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210929_065928_init_rbac cannot be reverted.\n";

        return false;
    }
    */
}
