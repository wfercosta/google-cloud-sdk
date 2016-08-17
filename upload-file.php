<?php

require_once __DIR__ . '/vendor/autoload.php';


if (empty($argv[1]) || empty($argv[2])) {
    die("usage: php upload-file [project_id] [file_name]\n");
}

$projectId = $argv[1];
$filename = $argv[2];


$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->addScope(Google_Service_Storage::DEVSTORAGE_FULL_CONTROL);
$storage = new Google_Service_Storage($client);
$buckets = $storage->buckets->listBuckets($projectId);

foreach ($buckets['items'] as $bucket) {
    $selectedBucket =  $bucket->getName();
    break;
}

try {

  $content = file_get_contents($filename);

  $postbody = array(
			'name' => $filename,
			'data' => $content,
			'uploadType' => "media",
  );

	$gsso = new Google_Service_Storage_StorageObject();
	$gsso->setName( $filename );
	$result = $storage->objects->insert( $selectedBucket, $gsso, $postbody );

} catch (Exception $e) {
  print $e->getMessage();
}
