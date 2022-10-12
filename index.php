<?php

$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

function getFullnameFromParts($surname, $name, $fname)
{
    $longName = $surname." ".$name." ".$fname;
    // echo $longName;
    return $longName;
}

function getPartsFromFullname($longName)
{
    $partList = explode(" ", $longName);
    $nameArr = array('surname' =>  $partList[0],'name' => $partList[1], 'patronomyc' => $partList[2]);
    // print_r($nameArr);
    return $nameArr;
}

function getShortName($longName){
    
    $tmpArr = getPartsFromFullname($longName);
    $name = $tmpArr['name'];
    $surname = $tmpArr['surname'];
    
    $sSurname = mb_substr($surname, 0,1,'utf-8');
    $result =  $name." ".$sSurname. ".";
    // echo $result;
    return $result;

}

function getGenderFromName($longName){
    $tmpArr = getPartsFromFullname($longName);

    // $lastSur = mb_substr($tmpArr['surname'], -1, -1,'utf-8');
    $lastSur = mb_substr($tmpArr['surname'], -1);
    $lastName = mb_substr($tmpArr['name'], -1);
    $lastFath = mb_substr($tmpArr['patronomyc'], -2);

    $iniGen = 0;
    
    if($lastFath=='ич'){
        $iniGen = $iniGen+1;
    }
    elseif($lastFath=='на'){
        $iniGen = $iniGen-1;
    }
    if($lastName=='й' || $lastName=='н'){
        $iniGen = $iniGen+1;
    }
    elseif($lastName=='а'){
        $iniGen = $iniGen-1;
    }
    if($lastSur=='в'){
        $iniGen = $iniGen+1;
    }
    elseif($lastSur=='а'){
        $iniGen = $iniGen-1;
    }

    if ($iniGen<0){
        return 'f';
    }
    elseif($iniGen>0){
        return 'm';
    }
    elseif($iniGen==0){
        return 'u';
    }

}

function getGenderDescription($example_persons){


    function checkMale($cur_person){
        return getGenderFromName($cur_person["fullname"])=='m';
    }
    function checkFemale($cur_person){
        return getGenderFromName($cur_person["fullname"])=='f';
    }
    function checkU($cur_person){
        return getGenderFromName($cur_person["fullname"])=='u';
    }

    $maleArray = array_filter($example_persons, 'checkMale');
    $femaleArray = array_filter($example_persons, 'checkFemale');
    $undefArray = array_filter($example_persons, 'checkU');

    $mCounter = round(100*count($maleArray)/count($example_persons),2);
    $fCounter = round(100*count($femaleArray)/count($example_persons),2);
    $uCounter = round(100*count($undefArray)/count($example_persons),2);



    header('Content-type: text/plain');
    echo "Гендерный состав аудитории:\r\n";
    echo "___________________________\r\n";

    echo "Мужчины - $mCounter%\r\n";
    echo "Женщины - $fCounter%\r\n";
    echo "Не удалось определить - $uCounter%\r\n";


}

function getPerfectPartner($surName,$name,$fName, $example_persons){
    $longName = getFullnameFromParts($surName, $name, $fName);
    $longName = mb_convert_case($longName, MB_CASE_TITLE, "UTF-8");
    $gender = getGenderFromName($longName);

    $ok = 0;

    while($ok == 0){
        $randID = array_rand($example_persons, 1);
        $randPerson = $example_persons[$randID];
        $randGender = getGenderFromName($randPerson["fullname"]);
        
        if ($gender != $randGender){
            // оставим шанс неопределившимся
            $ok = 1;
        }
    }
    $percent = round(mt_rand(5000, 10000)/100,2);
    header('Content-type: text/plain');
    echo getShortName($longName)." + ".getShortName($randPerson["fullname"])." = \r\n";
    echo "♥ Идеально на $percent% ♥\r\n";
    
    

}

getGenderDescription($example_persons_array);
getPerfectPartner("петрОв","пеТР","ПеТрОвИч",$example_persons_array);

?>