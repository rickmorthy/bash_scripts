<?php 
$path = $argv[1];
$template_file = "Brailko_A_CV_sample.docx";
$company_name = $file_name = $argv[2];
$pos = strpos($company_name, '%');
// echo $pos;
// var_dump(preg_replace('/%/i',' ',$company_name));
// var_dump(preg_filter('/%/i',' ',$company_name));

// var_dump($pos);
if($pos !== false){
    echo 'HERE';
    $company_name = preg_filter('/%/i',' ',$company_name);
    var_dump($company_name);
}
$today_date = date('m/d/Y');
$new_file = 'Brailko_A_CV_' . $file_name . '.docx';
// $folder   = "results_";
$full_path = $path . '/Brailko_A_CV_' . $file_name . '.docx';
 echo $full_path;
try
{
    if (!file_exists($full_path))
    {
        copy($template_file, $full_path);
        // mkdir($folder);
    }else{
        echo "File " . $file_name . ' already exists.';
    }
         
    //Copy the Template file to the Result Directory
 
    // add calss Zip Archive
    $zip_val = new ZipArchive;
 
    //Docx file is nothing but a zip file. Open this Zip File
    if($zip_val->open($new_file) == true)
    {
        // In the Open XML Wordprocessing format content is stored.
        // In the document.xml file located in the word directory.
         
        $key_file_name = 'word/document.xml';
        $message = $zip_val->getFromName($key_file_name);                
                     
        // $timestamp = date('d-M-Y H:i:s');
        
        // this data Replace the placeholders with actual values
        $message = str_replace("[Date]", $today_date,       $message);
        $message = str_replace("[Name]",  "Andrey Brailko",  $message);
        $message = str_replace(["[COMPANY]","[COMPANY],"],  $company_name,             $message); 
        // var_dump($argv[3])     ;
        if (!empty($argv[3])) {
            $message = str_replace("[Address]", $argv[3], $message);      
        }else{
            $message = str_replace("[Address]", '', $message);      

        }
        if (!empty($argv[4])) {
            $message = str_replace("[Regard]",    'Dear '.$argv[4].',', $message);      
        }else{
            $message = str_replace("[Regard]", '', $message);      
        }
        
        //Replace the content with the new content created above.
        // var_dump($message);
        $zip_val->addFromString($key_file_name, $message);
        $zip_val->close();
    }
}
catch (Exception $exc) 
{
    $error_message =  "Error creating the Word Document";
    var_dump($exc);
}
?>