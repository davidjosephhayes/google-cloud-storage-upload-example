<?php

require_once __DIR__ .'/vendor/autoload.php';

use Google\Cloud\Storage\StorageClient;
use google\appengine\api\cloud_storage\CloudStorageTools;

//   
$gsurl = upload_object('test-kcc', 'second-file', '/home/david/Downloads/House-GOP-Tax-Cuts-Job-Act.pdf');
$publicUrl = CloudStorageTools::getPublicUrl($gsurl, true);

echo $gsurl.' '.$publicUrl;
    
function upload_object($bucketName, $objectName, $source)
{
    $storage = new StorageClient([
        'keyFilePath' => __DIR__ .'/key.json'
    ]);
    $file = fopen($source, 'r');
    $bucket = $storage->bucket($bucketName);
    $object = $bucket->upload($file, [
        'name' => $objectName
    ]);
    $object->update(['acl' => []], ['predefinedAcl' => 'PUBLICREAD']);
    $gsurl = sprintf('gs://%s/%s',$bucketName, $objectName);
    return $gsurl;
}