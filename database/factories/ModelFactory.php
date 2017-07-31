<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */

// User
$factory->define(App\Http\Frontend\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->userName,
        'email' => $faker->unique()->email,
        'mobile' => '17' . $faker->unique()->randomNumber(9, true),
        'password' => bcrypt('123456'),
        'avatar' => asset('images/avatars/default.jpg'),
        'confirmation_token' => str_random(40),
        'remember_token' => str_random(10)
    ];
});

// Poem
$factory->define(App\Http\Frontend\Models\Poem::class, function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->numberBetween(1, 50),
        'category_id' => $faker->numberBetween(1, 50),
        'title' => $faker->word,
        'summary' => $faker->text(50),
        'body' => $faker->text(200),
        'dynasty' => $faker->randomElement(['夏朝', '商朝', '周朝', '秦朝', '汉代', '晋代', '南北朝', '隋朝', '唐代', '宋代', '元代', '明代', '清代', '当代', '当下']),
        'is_original' => $faker->boolean
    ];
});

// Tag
$factory->define(\App\Http\Frontend\Models\Tag::class, function (\Faker\Generator $faker) {
    return [
        'name' => $faker->unique()->randomElement(['夏朝', '商朝', '周朝', '秦朝', '汉代', '晋代', '南北朝', '隋朝', '唐代', '宋代', '元代', '明代', '清代', '当代', '当下']),
    ];
});

// Comment
$factory->define(\App\Http\Frontend\Models\Comment::class, function (\Faker\Generator $faker) {
    return [
        'user_id' => $faker->numberBetween(1, 50),
        'body' => $faker->sentence,
        'commentable_id' => $faker->numberBetween(1, 50),
        'commentable_type' => 'App\Http\Frontend\Models\Poem'
    ];
});

//  Vote
$factory->define(\App\Http\Frontend\Models\Vote::class, function (\Faker\Generator $faker) {
    return [
        'user_id' => $faker->unique()->numberBetween(1, 50)
    ];
});

//  Profile
$factory->define(\App\Http\Frontend\Models\Profile::class, function (\Faker\Generator $faker) {
    return [
        'user_id' => $faker->unique()->numberBetween(1, 50),
        'nickname' => $faker->userName,
        'gender' => $faker->numberBetween(1, 2),
        'birthday' => $faker->date(),
        'signature' => $faker->sentence,
        'location' => $faker->city,
        'occupation' => $faker->jobTitle,
        'bio' => $faker->text(200),
        'poet' => $faker->userName
    ];
});