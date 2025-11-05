<?php
declare(strict_types=1);

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Migrations\Migration;

class PopulateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $user = User::query()->create([
            'name'              => 'Admin',
            'email'             => 'admin@admin.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('admin'),
        ]);

        $user->roles()->attach(
            Role::query()
                ->where('key', 'admin')
                ->first()->id
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        User::query()->where('email', 'admin@admin.com')->delete();
    }
}
