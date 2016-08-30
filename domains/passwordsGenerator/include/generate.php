<?php
class GeneratePasswords{
    private $configArray = array(), $errorMessage = '', $output = '';
    private $listOfAllAvailableChars = array(
        'lower'         => 'abcdefghijklmnopqrstuvwxyz',
        'capital'       => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        'digits'        => '0123456789',
        'special'       => '`~@#$%^&*()-_=+[]{}\|<>`\'"',
        'punctuation'   => '.,?!;:'
    );
    private $continueChars = array();
    private $listOfChartToGenerate = array();

    public function __construct($var){

        if(isset($_POST['generate'])){
            $passwordLength                         = $this->checkVariableForConfig('passwordLength',   'int');
            if($passwordLength>300){
                $passwordLength = 300;
                $this->errorMessage .= 'Maximum password length is 300 chars<br />';
            }
            $this->configArray['passwordLength']    = $passwordLength;
            $passwordNumber                         = $this->checkVariableForConfig('passwordNumber',   'int');
            if($passwordNumber>300){
                $passwordNumber = 50;
                $this->errorMessage .= 'Maximum amount of passwords is 50<br />';
            }
            $this->configArray['passwordNumber']    = $passwordNumber;
            $this->configArray['lowerChars']        = $this->checkVariableForConfig('lowerChars',       'bool');
            $this->configArray['capitalChars']      = $this->checkVariableForConfig('capitalChars',     'bool');
            $this->configArray['digitsChars']       = $this->checkVariableForConfig('digitsChars',      'bool');
            $this->configArray['specialChars']      = $this->checkVariableForConfig('specialChars',     'bool');
            $this->configArray['punctuationChars']  = $this->checkVariableForConfig('punctuationChars', 'bool');
        }else {
            $this->configArray = $var;
        }
    }
    public function getErrorMessage(){
        if(strlen($this->errorMessage)>0) {
            return '<div class="error-message">' . $this->errorMessage . '</div>';
        }
    }
    public function getOutput(){
        return $this->output;
    }
    private function checkVariableForConfig($name, $type){
        if(isset($_POST[$name])){
            switch ($type) {
                case 'int':
                    return (trim(addslashes(htmlspecialchars($_POST[$name]))) +0);
                case 'bool':
                    return true;
            }
        }
        return false;
    }
    public function getValue($name){
        return $this->configArray[$name];
    }
    public function generate(){
        $o = '<div class="outputTextarea">';
        if($this->configArray['passwordLength']<5){
            $o .= 'Min password length is 5 chars';
            $this->configArray['passwordLength'] = 5;
        }
        if($this->configArray['passwordNumber']<1){
            $o .= 'Minimal passwords number to create is 1';
            $this->configArray['passwordNumber'] = 1;
        }
        $o .= $this->addCharLists();
        for($i=1;$i<=$this->configArray['passwordNumber'];$i++){
//            $o .= $i.' ';
            $o .= htmlspecialchars($this->createSinglePassword()).'<br />';
        }
        $o .= '</div>';
//        $o .= print_r($this->listOfChartToGenerate,true);
        $this->output = $o;
    }

    private function addCharLists(){
        $o = '';
        $listToGenerateFound = false;
        if($this->configArray['lowerChars'] == 1){
            $listToGenerateFound = true;
            $this->listOfChartToGenerate['lower'] = $this->listOfAllAvailableChars['lower'];
        }
        if($this->configArray['capitalChars'] == 1){
            $listToGenerateFound = true;
            $this->listOfChartToGenerate['capital'] = $this->listOfAllAvailableChars['capital'];
        }
        if($this->configArray['digitsChars'] == 1){
            $listToGenerateFound = true;
            $this->listOfChartToGenerate['digits'] = $this->listOfAllAvailableChars['digits'];
        }
        if($this->configArray['specialChars'] == 1){
            $listToGenerateFound = true;
            $this->listOfChartToGenerate['special'] = $this->listOfAllAvailableChars['special'];
        }
        if($this->configArray['punctuationChars'] == 1){
            $listToGenerateFound = true;
            $this->listOfChartToGenerate['punctuation'] = $this->listOfAllAvailableChars['punctuation'];
        }
        if(!$listToGenerateFound){
            $this->errorMessage .= 'No groups to generate passwords - added all<br />';
            $this->listOfChartToGenerate = $this->listOfAllAvailableChars;
        }
        return $o;
    }


    private function createSinglePassword(){
        $password = '';
        for($i=1;$i<=$this->configArray['passwordLength'];$i++){
            $password .= $this->getSingleChar();
        }
        return $password;
    }
    private function getSingleChar(){
        $arrayKey = array_rand($this->listOfChartToGenerate);
        if(isset($this->continueChars['name']) && $this->continueChars['name']==$arrayKey && $this->continueChars['inRow']>4 && count($this->listOfChartToGenerate)>1){
            $skipKey = $arrayKey;
            $attempts = 0;
            do{
                $arrayKey = array_rand($this->listOfChartToGenerate);
                $attempts++;
            }while ($skipKey == $arrayKey && $attempts<=100);
        }
        $valLength = strlen($this->listOfChartToGenerate[$arrayKey]);
        if(isset($this->continueChars['name']) && $this->continueChars['name']==$arrayKey){
            $this->continueChars['inRow'] = $this->continueChars['inRow'] + 1;
        }else{
            $this->continueChars['name'] = $arrayKey;
            $this->continueChars['inRow'] = 1;
        }
        return mb_substr($this->listOfChartToGenerate[$arrayKey],rand(0,$valLength - 1),1);
    }
}