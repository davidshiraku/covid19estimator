<?php

function covid19ImpactEstimator($data)
{
  //$result = json_decode($data,true);
  $result = $data;
  
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
  $interval = sprintf('%.0f', $timeelapse / 3);
  //End of determination of interval of 3 days
  
  /*$currentlyInfectedrequestedtime = $currentlyInfected * (pow(2,$interval));
  $severeImpactrequestedtime = $severeImpact * (pow(2,$interval));
  */
  $currentlyInfectedrequestedtime = $currentlyInfected * (2 ** $interval);
  $severeImpactrequestedtime = $severeImpact * (2 ** $interval);
  
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
  
  $currentlyInfectedcasesForICUByRequestedTime = $currentlyInfectedrequestedtime * 0.05;
  $severeImpactcasesForICUByRequestedTime = $severeImpactrequestedtime * 0.05;
  
  $currentlyInfectedcasesForVentilatorsByRequestedTime = $currentlyInfectedrequestedtime * 0.02;
  $severeImpactcasesForVentilatorsByRequestedTime = $severeImpactrequestedtime * 0.02;
  
  $currentlyInfecteddollarsInFlight = $currentlyInfectedrequestedtime * $result["region"]["avgDailyIncomePopulation"] * $result["region"]["avgDailyIncomeInUSD"] * $timeelapse;
  $severeImpactdollarsInFlight = $severeImpactrequestedtime * $result["region"]["avgDailyIncomePopulation"] * $result["region"]["avgDailyIncomeInUSD"] * $timeelapse;
  
  $currentlyInfected = sprintf('%.0f', $currentlyInfected);
  $severeImpact = sprintf('%.0f', $severeImpact);
  $currentlyInfectedrequestedtime = sprintf('%.0f', $currentlyInfectedrequestedtime);
  $severeImpactrequestedtime = sprintf('%.0f', $severeImpactrequestedtime);
  $currentlyInfectedrequestedtimesevere = sprintf('%.0f', $currentlyInfectedrequestedtimesevere);
  $severeImpactrequestedtimesevere = sprintf('%.0f', $severeImpactrequestedtimesevere);
  $currentlyInfectedhospitalBedsByRequestedTime = sprintf('%.0f', $currentlyInfectedhospitalBedsByRequestedTime);
  $severeImpacthospitalBedsByRequestedTime = sprintf('%.0f', $severeImpacthospitalBedsByRequestedTime);
  $currentlyInfectedcasesForICUByRequestedTime = sprintf('%.0f', $currentlyInfectedcasesForICUByRequestedTime);
  $severeImpactcasesForICUByRequestedTime = sprintf('%.0f', $severeImpactcasesForICUByRequestedTime);
  $currentlyInfectedcasesForVentilatorsByRequestedTime = sprintf('%.0f', $currentlyInfectedcasesForVentilatorsByRequestedTime);
  $severeImpactcasesForVentilatorsByRequestedTime = sprintf('%.0f', $severeImpactcasesForVentilatorsByRequestedTime);
  $currentlyInfecteddollarsInFlight = sprintf('%.0f', $currentlyInfecteddollarsInFlight);
  $severeImpactdollarsInFlight = sprintf('%.0f', $severeImpactdollarsInFlight);
  
  $data = array();
  
  $data = array(
    "data" => array(
      $result
    ),
    "impact"=> array(
      "currentlyInfected" => $currentlyInfected,
      "infectionsByRequestedTime" => $currentlyInfectedrequestedtime,
      "severeCasesByRequestedTime" => $currentlyInfectedrequestedtimesevere,
      "hospitalBedsByRequestedTime" => $currentlyInfectedhospitalBedsByRequestedTime,
      "casesForICUByRequestedTime" => $currentlyInfectedcasesForICUByRequestedTime,
      "casesForVentilatorsByRequestedTime" => $currentlyInfectedcasesForVentilatorsByRequestedTime,
      "dollarsInFlight" => $currentlyInfecteddollarsInFlight
    ),
    "severeImpact"=> array(
      "currentlyInfected" => $severeImpact,
      "infectionsByRequestedTime" => $severeImpactrequestedtime,
      "severeCasesByRequestedTime" => $severeImpactrequestedtimesevere,
      "hospitalBedsByRequestedTime" => $severeImpacthospitalBedsByRequestedTime,
      "casesForICUByRequestedTime" => $severeImpactcasesForICUByRequestedTime,
      "casesForVentilatorsByRequestedTime" => $severeImpactcasesForVentilatorsByRequestedTime,
      "dollarsInFlight" => $severeImpactdollarsInFlight
    )
  );
  //End
  return $data;
}
