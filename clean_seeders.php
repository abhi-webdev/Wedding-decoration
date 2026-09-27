<?php

// 1. Clean AdityaUtsavSeeder.php
$adityaPath = __DIR__ . '/database/seeders/AdityaUtsavSeeder.php';
$content = file_get_contents($adityaPath);
$content = preg_replace("/'image_url'\s*=>\s*'[^']+'/", "'image_url' => null", $content);
$content = preg_replace("/'primary_image'\s*=>\s*'[^']+'/", "'primary_image' => null", $content);
$content = preg_replace("/'image'\s*=>\s*'[^']+'/", "'image' => null", $content);
$content = preg_replace("/'thumbnail_path'\s*=>\s*'[^']+'/", "'thumbnail_path' => null", $content);
file_put_contents($adityaPath, $content);
echo "AdityaUtsavSeeder.php cleaned.\n";

// 2. Clean Phase5Seeder.php
$p5Path = __DIR__ . '/database/seeders/Phase5Seeder.php';
$p5 = file_get_contents($p5Path);
$p5 = preg_replace("/'image_url'\s*=>\s*'[^']+'/", "'image_url' => null", $p5);
$p5 = preg_replace("/'image'\s*=>\s*'[^']+'/", "'image' => null", $p5);
file_put_contents($p5Path, $p5);
echo "Phase5Seeder.php cleaned.\n";

// 3. Clean Phase8VideoSeeder.php
$p8Path = __DIR__ . '/database/seeders/Phase8VideoSeeder.php';
$p8 = file_get_contents($p8Path);
$p8 = preg_replace("/'thumbnail_path'\s*=>\s*'[^']+'/", "'thumbnail_path' => null", $p8);
$p8 = preg_replace("/'video_path'\s*=>\s*'[^']+'/", "'video_path' => null", $p8);
file_put_contents($p8Path, $p8);
echo "Phase8VideoSeeder.php cleaned.\n";
