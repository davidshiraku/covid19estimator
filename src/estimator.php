<?php

function covid19ImpactEstimator($data)
{
  $result = json_decode($data,true);
  
  //$currentlyInfected = (int)$result["region"]["avgAge"];
  
  $currentlyInfected = $result["reportedCases"] * 10;
  
  $severeImpact = $result["reportedCases"] * 50;
  
  //Normalize days depending on period type
  switch(strtoupper(rtrim($result["periodType"]))){
    case "DAYS":
      $timeelapse = $result["timeToElapse"];
      break;
      
    case "WEEKS":
      $timeelapse = $result["timeToElapse"] * 7;
      break;
      
    case "MONTHS":
      $timeelapse = $result["timeToElapse"] * 30;
      break;
      
  }
  //End of normalizing days depending on period type
  
  //Determination of interval of 3 days
  $interval = $timeelapse / 3;
  //End of determination of interval of 3 days
  
  $currentlyInfectedrequestedtime = $currentlyInfected * (pow(2,$interval));
  $severeImpactrequestedtime = $severeImpact * (pow(2,$interval));
  
  $currentlyInfectedrequestedtimesevere = $currentlyInfectedrequestedtime * 0.15;
  $severeImpactrequestedtimesevere = $severeImpactrequestedtime * 0.15;
  
  $currentlyInfectedhospitalBedsByRequestedTime = $result["totalHospitalBeds"] * 0.35;
  $severeImpacthospitalBedsByRequestedTime = $result["totalHospitalBeds"] * 0.35;
  
  if ($currentlyInfectedhospitalBedsByRequestedTime < $currentlyInfectedrequestedtimesevere){
    $currentlyInfectedhospitalBedsByRequestedTime -= $currentlyInfectedrequestedtimesevere;
  }
  
  if ($severeImpacthospitalBedsByRequestedTime < $severeImpactrequestedtimesevere){
    $severeImpacthospitalBedsByRequestedTime -= $severeImpactrequestedtimesevere;
  }
  
  $currentlyInfected = floor($currentlyInfected);
  $severeImpact = floor($severeImpact);
  $currentlyInfectedrequestedtime = floor($currentlyInfectedrequestedtime);
  $severeImpactrequestedtime = floor($severeImpactrequestedtime);
  $currentlyInfectedrequestedtimesevere = floor($currentlyInfectedrequestedtimesevere);
  $severeImpactrequestedtimesevere = floor($severeImpactrequestedtimesevere);
  $currentlyInfectedhospitalBedsByRequestedTime = floor($currentlyInfectedhospitalBedsByRequestedTime);
  $severeImpacthospitalBedsByRequestedTime = floor($severeImpacthospitalBedsByRequestedTime);
  
  $data = array();
  
  $data = array(
    "data" => array(
      $result
    ),
    "impact"=> array(
      "currentlyInfected" => $currentlyInfected,
      "infectionsByRequestedTime" => $currentlyInfectedrequestedtime,
      "severeCasesByRequestedTime" => $currentlyInfectedrequestedtimesevere,
      "hospitalBedsByRequestedTime" => $currentlyInfectedhospitalBedsByRequestedTime
    ),
    "severeImpact"=> array(
      "currentlyInfected" => $severeImpact,
      "infectionsByRequestedTime" => $severeImpactrequestedtime,
      "severeCasesByRequestedTime" => $severeImpactrequestedtimesevere,
      "hospitalBedsByRequestedTime" => $severeImpacthospitalBedsByRequestedTime
    )
  );
  
  return $data;
}
