<?php
include_once 'phing/system/io/PhingFile.php';

class yuicompressorTask extends Task
{

    protected $inputFiles     = NULL;
    protected $outputCombined = NULL;
    protected $outputMin      = NULL;

    public function init()
    {
        
    }

    public function main()
    {
        require($this->inputFiles);
        if (!isset($_files)) {
            $this->log(
                    'Variable $_files not defined in ' . $this->inputFiles,
                    Project::MSG_ERR
            );
            return;
        }
        $inputFiles = array();
        $dir = dirname($this->inputFiles) . '/';
        foreach ($_files as $f) {
            $inputFiles[] = new PhingFile($dir . $f);
        }


        $this->log("Combining files to " . $this->outputCombined->getPath(),
                   Project::MSG_VERBOSE);

        // These "slots" allow filters to retrieve information about the currently-being-process files      
        $slot         = $this->getRegisterSlot("currentFile");
        $basenameSlot = $this->getRegisterSlot("currentFile.basename");

        $contents = "";
        foreach ($inputFiles as $file) {
            // set the register slots
            $slot->setValue($file->getPath());
            $basenameSlot->setValue($file->getName());

            $in = NULL;
            try {
                $in     = new FileReader($file);
                while (-1 !== ($buffer = $in->read())) {
                    $contents .= $buffer;
                }
                $in->close();
            } catch (Exception $e) {
                if ($in) $in->close();
                $this->log(
                        "Error reading file: " . $e->getMessage(),
                        Project::MSG_ERR
                );
                return;
            }
            $contents .= PHP_EOL;
        }

        $out = NULL;
        try {
            $out = new FileWriter($this->outputCombined);
            $out->write($contents);
            $out->close();
        } catch (Exception $e) {
            if ($out) $out->close();
            $this->log(
                    "Error writing file: " . $e->getMessage(), Project::MSG_ERR
            );
            return;
        }


        $this->log(
                "Minimizing files to " . $this->outputMin->getPath(),
                Project::MSG_VERBOSE
        );

        exec('java -jar ' . __DIR__ . '/yuicompressor-2.4.7.jar -o ' . $this->outputMin->getPath() . ' ' . $this->outputCombined->getPath());
    }

    public function setInputfiles(PhingFile $files)
    {
        $this->inputFiles = $files;
    }

    public function setOutputCombined(PhingFile $file)
    {
        $this->outputCombined = $file;
    }

    public function setOutputMin(PhingFile $file)
    {
        $this->outputMin = $file;
    }
}