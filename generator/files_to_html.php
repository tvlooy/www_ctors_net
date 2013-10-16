<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\SingleCommandApplication;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Twig\Environment as TwigEnvironment;
use Twig\Loader\FilesystemLoader;

(new SingleCommandApplication())
->setCode(function (InputInterface $input, OutputInterface $output) {
    $posts = [];

    $finder = new Finder();
    foreach ($finder->files()->in(__DIR__.'/files') as $file) {
        $content = file($file);
        // '<!--' = 0
        $creation_date = trim(explode(':', $content[1])[1]);
        $permalink = trim(explode(':', $content[2])[1]);
        $title = trim(substr($content[3], strpos($content[3], ':') + 1));
        $keywords = trim(explode(':', $content[4])[1]);
        // '-->' = 5

        for ($i = 0; $i <= 5; $i++) {
            array_shift($content);
        }
        $content = implode('', $content);

        $posts[] = [
            'creation_date' => \DateTime::createFromFormat('Y-m-d', $creation_date),
            'permalink' => $permalink,
            'title' => $title,
            'keywords' => $keywords,
            'body' => $content,
        ];
    }

    usort($posts, static function ($a, $b) {
        return $a['creation_date'] < $b['creation_date'];
    });

    $twig = new TwigEnvironment(new FilesystemLoader(__DIR__.'/templates'));
    $filesystem = new Filesystem();

    foreach ($posts as $post) {
        $dir = __DIR__.'/../docs/'.$post['creation_date']->format('Y/m/d').'/'.$post['permalink'];
        $filesystem->mkdir($dir);
        $filesystem->dumpFile(
            $dir.'/index.html',
            $twig->load('post.html.twig')->render($post)
        );
    }

    $filesystem->dumpFile(
        __DIR__.'/../docs/blog/index.html',
        $twig->load('index.html.twig')->render(['posts' => $posts])
    );

    return 0;
})->run();

