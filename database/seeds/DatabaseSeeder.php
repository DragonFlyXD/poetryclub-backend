<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//         User
//        factory(App\Http\Frontend\Models\User::class, 50)->create();
        // Poem
//        factory(App\Http\Frontend\Models\Poem::class, 50)->create();
        // Tag
//        factory(\App\Http\Frontend\Models\Tag::class, 15)->create();
        // Poem_Tag
        /*for ($i = 0; $i < 50; $i++) {
            DB::table('poem_tag')->insert([
                'poem_id' => random_int(1, 50),
                'tag_id' => random_int(1, 15),
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]);
        }*/
        // Comment
//        factory(\App\Http\Frontend\Models\Comment::class, 200)->create();
        // Vote
//        factory(\App\Http\Frontend\Models\Vote::class, 50)->create();
        // Profile
//        factory(\App\Http\Frontend\Models\Profile::class, 50)->create();
    }
}
