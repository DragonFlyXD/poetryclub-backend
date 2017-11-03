<?php
use App\Http\Frontend\Models\Permission;
use App\Http\Frontend\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class EntrustSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        DB::beginTransaction();

        // Create table for storing roles
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for associating roles to users (Many-to-Many)
        Schema::create('role_user', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'role_id']);
        });

        // Create table for storing permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('permission_role', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });

        DB::commit();

        $this->init();
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::drop('permission_role');
        Schema::drop('permissions');
        Schema::drop('role_user');
        Schema::drop('roles');
    }

    public function init()
    {
        // create roles
        $founder = new Role();
        $founder->name = 'founder';
        $founder->display_name = 'Founder';
        $founder->description = 'Kardusen';
        $founder->save();

        $admin = new Role();
        $admin->name = 'admin';
        $admin->display_name = 'Admin';
        $admin->description = 'Mariana';
        $admin->save();

        $user = new Role();
        $user->name = 'user';
        $user->display_name = 'User';
        $user->description = 'Thunder in everyone of us';
        $user->save();

        // create permissions
        $createContent = new Permission();
        $createContent->name = 'create-content';
        $createContent->display_name = 'Create content';
        $createContent->description = 'Create new contents';
        $createContent->save();

        $editContent = new Permission();
        $editContent->name = 'edit-content';
        $editContent->display_name = 'Edit content';
        $editContent->description = 'Edit existing contents';
        $editContent->save();

        $createPoem = new Permission();
        $createPoem->name = 'create-poem';
        $createPoem->display_name = 'Create poem';
        $createPoem->description = 'Create new poems';
        $createPoem->save();

        $editPoem = new Permission();
        $editPoem->name = 'edit-poem';
        $editPoem->display_name = 'Edit poem';
        $editPoem->description = 'Edit existing poems';
        $editPoem->save();

        $createAppreciation = new Permission();
        $createAppreciation->name = 'create-appreciation';
        $createAppreciation->display_name = 'Create appreciation';
        $createAppreciation->description = 'Create new appreciations';
        $createAppreciation->save();

        $editAppreciation = new Permission();
        $editAppreciation->name = 'edit-appreciation';
        $editAppreciation->display_name = 'Edit appreciation';
        $editAppreciation->description = 'Edit existing appreciations';
        $editAppreciation->save();

        $createCategory = new Permission();
        $createCategory->name = 'create-category';
        $createCategory->display_name = 'Create category';
        $createCategory->description = 'Create new categories';
        $createCategory->save();

        $editCategory = new Permission();
        $editCategory->name = 'edit-category';
        $editCategory->display_name = 'Edit category';
        $editCategory->description = 'Edit existing categories';
        $editCategory->save();

        $createUser = new Permission();
        $createUser->name = 'create-user';
        $createUser->display_name = 'Create user';
        $createUser->description = 'Create new users';
        $createUser->save();

        $editUser = new Permission();
        $editUser->name = 'edit-user';
        $editUser->display_name = 'Edit user';
        $editUser->description = 'Edit existing users';
        $editUser->save();
    }
}
