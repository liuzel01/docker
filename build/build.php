<?php

include __DIR__ . '/WorkflowBuilder/GithubWorkflowBuilder.php';


buildManifestImageJobs();
build58shuweiVersionJob();
buildCustomImageJob();


/**
 *
 */
function buildManifestImageJobs()
{
    $builder = new GithubWorkflowBuilder();

    $manifest = file_get_contents(__DIR__ . '/../manifest.json');
    $manifestJson = json_decode($manifest, true);

    foreach ($manifestJson['images'] as $image => $variants) {

        $yml = 'name: ' . $image . ' (All)

on:
  workflow_dispatch:

jobs:';

        foreach ($variants as $variant) {

            $tag = explode(':', $variant)[1];

            $job = $builder->buildJob(
                $image . '-' . str_replace('.', '-', $tag),
                $image,
                $tag);

            $yml .= "\n  " . $job;
        }

        file_put_contents(
            __DIR__ . '/../.github/workflows/' . $image . '.yml',
            $yml
        );

    }
}


/**
 *
 */
function build58shuweiVersionJob()
{
    $builder = new GithubWorkflowBuilder();

    $yml = 'name: 58shuwei Version
run-name: 58shuei ${{ github.event.inputs.tagName }}

on:
  workflow_dispatch:
    inputs:
      tagName:
        description: \'Tag Name\'
        required: true
        
jobs:';

    $job = $builder->buildJob(
        'build-play',
        'play',
        '${{ github.event.inputs.tagName }}'
    );

    $yml .= "\n  " . $job;

    $job = $builder->buildJob(
        'build-dev',
        'dev',
        '${{ github.event.inputs.tagName }}'
    );

    $yml .= "\n  " . $job;

    file_put_contents(
        __DIR__ . '/../.github/workflows/shuwei.yml',
        $yml
    );
}

/**
 *
 */
function buildCustomImageJob()
{
    $builder = new GithubWorkflowBuilder();

    $yml = 'name: Custom Image
run-name: 58shuwei ${{ github.event.inputs.tagName }}

on:
  workflow_dispatch:
    inputs:
      imageName:
        description: \'Image Name\'
        required: true
      tagName:
        description: \'Tag Name\'
        required: true
        
jobs:';

    $job = $builder->buildJob(
        'build',
        '${{ github.event.inputs.imageName }}',
        '${{ github.event.inputs.tagName }}'
    );

    $yml .= "\n  " . $job;

    file_put_contents(
        __DIR__ . '/../.github/workflows/image-build.yml',
        $yml
    );
}