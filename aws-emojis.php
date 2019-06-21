<?php

//A few "constants"
$info = posix_getpwuid(posix_getuid());
$dir = $info['dir'].'/Downloads/AWS-Architecture-Icons_PNG/PNG Light/';
$emoji_dir = 'emojis/';
$emoji_url = 'https://raw.githubusercontent.com/Mystik738/aws_emojipacks/master/emojis/';

$subdirs = scandir($dir);
$pattern = '/(AWS|Amazon)-(.*)(?<!(bg|_1))@4x\.png/';

//Some of these come out with bad names, so this is a manual fix
$map = array(
    'simplenotificationservicesns' => 'sns',
    'simplequeueservicesqs' => 'sqs',
    'ec2containerregistry' => 'ecr',
    'elasticcontainerserviceforkubernetes' => 'ecs4k',
    'elasticcontainerservice' => 'ecs',
    'simpleemailserviceses' => 'ses',
    'databasemigrationservice' => 'dms',
    'databasemigrationservice' => 'dms',
    'quantumledgerdatabase_qldb' => 'qldb',
    'commandlineinterface' => 'cli',
    'appstream2.0' => 'appstream',
    'applicationdiscoveryservice' => 'ads',
    'identityandaccessmanagement_iam' => 'iam',
    'keymanagementservice' => 'kms',
    'resourceaccessmanager' => 'ram',
    'singlesignon' => 'sso',
    'elasticblockstoreebs' => 'ebs',
    'elasticfilesystem_efs' => 'efs',
    'simplestorageservices3' => 's3',
    's3glacier' => 'glacier',
    'elasticsearchservice' => 'es',
);

$emojis = array();

//The .zip has a subdirectory structure, so we need to traverse it.
foreach($subdirs as $subdir) {
    //If this is a directory we're interested in
    if(! in_array($subdir, array('.','..')) && !is_file($subdir)) {
        //Get all the files
        $files = scandir($dir.$subdir);

        //Traverse through the files
        foreach($files as $file) {
            //If the file matches the pattern we're looking for, turn it into an emoji
            if(preg_match($pattern, $file, $match)) {
                //Don't like dashes
                $emoji = utf8_encode(str_replace("-", "", strtolower($match[2])));
                //If it's in the manual map above, replace it.
                if(isset($map[$emoji])) {
                    $emoji = $map[$emoji];
                }

                //Add it to our array of emojis
                if(!isset($emojis[$emoji])) {
                    $emojis[$emoji] = $emoji;

                    //move it to our emoji directory.
                    $file_loc =  $dir.$subdir.'/'.$file;
                    copy($file_loc, $emoji_dir.$emoji.".png");
                }
            }
        }

    }
}

//Sort alphabetically, just to make the .yml files look nice.
sort($emojis);

//Write our yaml files
$npyml = fopen('noprefix-emojipacks.yml', 'w');
$yml = fopen('aws-emojipacks.yml', 'w');
fwrite($npyml, "emojis:\n");
fwrite($yml, "emojis:\n");
foreach($emojis as $emoji) {
    fwrite($npyml, "- name: $emoji\n  src: $emoji_url".$emoji.".png\n");
    fwrite($yml, "- name: aws-$emoji\n  src: $emoji_url".$emoji.".png\n");
}
fclose($npyml);
fclose($yml);

?>