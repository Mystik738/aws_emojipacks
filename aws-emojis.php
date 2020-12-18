<?php

$file = $argv[1];

if(file_exists($file)) {
    // get the absolute path to $file
    $path = pathinfo(realpath($file), PATHINFO_DIRNAME);

    $zip = new ZipArchive;
    $res = $zip->open($file);
    if ($res === TRUE) {
        $zip->extractTo($path);
        $zip->close();
    } else {
        die("Could not extract file");
    }

    //A few "constants"
    $dir = substr($file, 0, strpos($file, '.'));
    //Directory structure changed, we need to get the Service icons
    foreach(scandir($dir) as $elem) {
        if(strpos($elem, "ervice") !== false) {
            $dir = $dir."/".$elem."/";
            break;
        }
    }

    $emoji_dir = 'emojis/';
    $emoji_url = 'https://raw.githubusercontent.com/Mystik738/aws_emojipacks/master/emojis/';

    $subdirs = scandir($dir);
    $pattern = '/Arch_(AWS-|Amazon-)?(.*)_64@5x\.png/';

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
        'quantumledgerdatabaseqldb' => 'qldb',
        'commandlineinterface' => 'cli',
        'appstream20' => 'appstream',
        'applicationdiscoveryservice' => 'ads',
        'identityandaccessmanagementiam' => 'iam',
        'keymanagementservice' => 'kms',
        'singlesignon' => 'sso',
        'elasticblockstoreebs' => 'ebs',
        'elasticfilesystemefs' => 'efs',
        'simplestorageservices3' => 's3',
        's3glacier' => 'glacier',
        'elasticsearchservice' => 'ess',
        'elasticloadbalancing' => 'elb',
        'internetofthings' => 'iot',
        'securityidentityandcompliance' => 'sic',
    );

    //If there are any conflicts with stock emojis, list them here so they'll get prefixed regardless.
    $conflicts = array(
        'shield',
        'satellite',
    );

    //If there are any emojis that we want to skip, list them here so they'll get skipped
    $skip = array(
        'groupnamelightbgcopy5'
    );

    $emojis = array();

    //The .zip has a subdirectory structure, so we need to traverse it.
    foreach($subdirs as $subdir) {
        //If this is a directory we're interested in
        if(! in_array($subdir, array('.','..', '.DS_Store')) && !is_file($subdir)) {
            //Get all the files
            $dir64 = $dir.$subdir."/Arch_64";
            if(!is_dir($dir64)) {
                $dir64 = $dir.$subdir."/64";
            }
            $files = scandir($dir64);

            //Traverse through the files
            foreach($files as $file) {
                //If the file matches the pattern we're looking for, turn it into an emoji
                if(preg_match($pattern, $file, $match)) {
                    //Filter bad characters
                    $emoji = strtolower($match[2]);
                    if(preg_filter('/[^a-z0-9]/','',$emoji) != false)
                        $emoji = preg_filter('/[^a-z0-9]/','',$emoji);

                    //If it's in the manual map above, replace it.
                    if(isset($map[$emoji])) {
                        $emoji = $map[$emoji];
                    }

                    if(!in_array($emoji, $skip)) {
                        //Add it to our array of emojis
                        if(!isset($emojis[$emoji])) {
                            $emojis[$emoji] = $emoji;

                            //move it to our emoji directory.
                            $file_loc =  $dir64.'/'.$file;
                            copy($file_loc, $emoji_dir.$emoji.".png");
                        }
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
        fwrite($yml, "- name: aws-$emoji\n  src: $emoji_url".$emoji.".png\n");
        $name = $emoji;
        if(in_array($emoji, $conflicts)) {
            $name = 'aws-'.$emoji;
        }

        fwrite($npyml, "- name: $name\n  src: $emoji_url".$emoji.".png\n");
    }
    fclose($npyml);
    fclose($yml);

} else {
    die("Zip file does not exist");
}

?>