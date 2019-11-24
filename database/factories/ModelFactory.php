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
$factory->define(leck\User::class, function (Faker\Generator $faker) {
    static $password;
    $ffn = $faker->firstName;
    $fln = $faker->lastName;

    return [
        'str_id' => strtolower($ffn.'.'.$fln),
        'nickname' => $ffn.' '.$fln,
        'bio' => $faker->sentence($nbWords = rand(0, 14), $variableNbWords = true),
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'avatar' => 'a'.(string)rand(1, 10),
        'remember_token' => str_random(10)
    ];
});


$factory->define(leck\Follow::class, function (Faker\Generator $faker) {
    return [
        'track' => null
    ];
});

$factory->define(leck\Tag::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->unique()->word()
    ];
});

$factory->define(leck\Writing_tag::class, function (Faker\Generator $faker) {
    return [
        'tag_id' => rand(1, 27)
    ];
});

$factory->define(leck\Writing::class, function (Faker\Generator $faker) {
  $codes = ['ab', 'aa', 'af', 'ak', 'sq', 'am', 'ar', 'an', 'hy', 'as', 'av', 'ae', 'ay', 'az', 'bm', 'ba', 'eu', 'be', 'bn', 'bh', 'bi', 'bs', 'br', 'bg', 'my', 'ca', 'km', 'ch', 'ce', 'ny', 'zh', 'cu', 'cv', 'kw', 'co', 'cr', 'hr', 'cs', 'da', 'dv', 'nl', 'dz', 'en', 'eo', 'et', 'ee', 'fo', 'fj', 'fi', 'fr', 'ff', 'gd', 'gl', 'lg', 'ka', 'de', 'ki', 'el', 'kl', 'gn', 'gu', 'ht', 'ha', 'he', 'hz', 'hi', 'ho', 'hu', 'is', 'io', 'ig', 'id', 'ia', 'ie', 'iu', 'ik', 'ga', 'it', 'ja', 'jv', 'kn', 'kr', 'ks', 'kk', 'rw', 'kv', 'kg', 'ko', 'kj', 'ku', 'ky', 'lo', 'la', 'lv', 'lb', 'li', 'ln', 'lt', 'lu', 'mk', 'mg', 'ms', 'ml', 'mt', 'gv', 'mi', 'mr', 'mh', 'ro', 'mn', 'na', 'nv', 'nd', 'ng', 'ne', 'se', 'no', 'nb', 'nn', 'ii', 'oc', 'oj', 'or', 'om', 'os', 'pi', 'pa', 'ps', 'fa', 'pl', 'pt', 'qu', 'rm', 'rn', 'ru', 'sm', 'sg', 'sa', 'sc', 'sr', 'sn', 'sd', 'si', 'sk', 'sl', 'so', 'st', 'nr', 'es', 'su', 'sw', 'ss', 'sv', 'tl', 'ty', 'tg', 'ta', 'tt', 'te', 'th', 'bo', 'ti', 'to', 'ts', 'tn', 'tr', 'tk', 'tw', 'ug', 'uk', 'ur', 'uz', 've', 'vi', 'vo', 'wa', 'cy', 'fy', 'wo', 'xh', 'yi', 'yo', 'za', 'zu'];

  $date = Carbon\Carbon::now(config('app.timezone'))->subDays(rand(0, 40))->toDateTimeString();


  return [
    'id' => leck\Writing::uid(9),
    'title' => $faker->sentence($nbWords = 6, $variableNbWords = true),
    'description' => $faker->sentence($nbWords = rand(0, 20), $variableNbWords = true),
    'lang' => $codes[$faker->numberBetween(0,count($codes)-1)],
    'cover' => 'c'.(string)rand(0,10),
    'str_id' => str_replace(' ', '_', $faker->name),
    'public' => 1,
    'published_at' => $date,
  ];
});


$factory->define(leck\Writing_file::class, function (Faker\Generator $faker) {
  return [
    'position_after' => null,
    'atribute' => null,
    'paragraph_id' => null,
  ];
});


$factory->define(leck\Writing_privilege::class, function (Faker\Generator $faker) {
  return [
    // 'user_id' => leck\Writing::uid(9),
    // 'writing_id' => $faker->sentence($nbWords = 6, $variableNbWords = true),
    'type' => 'author',
    'public' => 1,
  ];
});



$factory->define(leck\Section::class, function (Faker\Generator $faker) {

  return [
    'id' => leck\Section::uid(9),
    'title' => $faker->sentence($nbWords = 2, $variableNbWords = true),
  ];
});


$factory->define(leck\Split::class, function (Faker\Generator $faker) {
  return [
    'id' => leck\Split::uid(9),
    'title' => $faker->sentence($nbWords = rand(1, 7), $variableNbWords = true),
  ];
});


$factory->define(leck\Writing_section::class, function (Faker\Generator $faker) {

  return [
    'writing_id' => leck\Section::uid(9),
    'section_id' => $faker->sentence($nbWords = 2, $variableNbWords = true),
  ];
});


$factory->define(leck\Paragraph::class, function (Faker\Generator $faker) {

  return [
    'id' => $faker->bothify('?#?#?#'),
    // 'section_id' => leck\Paragraph::uid(9),
    'content' => $faker->text,
    'position_after' => $faker->bothify('?#?#?#')
  ];
});


$factory->define(leck\Like::class, function (Faker\Generator $faker) {
    return [
        'likes' => rand(1, 10)
    ];
});
