<?php

$factory->define(SimpleFileStorage\Eloquent\TestModel::class, function (Faker\Generator $faker) {

    $path = $faker->image();
    $original_name = "test.jpg";
    $mime_type = "image/jpeg";
    $size = 777;
    $error = null;
    $test = true;
    
    $file_container = new SimpleFileStorage\SFSFacadeL52;
    $id = $file_container->save(new \Symfony\Component\HttpFoundation\File\UploadedFile($path, $original_name, $mime_type, $size, $error, $test));
    
    return [
        'name' => $faker->word,
        'picture' => $id
    ];
});
