<?php
/**
 * Class TranslationMigrationTask
 */
class TranslationMigrationTask extends BuildTask
{
    public function run($request)
    {
        set_time_limit(60000);

        $path = $_SERVER['DOCUMENT_ROOT'] . '/' . $request->requestVar('path');

        $directory = new DirectoryIterator($path);
        foreach ($directory as $fileinfo) {
            if ($fileinfo->isFile()) {
                $extension = strtolower(pathinfo($fileinfo->getFilename(), PATHINFO_EXTENSION));

                if ($extension == 'ss') {
                    $filePath = $fileinfo->getPathname();
                    $template = file_get_contents($filePath);

                    if($template != ''){
                        $newTemplate = $this->parseAndReplaceTranslatables($template);

                        $fileHandler = fopen($filePath,"w");
                        fwrite($fileHandler, $newTemplate);
                        fclose($fileHandler);

                        echo 'converted ' . $filePath . '<br><br>';
                    }
                }
            }
        }

        echo 'finished';
    }

    public function parseAndReplaceTranslatables($template){
        // match all <%t ... %>
        preg_match_all("/(<%t)(?<=<%t).*?(?=%>)(%>)/", $template, $matches);
        if(isset($matches[0]) && $translatables = $matches[0]){

            foreach($translatables as $t){
                $parts = str_getcsv($t, ' ', "'");
                $data = array_slice($parts, 1, -1);

                $entity = isset($data[0]) ? $data[0] : false;
                $string = isset($data[1]) ? $data[1] : false;
                $injections = array_slice($data, 2);

                $languageFunctionString = '$TranslatePhrase(\'' . $entity . '\'';
                if($string){
                    $languageFunctionString .= ',\'' . $string . '\'';
                }

                if(!empty($injections)){
                    foreach($injections as $injection){
                        $injectionPair = explode('=', $injection);
                        if(isset($injectionPair[0]) && isset($injectionPair[1])){
                            $languageFunctionString .= ',\'' . $injectionPair[0] . '\'';
                            $languageFunctionString .= ',' . $injectionPair[1];
                        }
                    }
                }

                $languageFunctionString .= ')';

                // replace with new function
                return preg_replace("/(<%t)(?<=<%t).*?(?=%>)(%>)/", $languageFunctionString, $template);
            }
        }

        // return original template if no translation was found
        return $template;
    }
}